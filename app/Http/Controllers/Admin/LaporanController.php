<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Peminjaman;
use App\Models\Pelanggaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $peminjaman = $this->peminjamanQuery($request)->latest()->paginate(20);
        $labs = Lab::orderBy('nama')->get();

        return view('admin.laporan.peminjaman', compact('peminjaman', 'labs'));
    }

    public function printPeminjaman(Request $request)
    {
        $peminjaman = $this->peminjamanQuery($request)->latest()->get();
        $filters = $this->peminjamanFilters($request);

        return view('admin.laporan.print.peminjaman', compact('peminjaman', 'filters'));
    }

    public function keterlambatan(Request $request)
    {
        $peminjaman = $this->keterlambatanQuery($request)->latest()->paginate(20);
        return view('admin.laporan.keterlambatan', compact('peminjaman'));
    }

    public function printKeterlambatan(Request $request)
    {
        $peminjaman = $this->keterlambatanQuery($request)->latest()->get();
        $filters = [
            'Periode' => $this->periodLabel($request),
        ];

        return view('admin.laporan.print.keterlambatan', compact('peminjaman', 'filters'));
    }

    public function pelanggaran(Request $request)
    {
        $pelanggaran = $this->pelanggaranQuery($request)->latest()->paginate(20);
        return view('admin.laporan.pelanggaran', compact('pelanggaran'));
    }

    public function printPelanggaran(Request $request)
    {
        $pelanggaran = $this->pelanggaranQuery($request)->latest()->get();
        $filters = $this->pelanggaranFilters($request);

        return view('admin.laporan.print.pelanggaran', compact('pelanggaran', 'filters'));
    }

    public function printDetail(Peminjaman $peminjaman)
    {
        $this->loadDetailRelations($peminjaman);

        return view('admin.laporan.print.detail', compact('peminjaman'));
    }

    private function peminjamanQuery(Request $request)
    {
        $query = Peminjaman::with(['murid', 'lab', 'penyerahanKunci.pengawas', 'passKunciKeluar.usedByUser']);

        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        return $query;
    }

    private function keterlambatanQuery(Request $request)
    {
        $query = Peminjaman::with(['murid', 'lab', 'passKunciKeluar.usedByUser'])
            ->whereIn('status', ['terlambat', 'selesai'])
            ->whereNotNull('jam_selesai_aktual')
            ->whereRaw('jam_selesai_aktual > jam_selesai_rencana');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        return $query;
    }

    private function pelanggaranQuery(Request $request)
    {
        $query = Pelanggaran::with(['murid', 'peminjaman.lab', 'dicatatOleh']);

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        return $query;
    }

    private function peminjamanFilters(Request $request): array
    {
        return [
            'Lab' => $request->filled('lab_id')
                ? (Lab::find($request->lab_id)?->nama ?? 'Lab tidak ditemukan')
                : 'Semua Lab',
            'Status' => $request->filled('status')
                ? Peminjaman::statusLabel($request->status)
                : 'Semua Status',
            'Periode' => $this->periodLabel($request),
        ];
    }

    private function pelanggaranFilters(Request $request): array
    {
        return [
            'Tipe' => $request->filled('tipe')
                ? Pelanggaran::tipeLabel($request->tipe)
                : 'Semua Tipe',
            'Periode' => $this->periodLabel($request),
        ];
    }

    private function periodLabel(Request $request): string
    {
        $from = $this->formatDateFilter($request->input('dari'));
        $to = $this->formatDateFilter($request->input('sampai'));

        if ($from && $to) {
            return "{$from} sampai {$to}";
        }
        if ($from) {
            return "Mulai {$from}";
        }
        if ($to) {
            return "Sampai {$to}";
        }

        return 'Semua tanggal';
    }

    private function formatDateFilter(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date)->format('d/m/Y');
        } catch (\Throwable) {
            return $date;
        }
    }

    /**
     * Detail peminjaman — checklist + foto kondisi lab
     */
    public function detail(Peminjaman $peminjaman)
    {
        $this->loadDetailRelations($peminjaman);

        return view('admin.laporan.detail', compact('peminjaman'));
    }

    private function loadDetailRelations(Peminjaman $peminjaman): void
    {
        $peminjaman->load([
            'murid',
            'lab',
            'penyerahanKunci.pengawas',
            'checklistPengembalian',
            'pelanggaran.dicatatOleh',
            'disetujuiOleh',
            'passKunciKeluar.usedByUser',
        ]);
    }
}
