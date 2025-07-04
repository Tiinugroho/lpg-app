<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\StokGasController;
use App\Http\Controllers\TipeGasController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MutasiGasController;
use App\Http\Controllers\PembelianGasController;
use App\Http\Controllers\PenjualanGasController;
use App\Http\Controllers\PengembalianGasController;

// Auth Routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Proteksi semua route pakai auth
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Semua role akses stok & transaksi
    Route::resource('/stok', StokGasController::class);
    Route::post('stok/adjustment', [StokGasController::class, 'adjustment'])->name('stok.adjustment');

    Route::get('transaksi/penjualan', [PenjualanGasController::class, 'index'])->name('transaksi.penjualan.index');
    Route::post('transaksi/penjualan/store', [PenjualanGasController::class, 'store'])->name('transaksi.penjualan.store');

    Route::get('transaksi/pengembalian', [PengembalianGasController::class, 'index'])->name('transaksi.pengembalian.index');
    Route::post('transaksi/pengembalian/store', [PengembalianGasController::class, 'store'])->name('transaksi.pengembalian.store');

    Route::get('transaksi/pembelian', [PembelianGasController::class, 'index'])->name('transaksi.pembelian.index');
    Route::post('transaksi/pembelian/store', [PembelianGasController::class, 'store'])->name('transaksi.pembelian.store');

    Route::get('/laporan/harian', [DashboardController::class, 'laporanHarian'])->name('laporan.harian');
    Route::get('/laporan/mingguan', [DashboardController::class, 'laporanMingguan'])->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [DashboardController::class, 'laporanBulanan'])->name('laporan.bulanan');

    // Group khusus super-admin & owner
    Route::middleware('role:super-admin,owner')->group(function () {
        // Manajemen Karyawan
        Route::get('/manajemen-user/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/manajemen-user/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/manajemen-user/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/manajemen-user/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::post('/manajemen-user/karyawan/update', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('/manajemen-user/karyawan/{id}/show', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::get('/manajemen-user/karyawan/{id}/delete', [KaryawanController::class, 'destroy'])->name('karyawan.delete');

        // Manajemen Vendor
        Route::get('/manajemen-user/vendor', [VendorController::class, 'index'])->name('vendor.index');
        Route::get('/manajemen-user/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/manajemen-user/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('/manajemen-user/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
        Route::post('/manajemen-user/vendor/update', [VendorController::class, 'update'])->name('vendor.update');
        Route::get('/manajemen-user/vendor/{id}/show', [VendorController::class, 'show'])->name('vendor.show');
        Route::get('/manajemen-user/vendor/{id}/delete', [VendorController::class, 'destroy'])->name('vendor.delete');

        // Tipe Gas
        Route::get('/master-data/tipe-gas', [TipeGasController::class, 'index'])->name('tipe-gas.index');
        Route::get('/master-data/tipe-gas/create', [TipeGasController::class, 'create'])->name('tipe-gas.create');
        Route::post('/master-data/tipe-gas/store', [TipeGasController::class, 'store'])->name('tipe-gas.store');
        Route::get('/master-data/tipe-gas/{id}/edit', [TipeGasController::class, 'edit'])->name('tipe-gas.edit');
        Route::post('/master-data/tipe-gas/update', [TipeGasController::class, 'update'])->name('tipe-gas.update');
        Route::get('/master-data/tipe-gas/{id}/show', [TipeGasController::class, 'show'])->name('tipe-gas.show');
        Route::get('/master-data/tipe-gas/{id}/delete', [TipeGasController::class, 'destroy'])->name('tipe-gas.delete');

        // Mutasi
        Route::get('/mutasi', [MutasiGasController::class, 'index'])->name('mutasi.index');
        Route::post('/mutasi/export-excel', [MutasiGasController::class, 'exportExcel'])->name('mutasi.exportExcel');
    });
});
