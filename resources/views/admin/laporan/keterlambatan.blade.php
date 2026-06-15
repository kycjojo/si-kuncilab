@extends('layouts.app')
@section('title', 'Laporan Keterlambatan')
@section('page-title', 'Laporan Keterlambatan')

@section('content')
<div class="card mb-4 no-print">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.laporan.keterlambatan') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.laporan.keterlambatan.print', request()->except('page')) }}" target="_blank" rel="noopener" class="btn btn-outline-success btn-sm w-100"><i class="bi bi-printer me-1"></i>Print</a>
            </div>
        </form>
    </div>
</div>

<div class="print-only print-report-heading">
    <h1>SiKunci Lab</h1>
    <p>Laporan Keterlambatan Pengembalian Kunci - Total {{ $peminjaman->total() }} data</p>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-clock-history text-danger me-2"></i>Laporan Keterlambatan ({{ $peminjaman->total() }} data)</div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Murid</th>
                    <th>Lab</th>
                    <th>Tanggal</th>
                    <th>Jam Selesai Rencana</th>
                    <th>Jam Selesai Aktual</th>
                    <th>Selisih</th>
                    <th>Status</th>
                    <th class="print-hidden">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $pm)
                @php
                    $rencana = \Carbon\Carbon::createFromTimeString($pm->jam_selesai_rencana);
                    $aktual  = \Carbon\Carbon::createFromTimeString($pm->jam_selesai_aktual);
                    $menit   = (int) $aktual->diffInMinutes($rencana, true);
                @endphp
                <tr>
                    <td>{{ $peminjaman->firstItem() + $i }}</td>
                    <td>
                        <div class="fw-semibold small">{{ $pm->murid->name }}</div>
                        <div class="text-muted" style="font-size:.73rem">{{ $pm->murid->kelas }}</div>
                    </td>
                    <td class="small">{{ $pm->lab->nama }}</td>
                    <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="small text-success">{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                    <td class="small text-danger fw-semibold">{{ substr($pm->jam_selesai_aktual,0,5) }}</td>
                    <td>
                        <span class="badge bg-danger">+{{ $menit }} menit</span>
                    </td>
                    <td><span class="badge bg-{{ $pm->statusBadgeForDisplay() }}">{{ $pm->statusLabelForDisplay() }}</span></td>
                    <td class="print-hidden">
                        <a href="{{ route('admin.laporan.detail', $pm) }}" class="btn btn-sm btn-outline-info" title="Lihat detail & foto">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data keterlambatan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer">{{ $peminjaman->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
