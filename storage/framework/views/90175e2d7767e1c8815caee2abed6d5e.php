<?php $__env->startSection('title', 'Laporan Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Laporan Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Lab</label>
                <select name="lab_id" class="form-select form-select-sm">
                    <option value="">Semua Lab</option>
                    <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($lab->id); ?>" <?php echo e(request('lab_id')==$lab->id?'selected':''); ?>><?php echo e($lab->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = ['menunggu','disetujui','ditolak','berlangsung','checklist_diisi','selesai','terlambat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('status')===$s?'selected':''); ?>><?php echo e(\App\Models\Peminjaman::statusLabel($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Dari</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="<?php echo e(request('dari')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="<?php echo e(request('sampai')); ?>">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="<?php echo e(route('admin.laporan.peminjaman')); ?>" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
            <div class="col-md-1">
                <button type="button" onclick="window.print()" class="btn btn-outline-success btn-sm w-100">
                    <i class="bi bi-printer"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-clipboard-data-fill text-primary me-2"></i>Hasil Laporan (<?php echo e($peminjaman->total()); ?> data)</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Murid</th>
                    <th>Lab</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai Rencana</th>
                    <th>Jam Selesai Aktual</th>
                    <th>Status</th>
                    <th>Pengawas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($peminjaman->firstItem() + $i); ?></td>
                    <td>
                        <div class="fw-semibold small"><?php echo e($pm->murid->name); ?></div>
                        <div class="text-muted" style="font-size:.73rem"><?php echo e($pm->murid->nis); ?></div>
                    </td>
                    <td class="small"><?php echo e($pm->lab->nama); ?></td>
                    <td class="small"><?php echo e($pm->tanggal_pinjam->format('d/m/Y')); ?></td>
                    <td class="small"><?php echo e(substr($pm->jam_mulai,0,5)); ?></td>
                    <td class="small"><?php echo e(substr($pm->jam_selesai_rencana,0,5)); ?></td>
                    <td class="small"><?php echo e($pm->jam_selesai_aktual ? substr($pm->jam_selesai_aktual,0,5) : '—'); ?></td>
                    <td><span class="badge bg-<?php echo e(\App\Models\Peminjaman::statusBadge($pm->status)); ?>"><?php echo e(\App\Models\Peminjaman::statusLabel($pm->status)); ?></span></td>
                    <td class="small"><?php echo e(optional($pm->penyerahanKunci?->pengawas)->name ?? '—'); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.laporan.detail', $pm)); ?>" class="btn btn-sm btn-outline-info" title="Lihat detail & foto">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($peminjaman->hasPages()): ?>
    <div class="card-footer"><?php echo e($peminjaman->withQueryString()->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\jojo\Desktop\lab-key-system\resources\views/admin/laporan/peminjaman.blade.php ENDPATH**/ ?>