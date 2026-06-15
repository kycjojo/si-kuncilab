<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nis', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,pengawas,murid',
            'nis'      => 'nullable|string|max:20',
            'kelas'    => 'nullable|string|max:20',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'  => 'required|in:admin,pengawas,murid',
            'nis'   => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:20',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function resetPoin(User $user)
    {
        $user->update(['poin_pelanggaran' => 0, 'is_blocked' => false]);
        return back()->with('success', "Poin pelanggaran {$user->name} berhasil direset dan akun dibuka blokir.");
    }

    public function toggleBlock(User $user)
    {
        $user->update(['is_blocked' => !$user->is_blocked]);
        $status = $user->is_blocked ? 'diblokir' : 'dibuka blokir';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function show(User $user)
    {
        $peminjaman = $user->peminjaman()
            ->with(['lab', 'passKunciKeluar.usedByUser'])
            ->latest()
            ->take(10)
            ->get();
        $pelanggaran = $user->pelanggaran()->with('peminjaman.lab', 'dicatatOleh')->latest()->get();
        return view('admin.users.show', compact('user', 'peminjaman', 'pelanggaran'));
    }
}
