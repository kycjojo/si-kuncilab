@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman Lab')
@section('back-url', route('murid.dashboard'))

@section('content')
{{-- Lab Cards Info --}}
<div class="row g-3 mb-4">
    @foreach($labs as $lab)
    <div class="col-6 col-md-3">
        <div class="card text-center p-3 h-100" style="cursor:pointer;border:2px solid transparent;transition:all .2s" onclick="pilihLab({{ $lab->id }})" id="lab-card-{{ $lab->id }}">
            <div class="fs-4 mb-1">🏫</div>
            <div class="fw-bold small">{{ $lab->nama }}</div>
            <div class="text-muted" style="font-size:.72rem"><i class="bi bi-people me-1"></i>{{ $lab->kapasitas }} orang</div>
            <span class="badge bg-success mt-1" style="font-size:.65rem">Tersedia</span>
        </div>
    </div>
    @endforeach
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Form Pengajuan Peminjaman</div>
    <div class="card-body p-4">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="alert alert-info py-2 small mb-4">
            <i class="bi bi-info-circle me-1"></i>
            Peminjaman akan dikonfirmasi oleh <strong>Pengawas</strong>. Pastikan jadwal tidak bentrok.
        </div>

        <form action="{{ route('murid.peminjaman.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Lab <span class="text-danger">*</span></label>
                <select name="lab_id" id="lab-select" class="form-select @error('lab_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Lab --</option>
                    @foreach($labs as $lab)
                    <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                        {{ $lab->nama }} (Kapasitas: {{ $lab->kapasitas }})
                    </option>
                    @endforeach
                </select>
                @error('lab_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tujuan Peminjaman <span class="text-danger">*</span></label>
                <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror"
                       value="{{ old('tujuan') }}" placeholder="Contoh: Praktikum Jaringan Komputer" required>
                @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan / Keperluan <span class="text-muted">(opsional)</span></label>
                <textarea name="keterangan" class="form-control" rows="3"
                          placeholder="Rincian kegiatan, jumlah peserta, dll...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                       value="{{ old('tanggal_pinjam') }}" min="{{ date('Y-m-d') }}" required>
                @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_mulai" id="jam-mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                           value="{{ old('jam_mulai') }}" required>
                    @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="jam_selesai_rencana" id="jam-selesai" class="form-control @error('jam_selesai_rencana') is-invalid @enderror"
                           value="{{ old('jam_selesai_rencana') }}" required>
                    @error('jam_selesai_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="btn-submit-peminjaman">
                    <i class="bi bi-send me-1"></i>Kirim Pengajuan
                </button>
                <a href="{{ route('murid.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function pilihLab(id) {
    document.getElementById('lab-select').value = id;
    document.querySelectorAll('[id^="lab-card-"]').forEach(c => {
        c.style.borderColor = 'transparent';
        c.style.background = '';
    });
    const card = document.getElementById('lab-card-' + id);
    card.style.borderColor = '#4F46E5';
    card.style.background = '#EEF2FF';
}

// Highlight selected lab on load
const selected = document.getElementById('lab-select').value;
if (selected) pilihLab(selected);

// Sync select -> card
document.getElementById('lab-select').addEventListener('change', function() {
    if (this.value) pilihLab(this.value);
});
</script>
@endpush
