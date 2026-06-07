<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pelanggaran;

class DashboardController extends Controller
{
    public function index()
    {
        $menunggu = Peminjaman::with(['murid', 'lab'])
            ->where('status', 'menunggu')
            ->whereDate('tanggal_pinjam', today())
            ->orWhere(function($q) {
                $q->where('status', 'menunggu')
                  ->whereDate('tanggal_pinjam', '>', today());
            })
            ->orderBy('tanggal_pinjam')
            ->orderBy('jam_mulai')
            ->get();

        $berlangsung = Peminjaman::with(['murid', 'lab'])
            ->where('status', 'berlangsung')
            ->get();

        $checklist_menunggu = Peminjaman::with(['murid', 'lab'])
            ->where('status', 'checklist_diisi')
            ->get();

        $stats = [
            'menunggu'          => $menunggu->count(),
            'berlangsung'       => $berlangsung->count(),
            'checklist_menunggu'=> $checklist_menunggu->count(),
            'selesai_hari_ini'  => Peminjaman::where('status', 'selesai')
                ->whereDate('updated_at', today())->count(),
        ];

        return view('pengawas.dashboard', compact('menunggu', 'berlangsung', 'checklist_menunggu', 'stats'));
    }
}
