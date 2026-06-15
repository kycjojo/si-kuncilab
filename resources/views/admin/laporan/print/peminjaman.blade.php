@extends('layouts.print')

@section('title', 'Cetak Laporan Peminjaman')

@section('content')
<div class="report-header">
    <div>
        <h1 class="brand-title">SiKunci Lab</h1>
        <div class="report-title">Laporan Peminjaman Kunci Laboratorium</div>
    </div>
    <div class="print-meta">
        <div><span class="muted">Tanggal cetak:</span> <span class="strong">{{ now()->format('d/m/Y H:i') }}</span></div>
        <div><span class="muted">Dicetak oleh:</span> <span class="strong">{{ auth()->user()->name }}</span></div>
        <div><span class="muted">Total data:</span> <span class="strong">{{ $peminjaman->count() }}</span></div>
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
    $statusCounts = $peminjaman->countBy('status');
@endphp
<div class="summary-grid">
    <div class="summary-item">
        <span class="summary-label">Total</span>
        <span class="summary-value">{{ $peminjaman->count() }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Selesai</span>
        <span class="summary-value">{{ $statusCounts->get('selesai', 0) }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Terlambat</span>
        <span class="summary-value">{{ $statusCounts->get('terlambat', 0) }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Menunggu</span>
        <span class="summary-value">{{ $statusCounts->get('menunggu', 0) }}</span>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th class="nowrap">No</th>
            <th>Murid</th>
            <th>Lab</th>
            <th class="nowrap">Tanggal</th>
            <th class="nowrap">Jam Mulai</th>
            <th class="nowrap">Selesai Rencana</th>
            <th class="nowrap">Selesai Aktual</th>
            <th>Status</th>
            <th>Pengawas</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peminjaman as $pm)
        <tr>
            <td class="nowrap">{{ $loop->iteration }}</td>
            <td>
                <div class="strong">{{ $pm->murid?->name ?? '-' }}</div>
                <div class="muted">{{ $pm->murid?->nis ?? '-' }} / {{ $pm->murid?->kelas ?? '-' }}</div>
            </td>
            <td>{{ $pm->lab?->nama ?? '-' }}</td>
            <td class="nowrap">{{ $pm->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
            <td class="nowrap">{{ $pm->jam_mulai ? substr($pm->jam_mulai, 0, 5) : '-' }}</td>
            <td class="nowrap">{{ $pm->jam_selesai_rencana ? substr($pm->jam_selesai_rencana, 0, 5) : '-' }}</td>
            <td class="nowrap">{{ $pm->jam_selesai_aktual ? substr($pm->jam_selesai_aktual, 0, 5) : '-' }}</td>
            <td>{{ $pm->statusLabelForDisplay() }}</td>
            <td>{{ $pm->penyerahanKunci?->pengawas?->name ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="empty-state">Tidak ada data peminjaman sesuai filter.</td>
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
