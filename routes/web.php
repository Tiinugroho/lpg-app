<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\StokGasController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MutasiGasController;
use App\Http\Controllers\PengembalianTabungController;
use App\Http\Controllers\PenjualanGasController;
use App\Http\Controllers\TipeGasController;
use App\Http\Controllers\TransaksiPembelianController;

// Auth Routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Proteksi semua route di sini pakai auth
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    

    // Role kasir dan admin boleh akses transaksi & pengembalian
    Route::resource('/stok', StokGasController::class);
    Route::get('/transaksi/pembelian', [TransaksiPembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/transaksi/pembelian/create', [TransaksiPembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/transaksi/pembelian/store', [TransaksiPembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/transaksi/pembelian/{id}/edit', [TransaksiPembelianController::class, 'edit'])->name('pembelian.edit');
    Route::post('/transaksi/pembelian/update', [TransaksiPembelianController::class, 'update'])->name('pembelian.update');
    Route::get('/transaksi/pembelian/{id}/show', [TransaksiPembelianController::class, 'show'])->name('pembelian.show');
    Route::get('/transaksi/pembelian/{id}/delete', [TransaksiPembelianController::class, 'destroy'])->name('pembelian.delete');

    Route::get('/transaksi/penjualan', [PenjualanGasController::class, 'index'])->name('penjualan.index');
    Route::get('/transaksi/penjualan/create', [PenjualanGasController::class, 'create'])->name('penjualan.create');
    Route::post('/transaksi/penjualan/store', [PenjualanGasController::class, 'store'])->name('penjualan.store');
    Route::get('/transaksi/penjualan/{id}/edit', [PenjualanGasController::class, 'edit'])->name('penjualan.edit');
    Route::post('/transaksi/penjualan/update', [PenjualanGasController::class, 'update'])->name('penjualan.update');
    Route::get('/transaksi/penjualan/{id}/show', [PenjualanGasController::class, 'show'])->name('penjualan.show');
    Route::get('/transaksi/penjualan/{id}/delete', [PenjualanGasController::class, 'destroy'])->name('penjualan.delete');

    Route::get('/transaksi/pengembalian', [PengembalianTabungController::class, 'index'])->name('pengembalian.index');
    Route::post('/transaksi/pengembalian/store', [PengembalianTabungController::class, 'store'])->name('pengembalian.store');
    Route::get('/transaksi/pengembalian/create', [PengembalianTabungController::class, 'create'])->name('pengembalian.create');
    Route::get('/transaksi/pengembalian/{id}/edit', [PengembalianTabungController::class, 'edit'])->name('pengembalian.edit');
    Route::post('/transaksi/pengembalian/update', [PengembalianTabungController::class, 'update'])->name('pengembalian.update');
    Route::get('/transaksi/pengembalian/{id}/show', [PengembalianTabungController::class, 'show'])->name('pengembalian.show');
    Route::get('/transaksi/pengembalian/{id}/delete', [PengembalianTabungController::class, 'destroy'])->name('pengembalian.delete');

    // Role admin khusus
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/manajemen-user/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/manajemen-user/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/manajemen-user/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/manajemen-user/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::post('/manajemen-user/karyawan/update', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('/manajemen-user/karyawan/{id}/show', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::get('/manajemen-user/karyawan/{id}/delete', [KaryawanController::class, 'destroy'])->name('karyawan.delete');

        Route::get('/manajemen-user/vendor', [VendorController::class, 'index'])->name('vendor.index');
        Route::get('/manajemen-user/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/manajemen-user/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('/manajemen-user/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
        Route::post('/manajemen-user/vendor/update', [VendorController::class, 'update'])->name('vendor.update');
        Route::get('/manajemen-user/vendor/{id}/show', [VendorController::class, 'show'])->name('vendor.show');
        Route::get('/manajemen-user/vendor/{id}/delete', [VendorController::class, 'destroy'])->name('vendor.delete');
        
        Route::get('/master-data/tipe-gas', [TipeGasController::class, 'index'])->name('tipe-gas.index');
        Route::get('/master-data/tipe-gas/create', [TipeGasController::class, 'create'])->name('tipe-gas.create');
        Route::post('/master-data/tipe-gas/store', [TipeGasController::class, 'store'])->name('tipe-gas.store');
        Route::get('/master-data/tipe-gas/{id}/edit', [TipeGasController::class, 'edit'])->name('tipe-gas.edit');
        Route::post('/master-data/tipe-gas/update', [TipeGasController::class, 'update'])->name('tipe-gas.update');
        Route::get('/master-data/tipe-gas/{id}/show', [TipeGasController::class, 'show'])->name('tipe-gas.show');
        Route::get('/master-data/tipe-gas/{id}/delete', [TipeGasController::class, 'destroy'])->name('tipe-gas.delete');
    });

    Route::middleware('role:owner')->group(function () {
        Route::get('/manajemen-user/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/manajemen-user/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/manajemen-user/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/manajemen-user/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::post('/manajemen-user/karyawan/update', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('/manajemen-user/karyawan/{id}/show', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::get('/manajemen-user/karyawan/{id}/delete', [KaryawanController::class, 'destroy'])->name('karyawan.delete');

        Route::get('/manajemen-user/vendor', [VendorController::class, 'index'])->name('vendor.index');
        Route::get('/manajemen-user/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/manajemen-user/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('/manajemen-user/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
        Route::post('/manajemen-user/vendor/update', [VendorController::class, 'update'])->name('vendor.update');
        Route::get('/manajemen-user/vendor/{id}/show', [VendorController::class, 'show'])->name('vendor.show');
        Route::get('/manajemen-user/vendor/{id}/delete', [VendorController::class, 'destroy'])->name('vendor.delete');

        Route::get('/mutasi', [MutasiGasController::class, 'index'])->name('vendor.index');
        Route::post('/mutasi/export-excel', [MutasiGasController::class, 'exportExcel'])->name('vendor.exportExcel');
    });

    
});
