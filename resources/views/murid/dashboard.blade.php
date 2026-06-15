@extends('layouts.app')
@section('title', 'Dashboard Murid')
@section('page-title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Blokir Warning --}}
@if($user->is_blocked)
<div class="row g-4">
    <div class="col-lg-8">
        <div class="alert alert-danger d-flex gap-3 align-items-center mb-0" role="alert">
            <i class="bi bi-lock-fill fs-3"></i>
            <div>
                <div class="fw-bold fs-5">Akun Anda Diblokir</div>
                <div>Total poin pelanggaran Anda telah mencapai <strong>{{ $user->poin_pelanggaran }} poin</strong>. Anda tidak dapat melakukan peminjaman. Hubungi Pengawas atau Admin untuk membuka blokir.</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100 border-danger">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <div class="fw-bold text-danger mb-2">Poin: {{ $user->poin_pelanggaran }}/5</div>
                <div class="small text-muted">Akun akan terbuka otomatis setelah admin mereset poin.</div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Poin Alert --}}
@if(!$user->is_blocked)
    @if($user->poin_pelanggaran >= 4)
    <div class="alert alert-danger py-2 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Peringatan!</strong> Poin pelanggaran Anda sudah <strong>{{ $user->poin_pelanggaran }}/5</strong>. Jika mencapai 5, akun akan diblokir!
    </div>
    @elseif($user->poin_pelanggaran >= 2)
    <div class="alert alert-warning py-2 mb-4">
        <i class="bi bi-exclamation-circle me-2"></i>
        Poin pelanggaran Anda: <strong>{{ $user->poin_pelanggaran }}/5</strong>. Harap menjaga ketertiban.
    </div>
    @endif
@endif

