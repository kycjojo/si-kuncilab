<?php $__env->startSection('title', 'Detail Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Detail Peminjaman — Laporan Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    
    <div class="col-lg-5">
        
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person-fill text-primary me-2"></i>Data Murid</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;font-size:1.3rem;font-weight:700;flex-shrink:0">
                        <?php echo e(strtoupper(substr($peminjaman->murid->name, 0, 1))); ?>

                    </div>
                    <div>
                        <div class="fw-bold"><?php echo e($peminjaman->murid->name); ?></div>
                        <div class="text-muted small"><?php echo e($peminjaman->murid->nis ?? '—'); ?> · <?php echo e($peminjaman->murid->kelas ?? '—'); ?></div>
                        <div class="small">
                            Poin: <span class="badge <?php echo e($peminjaman->murid->poin_pelanggaran >= 4 ? 'bg-danger' : ($peminjaman->murid->poin_pelanggaran >= 2 ? 'bg-warning text-dark' : 'bg-success')); ?>"><?php echo e($peminjaman->murid->poin_pelanggaran); ?>/5</span>
                            <?php if($peminjaman->murid->is_blocked): ?> <span class="badge bg-danger ms-1"><i class="bi bi-lock-fill me-1"></i>Diblokir</span> <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Peminjaman</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted small fw-semibold" style="width:40%">Lab</td><td class="fw-bold"><?php echo e($peminjaman->lab->nama); ?></td></tr>
                    <tr><td class="text-muted small fw-semibold">Tujuan</td><td><?php echo e($peminjaman->tujuan); ?></td></tr>
                    <?php if($peminjaman->keterangan): ?>
                    <tr><td class="text-muted small fw-semibold">Keterangan</td><td class="small"><?php echo e($peminjaman->keterangan); ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="text-muted small fw-semibold">Tanggal</td><td><?php echo e($peminjaman->tanggal_pinjam->format('d/m/Y')); ?></td></tr>
                    <tr><td class="text-muted small fw-semibold">Jam</td><td><?php echo e(substr($peminjaman->jam_mulai,0,5)); ?> — <?php echo e(substr($peminjaman->jam_selesai_rencana,0,5)); ?></td></tr>
                    <?php if($peminjaman->jam_selesai_aktual): ?>
                    <tr>
                        <td class="text-muted small fw-semibold">Selesai Aktual</td>
                        <td class="<?php echo e($peminjaman->isTerlambat() ? 'text-danger fw-bold' : 'text-success'); ?>">
                            <?php echo e(substr($peminjaman->jam_selesai_aktual,0,5)); ?>

                            <?php if($peminjaman->isTerlambat()): ?> <span class="badge bg-danger ms-1">Terlambat</span> <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted small fw-semibold">Status</td>
                        <td><span class="badge bg-<?php echo e(\App\Models\Peminjaman::statusBadge($peminjaman->status)); ?> fs-6"><?php echo e(\App\Models\Peminjaman::statusLabel($peminjaman->status)); ?></span></td>
                    </tr>
                    <?php if($peminjaman->disetujuiOleh): ?>
                    <tr><td class="text-muted small fw-semibold">Disetujui oleh</td><td class="small"><?php echo e($peminjaman->disetujuiOleh->name); ?></td></tr>
                    <?php endif; ?>
                    <?php if($peminjaman->catatan_pengawas): ?>
                    <tr><td class="text-muted small fw-semibold">Catatan Pengawas</td><td class="small"><?php echo e($peminjaman->catatan_pengawas); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        
        <?php if($peminjaman->penyerahanKunci): ?>
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-key-fill text-warning me-2"></i>Info Penyerahan Kunci</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted small" style="width:45%">Pengawas</td><td class="fw-semibold"><?php echo e($peminjaman->penyerahanKunci->pengawas->name); ?></td></tr>
                    <tr><td class="text-muted small">Waktu Serah</td><td><?php echo e($peminjaman->penyerahanKunci->waktu_serah?->format('H:i, d/m/Y') ?? '—'); ?></td></tr>
                    <tr><td class="text-muted small">Waktu Kembali</td><td><?php echo e($peminjaman->penyerahanKunci->waktu_terima_kembali?->format('H:i, d/m/Y') ?? '—'); ?></td></tr>
                </table>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($peminjaman->pelanggaran->count() > 0): ?>
        <div class="card">
            <div class="card-header"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Pelanggaran (<?php echo e($peminjaman->pelanggaran->count()); ?>)</div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $peminjaman->pelanggaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-warning text-dark"><?php echo e(\App\Models\Pelanggaran::tipeLabel($p->tipe)); ?></span>
                        <span class="badge bg-danger">+<?php echo e($p->poin); ?> poin</span>
                    </div>
                    <div class="small mt-1"><?php echo e($p->keterangan); ?></div>
                    <div class="text-muted" style="font-size:.7rem">Dicatat oleh: <?php echo e($p->dicatatOleh->name); ?> · <?php echo e($p->created_at->format('d/m/Y H:i')); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-7">
        <?php if($peminjaman->checklistPengembalian): ?>
        <?php $cl = $peminjaman->checklistPengembalian; ?>

        
        <div class="card mb-4 border-primary border-2">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-camera-fill me-2"></i>Foto Kondisi Lab — <?php echo e($peminjaman->lab->nama); ?>

            </div>
            <div class="card-body p-3">
                <?php if($cl->foto_path): ?>
                <div class="text-center">
                    <img src="<?php echo e(Storage::url($cl->foto_path)); ?>" alt="Foto kondisi <?php echo e($peminjaman->lab->nama); ?>"
                         class="img-fluid rounded shadow" style="max-height:450px;width:100%;object-fit:contain;cursor:pointer"
                         onclick="window.open(this.src, '_blank')" title="Klik untuk memperbesar">
                </div>
                <div class="text-center mt-2">
                    <span class="text-muted small"><i class="bi bi-zoom-in me-1"></i>Klik foto untuk memperbesar</span>
                </div>
                <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-image fs-1 d-block mb-2"></i>
                    <span>Tidak ada foto yang diupload</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-clipboard-check-fill text-success me-2"></i>Checklist Kondisi Ruangan</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <?php $__currentLoopData = [
                        ['label'=>'Listrik Dimatikan','val'=>$cl->kondisi_listrik,'icon'=>'bi-lightning-charge-fill'],
                        ['label'=>'AC Dimatikan','val'=>$cl->kondisi_ac,'icon'=>'bi-thermometer-half'],
                        ['label'=>'Perangkat Baik','val'=>$cl->kondisi_perangkat,'icon'=>'bi-pc-display'],
                        ['label'=>'Ruangan Bersih','val'=>$cl->kondisi_kebersihan,'icon'=>'bi-stars'],
                        ['label'=>'Pintu/Jendela Terkunci','val'=>$cl->kondisi_pintu,'icon'=>'bi-door-closed-fill'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 <?php echo e($item['val'] ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-danger bg-opacity-10 border border-danger border-opacity-25'); ?>">
                            <i class="<?php echo e($item['icon']); ?> fs-5 <?php echo e($item['val'] ? 'text-success' : 'text-danger'); ?>"></i>
                            <span class="small fw-semibold"><?php echo e($item['label']); ?></span>
                            <i class="ms-auto bi bi-<?php echo e($item['val'] ? 'check-circle-fill text-success' : 'x-circle-fill text-danger'); ?> fs-5"></i>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php
                    $masalah = collect([
                        !$cl->kondisi_listrik ? 'Listrik belum dimatikan' : null,
                        !$cl->kondisi_ac ? 'AC belum dimatikan' : null,
                        !$cl->kondisi_perangkat ? 'Perangkat tidak dalam kondisi baik' : null,
                        !$cl->kondisi_kebersihan ? 'Ruangan belum dibersihkan' : null,
                        !$cl->kondisi_pintu ? 'Pintu/jendela belum terkunci' : null,
                    ])->filter();
                ?>

                <?php if($masalah->count() > 0): ?>
                <div class="alert alert-danger py-2 mb-3">
                    <div class="fw-bold small mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo e($masalah->count()); ?> kondisi bermasalah:</div>
                    <ul class="mb-0 small ps-3">
                        <?php $__currentLoopData = $masalah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($m); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php else: ?>
                <div class="alert alert-success py-2 mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i><strong>Semua kondisi ruangan baik.</strong>
                </div>
                <?php endif; ?>

                <?php if($cl->catatan_tambahan): ?>
                <div class="p-3 bg-light rounded-3">
                    <div class="small fw-semibold text-muted mb-1"><i class="bi bi-chat-left-text me-1"></i>Catatan dari Murid:</div>
                    <div class="small"><?php echo e($cl->catatan_tambahan); ?></div>
                </div>
                <?php endif; ?>

                <div class="text-muted small mt-3">
                    <i class="bi bi-clock me-1"></i>Checklist diisi pada: <?php echo e($cl->created_at->format('H:i, d/m/Y')); ?>

                </div>
            </div>
        </div>

        <?php else: ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-clipboard-x text-muted fs-1 d-block mb-3"></i>
                <div class="fw-bold fs-5 text-muted">Belum Ada Checklist</div>
                <div class="text-muted small">Murid belum mengisi checklist pengembalian.</div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="<?php echo e(route('admin.laporan.peminjaman')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Laporan</a>
    <a href="<?php echo e(route('admin.users.show', $peminjaman->murid)); ?>" class="btn btn-outline-primary"><i class="bi bi-person me-1"></i>Profil Murid</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\jojo\Desktop\lab-key-system\resources\views/admin/laporan/detail.blade.php ENDPATH**/ ?>