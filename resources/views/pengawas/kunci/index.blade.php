@extends('layouts.app')
@section('title', 'Kelola Kunci Lab')
@section('page-title', 'Kelola Kunci Lab')

@section('content')
<div data-realtime-page data-realtime-interval="4000">
<div data-realtime-region="pengawas-kunci-index">
<div class="card">
    <div class="card-header"><i class="bi bi-key-fill text-primary me-2"></i>Semua Peminjaman Aktif</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>No</th><th>Murid</th><th>Lab</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $pm)
                <tr>
                    <td>{{ $peminjaman->firstItem() + $i }}</td>
                    <td>
                        <div class="fw-semibold">{{ $pm->murid->name }}</div>
                        <div class="text-muted small">{{ $pm->murid->kelas }}</div>
                    </td>
                    <td><span class="badge bg-primary">{{ $pm->lab->nama }}</span></td>
                    <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="small">{{ substr($pm->jam_mulai,0,5) }}–{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                    <td>
                        <span class="badge bg-{{ \App\Models\Peminjaman::statusBadge($pm->status) }}">
                            {{ \App\Models\Peminjaman::statusLabel($pm->status) }}
                        </span>
                    </td>
                    <td>
                        @if($pm->status === 'menunggu')
                        <div class="d-flex gap-1">
                            <form action="{{ route('pengawas.kunci.setujui', $pm) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui peminjaman ini?')"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $pm->id }}"><i class="bi bi-x-lg"></i></button>
                        </div>
                        @elseif($pm->status === 'disetujui')
                        <form action="{{ route('pengawas.kunci.serah', $pm) }}" method="POST" onsubmit="return confirm('Serahkan kunci ke {{ $pm->murid->name }}?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-key me-1"></i>Serahkan Kunci</button>
                        </form>
                        @elseif($pm->status === 'berlangsung')
                        <a href="{{ route('pengawas.kunci.show', $pm) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                        @elseif($pm->status === 'checklist_diisi')
                        <div class="d-flex gap-1">
                            <a href="{{ route('pengawas.kunci.show', $pm) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-camera me-1"></i>Lihat Foto & Checklist
                            </a>
                            <form action="{{ route('pengawas.kunci.terima', $pm) }}" method="POST" onsubmit="return confirm('Terima kunci dari {{ $pm->murid->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-key-fill me-1"></i>Terima</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                {{-- Modal Tolak --}}
                <div class="modal fade" id="modalTolak{{ $pm->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Tolak Peminjaman</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <form action="{{ route('pengawas.kunci.tolak', $pm) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p class="small text-muted">Tolak peminjaman <strong>{{ $pm->murid->name }}</strong> untuk <strong>{{ $pm->lab->nama }}</strong></p>
                                    <label class="form-label fw-semibold">Alasan <span class="text-danger">*</span></label>
                                    <textarea name="catatan_pengawas" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada peminjaman aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer">{{ $peminjaman->links() }}</div>
    @endif
</div>
</div>
</div>
@endsection
