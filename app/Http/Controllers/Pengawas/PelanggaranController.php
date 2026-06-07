<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index()
    {
        $pelanggaran = Pelanggaran::with(['murid', 'peminjaman.lab', 'dicatatOleh'])
            ->latest()
            ->paginate(15);

        return view('pengawas.pelanggaran.index', compact('pelanggaran'));
    }

    public function create(Request $request)
    {
        $murid = User::where('role', 'murid')->orderBy('name')->get();
        $peminjaman_list = [];

        if ($request->filled('murid_id')) {
            $peminjaman_list = Peminjaman::with('lab')
                ->where('murid_id', $request->murid_id)
                ->whereIn('status', ['selesai', 'terlambat', 'berlangsung', 'checklist_diisi'])
                ->latest()
                ->get();
        }

        return view('pengawas.pelanggaran.create', compact('murid', 'peminjaman_list'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'murid_id'      => 'required|exists:users,id',
            'peminjaman_id' => 'nullable|exists:peminjaman,id',
            'tipe'          => 'required|in:keterlambatan,kerusakan_alat,kebersihan,lainnya',
            'poin'          => 'required|integer|min:1|max:5',
            'keterangan'    => 'required|string|max:500',
        ]);

        $data['dicatat_oleh'] = auth()->id();
        Pelanggaran::create($data);

        // Update total poin murid
        $murid = User::find($data['murid_id']);
        $totalPoin = $murid->pelanggaran()->sum('poin');
        $murid->update([
            'poin_pelanggaran' => $totalPoin,
            'is_blocked'       => $totalPoin >= 5,
        ]);

        $msg = 'Pelanggaran berhasil dicatat.';
        if ($totalPoin >= 5) {
            $msg .= ' Murid telah diblokir karena poin >= 5.';
        }

        return redirect()->route('pengawas.pelanggaran.index')->with('success', $msg);
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $murid = $pelanggaran->murid;
        $pelanggaran->delete();

        // Recalculate poin
        $totalPoin = $murid->pelanggaran()->sum('poin');
        $murid->update([
            'poin_pelanggaran' => $totalPoin,
            'is_blocked'       => $totalPoin >= 5,
        ]);

        return back()->with('success', 'Pelanggaran berhasil dihapus.');
    }
}
