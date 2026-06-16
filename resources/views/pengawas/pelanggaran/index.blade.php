@extends('layouts.app')
@section('title', 'Daftar Pelanggaran')
@section('page-title', 'Daftar Pelanggaran')

@section('content')
<div class="card fade-in-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Daftar Pelanggaran ({{ $pelanggaran->total() }})</span>
        <a href="{{ route('pengawas.pelanggaran.create') }}" class="btn btn-danger btn-sm" id="btn-catat-pelanggaran">
            <i class="bi bi-plus-lg me-1"></i>Catat Pelanggaran
        </a>
    </div>
    <div class="card-body p-0">
        @if($pelanggaran->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>No</th><th>Murid</th><th>Lab</th><th>Tipe</th><th>Poin</th><th>Keterangan</th><th>Dicatat Oleh</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($pelanggaran as $i => $p)
                    <tr>
                        <td>{{ $pelanggaran->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold small">{{ $p->murid->name }}</div>
                            <div class="text-muted" style="font-size:.73rem">{{ $p->murid->kelas }}</div>
                        </td>
                        <td class="small">{{ optional($p->peminjaman?->lab)->nama ?? '—' }}</td>
                        <td><span class="badge bg-warning text-dark">{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }}</span></td>
                        <td><span class="badge bg-danger">+{{ $p->poin }}</span></td>
                        <td class="small">{{ Str::limit($p->keterangan, 50) }}</td>
                        <td class="small">{{ $p->dicatatOleh->name }}</td>
                        <td class="small">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('pengawas.pelanggaran.destroy', $p) }}" method="POST"
                                  onsubmit="return confirm('Hapus catatan pelanggaran ini? Poin murid akan dikurangi.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada catatan pelanggaran.</p>
        </div>
        @endif
    </div>
    @if($pelanggaran->hasPages())
    <div class="card-footer">{{ $pelanggaran->links() }}</div>
    @endif
</div>
@endsection
