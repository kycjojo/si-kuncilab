@extends('layouts.app')
@section('title', 'Catat Pelanggaran')
@section('page-title', 'Catat Pelanggaran')
@section('back-url', route('pengawas.pelanggaran.index'))

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card fade-in-up">
    <div class="card-header"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Form Catat Pelanggaran</div>
    <div class="card-body p-4">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('pengawas.pelanggaran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Murid <span class="text-danger">*</span></label>
                <select name="murid_id" id="murid_id" class="form-select @error('murid_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Murid --</option>
                    @foreach($murid as $m)
                    <option value="{{ $m->id }}"
                        data-poin="{{ $m->poin_pelanggaran }}"
                        {{ (request('murid_id') == $m->id || old('murid_id') == $m->id) ? 'selected' : '' }}>
                        {{ $m->name }} ({{ $m->kelas }}) — Poin: {{ $m->poin_pelanggaran }}/5
                    </option>
                    @endforeach
                </select>
                @error('murid_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if(count($peminjaman_list) > 0)
            <div class="alert alert-info py-2 small">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan peminjaman terkait murid yang dipilih.
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Peminjaman Terkait <span class="text-muted small">(opsional)</span></label>
                <select name="peminjaman_id" class="form-select">
                    <option value="">-- Pilih Peminjaman (Opsional) --</option>
                    @foreach($peminjaman_list as $pm)
                    <option value="{{ $pm->id }}" {{ old('peminjaman_id') == $pm->id ? 'selected' : '' }}>
                        {{ $pm->lab->nama }} — {{ $pm->tanggal_pinjam->format('d/m/Y') }} ({{ \App\Models\Peminjaman::statusLabel($pm->status) }})
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Pelanggaran <span class="text-danger">*</span></label>
                <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="keterlambatan" {{ old('tipe')==='keterlambatan'?'selected':'' }}>Keterlambatan</option>
                    <option value="kerusakan_alat" {{ old('tipe')==='kerusakan_alat'?'selected':'' }}>Kerusakan Alat</option>
                    <option value="kebersihan" {{ old('tipe')==='kebersihan'?'selected':'' }}>Kebersihan</option>
                    <option value="lainnya" {{ old('tipe')==='lainnya'?'selected':'' }}>Lainnya</option>
                </select>
                @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah Poin <span class="text-danger">*</span></label>
                <input type="number" name="poin" class="form-control @error('poin') is-invalid @enderror"
                       value="{{ old('poin', 1) }}" min="1" max="5" required>
                <div class="form-text">Maksimal 5 poin per pelanggaran.</div>
                @error('poin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="4"
                          placeholder="Jelaskan detail pelanggaran..." required>{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger" id="btn-simpan-pelanggaran">
                    <i class="bi bi-exclamation-triangle me-1"></i>Catat Pelanggaran
                </button>
                <a href="{{ route('pengawas.pelanggaran.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<script>
document.getElementById('murid_id').addEventListener('change', function() {
    var url = new URL(window.location);
    url.searchParams.set('murid_id', this.value);
    window.location = url;
});
</script>
@endsection
