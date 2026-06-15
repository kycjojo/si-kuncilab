<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Peminjaman;
use App\Models\Pelanggaran;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_murid'      => User::where('role', 'murid')->count(),
            'total_pengawas'   => User::where('role', 'pengawas')->count(),
            'total_lab'        => Lab::count(),
            'lab_aktif'        => Lab::where('is_active', true)->count(),
            'peminjaman_hari_ini'  => Peminjaman::whereDate('tanggal_pinjam', today())->count(),
            'peminjaman_berlangsung' => Peminjaman::where('status', 'berlangsung')->count(),
            'peminjaman_menunggu'   => Peminjaman::where('status', 'menunggu')->count(),
            'murid_diblokir'   => User::where('role', 'murid')->where('is_blocked', true)->count(),
            'total_pelanggaran'=> Pelanggaran::count(),
        ];

        $peminjaman_terbaru = Peminjaman::with(['murid', 'lab', 'passKunciKeluar.usedByUser'])
            ->latest()
            ->take(10)
            ->get();

        $pelanggaran_terbaru = Pelanggaran::with(['murid', 'peminjaman.lab'])
            ->latest()
            ->take(5)
            ->get();

        // Data untuk chart (7 hari terakhir)
        $chart_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chart_data[] = [
                'tanggal' => $date->format('d/m'),
                'count'   => Peminjaman::whereDate('tanggal_pinjam', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'peminjaman_terbaru', 'pelanggaran_terbaru', 'chart_data'));
    }
}
