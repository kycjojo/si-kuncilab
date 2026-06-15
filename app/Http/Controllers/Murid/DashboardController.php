<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $peminjaman_aktif = Peminjaman::with('lab', 'checklistPengembalian')
            ->where('murid_id', $user->id)
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi'])
            ->latest()
            ->first();

        $riwayat_terbaru = Peminjaman::with(['lab', 'passKunciKeluar.usedByUser'])
            ->where('murid_id', $user->id)
            ->whereIn('status', ['selesai', 'terlambat', 'ditolak'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_peminjaman' => Peminjaman::where('murid_id', $user->id)->count(),
            'selesai'          => Peminjaman::where('murid_id', $user->id)->whereIn('status', ['selesai', 'terlambat'])->count(),
            'terlambat'        => Peminjaman::where('murid_id', $user->id)->where('status', 'terlambat')->count(),
            'poin_pelanggaran' => $user->poin_pelanggaran,
        ];

        $labs = Lab::where('is_active', true)->orderBy('nama')->get();

        return view('murid.dashboard', compact('peminjaman_aktif', 'riwayat_terbaru', 'stats', 'labs'));
    }
}
