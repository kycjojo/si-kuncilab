@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
{{-- Stat Cards Row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3 fade-in-up delay-1">
        <div class="stat-card" style="background:linear-gradient(135deg,#4F46E5,#7C3AED)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['total_murid'] }}</div>
                    <div class="stat-label">Total Murid</div>
                </div>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-2">
        <div class="stat-card" style="background:linear-gradient(135deg,#10B981,#059669)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['peminjaman_hari_ini'] }}</div>
                    <div class="stat-label">Peminjaman Hari Ini</div>
                </div>
                <i class="bi bi-calendar-check-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#F59E0B,#D97706)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['peminjaman_berlangsung'] }}</div>
                    <div class="stat-label">Sedang Berlangsung</div>
                </div>
                <i class="bi bi-door-open-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#EF4444,#DC2626)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['murid_diblokir'] }}</div>
                    <div class="stat-label">Murid Diblokir</div>
                </div>
                <i class="bi bi-person-x-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 fade-in-up delay-2">
        <div class="card text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $stats['total_lab'] }}</div>
            <div class="text-muted small">Total Lab</div>
            <div class="badge bg-success mt-1">{{ $stats['lab_aktif'] }} aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-in-up delay-3">
        <div class="card text-center p-3">
            <div class="fs-2 fw-bold text-warning">{{ $stats['peminjaman_menunggu'] }}</div>
            <div class="text-muted small">Menunggu Konfirmasi</div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-in-up delay-4">
        <div class="card text-center p-3">
            <div class="fs-2 fw-bold text-secondary">{{ $stats['total_pengawas'] }}</div>
            <div class="text-muted small">Total Pengawas</div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-in-up delay-5">
        <div class="card text-center p-3">
            <div class="fs-2 fw-bold text-danger">{{ $stats['total_pelanggaran'] }}</div>
            <div class="text-muted small">Total Pelanggaran</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Chart --}}
    <div class="col-lg-7 fade-in-up delay-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart-fill text-primary me-2"></i>Tren Peminjaman 7 Hari Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="peminjamanChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Pelanggaran Terbaru --}}
    <div class="col-lg-5 fade-in-up delay-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Pelanggaran Terbaru</span>
                <a href="{{ route('admin.laporan.pelanggaran') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($pelanggaran_terbaru as $p)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:36px;height:36px;flex-shrink:0">
                        <i class="bi bi-exclamation-circle text-danger"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold small text-truncate">{{ $p->murid->name }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ \App\Models\Pelanggaran::tipeLabel($p->tipe) }} · +{{ $p->poin }} poin</div>
                    </div>
                    <span class="badge bg-danger">{{ $p->poin }}</span>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-shield-check"></i>
                    <p>Belum ada pelanggaran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Peminjaman Terbaru --}}
<div class="card mt-4 fade-in-up delay-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-data-fill text-primary me-2"></i>Peminjaman Terbaru</span>
        <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        @if($peminjaman_terbaru->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Murid</th>
                        <th>Lab</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman_terbaru as $i => $pm)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $pm->murid->name }}</div>
                            <div class="text-muted small">{{ $pm->murid->nis }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $pm->lab->nama }}</span></td>
                        <td>{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="small">{{ substr($pm->jam_mulai,0,5) }}–{{ substr($pm->jam_selesai_rencana,0,5) }}</td>
                        <td>
                            <span class="badge bg-{{ $pm->statusBadgeForDisplay() }}">
                                {{ $pm->statusLabelForDisplay() }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada data peminjaman</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('peminjamanChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($chart_data, 'tanggal')) !!},
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: {!! json_encode(array_column($chart_data, 'count')) !!},
            backgroundColor: 'rgba(79,70,229,0.8)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush
