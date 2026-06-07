<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('lab')
            ->where('murid_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('murid.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        // Cek apakah murid punya peminjaman aktif
        $aktif = Peminjaman::where('murid_id', auth()->id())
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi'])
            ->exists();

        if ($aktif) {
            return redirect()->route('murid.peminjaman.index')
                ->with('error', 'Anda masih memiliki peminjaman aktif. Selesaikan terlebih dahulu.');
        }

        $labs = Lab::where('is_active', true)->orderBy('nama')->get();
        return view('murid.peminjaman.create', compact('labs'));
    }

    public function store(Request $request)
    {
        // Cek apakah murid punya peminjaman aktif
        $aktif = Peminjaman::where('murid_id', auth()->id())
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi'])
            ->exists();

        if ($aktif) {
            return redirect()->route('murid.peminjaman.index')
                ->with('error', 'Anda masih memiliki peminjaman aktif.');
        }

        $data = $request->validate([
            'lab_id'             => 'required|exists:labs,id',
            'tujuan'             => 'required|string|max:255',
            'keterangan'         => 'nullable|string',
            'tanggal_pinjam'     => 'required|date|after_or_equal:today',
            'jam_mulai'          => 'required',
            'jam_selesai_rencana'=> 'required|after:jam_mulai',
        ]);

        // Cek konflik jadwal lab
        $lab = Lab::findOrFail($data['lab_id']);
        if ($lab->isBusy($data['tanggal_pinjam'], $data['jam_mulai'], $data['jam_selesai_rencana'])) {
            return back()->withInput()->withErrors([
                'lab_id' => 'Lab ini sudah dipesan pada waktu tersebut. Silakan pilih waktu lain.',
            ]);
        }

        $data['murid_id'] = auth()->id();
        $data['status']   = 'menunggu';
        Peminjaman::create($data);

        return redirect()->route('murid.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu konfirmasi pengawas.');
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->murid_id !== auth()->id()) {
            abort(403);
        }
        $peminjaman->load(['lab', 'penyerahanKunci.pengawas', 'checklistPengembalian', 'pelanggaran']);
        return view('murid.peminjaman.show', compact('peminjaman'));
    }
}
