@extends('layouts.app')
@section('title', 'Laporan Pelanggaran')
@section('page-title', 'Laporan Pelanggaran')

@section('content')
<div class="card mb-4 no-print fade-in-up delay-1">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tipe Pelanggaran</label>
                <select name="tipe" class="form-select form-select-sm">
                    <option value="">Semua Tipe</option>
                    <option value="keterlambatan" {{ request('tipe')==='keterlambatan'?'selected':'' }}>Keterlambatan</option>
                    <option value="kerusakan_alat" {{ request('tipe')==='kerusakan_alat'?'selected':'' }}>Kerusakan Alat</option>
                    <option value="kebersihan" {{ request('tipe')==='kebersihan'?'selected':'' }}>Kebersihan</option>
                    <option value="lainnya" {{ request('tipe')==='lainnya'?'selected':'' }}>Lainnya</option>
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
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.laporan.pelanggaran') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card fade-in-up delay-2">
    <div class="card-header"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Laporan Pelanggaran ({{ $pelanggaran->total() }} data)</div>
    <div class="card-body p-0">
        @if($pelanggaran->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Murid</th>
                        <th>Lab</th>
                        <th>Tipe</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Dicatat Oleh</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggaran as $i => $p)
                    <tr>
                        <td>{{ $pelanggaran->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold small">{{ $p->murid->name }}</div>
                            <div class="text-muted" style="font-size:.73rem">{{ $p->murid->kelas }}</div>
                        </td>
                        <td class="small">{{ optional($p->peminjaman?->lab)->nama ?? '—' }}</td>
                        <td><span class="badge bg-warning text-dark">{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }}</span></td>
                        <td><span class="badge bg-danger">+{{ $p->poin }}</span></td>
                        <td class="small">{{ Str::limit($p->keterangan, 50) }}</td>
                        <td class="small">{{ $p->dicatatOleh->name }}</td>
                        <td class="small">{{ $p->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-shield-check"></i>
            <p>Tidak ada data pelanggaran.</p>
        </div>
        @endif
    </div>
    @if($pelanggaran->hasPages())
    <div class="card-footer">{{ $pelanggaran->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
