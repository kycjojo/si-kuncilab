<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\PassKunciToken;
use App\Models\Peminjaman;
use App\Models\PenyerahanKunci;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PassKunciController extends Controller
{
    /**
     * Tampilkan halaman QR Code untuk pass kunci (layar Peminjam A)
     */
    public function showQr(Peminjaman $peminjaman)
    {
        if ($peminjaman->murid_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($peminjaman->status, ['berlangsung', 'checklist_diisi'])) {
            return redirect()->route('murid.peminjaman.show', $peminjaman)
                ->with('error', 'Pass kunci hanya bisa dilakukan saat peminjaman berlangsung atau checklist sudah diisi.');
        }

        if (!$peminjaman->checklistPengembalian) {
            return redirect()->route('murid.checklist.create', $peminjaman)
                ->with('error', 'Isi checklist pengembalian terlebih dahulu sebelum pass kunci.');
        }

        $peminjaman->load('lab', 'checklistPengembalian');

        return view('murid.pass-kunci.qr', compact('peminjaman'));
    }

    /**
     * Generate token QR baru (dipanggil via fetch/AJAX dari halaman QR)
     */
    public function generate(Peminjaman $peminjaman)
    {
        if ($peminjaman->murid_id !== auth()->id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        if (!in_array($peminjaman->status, ['berlangsung', 'checklist_diisi'])) {
            return response()->json(['error' => 'Peminjaman tidak dalam status berlangsung.'], 422);
        }

        if (!$peminjaman->checklistPengembalian) {
            return response()->json(['error' => 'Isi checklist pengembalian terlebih dahulu.'], 422);
        }

        // Invalidasi semua token lama yang masih unused
        PassKunciToken::where('from_peminjaman_id', $peminjaman->id)
            ->where('status', 'unused')
            ->update(['status' => 'expired']);

        // Generate token baru
        $token = Str::random(48);
        $expiredAt = now()->addMinutes(5);

        $passToken = PassKunciToken::create([
            'from_peminjaman_id' => $peminjaman->id,
            'from_murid_id'      => auth()->id(),
            'token'              => $token,
            'status'             => 'unused',
            'expired_at'         => $expiredAt,
        ]);

        return response()->json([
            'token'      => $token,
            'expired_at' => $expiredAt->toIso8601String(),
        ]);
    }

    /**
     * Cek status token QR yang sedang tampil di layar peminjam awal.
     */
    public function status(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->murid_id !== auth()->id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate(['token' => 'required|string']);

        $passToken = PassKunciToken::with(['usedByUser', 'toPeminjaman'])
            ->where('from_peminjaman_id', $peminjaman->id)
            ->where('token', $request->input('token'))
            ->first();

        if (!$passToken) {
            return response()->json(['error' => 'Token tidak ditemukan.'], 404);
        }

        if ($passToken->status === 'unused' && $passToken->expired_at < now()) {
            $passToken->update(['status' => 'expired']);
            $passToken->status = 'expired';
        }

        return response()->json([
            'status'          => $passToken->status,
            'used_by_name'    => $passToken->usedByUser?->name,
            'used_at'         => $passToken->used_at?->toIso8601String(),
            'to_peminjaman_id'=> $passToken->to_peminjaman_id,
            'redirect_url'    => route('murid.peminjaman.index'),
        ]);
    }

    /**
     * Halaman scan QR (layar Peminjam B)
     */
    public function scanPage()
    {
        // Cek apakah B sudah punya peminjaman aktif
        $aktif = Peminjaman::where('murid_id', auth()->id())
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi'])
            ->exists();

        if ($aktif) {
            return redirect()->route('murid.dashboard')
                ->with('error', 'Anda masih memiliki peminjaman aktif. Selesaikan terlebih dahulu sebelum mengambil kunci.');
        }

        return view('murid.pass-kunci.scan');
    }

    /**
     * Redeem token QR — transfer kunci dari A ke B
     */
    public function redeem(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $token = $request->input('token');

        // Cari token yang masih unused
        $passToken = PassKunciToken::where('token', $token)
            ->where('status', 'unused')
            ->first();

        if (!$passToken) {
            return response()->json([
                'error' => 'Token tidak valid atau sudah digunakan.'
            ], 422);
        }

        // Cek expired
        if ($passToken->expired_at < now()) {
            $passToken->update(['status' => 'expired']);
            return response()->json([
                'error' => 'QR sudah kadaluarsa. Minta peminjam sebelumnya untuk generate ulang.'
            ], 422);
        }

        // B tidak boleh sama dengan A
        if ($passToken->from_murid_id === auth()->id()) {
            return response()->json([
                'error' => 'Anda tidak bisa scan QR milik sendiri.'
            ], 422);
        }

        // B tidak boleh punya peminjaman aktif
        $aktif = Peminjaman::where('murid_id', auth()->id())
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi'])
            ->exists();

        if ($aktif) {
            return response()->json([
                'error' => 'Anda masih memiliki peminjaman aktif. Selesaikan terlebih dahulu.'
            ], 422);
        }

        // Ambil peminjaman A
        $peminjamanA = Peminjaman::findOrFail($passToken->from_peminjaman_id);

        // Guard: pastikan A masih berlangsung atau checklist_diisi
        if (!in_array($peminjamanA->status, ['berlangsung', 'checklist_diisi'])) {
            return response()->json([
                'error' => 'Peminjaman asal sudah tidak berlangsung.'
            ], 422);
        }

        try {
            DB::transaction(function () use ($passToken, $peminjamanA) {
                // 1. Tutup peminjaman A
                $peminjamanA->update([
                    'status'             => 'selesai',
                    'jam_selesai_aktual' => now()->format('H:i:s'),
                ]);

                // 2. Buat peminjaman baru untuk B
                $peminjamanB = Peminjaman::create([
                    'murid_id'            => auth()->id(),
                    'lab_id'              => $peminjamanA->lab_id,
                    'tujuan'              => 'Pass kunci dari ' . $peminjamanA->murid->name,
                    'tanggal_pinjam'      => $peminjamanA->tanggal_pinjam,
                    'jam_mulai'           => now()->format('H:i:s'),
                    'jam_selesai_rencana' => $peminjamanA->jam_selesai_rencana,
                    'status'              => 'berlangsung',
                ]);

                // 3. Buat record PenyerahanKunci untuk B
                // Gunakan A (from_murid_id) sebagai "pengawas" / pihak yang menyerahkan
                PenyerahanKunci::create([
                    'peminjaman_id' => $peminjamanB->id,
                    'pengawas_id'   => $passToken->from_murid_id,
                    'waktu_serah'   => now(),
                ]);

                // 4. Update token
                $passToken->update([
                    'status'           => 'used',
                    'used_at'          => now(),
                    'used_by'          => auth()->id(),
                    'to_peminjaman_id' => $peminjamanB->id,
                ]);
            });

            // Ambil peminjaman B yang baru dibuat
            $peminjamanB = Peminjaman::where('murid_id', auth()->id())
                ->where('status', 'berlangsung')
                ->latest()
                ->first();

            return response()->json([
                'success'        => true,
                'peminjaman_id'  => $peminjamanB->id,
                'lab_nama'       => $peminjamanB->lab->nama,
                'message'        => 'Kunci berhasil diambil! Anda sekarang memegang kunci ' . $peminjamanB->lab->nama . '.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses transfer kunci. Silakan coba lagi.'
            ], 500);
        }
    }
}