{{-- Main grid: 2-column layout to fill horizontal space --}}
<div class="row g-4">

    {{-- ── LEFT COLUMN (welcome + active content) ── --}}
    <div class="col-lg-7">

        {{-- Selamat Datang --}}
        <div class="card p-4 mb-4" style="background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold fs-5">Selamat Datang!</div>
                    <div class="opacity-75 small mb-3">{{ $user->name }}</div>
                    <div class="row gx-3">
                        <div class="col-auto"><span class="small opacity-75">NIS:</span> <span class="fw-semibold">{{ $user->nis ?? '—' }}</span></div>
                        <div class="col-auto"><span class="small opacity-75">Kelas:</span> <span class="fw-semibold">{{ $user->kelas ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:56px;height:56px;background:rgba(255,255,255,.2);font-size:1.5rem;font-weight:700">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
            @if(!$user->is_blocked)
            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('murid.peminjaman.create') }}" class="btn btn-light btn-sm fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i>Ajukan Peminjaman
                </a>
                <a href="{{ route('murid.pass-kunci.scan') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-qr-code-scan me-1"></i>Ambil Kunci
                </a>
            </div>
            @endif
        </div>

        {{-- Quick Borrow (tidak ada peminjaman aktif) --}}
        @if(!$user->is_blocked && !$peminjaman_aktif)
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-lightning-fill text-warning"></i>
                <span>Quick Borrow</span>
                <span class="badge bg-warning text-dark ms-1" style="font-size:.6rem">3 KLIK SAJA</span>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3" id="quick-lab-grid">
                    @foreach($labs as $lab)
                    <div class="col-4 col-md-3 col-lg-2">
                        <div class="card text-center p-2 quick-lab-card"
                             data-lab-id="{{ $lab->id }}"
                             data-lab-name="{{ $lab->nama }}"
                             style="cursor:pointer;border:2px solid transparent;transition:all .15s">
                            <div class="fw-bold" style="font-size:.8rem">{{ $lab->kode }}</div>
                            <div style="font-size:.6rem;color:#64748B">{{ $lab->kapasitas }} org</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div id="quick-form-panel" class="border rounded p-3" style="display:none;background:#F8FAFC">
                    <form id="quick-borrow-form" action="{{ route('murid.peminjaman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lab_id" id="q-lab-id">
                        <input type="hidden" name="tanggal_pinjam" value="{{ date('Y-m-d') }}">
                        <input type="hidden" name="jam_mulai" id="q-jam-mulai">
                        <input type="hidden" name="jam_selesai_rencana" id="q-jam-selesai">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 mb-1">
                                <span class="fw-bold" id="q-lab-display" style="font-size:.9rem"></span>
                                <span class="text-muted ms-2" style="font-size:.75rem">Hari ini, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</span>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" style="font-size:.75rem;font-weight:600">Pilih Sesi</label>
                                <div class="d-flex flex-wrap gap-1" id="q-sessions">
                                    <button type="button" class="btn btn-sm btn-outline-primary sesi-btn" data-start="07:00" data-end="08:30">07:00–08:30</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary sesi-btn" data-start="08:30" data-end="10:00">08:30–10:00</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary sesi-btn" data-start="10:00" data-end="11:30">10:00–11:30</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary sesi-btn" data-start="11:30" data-end="13:00">11:30–13:00</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary sesi-btn" data-start="13:00" data-end="14:30">13:00–14:30</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary sesi-btn" data-start="14:30" data-end="16:00">14:30–16:00</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:.75rem;font-weight:600">Tujuan</label>
                                <input type="text" name="tujuan" class="form-control form-control-sm" value="Praktikum" placeholder="Praktikum..." required>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-primary btn-sm" id="q-submit" disabled>
                                    <i class="bi bi-send me-1"></i>Kirim Pengajuan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('scripts')
        <script>
        (function() {
            const cards = document.querySelectorAll('.quick-lab-card');
            const panel = document.getElementById('quick-form-panel');
            const labId = document.getElementById('q-lab-id');
            const labDisplay = document.getElementById('q-lab-display');
            const jamMulai = document.getElementById('q-jam-mulai');
            const jamSelesai = document.getElementById('q-jam-selesai');
            const submitBtn = document.getElementById('q-submit');
            const sesiBtns = document.querySelectorAll('.sesi-btn');
            let selectedLab = null, selectedSesi = null;

            cards.forEach(c => c.addEventListener('click', function() {
                cards.forEach(x => { x.style.borderColor = 'transparent'; x.style.background = ''; });
                this.style.borderColor = '#4F46E5';
                this.style.background = '#EEF2FF';
                selectedLab = { id: this.dataset.labId, name: this.dataset.labName };
                labId.value = selectedLab.id;
                labDisplay.textContent = selectedLab.name;
                selectedSesi = null;
                sesiBtns.forEach(b => b.classList.remove('active'));
                submitBtn.disabled = true;
                panel.style.display = 'block';
            }));

            sesiBtns.forEach(b => b.addEventListener('click', function() {
                sesiBtns.forEach(x => x.classList.remove('active'));
                this.classList.add('active');
                selectedSesi = { start: this.dataset.start, end: this.dataset.end };
                jamMulai.value = selectedSesi.start;
                jamSelesai.value = selectedSesi.end;
                submitBtn.disabled = !selectedLab;
            }));
        })();
        </script>
        @endpush
        @endif

    </div>

    {{-- ── RIGHT COLUMN (stats + status) ── --}}
    <div class="col-lg-5">

        {{-- Stat cards grid --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-3 fw-bold text-primary">{{ $stats['total_peminjaman'] }}</div>
                    <div class="text-muted" style="font-size:.75rem">Total Peminjaman</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-3 fw-bold text-success">{{ $stats['selesai'] }}</div>
                    <div class="text-muted" style="font-size:.75rem">Selesai</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-3 fw-bold text-danger">{{ $stats['terlambat'] }}</div>
                    <div class="text-muted" style="font-size:.75rem">Terlambat</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center p-3 h-100">
                    <div class="fs-3 fw-bold {{ $user->poin_pelanggaran >= 4 ? 'text-danger' : 'text-secondary' }}">
                        {{ $stats['poin_pelanggaran'] }}
                    </div>
                    <div class="text-muted" style="font-size:.75rem">Poin Pelanggaran</div>
                </div>
            </div>
        </div>

        {{-- Active Peminjaman card (when user has one) --}}
        @if($peminjaman_aktif)
        <div class="card border-primary border-2 mb-4">
            <div class="card-header bg-primary text-white py-2">
                <i class="bi bi-clock-fill me-2"></i>Peminjaman Aktif
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $peminjaman_aktif->lab->nama }}</h5>
                <div class="text-muted small mb-2">
                    <i class="bi bi-calendar3 me-1"></i>{{ $peminjaman_aktif->tanggal_pinjam->format('d/m/Y') }}
                    &nbsp;|&nbsp;
                    <i class="bi bi-clock me-1"></i>{{ substr($peminjaman_aktif->jam_mulai,0,5) }}–{{ substr($peminjaman_aktif->jam_selesai_rencana,0,5) }}
                </div>
                <div class="fw-semibold small mb-2">{{ $peminjaman_aktif->tujuan }}</div>

                <hr class="my-2">

                @if($peminjaman_aktif->status === 'menunggu')
                    <span class="badge bg-warning text-dark fs-6 d-block text-center py-2"><i class="bi bi-hourglass me-1"></i>Menunggu Konfirmasi</span>
                @elseif($peminjaman_aktif->status === 'disetujui')
                    <span class="badge bg-info text-dark fs-6 d-block text-center py-2 mb-2"><i class="bi bi-check me-1"></i>Disetujui</span>
                    <div class="small text-muted text-center">Ambil kunci dari pengawas</div>
                @elseif($peminjaman_aktif->status === 'berlangsung')
                    <span class="badge bg-success fs-6 d-block text-center py-2 mb-2"><i class="bi bi-door-open me-1"></i>Berlangsung</span>
                    @if($peminjaman_aktif->checklistPengembalian)
                        <a href="{{ route('murid.pass-kunci.qr', $peminjaman_aktif) }}" class="btn btn-warning btn-sm w-100 mb-1">
                            <i class="bi bi-qr-code me-1"></i>Pass Kunci ke Murid Lain
                        </a>
                        <div class="text-muted text-center" style="font-size:.7rem">Checklist sudah diisi ✓</div>
                    @else
                        <a href="{{ route('murid.checklist.create', $peminjaman_aktif) }}" class="btn btn-primary btn-sm w-100 mb-1" id="btn-isi-checklist">
                            <i class="bi bi-clipboard-check me-1"></i>Isi Checklist Pengembalian
                        </a>
                        <div class="text-muted text-center" style="font-size:.7rem">Isi checklist dulu untuk pass kunci</div>
                    @endif
                @elseif($peminjaman_aktif->status === 'checklist_diisi')
                    <span class="badge bg-info text-dark fs-6 d-block text-center py-2 mb-2"><i class="bi bi-check-circle me-1"></i>Checklist Diisi</span>
                    <a href="{{ route('murid.pass-kunci.qr', $peminjaman_aktif) }}" class="btn btn-warning btn-sm w-100 mb-1">
                        <i class="bi bi-qr-code me-1"></i>Pass Kunci ke Murid Lain
                    </a>
                    <div class="text-muted text-center" style="font-size:.7rem">atau kembalikan kunci ke pengawas</div>
                @endif
            </div>
            <div class="card-footer text-center py-2">
                <a href="{{ route('murid.peminjaman.show', $peminjaman_aktif) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
        @elseif(!$user->is_blocked)
        {{-- Empty state (no active peminjaman) --}}
        <div class="card mb-4" style="border:2px dashed #CBD5E1;box-shadow:none">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-plus text-muted fs-1 mb-2 d-block"></i>
                <div class="text-muted mb-3 small">Tidak ada peminjaman aktif.</div>
                <div class="d-grid gap-2">
                    <a href="{{ route('murid.peminjaman.create') }}" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-plus-circle me-1"></i>Ajukan Peminjaman
                    </a>
                    <a href="{{ route('murid.pass-kunci.scan') }}" class="btn btn-outline-warning btn-sm px-3">
                        <i class="bi bi-qr-code-scan me-1"></i>Ambil Kunci (Scan QR)
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Riwayat Terbaru (always visible) --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <span style="font-size:.85rem"><i class="bi bi-clock-history text-primary me-1"></i>Riwayat Terbaru</span>
                @if($riwayat_terbaru->count() > 0)
                <a href="{{ route('murid.peminjaman.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.7rem">Lihat Semua</a>
                @endif
            </div>
            @if($riwayat_terbaru->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Lab</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @foreach($riwayat_terbaru as $pm)
                        <tr>
                            <td class="fw-semibold" style="font-size:.8rem">{{ $pm->lab->nama }}</td>
                            <td style="font-size:.75rem">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $pm->statusBadgeForDisplay() }}" style="font-size:.65rem">{{ $pm->statusLabelForDisplay() }}</span></td>
                            <td><a href="{{ route('murid.peminjaman.show', $pm) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.65rem;padding:1px 6px"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center py-3">
                <i class="bi bi-inbox text-muted d-block mb-1" style="font-size:1.5rem"></i>
                <span class="text-muted" style="font-size:.8rem">Belum ada riwayat peminjaman.</span>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
