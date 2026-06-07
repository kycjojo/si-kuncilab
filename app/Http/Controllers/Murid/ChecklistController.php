<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\ChecklistPengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function create(Peminjaman $peminjaman)
    {
        if ($peminjaman->murid_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'berlangsung') {
            return redirect()->route('murid.peminjaman.show', $peminjaman)
                ->with('error', 'Checklist hanya bisa diisi saat peminjaman berlangsung.');
        }

        if ($peminjaman->checklistPengembalian) {
            return redirect()->route('murid.peminjaman.show', $peminjaman)
                ->with('info', 'Checklist sudah pernah diisi.');
        }

        return view('murid.checklist.create', compact('peminjaman'));
    }

    public function store(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->murid_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'berlangsung') {
            return back()->with('error', 'Checklist hanya bisa diisi saat peminjaman berlangsung.');
        }

        $data = $request->validate([
            'kondisi_listrik'    => 'required|boolean',
            'kondisi_ac'         => 'required|boolean',
            'kondisi_perangkat'  => 'required|boolean',
            'kondisi_kebersihan' => 'required|boolean',
            'kondisi_pintu'      => 'required|boolean',
            'catatan_tambahan'   => 'nullable|string|max:1000',
            'foto'               => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Upload foto
        $fotoPath = $request->file('foto')->store('checklist-photos', 'public');

        ChecklistPengembalian::create([
            'peminjaman_id'      => $peminjaman->id,
            'murid_id'           => auth()->id(),
            'kondisi_listrik'    => $data['kondisi_listrik'],
            'kondisi_ac'         => $data['kondisi_ac'],
            'kondisi_perangkat'  => $data['kondisi_perangkat'],
            'kondisi_kebersihan' => $data['kondisi_kebersihan'],
            'kondisi_pintu'      => $data['kondisi_pintu'],
            'catatan_tambahan'   => $data['catatan_tambahan'] ?? null,
            'foto_path'          => $fotoPath,
        ]);

        $peminjaman->update(['status' => 'checklist_diisi']);

        return redirect()->route('murid.peminjaman.show', $peminjaman)
            ->with('success', 'Checklist berhasil diisi. Kembalikan kunci ke pengawas.');
    }
}
