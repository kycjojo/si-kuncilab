@extends('layouts.app')
@section('title', 'Dashboard Pengawas')
@section('page-title', 'Dashboard Pengawas')

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#F59E0B,#D97706)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-value">{{ $stats['menunggu'] }}</div><div class="stat-label">Menunggu Konfirmasi</div></div>
                <i class="bi bi-hourglass-split stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#4F46E5,#7C3AED)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-value">{{ $stats['berlangsung'] }}</div><div class="stat-label">Sedang Berlangsung</div></div>
                <i class="bi bi-door-open-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#EF4444,#DC2626)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-value">{{ $stats['checklist_menunggu'] }}</div><div class="stat-label">Terima Kunci</div></div>
                <i class="bi bi-key-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10B981,#059669)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-value">{{ $stats['selesai_hari_ini'] }}</div><div class="stat-label">Selesai Hari Ini</div></div>
                <i class="bi bi-check-circle-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Menunggu Konfirmasi --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-hourglass-split text-warning me-2"></i>Menunggu Konfirmasi</span>
        <span class="badge bg-warning text-dark">{{ $menunggu->count() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Murid</th><th>Lab</th><th>Tanggal</th><th>Jam</th><th>Tujuan</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($menunggu as $pm)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $pm->murid->name }}</div>
                        <div class="text-muted small">{{ $pm->murid->kelas }}</div>
                    </td>
                    <td><span class="badge bg-primary">{{ $pm->lab->nama }}</span></td>
                    <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="small">{{ substr($pm->jam_mulai,0,5) }}–{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                    <td class="small">{{ Str::limit($pm->tujuan, 40) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="{{ route('pengawas.kunci.setujui', $pm) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui peminjaman ini?')">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                            </form>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $pm->id }}">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </div>
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
                                    <p class="text-muted small">Tolak peminjaman <strong>{{ $pm->murid->name }}</strong> — <strong>{{ $pm->lab->nama }}</strong></p>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                        <textarea name="catatan_pengawas" class="form-control" rows="3" required placeholder="Tulis alasan penolakan..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada peminjaman menunggu konfirmasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Sedang Berlangsung --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-door-open-fill text-primary me-2"></i>Sedang Berlangsung</span>
        <span class="badge bg-primary">{{ $berlangsung->count() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Murid</th><th>Lab</th><th>Tanggal</th><th>Jam Selesai Rencana</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($berlangsung as $pm)
                <tr>
                    <td><div class="fw-semibold">{{ $pm->murid->name }}</div><div class="text-muted small">{{ $pm->murid->kelas }}</div></td>
                    <td><span class="badge bg-primary">{{ $pm->lab->nama }}</span></td>
                    <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="small text-danger fw-semibold">{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                    <td><span class="badge bg-info text-dark">Menunggu checklist murid</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada peminjaman berlangsung.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Menunggu Terima Kunci --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-key-fill text-danger me-2"></i>Menunggu Terima Kunci Kembali</span>
        <span class="badge bg-danger">{{ $checklist_menunggu->count() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Murid</th><th>Lab</th><th>Tanggal</th><th>Jam Selesai Rencana</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($checklist_menunggu as $pm)
                <tr>
                    <td><div class="fw-semibold">{{ $pm->murid->name }}</div><div class="text-muted small">{{ $pm->murid->kelas }}</div></td>
                    <td><span class="badge bg-primary">{{ $pm->lab->nama }}</span></td>
                    <td class="small">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="small text-danger fw-semibold">{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('pengawas.kunci.show', $pm) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-camera me-1"></i>Lihat Foto & Checklist
                            </a>
                            <form action="{{ route('pengawas.kunci.terima', $pm) }}" method="POST" onsubmit="return confirm('Konfirmasi terima kunci dari {{ $pm->murid->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-key me-1"></i>Terima
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada kunci yang menunggu diterima.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
