@extends('layouts.app')
@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;background:linear-gradient(135deg,#4F46E5,#7C3AED);font-size:2rem;color:#fff;font-weight:700">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <div class="text-muted small mb-2">{{ $user->email }}</div>
                @if($user->role === 'admin')
                    <span class="badge bg-danger mb-2">Admin</span>
                @elseif($user->role === 'pengawas')
                    <span class="badge bg-warning text-dark mb-2">Pengawas</span>
                @else
                    <span class="badge bg-success mb-2">Murid</span>
                @endif

                @if($user->is_blocked)
                    <div class="alert alert-danger py-2 small mt-2"><i class="bi bi-lock-fill me-1"></i>Akun Diblokir</div>
                @endif

                @if($user->role === 'murid')
                <div class="mt-3 text-start">
                    <div class="small text-muted mb-1">NIS: <strong>{{ $user->nis ?? '—' }}</strong></div>
                    <div class="small text-muted mb-2">Kelas: <strong>{{ $user->kelas ?? '—' }}</strong></div>
                    <div class="small fw-semibold mb-1">Poin Pelanggaran: {{ $user->poin_pelanggaran }}/5</div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar {{ $user->poin_pelanggaran >= 4 ? 'bg-danger' : ($user->poin_pelanggaran >= 2 ? 'bg-warning' : 'bg-success') }}"
                             style="width:{{ ($user->poin_pelanggaran/5)*100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                @if($user->role === 'murid')
                <form action="{{ route('admin.users.reset-poin', $user) }}" method="POST" onsubmit="return confirm('Reset poin?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning" title="Reset Poin">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </form>
                <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_blocked ? 'Buka blokir' : 'Blokir' }} akun ini?')">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-danger' }}" title="{{ $user->is_blocked ? 'Buka Blokir' : 'Blokir' }}">
                        <i class="bi bi-{{ $user->is_blocked ? 'unlock' : 'lock' }}"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Riwayat Peminjaman --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Peminjaman</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Lab</th><th>Tanggal</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $pm)
                        <tr>
                            <td>{{ $pm->lab->nama }}</td>
                            <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $pm->statusBadgeForDisplay() }}">{{ $pm->statusLabelForDisplay() }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada peminjaman</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat Pelanggaran --}}
        @if($user->role === 'murid')
        <div class="card">
            <div class="card-header"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Riwayat Pelanggaran</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Tipe</th><th>Poin</th><th>Keterangan</th><th>Dicatat Oleh</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggaran as $pl)
                        <tr>
                            <td><span class="badge bg-warning text-dark">{{ \App\Models\Pelanggaran::tipeLabel($pl->tipe) }}</span></td>
                            <td><span class="badge bg-danger">+{{ $pl->poin }}</span></td>
                            <td class="small">{{ $pl->keterangan }}</td>
                            <td class="small">{{ $pl->dicatatOleh->name }}</td>
                            <td class="small">{{ $pl->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pelanggaran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
@endsection
