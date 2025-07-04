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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PembelianGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::all();
        $tipeGas = TipeGas::all();

        // Total pembelian hari ini (jumlah transaksi)
        $totalPembelianHariIni = PembelianGas::whereDate('tanggal_masuk', now())
            ->count();

        // Total pembelian bulan ini (jumlah transaksi)
        $totalPembelianBlnIni = PembelianGas::whereMonth('tanggal_masuk', now()->month)
            ->count();

        // Total pengeluaran hari ini (total harga)
        $totalPengeluaranHariIni = PembelianGas::whereDate('tanggal_masuk', now())
            ->selectRaw('SUM(jumlah * harga_beli) as total')
            ->value('total') ?? 0;

        // Total pengeluaran bulan ini (total harga)
        $totalPengeluaranBlnIni = PembelianGas::whereMonth('tanggal_masuk', now()->month)
            ->selectRaw('SUM(jumlah * harga_beli) as total')
            ->value('total') ?? 0;

        // Total tabung dibeli hari ini
        $totalTabungHariIni = PembelianGas::whereDate('tanggal_masuk', now())
            ->sum('jumlah');

        // Total tabung dibeli bulan ini
        $totalTabungBlnIni = PembelianGas::whereMonth('tanggal_masuk', now()->month)
            ->sum('jumlah');

        // Pembelian bulan ini per tipe gas
        $pembelianBlnIniPerTipe = PembelianGas::selectRaw('tipe_id, tipe_gas.nama, SUM(pembelian_gas.jumlah) as total_beli')
            ->join('tipe_gas', 'pembelian_gas.tipe_id', '=', 'tipe_gas.id')
            ->whereMonth('pembelian_gas.tanggal_masuk', now()->month)
            ->groupBy('tipe_id', 'tipe_gas.nama')
            ->get();

        // Total stok penuh per tipe gas
        $stokPenuhPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_penuh) as total_penuh')
            ->groupBy('tipe_gas_id')
            ->with('tipeGas')
            ->get();

        // Pembelian per vendor bulan ini
        $pembelianPerVendor = PembelianGas::selectRaw('vendor_id, vendor.nama_vendor, SUM(pembelian_gas.jumlah) as total_beli, SUM(pembelian_gas.jumlah * pembelian_gas.harga_beli) as total_harga')
            ->join('vendor', 'pembelian_gas.vendor_id', '=', 'vendor.id')
            ->whereMonth('pembelian_gas.tanggal_masuk', now()->month)
            ->groupBy('vendor_id', 'vendor.nama_vendor')
            ->get();

        // Data pembelian group by kode_pembelian
        $pembelians = PembelianGas::with(['tipeGas', 'vendor'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get()
            ->groupBy('kode_pembelian');

        return view('transaksi.pembelian.index', compact(
            'pembelians',
            'totalPembelianHariIni',
            'totalPembelianBlnIni',
            'totalPengeluaranHariIni',
            'totalPengeluaranBlnIni',
            'totalTabungHariIni',
            'totalTabungBlnIni',
            'pembelianBlnIniPerTipe',
            'stokPenuhPerTipe',
            'pembelianPerVendor',
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

                // Simpan stok awal untuk mutasi
                $stokAwal = $stok->jumlah_penuh ?? 0;

                // Update stok - tambah ke jumlah_penuh
                $stok->jumlah_penuh = ($stok->jumlah_penuh ?? 0) + $item['jumlah'];
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
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $item['jumlah'],
                    'stok_keluar' => 0,
                    'stok_akhir' => $stok->jumlah_penuh,
                    'total_harga' => $totalHarga,
                    'kode_mutasi' => 'M',
                    'ket_mutasi' => 'Pembelian - ' . $kodePembelian,
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

    /**
     * Method untuk mencatat mutasi stok
     */
    private function catatMutasi($data)
    {
        try {
            MutasiGas::create([
                'produk_id' => $data['produk_id'],
                'tipe_id' => $data['tipe_id'],
                'stok_awal' => $data['stok_awal'],
                'stok_masuk' => $data['stok_masuk'],
                'stok_keluar' => $data['stok_keluar'],
                'stok_akhir' => $data['stok_akhir'],
                'total_harga' => $data['total_harga'],
                'kode_mutasi' => $data['kode_mutasi'],
                'ket_mutasi' => $data['ket_mutasi'],
                'tanggal' => $data['tanggal'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mencatat mutasi: ' . $e->getMessage());
            throw $e;
        }
    }
}
