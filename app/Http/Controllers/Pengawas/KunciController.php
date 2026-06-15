<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PenyerahanKunci;
use App\Models\PassKunciToken;
use Illuminate\Http\Request;

class KunciController extends Controller
{
    /**
     * Tampilkan daftar peminjaman yang menunggu konfirmasi
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['murid', 'lab'])
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi'])
            ->orderBy('tanggal_pinjam')
            ->orderBy('jam_mulai')
            ->paginate(15);

        return view('pengawas.kunci.index', compact('peminjaman'));
    }

    /**
     * Lihat detail peminjaman — checklist + foto kondisi lab
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'murid',
            'lab',
            'penyerahanKunci.pengawas',
            'checklistPengembalian',
            'pelanggaran.dicatatOleh',
            'passKunciKeluar.usedByUser',
        ]);

        // Transfer log: tokens related to this peminjaman (as source or destination)
        $transferLogs = PassKunciToken::with(['fromMurid', 'usedByUser', 'fromPeminjaman.lab', 'toPeminjaman.lab'])
            ->where(function ($q) use ($peminjaman) {
                $q->where('from_peminjaman_id', $peminjaman->id)
                  ->orWhere('to_peminjaman_id', $peminjaman->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengawas.kunci.show', compact('peminjaman', 'transferLogs'));
    }

    /**
     * Setujui peminjaman (belum serahkan kunci)
     */
    public function setujui(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak dalam status menunggu.');
        }

        $request->validate(['catatan_pengawas' => 'nullable|string']);

        $peminjaman->update([
            'status'            => 'disetujui',
            'catatan_pengawas'  => $request->catatan_pengawas,
            'disetujui_oleh'    => auth()->id(),
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Tolak peminjaman
     */
    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_pengawas' => 'required|string']);

        $peminjaman->update([
            'status'           => 'ditolak',
            'catatan_pengawas' => $request->catatan_pengawas,
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Serahkan kunci ke murid — status jadi berlangsung
     */
    public function serahKunci(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Kunci hanya bisa diserahkan setelah peminjaman disetujui.');
        }

        $peminjaman->update(['status' => 'berlangsung']);

        PenyerahanKunci::create([
            'peminjaman_id' => $peminjaman->id,
            'pengawas_id'   => auth()->id(),
            'waktu_serah'   => now(),
        ]);

        return back()->with('success', 'Kunci berhasil diserahkan. Peminjaman sedang berlangsung.');
    }

    /**
     * Terima kunci kembali — status jadi selesai
     */
    public function terimaKunci(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'checklist_diisi') {
            return back()->with('error', 'Kunci bisa diterima setelah murid mengisi checklist pengembalian.');
        }

        $aktual = now();
        $terlambat = $aktual->format('H:i:s') > $peminjaman->jam_selesai_rencana;

        $peminjaman->update([
            'status'              => $terlambat ? 'terlambat' : 'selesai',
            'jam_selesai_aktual'  => $aktual->format('H:i:s'),
        ]);

        // Update catatan penyerahan kunci
        if ($peminjaman->penyerahanKunci) {
            $peminjaman->penyerahanKunci->update(['waktu_terima_kembali' => $aktual]);
        }

        return back()->with('success', 'Kunci berhasil diterima kembali. Peminjaman ' . ($terlambat ? 'terlambat' : 'selesai') . '.');
    }
}
