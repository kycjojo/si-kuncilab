@extends('layouts.app')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, email, atau NIS..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="pengawas" {{ request('role')=='pengawas'?'selected':'' }}>Pengawas</option>
                    <option value="murid" {{ request('role')=='murid'?'selected':'' }}>Murid</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill text-primary me-2"></i>Daftar User ({{ $users->total() }})</span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm" id="btn-tambah-user">
            <i class="bi bi-plus-lg me-1"></i>Tambah User
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>NIS / Kelas</th>
                    <th>Poin</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td>{{ $users->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td class="text-muted small">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->role === 'pengawas')
                            <span class="badge bg-warning text-dark">Pengawas</span>
                        @else
                            <span class="badge bg-success">Murid</span>
                        @endif
                    </td>
                    <td class="small">
                        @if($user->nis) {{ $user->nis }} @endif
                        @if($user->kelas) <br><span class="text-muted">{{ $user->kelas }}</span> @endif
                        @if(!$user->nis && !$user->kelas) <span class="text-muted">—</span> @endif
                    </td>
                    <td>
                        @if($user->role === 'murid')
                            <span class="badge {{ $user->poin_pelanggaran >= 4 ? 'bg-danger' : ($user->poin_pelanggaran >= 2 ? 'bg-warning text-dark' : 'bg-light text-dark border') }}">
                                {{ $user->poin_pelanggaran }}/5
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_blocked)
                            <span class="badge bg-danger"><i class="bi bi-lock-fill me-1"></i>Diblokir</span>
                        @else
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-xs btn-outline-info" style="font-size:.75rem;padding:2px 8px" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 8px" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->role === 'murid')
                            <form action="{{ route('admin.users.reset-poin', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset poin pelanggaran {{ $user->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-outline-warning" style="font-size:.75rem;padding:2px 8px" title="Reset Poin">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $user->is_blocked ? 'Buka blokir' : 'Blokir' }} akun {{ $user->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-xs {{ $user->is_blocked ? 'btn-outline-success' : 'btn-outline-secondary' }}" style="font-size:.75rem;padding:2px 8px" title="{{ $user->is_blocked ? 'Buka Blokir' : 'Blokir' }}">
                                    <i class="bi bi-{{ $user->is_blocked ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                            @endif
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:2px 8px" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada user ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer">{{ $users->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
