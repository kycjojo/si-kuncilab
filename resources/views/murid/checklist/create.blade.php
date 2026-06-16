@extends('layouts.app')
@section('title', 'Checklist Pengembalian')
@section('page-title', 'Checklist Pengembalian')
@section('back-url', route('murid.peminjaman.index'))

@section('content')
{{-- Header Info --}}
<div class="alert alert-primary d-flex gap-3 align-items-center mb-4">
    <i class="bi bi-key-fill fs-2"></i>
    <div>
        <div class="fw-bold fs-5">{{ $peminjaman->lab->nama }}</div>
        <div class="small">
            <i class="bi bi-calendar3 me-1"></i>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}
            &nbsp;|&nbsp;
            <i class="bi bi-clock me-1"></i>Selesai rencana: <strong>{{ substr($peminjaman->jam_selesai_rencana,0,5) }}</strong>
        </div>
    </div>
</div>

<div class="alert alert-warning py-2 mb-4">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Penting:</strong> Isi checklist dengan jujur. Setelah submit, segera kembalikan kunci ke pengawas.
</div>

<form action="{{ route('murid.checklist.store', $peminjaman) }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-clipboard-check text-primary me-2"></i>Kondisi Ruangan</div>
            <div class="card-body">
                @foreach([
                    ['name'=>'kondisi_listrik','label'=>'Listrik sudah dimatikan','icon'=>'bi-lightning-charge-fill','desc'=>'Pastikan semua saklar lampu dan colokan sudah OFF'],
                    ['name'=>'kondisi_ac','label'=>'AC sudah dimatikan','icon'=>'bi-thermometer-half','desc'=>'Pastikan remote AC sudah digunakan untuk mematikan'],
                    ['name'=>'kondisi_perangkat','label'=>'Perangkat dalam kondisi baik','icon'=>'bi-pc-display','desc'=>'Komputer, monitor, keyboard, dan mouse sudah dirapikan'],
                    ['name'=>'kondisi_kebersihan','label'=>'Ruangan sudah dibersihkan','icon'=>'bi-stars','desc'=>'Sampah dibuang, meja dan kursi dirapikan'],
                    ['name'=>'kondisi_pintu','label'=>'Pintu dan jendela sudah dikunci','icon'=>'bi-door-closed-fill','desc'=>'Semua pintu dan jendela sudah ditutup dan dikunci'],
                ] as $item)
                <div class="p-3 rounded-3 mb-3 border">
                    <div class="d-flex align-items-start gap-3 mb-2">
                        <i class="{{ $item['icon'] }} fs-4 text-primary mt-1"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $item['label'] }}</div>
                            <div class="text-muted small">{{ $item['desc'] }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="{{ $item['name'] }}" id="{{ $item['name'] }}_ya" value="1" required {{ old($item['name'])==='1'?'checked':'' }}>
                            <label class="form-check-label text-success fw-semibold" for="{{ $item['name'] }}_ya">
                                <i class="bi bi-check-circle me-1"></i>Sudah
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="{{ $item['name'] }}" id="{{ $item['name'] }}_tidak" value="0" {{ old($item['name'])==='0'?'checked':'' }}>
                            <label class="form-check-label text-danger fw-semibold" for="{{ $item['name'] }}_tidak">
                                <i class="bi bi-x-circle me-1"></i>Belum/Tidak
                            </label>
                        </div>
                    </div>
                    @error($item['name'])<div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
                </div>
                @endforeach

                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan Tambahan <span class="text-muted">(opsional)</span></label>
                    <textarea name="catatan_tambahan" class="form-control" rows="3"
                              placeholder="Tuliskan jika ada kerusakan atau hal yang perlu dilaporkan...">{{ old('catatan_tambahan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-semibold"><i class="bi bi-camera-fill text-primary me-2"></i>Foto Kondisi Ruangan</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Foto <span class="text-danger">*</span></label>
                    <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                           accept="image/*" required>
                    <div class="form-text">Format: JPG, PNG. Maks 5MB.</div>
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div id="foto-preview-container" class="d-none">
                    <div class="fw-semibold small text-muted mb-2">Preview:</div>
                    <img id="foto-preview" src="" alt="Preview foto" class="img-fluid rounded border" style="max-height:250px;width:100%;object-fit:cover">
                </div>
                <div id="foto-placeholder" class="text-center py-4 text-muted border rounded">
                    <i class="bi bi-image fs-2 d-block mb-2"></i>
                    <span class="small">Foto belum dipilih</span>
                </div>
            </div>
        </div>

        <div class="card mt-4 border-danger">
            <div class="card-body text-center p-4">
                <i class="bi bi-exclamation-circle-fill text-danger fs-2 mb-2 d-block"></i>
                <div class="fw-bold mb-1">Setelah Submit</div>
                <div class="text-muted small mb-3">Segera kembalikan kunci kepada Pengawas untuk menyelesaikan proses peminjaman.</div>
                <button type="submit" class="btn btn-success btn-lg w-100" id="btn-submit-checklist">
                    <i class="bi bi-clipboard-check me-2"></i>Submit Checklist
                </button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('foto-preview').src = e.target.result;
        document.getElementById('foto-preview-container').classList.remove('d-none');
        document.getElementById('foto-placeholder').classList.add('d-none');
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endpush
