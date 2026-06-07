<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Peminjaman;
use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['murid', 'lab', 'penyerahanKunci.pengawas']);

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

        $peminjaman = $query->latest()->paginate(20);
        $labs = Lab::all();

        return view('admin.laporan.peminjaman', compact('peminjaman', 'labs'));
    }

    public function keterlambatan(Request $request)
    {
        $query = Peminjaman::with(['murid', 'lab'])
            ->whereIn('status', ['terlambat', 'selesai'])
            ->whereNotNull('jam_selesai_aktual')
            ->whereRaw('jam_selesai_aktual > jam_selesai_rencana');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        $peminjaman = $query->latest()->paginate(20);
        return view('admin.laporan.keterlambatan', compact('peminjaman'));
    }

    public function pelanggaran(Request $request)
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

        $pelanggaran = $query->latest()->paginate(20);
        return view('admin.laporan.pelanggaran', compact('pelanggaran'));
    }

    /**
     * Detail peminjaman — checklist + foto kondisi lab
     */
    public function detail(Peminjaman $peminjaman)
    {
        $peminjaman->load(['murid', 'lab', 'penyerahanKunci.pengawas', 'checklistPengembalian', 'pelanggaran.dicatatOleh', 'disetujuiOleh']);

        return view('admin.laporan.detail', compact('peminjaman'));
    }
}
