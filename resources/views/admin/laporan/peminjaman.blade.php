@extends('layouts.app')
@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')

@section('content')
<div class="card mb-4 no-print">
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
            <div class="col-md-1">
                <a href="{{ route('admin.laporan.peminjaman.print', request()->except('page')) }}" target="_blank" rel="noopener" class="btn btn-outline-success btn-sm w-100" title="Cetak semua hasil filter">
                    <i class="bi bi-printer"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="print-only print-report-heading">
    <h1>SiKunci Lab</h1>
    <p>Laporan Peminjaman Kunci Laboratorium - Total {{ $peminjaman->total() }} data</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clipboard-data-fill text-primary me-2"></i>Hasil Laporan ({{ $peminjaman->total() }} data)</span>
    </div>
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
                    <th class="print-hidden">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $pm)
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
                    <td class="print-hidden">
                        <a href="{{ route('admin.laporan.detail', $pm) }}" class="btn btn-sm btn-outline-info" title="Lihat detail & foto">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer">{{ $peminjaman->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
