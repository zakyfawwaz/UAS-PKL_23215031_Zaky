<?php

use App\Http\Controllers\Admin\AnggotaDewanController;
use App\Http\Controllers\Admin\AktivitasDewanController;
use App\Http\Controllers\Admin\AktivitasStafFraksiController;
use App\Http\Controllers\Admin\StafFraksiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Anggota\AktivitasSayaController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\RekapSayaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Publik\BerandaController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Sistem Monitoring Aktivitas Anggota Dewan Fraksi PKS DPRD Kota Tegal
| Routes dikelompokkan berdasarkan hak akses (RBAC):
| 1. Public   - Akses tanpa login (Masyarakat / Guest)
| 2. Auth     - Memerlukan login
| 3. Admin    - Memerlukan login + role admin
| 4. Anggota  - Memerlukan login + role anggota
|
*/

// =============================================================================
// PUBLIC ROUTES (Masyarakat / Guest) — Tanpa Login
// =============================================================================
Route::controller(BerandaController::class)->group(function () {
    Route::get('/', 'index')->name('beranda');
    Route::get('/aktivitas-publik', 'aktivitasPublik')->name('publik.aktivitas');
    Route::get('/statistik', 'statistik')->name('publik.statistik');
    Route::get('/laporan-publik', 'laporanPublik')->name('publik.laporan');
});

// =============================================================================
// AUTH ROUTES (Login / Logout)
// =============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.proses');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =============================================================================
// AUTHENTICATED ROUTES — Memerlukan Login
// =============================================================================
Route::middleware('auth')->group(function () {

    // =========================================================================
    // PROFIL — Akses untuk semua user yang sudah login
    // =========================================================================
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    // =========================================================================
    // ADMIN ROUTES — Hanya Admin Fraksi
    // =========================================================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD Anggota Dewan
        Route::resource('anggota-dewan', AnggotaDewanController::class);

        // CRUD Staf Fraksi
        Route::get('staf-fraksi', [StafFraksiController::class, 'index'])->name('staf-fraksi.index');
        Route::get('staf-fraksi/create', [StafFraksiController::class, 'create'])->name('staf-fraksi.create');
        Route::post('staf-fraksi', [StafFraksiController::class, 'store'])->name('staf-fraksi.store');
        Route::get('staf-fraksi/{type}/{id}/edit', [StafFraksiController::class, 'edit'])->name('staf-fraksi.edit');
        Route::put('staf-fraksi/{type}/{id}', [StafFraksiController::class, 'update'])->name('staf-fraksi.update');
        Route::delete('staf-fraksi/{type}/{id}', [StafFraksiController::class, 'destroy'])->name('staf-fraksi.destroy');

        // CRUD Aktivitas Dewan & Aktivitas Staf Fraksi
        Route::resource('aktivitas-dewan', AktivitasDewanController::class);

        Route::get('aktivitas-staf-fraksi', [AktivitasStafFraksiController::class, 'index'])->name('aktivitas-staf-fraksi.index');
        Route::get('aktivitas-staf-fraksi/create', [AktivitasStafFraksiController::class, 'create'])->name('aktivitas-staf-fraksi.create');
        Route::post('aktivitas-staf-fraksi', [AktivitasStafFraksiController::class, 'store'])->name('aktivitas-staf-fraksi.store');
        Route::get('aktivitas-staf-fraksi/{type}/{id}/edit', [AktivitasStafFraksiController::class, 'edit'])->name('aktivitas-staf-fraksi.edit');
        Route::put('aktivitas-staf-fraksi/{type}/{id}', [AktivitasStafFraksiController::class, 'update'])->name('aktivitas-staf-fraksi.update');
        Route::delete('aktivitas-staf-fraksi/{type}/{id}', [AktivitasStafFraksiController::class, 'destroy'])->name('aktivitas-staf-fraksi.destroy');

        // Rekap Aktivitas
        Route::prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/bulanan', [RekapController::class, 'bulanan'])->name('bulanan');
            Route::get('/triwulan', [RekapController::class, 'triwulan'])->name('triwulan');
            Route::get('/semester', [RekapController::class, 'semester'])->name('semester');
            Route::get('/tahunan', [RekapController::class, 'tahunan'])->name('tahunan');
        });

        // Laporan (cetak / export)
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/cetak', [LaporanController::class, 'cetak'])->name('cetak');
            Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
        });

        // Manajemen Pengguna
        Route::resource('pengguna', PenggunaController::class);
    });

    // =========================================================================
    // ANGGOTA DEWAN ROUTES — Hanya Anggota Dewan
    // =========================================================================
    Route::middleware('role:anggota')->prefix('anggota')->name('anggota.')->group(function () {

        // Dashboard Anggota
        Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])
            ->name('dashboard');

        // Aktivitas Saya (input mandiri)
        Route::get('/aktivitas-saya', [AktivitasSayaController::class, 'index'])
            ->name('aktivitas.index');
        Route::get('/aktivitas-saya/create', [AktivitasSayaController::class, 'create'])
            ->name('aktivitas.create');
        Route::post('/aktivitas-saya', [AktivitasSayaController::class, 'store'])
            ->name('aktivitas.store');
        Route::get('/aktivitas-saya/{aktivitas}', [AktivitasSayaController::class, 'show'])
            ->name('aktivitas.show');
        Route::get('/aktivitas-saya/{aktivitas}/edit', [AktivitasSayaController::class, 'edit'])
            ->name('aktivitas.edit');
        Route::put('/aktivitas-saya/{aktivitas}', [AktivitasSayaController::class, 'update'])
            ->name('aktivitas.update');
        Route::delete('/aktivitas-saya/{aktivitas}', [AktivitasSayaController::class, 'destroy'])
            ->name('aktivitas.destroy');

        // Rekap Saya
        Route::prefix('rekap-saya')->name('rekap.')->group(function () {
            Route::get('/bulanan', [RekapSayaController::class, 'bulanan'])->name('bulanan');
            Route::get('/triwulan', [RekapSayaController::class, 'triwulan'])->name('triwulan');
            Route::get('/semester', [RekapSayaController::class, 'semester'])->name('semester');
            Route::get('/tahunan', [RekapSayaController::class, 'tahunan'])->name('tahunan');
        });
    });
});
