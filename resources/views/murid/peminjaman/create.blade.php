@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman Lab')
@section('back-url', route('murid.dashboard'))

@section('content')

@if($errors->any())
<div class="alert alert-danger fade-in-up mb-3">
    <ul class="mb-0 ps-3 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="row g-3">
    {{-- Left: Form --}}
    <div class="col-lg-7 order-2 order-lg-1">
        <div class="card fade-in-up delay-1">
            <div class="card-header">
                <i class="bi bi-pencil-square text-primary me-2"></i>Detail Peminjaman
            </div>
            <div class="card-body">
                <form action="{{ route('murid.peminjaman.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lab_id" id="lab-select" value="{{ old('lab_id') }}">

                    {{-- Selected Lab Display --}}
                    <div class="mb-3">
                        <label class="form-label">Lab Dipilih <span class="text-danger">*</span></label>
                        <div id="lab-display" class="form-control d-flex align-items-center gap-2 @error('lab_id') is-invalid @enderror" style="background:#F9FAFB;min-height:44px;cursor:pointer" onclick="document.getElementById('lab-cards-section').scrollIntoView({behavior:'smooth'})">
                            <i class="bi bi-building text-muted"></i>
                            <span id="lab-display-text" class="text-muted">Pilih lab di {{ request()->is('*') ? 'bawah' : 'samping' }} →</span>
                        </div>
                        @error('lab_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                        <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror"
                               value="{{ old('tujuan') }}" placeholder="Contoh: Praktikum Jaringan" required>
                        @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Rincian kegiatan, jumlah peserta, dll...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                               value="{{ old('tanggal_pinjam') }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                                   value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai_rencana" class="form-control @error('jam_selesai_rencana') is-invalid @enderror"
                                   value="{{ old('jam_selesai_rencana') }}" required>
                            @error('jam_selesai_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btn-submit-peminjaman">
                            <i class="bi bi-send me-1"></i>Kirim Pengajuan
                        </button>
                        <a href="{{ route('murid.dashboard') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Lab selection --}}
    <div class="col-lg-5 order-1 order-lg-2" id="lab-cards-section">
        <div class="card fade-in-up">
            <div class="card-header">
                <i class="bi bi-building text-primary me-2"></i>Pilih Lab
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($labs as $lab)
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center fade-in-up delay-{{ ($loop->index % 4) + 1 }}"
                             style="cursor:pointer;border:2px solid #E5E7EB;transition:all .15s;background:#FAFAFA"
                             onclick="pilihLab({{ $lab->id }}, '{{ $lab->nama }}')"
                             id="lab-card-{{ $lab->id }}">
                            <div style="font-size:1.5rem;margin-bottom:4px">🏫</div>
                            <div class="fw-bold" style="font-size:.82rem">{{ $lab->nama }}</div>
                            <div class="text-muted" style="font-size:.68rem"><i class="bi bi-people me-1"></i>{{ $lab->kapasitas }} orang</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 small text-muted text-center">
                    <i class="bi bi-info-circle me-1"></i>Tap untuk memilih lab
                </div>
            </div>
        </div>

        {{-- Info box --}}
        <div class="card mt-3 fade-in-up delay-3">
            <div class="card-body py-3">
                <div class="d-flex gap-2 align-items-start">
                    <i class="bi bi-lightbulb text-warning"></i>
                    <div class="small text-muted" style="line-height:1.5">
                        Peminjaman akan dikonfirmasi oleh <strong>Pengawas</strong>. Pastikan jadwal tidak bentrok dengan peminjaman lain.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var labNames = {};
@foreach($labs as $lab)
labNames[{{ $lab->id }}] = '{{ $lab->nama }}';
@endforeach

function pilihLab(id, nama) {
    document.getElementById('lab-select').value = id;
    // Reset all cards
    document.querySelectorAll('[id^="lab-card-"]').forEach(function(c) {
        c.style.borderColor = '#E5E7EB';
        c.style.background = '#FAFAFA';
    });
    // Highlight selected
    var card = document.getElementById('lab-card-' + id);
    if (card) {
        card.style.borderColor = '#4F46E5';
        card.style.background = '#EEF2FF';
    }
    // Update display
    var display = document.getElementById('lab-display-text');
    display.textContent = nama || labNames[id] || 'Lab ' + id;
    display.classList.remove('text-muted');
    display.classList.add('fw-semibold', 'text-dark');
}

// Highlight selected lab on load
var selected = document.getElementById('lab-select').value;
if (selected) pilihLab(parseInt(selected), labNames[parseInt(selected)]);
</script>
@endsection
