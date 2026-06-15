<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Pengawas\DashboardController as PengawasDashboard;
use App\Http\Controllers\Pengawas\KunciController;
use App\Http\Controllers\Pengawas\PelanggaranController;
use App\Http\Controllers\Murid\DashboardController as MuridDashboard;
use App\Http\Controllers\Murid\PeminjamanController;
use App\Http\Controllers\Murid\ChecklistController;
use App\Http\Controllers\Murid\PassKunciController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/reset-poin', [UserController::class, 'resetPoin'])->name('users.reset-poin');
        Route::post('/users/{user}/toggle-block', [UserController::class, 'toggleBlock'])->name('users.toggle-block');

        // Lab Management
        Route::get('/labs', [LabController::class, 'index'])->name('labs.index');
        Route::get('/labs/create', [LabController::class, 'create'])->name('labs.create');
        Route::post('/labs', [LabController::class, 'store'])->name('labs.store');
        Route::get('/labs/{lab}/edit', [LabController::class, 'edit'])->name('labs.edit');
        Route::put('/labs/{lab}', [LabController::class, 'update'])->name('labs.update');
        Route::delete('/labs/{lab}', [LabController::class, 'destroy'])->name('labs.destroy');

        // Laporan
        Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/peminjaman/print', [LaporanController::class, 'printPeminjaman'])->name('laporan.peminjaman.print');
        Route::get('/laporan/peminjaman/{peminjaman}/print', [LaporanController::class, 'printDetail'])->name('laporan.detail.print');
        Route::get('/laporan/peminjaman/{peminjaman}', [LaporanController::class, 'detail'])->name('laporan.detail');
        Route::get('/laporan/keterlambatan', [LaporanController::class, 'keterlambatan'])->name('laporan.keterlambatan');
        Route::get('/laporan/keterlambatan/print', [LaporanController::class, 'printKeterlambatan'])->name('laporan.keterlambatan.print');
        Route::get('/laporan/pelanggaran', [LaporanController::class, 'pelanggaran'])->name('laporan.pelanggaran');
        Route::get('/laporan/pelanggaran/print', [LaporanController::class, 'printPelanggaran'])->name('laporan.pelanggaran.print');
    });

/*
|--------------------------------------------------------------------------
| Pengawas Routes
|--------------------------------------------------------------------------
*/
Route::prefix('pengawas')
    ->name('pengawas.')
    ->middleware(['auth', 'role:pengawas'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PengawasDashboard::class, 'index'])->name('dashboard');

        // Kunci Management
        Route::get('/kunci', [KunciController::class, 'index'])->name('kunci.index');
        Route::get('/kunci/{peminjaman}', [KunciController::class, 'show'])->name('kunci.show');
        Route::post('/kunci/{peminjaman}/setujui', [KunciController::class, 'setujui'])->name('kunci.setujui');
        Route::post('/kunci/{peminjaman}/tolak', [KunciController::class, 'tolak'])->name('kunci.tolak');
        Route::post('/kunci/{peminjaman}/serah', [KunciController::class, 'serahKunci'])->name('kunci.serah');
        Route::post('/kunci/{peminjaman}/terima', [KunciController::class, 'terimaKunci'])->name('kunci.terima');

        // Pelanggaran
        Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
        Route::get('/pelanggaran/create', [PelanggaranController::class, 'create'])->name('pelanggaran.create');
        Route::post('/pelanggaran', [PelanggaranController::class, 'store'])->name('pelanggaran.store');
        Route::delete('/pelanggaran/{pelanggaran}', [PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');
    });

/*
|--------------------------------------------------------------------------
| Murid Routes
|--------------------------------------------------------------------------
*/
Route::prefix('murid')
    ->name('murid.')
    ->middleware(['auth', 'role:murid', 'check.blocked'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [MuridDashboard::class, 'index'])->name('dashboard');

        // Peminjaman
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

        // Checklist Pengembalian
        Route::get('/peminjaman/{peminjaman}/checklist', [ChecklistController::class, 'create'])->name('checklist.create');
        Route::post('/peminjaman/{peminjaman}/checklist', [ChecklistController::class, 'store'])->name('checklist.store');

        // Pass Kunci Langsung
        Route::get('/peminjaman/{peminjaman}/pass-kunci', [PassKunciController::class, 'showQr'])->name('pass-kunci.qr');
        Route::post('/peminjaman/{peminjaman}/pass-kunci/generate', [PassKunciController::class, 'generate'])->name('pass-kunci.generate');
        Route::post('/peminjaman/{peminjaman}/pass-kunci/status', [PassKunciController::class, 'status'])->name('pass-kunci.status');
        Route::get('/ambil-kunci', [PassKunciController::class, 'scanPage'])->name('pass-kunci.scan');
        Route::post('/ambil-kunci/redeem', [PassKunciController::class, 'redeem'])->name('pass-kunci.redeem');
    });
