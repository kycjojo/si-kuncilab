@extends('layouts.app')
@section('title', 'Kelola Lab')
@section('page-title', 'Kelola Lab')

@section('content')
<div class="card fade-in-up delay-1">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building text-primary me-2"></i>Daftar Lab ({{ $labs->total() }})</span>
        <a href="{{ route('admin.labs.create') }}" class="btn btn-primary btn-sm" id="btn-tambah-lab">
            <i class="bi bi-plus-lg me-1"></i>Tambah Lab
        </a>
    </div>
    <div class="card-body p-0">
        @if($labs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Lab</th>
                        <th>Kapasitas</th>
                        <th>Total Peminjaman</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($labs as $i => $lab)
                    <tr>
                        <td>{{ $labs->firstItem() + $i }}</td>
                        <td><span class="badge bg-primary">{{ $lab->kode }}</span></td>
                        <td class="fw-semibold">{{ $lab->nama }}</td>
                        <td><i class="bi bi-people text-muted me-1"></i>{{ $lab->kapasitas }}</td>
                        <td>{{ $lab->peminjaman_count }}</td>
                        <td>
                            @if($lab->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.labs.edit', $lab) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.labs.destroy', $lab) }}" method="POST"
                                      onsubmit="return confirm('Hapus lab {{ $lab->nama }}? Data peminjaman terkait akan ikut terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-building"></i>
            <p>Belum ada data lab.</p>
        </div>
        @endif
    </div>
    @if($labs->hasPages())
    <div class="card-footer">{{ $labs->links() }}</div>
    @endif
</div>
@endsection
