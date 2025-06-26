<?php

namespace App\Http\Controllers;

use App\Models\StokGas;
use App\Models\TransaksiPembelian;
use App\Models\PengembalianTabung;
use App\Models\RestockVendor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stokTerkini = StokGas::getStokTerkini();
        
        // Data hari ini
        $transaksiHariIni = TransaksiPembelian::whereDate('tanggal_transaksi', today())->count();
        $penjualanHariIni = TransaksiPembelian::whereDate('tanggal_transaksi', today())->sum('total_harga');
        $pengembalianHariIni = PengembalianTabung::whereDate('tanggal_pengembalian', today())->sum('jumlah_tabung_dikembalikan');
        
        // Data bulan ini
        $transaksiBlnIni = TransaksiPembelian::whereMonth('tanggal_transaksi', now()->month)->count();
        $penjualanBlnIni = TransaksiPembelian::whereMonth('tanggal_transaksi', now()->month)->sum('total_harga');
        
        // Grafik penjualan 7 hari terakhir
        $grafikPenjualan = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i);
            $penjualan = TransaksiPembelian::whereDate('tanggal_transaksi', $tanggal)->sum('total_harga');
            $grafikPenjualan[] = [
                'tanggal' => $tanggal->format('d/m'),
                'penjualan' => $penjualan
            ];
        }

        return view('dashboard', compact(
            'stokTerkini',
            'transaksiHariIni',
            'penjualanHariIni',
            'pengembalianHariIni',
            'transaksiBlnIni',
            'penjualanBlnIni',
            'grafikPenjualan'
        ));
    }
}
