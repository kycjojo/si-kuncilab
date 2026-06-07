<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function index()
    {
        $labs = Lab::withCount('peminjaman')->orderBy('nama')->paginate(15);
        return view('admin.labs.index', compact('labs'));
    }

    public function create()
    {
        return view('admin.labs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'kode'      => 'required|string|max:20|unique:labs,kode',
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Lab::create($data);

        return redirect()->route('admin.labs.index')
            ->with('success', 'Lab berhasil ditambahkan.');
    }

    public function edit(Lab $lab)
    {
        return view('admin.labs.edit', compact('lab'));
    }

    public function update(Request $request, Lab $lab)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'kode'      => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('labs')->ignore($lab->id)],
            'kapasitas' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', false);
        $lab->update($data);

        return redirect()->route('admin.labs.index')
            ->with('success', 'Lab berhasil diperbarui.');
    }

    public function destroy(Lab $lab)
    {
        $lab->delete();
        return redirect()->route('admin.labs.index')
            ->with('success', 'Lab berhasil dihapus.');
    }
}
