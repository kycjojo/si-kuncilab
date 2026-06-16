<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Peminjaman;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $peminjaman = $this->peminjamanQuery($request)->latest()->paginate(20);
        $labs = Lab::orderBy('nama')->get();

        return view('admin.laporan.peminjaman', compact('peminjaman', 'labs'));
    }

    public function keterlambatan(Request $request)
    {
        $peminjaman = $this->keterlambatanQuery($request)->latest()->paginate(20);
        return view('admin.laporan.keterlambatan', compact('peminjaman'));
    }

    public function pelanggaran(Request $request)
    {
        $pelanggaran = $this->pelanggaranQuery($request)->latest()->paginate(20);
        return view('admin.laporan.pelanggaran', compact('pelanggaran'));
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
