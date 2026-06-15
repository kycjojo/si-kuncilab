@extends('layouts.print')

@section('title', 'Cetak Laporan Pelanggaran')

@section('content')
<div class="report-header">
    <div>
        <h1 class="brand-title">SiKunci Lab</h1>
        <div class="report-title">Laporan Pelanggaran Laboratorium</div>
    </div>
    <div class="print-meta">
        <div><span class="muted">Tanggal cetak:</span> <span class="strong">{{ now()->format('d/m/Y H:i') }}</span></div>
        <div><span class="muted">Dicetak oleh:</span> <span class="strong">{{ auth()->user()->name }}</span></div>
        <div><span class="muted">Total data:</span> <span class="strong">{{ $pelanggaran->count() }}</span></div>
    </div>
</div>

<div class="filter-grid">
    @foreach($filters as $label => $value)
    <div class="filter-item">
        <span class="filter-label">{{ $label }}</span>
        <span class="strong">{{ $value }}</span>
    </div>
    @endforeach
</div>

@php
    $typeCounts = $pelanggaran->countBy('tipe');
@endphp
<div class="summary-grid">
    <div class="summary-item">
        <span class="summary-label">Total</span>
        <span class="summary-value">{{ $pelanggaran->count() }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Total Poin</span>
        <span class="summary-value">{{ $pelanggaran->sum('poin') }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Keterlambatan</span>
        <span class="summary-value">{{ $typeCounts->get('keterlambatan', 0) }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Kerusakan Alat</span>
        <span class="summary-value">{{ $typeCounts->get('kerusakan_alat', 0) }}</span>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th class="nowrap">No</th>
            <th>Murid</th>
            <th>Lab</th>
            <th>Tipe</th>
            <th class="nowrap">Poin</th>
            <th>Keterangan</th>
            <th>Dicatat Oleh</th>
            <th class="nowrap">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pelanggaran as $p)
        <tr>
            <td class="nowrap">{{ $loop->iteration }}</td>
            <td>
                <div class="strong">{{ $p->murid?->name ?? '-' }}</div>
                <div class="muted">{{ $p->murid?->nis ?? '-' }} / {{ $p->murid?->kelas ?? '-' }}</div>
            </td>
            <td>{{ $p->peminjaman?->lab?->nama ?? '-' }}</td>
            <td>{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }}</td>
            <td class="nowrap">+{{ $p->poin }}</td>
            <td>{{ $p->keterangan }}</td>
            <td>{{ $p->dicatatOleh?->name ?? '-' }}</td>
            <td class="nowrap">{{ $p->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="empty-state">Tidak ada data pelanggaran sesuai filter.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="signature-grid">
    <div></div>
    <div class="signature-box">
        <div>Admin</div>
        <div class="signature-space"></div>
        <div class="signature-name">{{ auth()->user()->name }}</div>
    </div>
</div>
@endsection
