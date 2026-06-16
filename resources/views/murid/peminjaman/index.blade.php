@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
<div data-realtime-page data-realtime-interval="4000">
<div data-realtime-region="murid-peminjaman-index">
<div class="card fade-in-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Peminjaman Saya</span>
        <a href="{{ route('murid.peminjaman.create') }}" class="btn btn-primary btn-sm" id="btn-ajukan-baru">
            <i class="bi bi-plus-lg me-1"></i>Ajukan Peminjaman
        </a>
    </div>
    <div class="card-body p-0">
        @if($peminjaman->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
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
        @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada riwayat peminjaman.</p>
        </div>
        @endif
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer">{{ $peminjaman->links() }}</div>
    @endif
</div>
</div>
</div>
@endsection
