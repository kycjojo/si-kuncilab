@extends('layouts.print')

@section('title', 'Cetak Bukti Peminjaman')

@push('styles')
<style>
    @page {
        size: A4 portrait;
        margin: 12mm;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    .detail-panel {
        border: 1px solid #cbd5e1;
    }

    .detail-panel-title {
        background: #f1f5f9;
        border-bottom: 1px solid #cbd5e1;
        color: #334155;
        font-size: 10px;
        font-weight: 700;
        padding: 6px 8px;
        text-transform: uppercase;
    }

    .detail-panel-body {
        padding: 8px;
    }

    .kv-row {
        display: grid;
        grid-template-columns: 115px 1fr;
        gap: 8px;
        padding: 3px 0;
    }

    .photo-box {
        border: 1px solid #cbd5e1;
        margin-bottom: 12px;
        padding: 8px;
        text-align: center;
    }

    .photo-box img {
        display: block;
        margin: 0 auto;
        max-height: 260px;
        max-width: 100%;
        object-fit: contain;
    }

    .signature-grid {
        grid-template-columns: repeat(3, 1fr);
    }
</style>
@endpush

@section('content')
@php $passKunciKeluar = $peminjaman->passKunciKeluar; @endphp
<div class="report-header">
    <div>
        <h1 class="brand-title">SiKunci Lab</h1>
        <div class="report-title">Bukti Peminjaman dan Pengembalian Kunci Laboratorium</div>
    </div>
    <div class="print-meta">
        <div><span class="muted">Tanggal cetak:</span> <span class="strong">{{ now()->format('d/m/Y H:i') }}</span></div>
        <div><span class="muted">Dicetak oleh:</span> <span class="strong">{{ auth()->user()->name }}</span></div>
        <div><span class="muted">Status:</span> <span class="strong">{{ $peminjaman->statusLabelForDisplay() }}</span></div>
    </div>
</div>

<div class="detail-grid">
    <section class="detail-panel">
        <div class="detail-panel-title">Data Murid</div>
        <div class="detail-panel-body">
            <div class="kv-row"><span class="muted">Nama</span><span class="strong">{{ $peminjaman->murid->name }}</span></div>
            <div class="kv-row"><span class="muted">NIS</span><span>{{ $peminjaman->murid->nis ?? '-' }}</span></div>
            <div class="kv-row"><span class="muted">Kelas</span><span>{{ $peminjaman->murid->kelas ?? '-' }}</span></div>
            <div class="kv-row"><span class="muted">Poin</span><span>{{ $peminjaman->murid->poin_pelanggaran }}/5</span></div>
        </div>
    </section>

    <section class="detail-panel">
        <div class="detail-panel-title">Informasi Peminjaman</div>
        <div class="detail-panel-body">
            <div class="kv-row"><span class="muted">Lab</span><span class="strong">{{ $peminjaman->lab->nama }}</span></div>
            <div class="kv-row"><span class="muted">Tanggal</span><span>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span></div>
            <div class="kv-row"><span class="muted">Jam Rencana</span><span>{{ substr($peminjaman->jam_mulai, 0, 5) }} - {{ substr($peminjaman->jam_selesai_rencana, 0, 5) }}</span></div>
            <div class="kv-row"><span class="muted">Selesai Aktual</span><span>{{ $peminjaman->jam_selesai_aktual ? substr($peminjaman->jam_selesai_aktual, 0, 5) : '-' }}</span></div>
        </div>
    </section>
</div>

<section class="detail-panel" style="margin-bottom: 12px;">
    <div class="detail-panel-title">Tujuan dan Catatan</div>
    <div class="detail-panel-body">
        <div class="kv-row"><span class="muted">Tujuan</span><span>{{ $peminjaman->tujuan }}</span></div>
        <div class="kv-row"><span class="muted">Keterangan</span><span>{{ $peminjaman->keterangan ?: '-' }}</span></div>
        <div class="kv-row"><span class="muted">Catatan Pengawas</span><span>{{ $peminjaman->catatan_pengawas ?: '-' }}</span></div>
        <div class="kv-row"><span class="muted">Disetujui Oleh</span><span>{{ $peminjaman->disetujuiOleh?->name ?? '-' }}</span></div>
    </div>
</section>

@if($peminjaman->penyerahanKunci)
<section class="detail-panel" style="margin-bottom: 12px;">
    <div class="detail-panel-title">Penyerahan Kunci</div>
    <div class="detail-panel-body">
        <div class="kv-row"><span class="muted">Pengawas</span><span>{{ $peminjaman->penyerahanKunci->pengawas?->name ?? '-' }}</span></div>
        @if($passKunciKeluar)
        <div class="kv-row"><span class="muted">Pengembalian</span><span>{{ $peminjaman->statusLabelForDisplay() }}</span></div>
        <div class="kv-row"><span class="muted">Waktu Pass</span><span>{{ $passKunciKeluar->used_at?->format('H:i, d/m/Y') ?? '-' }}</span></div>
        @endif
        <div class="kv-row"><span class="muted">Waktu Serah</span><span>{{ $peminjaman->penyerahanKunci->waktu_serah?->format('H:i, d/m/Y') ?? '-' }}</span></div>
        @unless($passKunciKeluar)
        <div class="kv-row"><span class="muted">Waktu Kembali</span><span>{{ $peminjaman->penyerahanKunci->waktu_terima_kembali?->format('H:i, d/m/Y') ?? '-' }}</span></div>
        @endunless
    </div>
</section>
@endif

@if($peminjaman->checklistPengembalian)
@php $cl = $peminjaman->checklistPengembalian; @endphp

@if($cl->foto_path)
<section class="photo-box">
    <div class="strong" style="margin-bottom: 6px;">Foto Kondisi Lab</div>
    <img src="{{ Storage::url($cl->foto_path) }}" alt="Foto kondisi {{ $peminjaman->lab->nama }}">
</section>
@endif

<section style="margin-bottom: 12px;">
    <table>
        <thead>
            <tr>
                <th>Checklist Kondisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'Listrik Dimatikan' => $cl->kondisi_listrik,
                'AC Dimatikan' => $cl->kondisi_ac,
                'Perangkat Baik' => $cl->kondisi_perangkat,
                'Ruangan Bersih' => $cl->kondisi_kebersihan,
                'Pintu/Jendela Terkunci' => $cl->kondisi_pintu,
            ] as $label => $value)
            <tr>
                <td>{{ $label }}</td>
                <td class="strong">{{ $value ? 'Baik' : 'Bermasalah' }}</td>
            </tr>
            @endforeach
            <tr>
                <td>Catatan Tambahan</td>
                <td>{{ $cl->catatan_tambahan ?: '-' }}</td>
            </tr>
            <tr>
                <td>Checklist Diisi Pada</td>
                <td>{{ $cl->created_at->format('H:i, d/m/Y') }}</td>
            </tr>
        </tbody>
    </table>
