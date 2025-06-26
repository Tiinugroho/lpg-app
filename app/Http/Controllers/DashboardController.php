<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\PembelianGas;
use App\Models\PenjualanGas;
use Illuminate\Http\Request;
use App\Models\PengembalianGas;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data pendapatan & transaksi
        $penjualanHariIni = PenjualanGas::whereDate('tanggal_transaksi', Carbon::today())->sum('total_harga');
        $penjualanBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', Carbon::now()->month)->sum('total_harga');
        $transaksiBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', Carbon::now()->month)->count();
        $transaksiHariIni = PenjualanGas::whereDate('tanggal_transaksi', Carbon::today())->count();
        $pengembalianHariIni = PengembalianGas::whereDate('tanggal_pengembalian', Carbon::today())->count();
        $stokTerkini = StokGas::orderBy('updated_at', 'desc')->first();

        // Data grafik 7 hari terakhir
        $grafikPenjualan = PenjualanGas::select(DB::raw('DATE(tanggal_transaksi) as tanggal'), DB::raw('SUM(total_harga) as penjualan'))
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(6))
            ->groupBy(DB::raw('DATE(tanggal_transaksi)'))
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d M'),
                    'penjualan' => $item->penjualan,
                ];
            })
            ->toArray();

        $vendorPerTipe = StokGas::selectRaw('count(distinct vendor_id) as jumlah, tipe_gas_id')->groupBy('tipe_gas_id')->with('tipeGas')->get();

        return view('dashboard', compact('penjualanHariIni', 'penjualanBlnIni', 'transaksiBlnIni', 'transaksiHariIni', 'pengembalianHariIni', 'stokTerkini', 'grafikPenjualan', 'vendorPerTipe'));
    }
}
