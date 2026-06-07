@extends('layouts.app')
@section('title', 'Dashboard Murid')
@section('page-title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Blokir Warning --}}
@if($user->is_blocked)
<div class="alert alert-danger d-flex gap-3 align-items-center mb-4" role="alert">
    <i class="bi bi-lock-fill fs-3"></i>
    <div>
        <div class="fw-bold fs-5">Akun Anda Diblokir</div>
        <div>Total poin pelanggaran Anda telah mencapai <strong>{{ $user->poin_pelanggaran }} poin</strong>. Anda tidak dapat melakukan peminjaman. Hubungi Pengawas atau Admin untuk membuka blokir.</div>
    </div>
</div>
@endif

{{-- Poin Alert --}}
@if(!$user->is_blocked)
    @if($user->poin_pelanggaran >= 4)
    <div class="alert alert-danger py-2 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Peringatan!</strong> Poin pelanggaran Anda sudah <strong>{{ $user->poin_pelanggaran }}/5</strong>. Jika mencapai 5, akun akan diblokir!
    </div>
    @elseif($user->poin_pelanggaran >= 2)
    <div class="alert alert-warning py-2 mb-4">
        <i class="bi bi-exclamation-circle me-2"></i>
        Poin pelanggaran Anda: <strong>{{ $user->poin_pelanggaran }}/5</strong>. Harap menjaga ketertiban.
    </div>
    @endif
@endif

{{-- Selamat Datang & Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-4 h-100" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold fs-5">Selamat Datang!</div>
                    <div class="opacity-75 small mb-3">{{ $user->name }}</div>
                    <div class="small opacity-75 mb-1">NIS: {{ $user->nis ?? '—' }}</div>
                    <div class="small opacity-75">Kelas: {{ $user->kelas ?? '—' }}</div>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:56px;height:56px;background:rgba(255,255,255,.2);font-size:1.5rem;font-weight:700">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
            @if(!$user->is_blocked)
            <div class="mt-3">
                <a href="{{ route('murid.peminjaman.create') }}" class="btn btn-light btn-sm fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i>Ajukan Peminjaman
                </a>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="row g-3">
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-2 fw-bold text-primary">{{ $stats['total_peminjaman'] }}</div>
                    <div class="text-muted small">Total Peminjaman</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-2 fw-bold text-success">{{ $stats['selesai'] }}</div>
                    <div class="text-muted small">Selesai</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-2 fw-bold text-danger">{{ $stats['terlambat'] }}</div>
                    <div class="text-muted small">Terlambat</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-2 fw-bold {{ $user->poin_pelanggaran >= 4 ? 'text-danger' : 'text-secondary' }}">
                        {{ $stats['poin_pelanggaran'] }}
                    </div>
                    <div class="text-muted small">Poin Pelanggaran</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Peminjaman Aktif --}}
@if($peminjaman_aktif)
<div class="card mb-4 border-primary border-2">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-clock-fill me-2"></i>Peminjaman Aktif Saat Ini
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="fw-bold mb-1">{{ $peminjaman_aktif->lab->nama }}</h5>
                <div class="text-muted small mb-2">
                    <i class="bi bi-calendar3 me-1"></i>{{ $peminjaman_aktif->tanggal_pinjam->format('d/m/Y') }}
                    &nbsp;|&nbsp;
                    <i class="bi bi-clock me-1"></i>{{ substr($peminjaman_aktif->jam_mulai,0,5) }} – {{ substr($peminjaman_aktif->jam_selesai_rencana,0,5) }}
                </div>
                <div class="fw-semibold">{{ $peminjaman_aktif->tujuan }}</div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @if($peminjaman_aktif->status === 'menunggu')
                    <span class="badge bg-warning text-dark fs-6"><i class="bi bi-hourglass me-1"></i>Menunggu Konfirmasi</span>
                @elseif($peminjaman_aktif->status === 'disetujui')
                    <span class="badge bg-info text-dark fs-6"><i class="bi bi-check me-1"></i>Disetujui</span>
                    <div class="small text-muted mt-1">Ambil kunci dari pengawas</div>
                @elseif($peminjaman_aktif->status === 'berlangsung')
                    <span class="badge bg-success fs-6 mb-2"><i class="bi bi-door-open me-1"></i>Berlangsung</span>
                    <div>
                        <a href="{{ route('murid.checklist.create', $peminjaman_aktif) }}" class="btn btn-primary btn-sm" id="btn-isi-checklist">
                            <i class="bi bi-clipboard-check me-1"></i>Isi Checklist Pengembalian
                        </a>
                    </div>
                @elseif($peminjaman_aktif->status === 'checklist_diisi')
                    <span class="badge bg-secondary fs-6"><i class="bi bi-check-circle me-1"></i>Checklist Diisi</span>
                    <div class="small text-muted mt-1">Kembalikan kunci ke pengawas</div>
                @endif
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('murid.peminjaman.show', $peminjaman_aktif) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-eye me-1"></i>Lihat Detail
        </a>
    </div>
</div>
@else
@if(!$user->is_blocked)
<div class="card mb-4 text-center p-4 border-dashed" style="border:2px dashed #CBD5E1">
    <i class="bi bi-calendar-plus text-muted fs-1 mb-2"></i>
    <div class="text-muted mb-3">Anda tidak memiliki peminjaman aktif.</div>
    <a href="{{ route('murid.peminjaman.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Ajukan Peminjaman Sekarang
    </a>
</div>
@endif
@endif

{{-- Riwayat Terbaru --}}
@if($riwayat_terbaru->count() > 0)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Peminjaman Terbaru</span>
        <a href="{{ route('murid.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Lab</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($riwayat_terbaru as $pm)
                <tr>
                    <td class="fw-semibold">{{ $pm->lab->nama }}</td>
                    <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ \App\Models\Peminjaman::statusBadge($pm->status) }}">{{ \App\Models\Peminjaman::statusLabel($pm->status) }}</span></td>
                    <td><a href="{{ route('murid.peminjaman.show', $pm) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:2px 8px"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
