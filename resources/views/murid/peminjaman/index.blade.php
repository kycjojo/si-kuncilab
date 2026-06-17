@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
<div data-realtime-page data-realtime-interval="4000">
<div data-realtime-region="murid-peminjaman-index">

{{-- Header with action --}}
<div class="d-flex justify-content-between align-items-center mb-3 fade-in-up">
    <div>
        <h6 class="fw-bold mb-0">Peminjaman Saya</h6>
        <small class="text-muted">{{ $peminjaman->total() }} total riwayat</small>
    </div>
    <a href="{{ route('murid.peminjaman.create') }}" class="btn btn-primary btn-sm" id="btn-ajukan-baru">
        <i class="bi bi-plus-lg me-1"></i>Ajukan Baru
    </a>
</div>

@if($peminjaman->count() > 0)

    {{-- Desktop table: hidden on mobile --}}
    <div class="card fade-in-up d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>No</th><th>Lab</th><th>Tujuan</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $i => $pm)
                        <tr>
                            <td>{{ $peminjaman->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $pm->lab->nama }}</td>
                            <td class="small">{{ Str::limit($pm->tujuan, 40) }}</td>
                            <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="small">{{ substr($pm->jam_mulai,0,5) }}–{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                            <td>
                                <span class="badge bg-{{ $pm->statusBadgeForDisplay() }}">
                                    {{ $pm->statusLabelForDisplay() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('murid.peminjaman.show', $pm) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Mobile card list: hidden on desktop --}}
    <div class="d-md-none d-flex flex-column gap-2">
        @foreach($peminjaman as $i => $pm)
        <a href="{{ route('murid.peminjaman.show', $pm) }}" class="card fade-in-up delay-{{ min($loop->iteration, 5) }}" style="text-decoration:none;color:inherit">
            <div class="card-body py-3 px-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold" style="font-size:.9rem">{{ $pm->lab->nama }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ Str::limit($pm->tujuan, 35) }}</div>
                    </div>
                    <span class="badge bg-{{ $pm->statusBadgeForDisplay() }}" style="font-size:.68rem">
                        {{ $pm->statusLabelForDisplay() }}
                    </span>
                </div>
                <div class="d-flex gap-3" style="font-size:.75rem;color:#6B7280">
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $pm->tanggal_pinjam->format('d M Y') }}</span>
                    <span><i class="bi bi-clock me-1"></i>{{ substr($pm->jam_mulai,0,5) }}–{{ substr($pm->jam_selesai_rencana,0,5) }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

@else
    <div class="card fade-in-up">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada riwayat peminjaman.</p>
        </div>
    </div>
@endif

@if($peminjaman->hasPages())
<div class="mt-3 d-flex justify-content-center">{{ $peminjaman->links() }}</div>
@endif

</div>
</div>
@endsection
