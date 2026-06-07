@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman — Laporan Admin')

@section('content')
<div class="row g-4">
    {{-- Kolom Kiri: Info Peminjaman --}}
    <div class="col-lg-5">
        {{-- Info Murid --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person-fill text-primary me-2"></i>Data Murid</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;font-size:1.3rem;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($peminjaman->murid->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $peminjaman->murid->name }}</div>
                        <div class="text-muted small">{{ $peminjaman->murid->nis ?? '—' }} · {{ $peminjaman->murid->kelas ?? '—' }}</div>
                        <div class="small">
                            Poin: <span class="badge {{ $peminjaman->murid->poin_pelanggaran >= 4 ? 'bg-danger' : ($peminjaman->murid->poin_pelanggaran >= 2 ? 'bg-warning text-dark' : 'bg-success') }}">{{ $peminjaman->murid->poin_pelanggaran }}/5</span>
                            @if($peminjaman->murid->is_blocked) <span class="badge bg-danger ms-1"><i class="bi bi-lock-fill me-1"></i>Diblokir</span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Peminjaman --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Peminjaman</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted small fw-semibold" style="width:40%">Lab</td><td class="fw-bold">{{ $peminjaman->lab->nama }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">Tujuan</td><td>{{ $peminjaman->tujuan }}</td></tr>
                    @if($peminjaman->keterangan)
                    <tr><td class="text-muted small fw-semibold">Keterangan</td><td class="small">{{ $peminjaman->keterangan }}</td></tr>
                    @endif
                    <tr><td class="text-muted small fw-semibold">Tanggal</td><td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">Jam</td><td>{{ substr($peminjaman->jam_mulai,0,5) }} — {{ substr($peminjaman->jam_selesai_rencana,0,5) }}</td></tr>
                    @if($peminjaman->jam_selesai_aktual)
                    <tr>
                        <td class="text-muted small fw-semibold">Selesai Aktual</td>
                        <td class="{{ $peminjaman->isTerlambat() ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ substr($peminjaman->jam_selesai_aktual,0,5) }}
                            @if($peminjaman->isTerlambat()) <span class="badge bg-danger ms-1">Terlambat</span> @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted small fw-semibold">Status</td>
                        <td><span class="badge bg-{{ \App\Models\Peminjaman::statusBadge($peminjaman->status) }} fs-6">{{ \App\Models\Peminjaman::statusLabel($peminjaman->status) }}</span></td>
                    </tr>
                    @if($peminjaman->disetujuiOleh)
                    <tr><td class="text-muted small fw-semibold">Disetujui oleh</td><td class="small">{{ $peminjaman->disetujuiOleh->name }}</td></tr>
                    @endif
                    @if($peminjaman->catatan_pengawas)
                    <tr><td class="text-muted small fw-semibold">Catatan Pengawas</td><td class="small">{{ $peminjaman->catatan_pengawas }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Penyerahan Kunci --}}
        @if($peminjaman->penyerahanKunci)
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-key-fill text-warning me-2"></i>Info Penyerahan Kunci</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted small" style="width:45%">Pengawas</td><td class="fw-semibold">{{ $peminjaman->penyerahanKunci->pengawas->name }}</td></tr>
                    <tr><td class="text-muted small">Waktu Serah</td><td>{{ $peminjaman->penyerahanKunci->waktu_serah?->format('H:i, d/m/Y') ?? '—' }}</td></tr>
                    <tr><td class="text-muted small">Waktu Kembali</td><td>{{ $peminjaman->penyerahanKunci->waktu_terima_kembali?->format('H:i, d/m/Y') ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
        @endif

        {{-- Pelanggaran --}}
        @if($peminjaman->pelanggaran->count() > 0)
        <div class="card">
            <div class="card-header"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Pelanggaran ({{ $peminjaman->pelanggaran->count() }})</div>
            <div class="card-body p-0">
                @foreach($peminjaman->pelanggaran as $p)
                <div class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-warning text-dark">{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }}</span>
                        <span class="badge bg-danger">+{{ $p->poin }} poin</span>
                    </div>
                    <div class="small mt-1">{{ $p->keterangan }}</div>
                    <div class="text-muted" style="font-size:.7rem">Dicatat oleh: {{ $p->dicatatOleh->name }} · {{ $p->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Kolom Kanan: Checklist + Foto --}}
    <div class="col-lg-7">
        @if($peminjaman->checklistPengembalian)
        @php $cl = $peminjaman->checklistPengembalian; @endphp

        {{-- Foto Kondisi Lab --}}
        <div class="card mb-4 border-primary border-2">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-camera-fill me-2"></i>Foto Kondisi Lab — {{ $peminjaman->lab->nama }}
            </div>
            <div class="card-body p-3">
                @if($cl->foto_path)
                <div class="text-center">
                    <img src="{{ Storage::url($cl->foto_path) }}" alt="Foto kondisi {{ $peminjaman->lab->nama }}"
                         class="img-fluid rounded shadow" style="max-height:450px;width:100%;object-fit:contain;cursor:pointer"
                         onclick="window.open(this.src, '_blank')" title="Klik untuk memperbesar">
                </div>
                <div class="text-center mt-2">
                    <span class="text-muted small"><i class="bi bi-zoom-in me-1"></i>Klik foto untuk memperbesar</span>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-image fs-1 d-block mb-2"></i>
                    <span>Tidak ada foto yang diupload</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Checklist Kondisi --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-clipboard-check-fill text-success me-2"></i>Checklist Kondisi Ruangan</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    @foreach([
                        ['label'=>'Listrik Dimatikan','val'=>$cl->kondisi_listrik,'icon'=>'bi-lightning-charge-fill'],
                        ['label'=>'AC Dimatikan','val'=>$cl->kondisi_ac,'icon'=>'bi-thermometer-half'],
                        ['label'=>'Perangkat Baik','val'=>$cl->kondisi_perangkat,'icon'=>'bi-pc-display'],
                        ['label'=>'Ruangan Bersih','val'=>$cl->kondisi_kebersihan,'icon'=>'bi-stars'],
                        ['label'=>'Pintu/Jendela Terkunci','val'=>$cl->kondisi_pintu,'icon'=>'bi-door-closed-fill'],
                    ] as $item)
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 {{ $item['val'] ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-danger bg-opacity-10 border border-danger border-opacity-25' }}">
                            <i class="{{ $item['icon'] }} fs-5 {{ $item['val'] ? 'text-success' : 'text-danger' }}"></i>
                            <span class="small fw-semibold">{{ $item['label'] }}</span>
                            <i class="ms-auto bi bi-{{ $item['val'] ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} fs-5"></i>
                        </div>
                    </div>
                    @endforeach
                </div>

                @php
                    $masalah = collect([
                        !$cl->kondisi_listrik ? 'Listrik belum dimatikan' : null,
                        !$cl->kondisi_ac ? 'AC belum dimatikan' : null,
                        !$cl->kondisi_perangkat ? 'Perangkat tidak dalam kondisi baik' : null,
                        !$cl->kondisi_kebersihan ? 'Ruangan belum dibersihkan' : null,
                        !$cl->kondisi_pintu ? 'Pintu/jendela belum terkunci' : null,
                    ])->filter();
                @endphp

                @if($masalah->count() > 0)
                <div class="alert alert-danger py-2 mb-3">
                    <div class="fw-bold small mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $masalah->count() }} kondisi bermasalah:</div>
                    <ul class="mb-0 small ps-3">
                        @foreach($masalah as $m)<li>{{ $m }}</li>@endforeach
                    </ul>
                </div>
                @else
                <div class="alert alert-success py-2 mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i><strong>Semua kondisi ruangan baik.</strong>
                </div>
                @endif

                @if($cl->catatan_tambahan)
                <div class="p-3 bg-light rounded-3">
                    <div class="small fw-semibold text-muted mb-1"><i class="bi bi-chat-left-text me-1"></i>Catatan dari Murid:</div>
                    <div class="small">{{ $cl->catatan_tambahan }}</div>
                </div>
                @endif

                <div class="text-muted small mt-3">
                    <i class="bi bi-clock me-1"></i>Checklist diisi pada: {{ $cl->created_at->format('H:i, d/m/Y') }}
                </div>
            </div>
        </div>

        @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-clipboard-x text-muted fs-1 d-block mb-3"></i>
                <div class="fw-bold fs-5 text-muted">Belum Ada Checklist</div>
                <div class="text-muted small">Murid belum mengisi checklist pengembalian.</div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Laporan</a>
    <a href="{{ route('admin.users.show', $peminjaman->murid) }}" class="btn btn-outline-primary"><i class="bi bi-person me-1"></i>Profil Murid</a>
</div>
@endsection
