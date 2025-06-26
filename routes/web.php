<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StokGasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengembalianTabungController;
use App\Http\Controllers\TransaksiPembelianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/stok-gas', [StokGasController::class, 'index'])->name('stok-gas.index');
// Route::post('/stok-gas/update', [StokGasController::class, 'update'])->name('stok-gas.update');

Route::get('/transaksi-pembelian', [TransaksiPembelianController::class, 'index'])->name('transaksi.index');
Route::get('/transaksi-pembelian/create', [TransaksiPembelianController::class, 'create'])->name('transaksi.create');
Route::post('/transaksi-pembelian/store', [TransaksiPembelianController::class, 'store'])->name('transaksi.store');
Route::get('/transaksi-pembelian/{id}/edit', [TransaksiPembelianController::class, 'edit'])->name('transaksi.edit');
Route::post('/transaksi-pembelian/update', [TransaksiPembelianController::class, 'update'])->name('transaksi.update');
Route::get('/transaksi-pembelian/{id}/show', [TransaksiPembelianController::class, 'show'])->name('transaksi.show');
Route::get('/transaksi-pembelian/{id}/delete', [TransaksiPembelianController::class, 'destroy'])->name('transaksi.delete');
// Route::get('/transaksi-pembelian/cetak/{id}', [TransaksiPembelianController::class, 'cetak'])->name('transaksi-pembelian.cetak');
// Route::get('/transaksi-pembelian/cetak-all', [TransaksiPembelianController::class, 'cetakAll'])->name('transaksi-pembelian.cetak-all');
// Route::get('/transaksi-pembelian/cetak-bulan', [TransaksiPembelianController::class, 'cetakBulan'])->name('transaksi-pembelian.cetak-bulan');
// Route::get('/transaksi-pembelian/cetak-tahun', [TransaksiPembelianController::class, 'cetakTahun'])->name('transaksi-pembelian.cetak-tahun');

Route::get('/pengembalian-tabung', [PengembalianTabungController::class, 'index'])->name('pengembalian.index');
Route::post('/pengembalian-tabung/store', [PengembalianTabungController::class, 'store'])->name('pengembalian.store');
Route::get('/pengembalian-tabung/create', [PengembalianTabungController::class, 'create'])->name('pengembalian.create');
Route::get('/pengembalian-tabung/{id}/edit', [PengembalianTabungController::class, 'edit'])->name('pengembalian.edit');
Route::post('/pengembalian-tabung/update', [PengembalianTabungController::class, 'update'])->name('pengembalian.update');
Route::get('/pengembalian-tabung/{id}/show', [PengembalianTabungController::class, 'show'])->name('pengembalian.show');
Route::get('/pengembalian-tabung/{id}/delete', [PengembalianTabungController::class, 'destroy'])->name('pengembalian.delete');
// Route::get('/pengembalian-tabung/cetak/{id}', [PengembalianTabungController::class, '

Route::resource('/stok', StokGasController::class);
