@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('back-url', route('admin.users.index'))

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7 fade-in-up delay-1">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil-fill text-primary me-2"></i>Edit User — {{ $user->name }}</div>
    <div class="card-body p-4">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password Baru <span class="text-muted">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                <select name="role" id="role-select" class="form-select" required>
                    <option value="admin" {{ old('role', $user->role)==='admin'?'selected':'' }}>Admin</option>
                    <option value="pengawas" {{ old('role', $user->role)==='pengawas'?'selected':'' }}>Pengawas</option>
                    <option value="murid" {{ old('role', $user->role)==='murid'?'selected':'' }}>Murid</option>
                </select>
            </div>
            <div id="murid-fields" class="{{ old('role', $user->role) === 'murid' ? '' : 'd-none' }}">
                <div class="p-3 bg-light rounded-3 mb-3">
                    <div class="fw-semibold small text-muted mb-2"><i class="bi bi-person-badge me-1"></i>Data Murid</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIS</label>
                            <input type="text" name="nis" class="form-control" value="{{ old('nis', $user->nis) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kelas</label>
                            <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $user->kelas) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="btn-update-user"><i class="bi bi-check-lg me-1"></i>Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('role-select').addEventListener('change', function() {
    document.getElementById('murid-fields').classList.toggle('d-none', this.value !== 'murid');
});
</script>
@endpush
