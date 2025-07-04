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

class PenjualanGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $stokGas = StokGas::with(['tipeGas', 'vendor'])->get();
    $tipeGas = TipeGas::all();

    // Total pendapatan hari ini
    $totalPendapatanHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())
        ->sum('total_harga');

    // Total transaksi hari ini (kode transaksi unik)
    $transaksiHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())
        ->select('kode_transaksi')
        ->distinct()
        ->count('kode_transaksi');

    // Total transaksi bulan ini (kode transaksi unik)
    $transaksiBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)
        ->select('kode_transaksi')
        ->distinct()
        ->count('kode_transaksi');

    // Total stok penuh per tipe gas
    $stokPenuhPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_penuh) as total_penuh')
        ->groupBy('tipe_gas_id')
        ->with('tipeGas')
        ->get();

    // Data penjualan group by kode transaksi
    $penjualans = PenjualanGas::with('stokGas.tipeGas')
        ->orderBy('tanggal_transaksi', 'desc')
        ->get()
        ->groupBy('kode_transaksi');

    // Total tabung terjual bulan ini per tipe gas
    $penjualanBlnIniPerTipe = PenjualanGas::selectRaw('stok_gas.tipe_gas_id, tipe_gas.nama, SUM(penjualan_gas.jumlah) as total_terjual')
        ->join('stok_gas', 'penjualan_gas.produk_id', '=', 'stok_gas.id')
        ->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')
        ->whereMonth('penjualan_gas.tanggal_transaksi', now()->month)
        ->groupBy('stok_gas.tipe_gas_id', 'tipe_gas.nama')
        ->get();

    return view('transaksi.penjualan.index', compact(
        'penjualans',
        'totalPendapatanHariIni',
        'transaksiHariIni',
        'transaksiBlnIni',
        'stokPenuhPerTipe',
        'penjualanBlnIniPerTipe',
        'stokGas',
        'tipeGas'
    ));
}


    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tanggal_transaksi' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:stok_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $kodeTransaksi = 'PJ-' . strtoupper(Str::random(8));

            foreach ($request->items as $item) {
                // Ambil data stok
                $stok = StokGas::findOrFail($item['produk_id']);
                
                // Cek stok tersedia
                if ($stok->jumlah_penuh < $item['jumlah']) {
                    throw new \Exception("Stok {$stok->tipeGas->nama} tidak mencukupi. Tersedia: {$stok->jumlah_penuh}");
                }

                // Harga jual satuan dari stok
                $hargaJualSatuan = $stok->harga_jual;
                $totalHarga = $item['jumlah'] * $hargaJualSatuan;

                // Simpan stok awal sebelum dikurangi
                $stokAwal = $stok->jumlah_penuh;

                // Simpan penjualan
                PenjualanGas::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'nama_pembeli' => $request->nama_pembeli,
                    'no_kk' => $request->no_kk ?? null,
                    'no_telp' => $request->no_telp ?? null,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_jual_satuan' => $hargaJualSatuan,
                    'total_harga' => $totalHarga,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'keterangan' => $request->keterangan ?? null,
                ]);

                // Update stok - kurangi jumlah_penuh
                $stok->jumlah_penuh -= $item['jumlah'];
                $stok->save();

                // Catat mutasi dengan semua field yang diperlukan
                $this->catatMutasi([
                    'produk_id' => $stok->id,
                    'tipe_id' => $stok->tipe_gas_id,
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => 0, // Penjualan = stok keluar, jadi stok masuk = 0
                    'stok_keluar' => $item['jumlah'],
                    'stok_akhir' => $stok->jumlah_penuh, // Stok setelah dikurangi
                    'total_harga' => $totalHarga,
                    'kode_mutasi' => 'K', // K = Keluar
                    'ket_mutasi' => 'Penjualan - ' . $kodeTransaksi,
                    'tanggal' => $request->tanggal_transaksi,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penjualan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
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
            // Log error jika diperlukan
            Log::error('Error saat mencatat mutasi: ' . $e->getMessage());
            throw $e;
        }
    }
}
