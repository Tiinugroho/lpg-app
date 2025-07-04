<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use Illuminate\Support\Str;
use App\Models\PembelianGas;
use App\Models\PenjualanGas;
use Illuminate\Http\Request;
use App\Models\PengembalianGas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stokGas = StokGas::with(['tipeGas', 'vendor'])->get();
        $tipeGas = TipeGas::all();
        $vendors = Vendor::where('status_aktif', true)->get();

        $penjualanHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())->sum('total_harga');
        $transaksiBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->count();
        $totalStokPenuh = StokGas::sum('jumlah_penuh');
        $totalStokKosong = StokGas::sum('jumlah_pengembalian');
        $totalStokRusak = StokGas::sum('jumlah_rusak');
        $totalVendor = Vendor::count();
        $penjualanBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->sum('jumlah');
        $pengembalianBlnIni = PengembalianGas::whereMonth('tanggal_pengembalian', now()->month)->sum('jumlah');

        $transaksiHariIniList = PenjualanGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_transaksi', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact(
            'penjualanHariIni', 
            'transaksiBlnIni', 
            'totalStokPenuh', 
            'totalStokKosong', 
            'totalStokRusak', 
            'totalVendor', 
            'penjualanBlnIni', 
            'pengembalianBlnIni', 
            'transaksiHariIniList', 
            'stokGas', 
            'vendors', 
            'tipeGas'
        ));
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendor,id',
            'items' => 'required|array|min:1',
            'items.*.tipe_id' => 'required|exists:tipe_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
            'items.*.tanggal_masuk' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $kodePembelian = 'PB-' . strtoupper(Str::random(8));

            foreach ($request->items as $item) {
                $totalHarga = $item['jumlah'] * $item['harga_beli'];

                // Simpan ke pembelian_gas
                PembelianGas::create([
                    'kode_pembelian' => $kodePembelian,
                    'vendor_id' => $request->vendor_id,
                    'tipe_id' => $item['tipe_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_beli' => $item['harga_beli'],
                    'tanggal_masuk' => $item['tanggal_masuk'],
                    'keterangan' => $request->keterangan,
                ]);

                // Update atau buat stok gas
                $stok = StokGas::where('tipe_gas_id', $item['tipe_id'])
                    ->where('vendor_id', $request->vendor_id)
                    ->first();

                if (!$stok) {
                    $stok = new StokGas();
                    $stok->kode = 'SG-' . strtoupper(Str::random(6));
                    $stok->tipe_gas_id = $item['tipe_id'];
                    $stok->vendor_id = $request->vendor_id;
                    $stok->jumlah_penuh = 0;
                    $stok->jumlah_pengembalian = 0;
                    $stok->jumlah_rusak = 0;
                }

                // Update stok - tambah ke jumlah_penuh
                $stok->jumlah_penuh += $item['jumlah'];
                $stok->harga_beli = $item['harga_beli'];
                // Harga jual = harga beli + 11%
                $stok->harga_jual = $item['harga_beli'] + ($item['harga_beli'] * 0.11);
                $stok->tanggal_masuk = $item['tanggal_masuk'];
                $stok->save();

                // Catat mutasi
                $this->catatMutasi([
                    'kode' => $kodePembelian,
                    'tipe_id' => $item['tipe_id'],
                    'produk_id' => $stok->id,
                    'stok_masuk' => $item['jumlah'],
                    'total_harga' => $totalHarga,
                    'kode_mutasi' => 'M',
                    'ket_mutasi' => 'Pembelian',
                    'tanggal' => $item['tanggal_masuk'],
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }

    private function catatMutasi($data)
    {
        $stokAwal = MutasiGas::where('produk_id', $data['produk_id'])
            ->latest()
            ->value('stok_akhir') ?? 0;

        $stokMasuk = $data['stok_masuk'] ?? 0;
        $stokKeluar = $data['stok_keluar'] ?? 0;
        $stokAkhir = $stokAwal + $stokMasuk - $stokKeluar;

        MutasiGas::create([
            'kode' => $data['kode'],
            'tipe_id' => $data['tipe_id'],
            'produk_id' => $data['produk_id'],
            'stok_awal' => $stokAwal,
            'stok_masuk' => $stokMasuk,
            'stok_keluar' => $stokKeluar,
            'stok_akhir' => $stokAkhir,
            'total_harga' => $data['total_harga'],
            'kode_mutasi' => $data['kode_mutasi'],
            'ket_mutasi' => $data['ket_mutasi'],
            'tanggal' => $data['tanggal'],
        ]);
    }

    public function laporanHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        
        $penjualan = PenjualanGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_transaksi', $tanggal)
            ->get();
            
        $pengembalian = PengembalianGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_pengembalian', $tanggal)
            ->get();
            
        $pembelian = PembelianGas::with(['tipeGas', 'vendor'])
            ->whereDate('tanggal_masuk', $tanggal)
            ->get();

        return view('laporan.harian', compact('penjualan', 'pengembalian', 'pembelian', 'tanggal'));
    }

    public function laporanMingguan(Request $request)
    {
        $minggu = $request->minggu ?? now()->format('Y-\WW');
        $startDate = Carbon::now()->setISODate(substr($minggu, 0, 4), substr($minggu, 6))->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        $data = $this->getLaporanData($startDate, $endDate);
        
        return view('laporan.mingguan', compact('data', 'minggu', 'startDate', 'endDate'));
    }

    public function laporanBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        $startDate = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $data = $this->getLaporanData($startDate, $endDate);
        
        return view('laporan.bulanan', compact('data', 'bulan', 'startDate', 'endDate'));
    }

    private function getLaporanData($startDate, $endDate)
    {
        return [
            'penjualan' => PenjualanGas::with(['stokGas.tipeGas'])
                ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                ->get(),
            'pengembalian' => PengembalianGas::with(['stokGas.tipeGas'])
                ->whereBetween('tanggal_pengembalian', [$startDate, $endDate])
                ->get(),
            'pembelian' => PembelianGas::with(['tipeGas', 'vendor'])
                ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                ->get(),
            'mutasi' => MutasiGas::with(['tipeGas', 'stokGas'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get(),
        ];
    }
}
