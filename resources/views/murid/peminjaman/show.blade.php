@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')
@section('back-url', route('murid.peminjaman.index'))

@section('content')
@php $passKunciKeluar = $peminjaman->passKunciKeluar; @endphp
<div data-realtime-page data-realtime-interval="4000">
<div data-realtime-region="murid-peminjaman-show">
<div class="row g-4">
    <div class="col-lg-5">
        {{-- Info Card --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Peminjaman</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted small fw-semibold" style="width:40%">Lab</td><td class="fw-bold">{{ $peminjaman->lab->nama }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">Tujuan</td><td>{{ $peminjaman->tujuan }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">Tanggal</td><td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">Jam Mulai</td><td>{{ substr($peminjaman->jam_mulai,0,5) }}</td></tr>
                    <tr><td class="text-muted small fw-semibold">Jam Selesai</td><td>{{ substr($peminjaman->jam_selesai_rencana,0,5) }}</td></tr>
                    @if($peminjaman->jam_selesai_aktual)
                    <tr>
                        <td class="text-muted small fw-semibold">Aktual Selesai</td>
                        <td class="{{ $peminjaman->isTerlambat() ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ substr($peminjaman->jam_selesai_aktual,0,5) }}
                            @if($peminjaman->isTerlambat()) <i class="bi bi-exclamation-circle ms-1"></i> @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted small fw-semibold">Status</td>
                        <td><span class="badge bg-{{ $peminjaman->statusBadgeForDisplay() }} fs-6">{{ $peminjaman->statusLabelForDisplay() }}</span></td>
                    </tr>
                    @if($peminjaman->catatan_pengawas)
                    <tr><td class="text-muted small fw-semibold">Catatan Pengawas</td><td class="small">{{ $peminjaman->catatan_pengawas }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Penyerahan Kunci --}}
        @if($peminjaman->penyerahanKunci)
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-key-fill text-warning me-2"></i>Informasi Kunci</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted small" style="width:45%">Pengawas</td><td class="fw-semibold">{{ $peminjaman->penyerahanKunci->pengawas->name }}</td></tr>
                    @if($passKunciKeluar)
                    <tr>
                        <td class="text-muted small">Pengembalian</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $peminjaman->statusLabelForDisplay() }}</span>
                            <div class="text-muted small mt-1">{{ $passKunciKeluar->used_at?->format('H:i, d/m/Y') ?? 'Selesai via pass kunci' }}</div>
                        </td>
                    </tr>
                    @endif
                    <tr><td class="text-muted small">Waktu Serah</td><td>{{ $peminjaman->penyerahanKunci->waktu_serah?->format('H:i, d/m/Y') ?? '—' }}</td></tr>
                    @unless($passKunciKeluar)
                    <tr><td class="text-muted small">Waktu Kembali</td><td>{{ $peminjaman->penyerahanKunci->waktu_terima_kembali?->format('H:i, d/m/Y') ?? '—' }}</td></tr>
                    @endunless
                </table>
            </div>
        </div>
        @endif

        {{-- Pelanggaran --}}
        @if($peminjaman->pelanggaran->count() > 0)
        <div class="card">
            <div class="card-header"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Pelanggaran</div>
            <div class="card-body p-0">
                @foreach($peminjaman->pelanggaran as $p)
                <div class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-warning text-dark">{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }}</span>
                        <span class="badge bg-danger">+{{ $p->poin }} poin</span>
                    </div>
                    <div class="small mt-1">{{ $p->keterangan }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-7">
        {{-- Progress Timeline --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-diagram-3 text-primary me-2"></i>Status Peminjaman</div>
            <div class="card-body py-4">
                @php
                    $steps = [
                        ['label'=>'Pengajuan Dikirim','icon'=>'bi-send','done'=> true],
                        ['label'=>'Konfirmasi Pengawas','icon'=>'bi-person-check','done'=> !in_array($peminjaman->status,['menunggu'])],
                        ['label'=>'Kunci Diserahkan','icon'=>'bi-key-fill','done'=> $peminjaman->penyerahanKunci && $peminjaman->penyerahanKunci->waktu_serah],
                        ['label'=>'Checklist Diisi','icon'=>'bi-clipboard-check','done'=> $peminjaman->checklistPengembalian !== null],
                        ['label'=> $passKunciKeluar ? $peminjaman->statusLabelForDisplay() : 'Kunci Dikembalikan','icon'=>'bi-house-check','done'=> in_array($peminjaman->status,['selesai','terlambat'])],
                    ];
                @endphp
                <div class="d-flex justify-content-between position-relative" style="padding-bottom:16px">
                    <div style="position:absolute;top:20px;left:20px;right:20px;height:3px;background:#E2E8F0;z-index:0"></div>
                    @foreach($steps as $step)
                    <div class="text-center" style="z-index:1;flex:1">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                             style="width:40px;height:40px;background:{{ $step['done'] ? '#10B981' : '#E2E8F0' }};color:{{ $step['done'] ? '#fff' : '#94A3B8' }}">
                            <i class="{{ $step['icon'] }}"></i>
                        </div>
                        <div style="font-size:.7rem;color:{{ $step['done'] ? '#10B981' : '#94A3B8' }};font-weight:{{ $step['done'] ? '600' : '400' }}">
                            {{ $step['label'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Action Button --}}
        @if($peminjaman->status === 'berlangsung')
        <div class="alert alert-primary d-flex gap-3 align-items-center mb-4">
            <i class="bi bi-clipboard-check fs-3"></i>
            <div class="flex-grow-1">
                <div class="fw-bold">Lab sedang Anda gunakan</div>
                <div class="small">Setelah selesai, isi checklist kondisi ruangan dan upload foto.</div>
            </div>
            <a href="{{ route('murid.checklist.create', $peminjaman) }}" class="btn btn-primary btn-sm flex-shrink-0" id="btn-isi-checklist-detail">
                <i class="bi bi-clipboard-check me-1"></i>Isi Checklist
            </a>
        </div>
        @endif

        {{-- Checklist Detail --}}
        @if($peminjaman->checklistPengembalian)
        <div class="card">
            <div class="card-header"><i class="bi bi-clipboard-check-fill text-success me-2"></i>Checklist Pengembalian</div>
            <div class="card-body">
                @php $cl = $peminjaman->checklistPengembalian; @endphp
                <div class="row g-2 mb-3">
                    @foreach([
                        ['label'=>'Listrik Dimatikan','val'=>$cl->kondisi_listrik,'icon'=>'bi-lightning-charge'],
                        ['label'=>'AC Dimatikan','val'=>$cl->kondisi_ac,'icon'=>'bi-thermometer-half'],
                        ['label'=>'Perangkat Baik','val'=>$cl->kondisi_perangkat,'icon'=>'bi-pc-display'],
                        ['label'=>'Ruangan Bersih','val'=>$cl->kondisi_kebersihan,'icon'=>'bi-stars'],
                        ['label'=>'Pintu Terkunci','val'=>$cl->kondisi_pintu,'icon'=>'bi-door-closed'],
                    ] as $item)
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded {{ $item['val'] ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                            <i class="{{ $item['icon'] }} {{ $item['val'] ? 'text-success' : 'text-danger' }}"></i>
                            <span class="small">{{ $item['label'] }}</span>
                            <i class="ms-auto bi bi-{{ $item['val'] ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }}"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($cl->catatan_tambahan)
                <div class="mb-3">
                    <div class="small fw-semibold text-muted mb-1">Catatan:</div>
                    <div class="small">{{ $cl->catatan_tambahan }}</div>
                </div>
                @endif
                @if($cl->foto_path)
                <div>
                    <div class="small fw-semibold text-muted mb-2">Foto Kondisi Ruangan:</div>
                    <img src="{{ Storage::url($cl->foto_path) }}" alt="Foto ruangan" class="img-fluid rounded" style="max-height:300px">
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('murid.peminjaman.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
</div>
</div>
@endsection