</section>
@else
<section class="detail-panel" style="margin-bottom: 12px;">
    <div class="detail-panel-title">Checklist Pengembalian</div>
    <div class="detail-panel-body muted">Belum ada checklist pengembalian.</div>
</section>
@endif

@if($peminjaman->pelanggaran->count() > 0)
<section style="margin-bottom: 12px;">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Poin</th>
                <th>Keterangan</th>
                <th>Dicatat Oleh</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->pelanggaran as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }}</td>
                <td>+{{ $p->poin }}</td>
                <td>{{ $p->keterangan }}</td>
                <td>{{ $p->dicatatOleh?->name ?? '-' }}</td>
                <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endif

<div class="signature-grid">
    <div class="signature-box">
        <div>Murid</div>
        <div class="signature-space"></div>
        <div class="signature-name">{{ $peminjaman->murid->name }}</div>
    </div>
    <div class="signature-box">
        <div>Pengawas</div>
        <div class="signature-space"></div>
        <div class="signature-name">{{ $peminjaman->penyerahanKunci?->pengawas?->name ?? '-' }}</div>
    </div>
    <div class="signature-box">
        <div>Admin</div>
        <div class="signature-space"></div>
        <div class="signature-name">{{ auth()->user()->name }}</div>
    </div>
</div>
@endsection
