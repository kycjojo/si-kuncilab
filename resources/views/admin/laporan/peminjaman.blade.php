@extends('layouts.app')
@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')

@section('content')
<div class="card mb-4 no-print fade-in-up delay-1">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Lab</label>
                <select name="lab_id" class="form-select form-select-sm">
                    <option value="">Semua Lab</option>
                    @foreach($labs as $lab)
                    <option value="{{ $lab->id }}" {{ request('lab_id')==$lab->id?'selected':'' }}>{{ $lab->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['menunggu','disetujui','ditolak','berlangsung','checklist_diisi','selesai','terlambat'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ \App\Models\Peminjaman::statusLabel($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Dari</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card fade-in-up delay-2">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clipboard-data-fill text-primary me-2"></i>Hasil Laporan ({{ $peminjaman->total() }} data)</span>
    </div>
    <div class="card-body p-0">
        @if($peminjaman->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Murid</th>
                        <th>Lab</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai Rencana</th>
                        <th>Jam Selesai Aktual</th>
                        <th>Status</th>
                        <th>Pengawas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $i => $pm)
                    <tr>
                        <td>{{ $peminjaman->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold small">{{ $pm->murid->name }}</div>
                            <div class="text-muted" style="font-size:.73rem">{{ $pm->murid->nis }}</div>
                        </td>
                        <td class="small">{{ $pm->lab->nama }}</td>
                        <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="small">{{ substr($pm->jam_mulai,0,5) }}</td>
                        <td class="small">{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                        <td class="small">{{ $pm->jam_selesai_aktual ? substr($pm->jam_selesai_aktual,0,5) : '—' }}</td>
                        <td><span class="badge bg-{{ $pm->statusBadgeForDisplay() }}">{{ $pm->statusLabelForDisplay() }}</span></td>
                        <td class="small">{{ optional($pm->penyerahanKunci?->pengawas)->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.laporan.detail', $pm) }}" class="btn btn-sm btn-outline-info" title="Lihat detail & foto">
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
            <p>Tidak ada data.</p>
        </div>
        @endif
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer">{{ $peminjaman->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
