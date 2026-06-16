@extends('layouts.app')
@section('title', 'Edit Lab')
@section('page-title', 'Edit Lab')
@section('back-url', route('admin.labs.index'))

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6 fade-in-up delay-1">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil-fill text-primary me-2"></i>Edit Lab — {{ $lab->nama }}</div>
    <div class="card-body p-4">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('admin.labs.update', $lab) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lab <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $lab->nama) }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Lab <span class="text-danger">*</span></label>
                <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $lab->kode) }}" maxlength="20" required>
                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', $lab->kapasitas) }}" min="1" required>
                    <span class="input-group-text">orang</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $lab->deskripsi) }}</textarea>
            </div>
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is-active"
                           {{ old('is_active', $lab->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is-active">Lab Aktif</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="btn-update-lab"><i class="bi bi-check-lg me-1"></i>Update Lab</button>
                <a href="{{ route('admin.labs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
