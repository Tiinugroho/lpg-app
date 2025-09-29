<?php

namespace App\Http\Controllers;

use App\Models\MutasiGas;
use App\Models\PenjualanGas;
use App\Models\StokGas;
use App\Models\TipeGas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PenjualanGasController extends Controller
{
    /**
     * Menampilkan halaman utama penjualan (form dan daftar transaksi).
     */
    public function index()
    {
        // ========================================================================
        // BAGIAN 1: PERSIAPAN DATA UNTUK FORM PENJUALAN (LOGIKA BARU)
        // ========================================================================
        
        // Ambil semua tipe gas yang ada
        $semuaTipeGas = TipeGas::all();
        $tipeGasTersedia = [];

        foreach ($semuaTipeGas as $tipe) {
            // Cari stok paling lama (FIFO) untuk tipe gas ini guna menentukan harga jual awal di form
            $stokPertama = StokGas::where('tipe_gas_id', $tipe->id)
                ->where('jumlah_penuh', '>', 0)
                ->orderBy('tanggal_masuk', 'asc')
                ->first();

            // Hitung total stok yang tersedia untuk tipe gas ini di semua vendor
            $totalStok = StokGas::where('tipe_gas_id', $tipe->id)->sum('jumlah_penuh');

            // Hanya tampilkan di dropdown jika stoknya ada
            if ($totalStok > 0) {
                $tipeGasTersedia[] = [
                    'id' => $tipe->id,
                    'nama' => $tipe->nama,
                    'total_stok' => $totalStok,
                    // Harga jual diambil dari stok paling lama yang akan dijual pertama kali
                    'harga_jual' => $stokPertama ? $stokPertama->harga_jual : 0,
                ];
            }
        }

        // ========================================================================
        // BAGIAN 2: PERSIAPAN DATA UNTUK STATISTIK DAN TABEL (LOGIKA LAMA ANDA, SUDAH BENAR)
        // ========================================================================

        // Total pendapatan hari ini
        $totalPendapatanHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())->sum('total_harga');
        // Total transaksi unik hari ini
        $transaksiHariIni = PenjualanGas::whereDate('tanggal_transaksi', now())->distinct('kode_transaksi')->count();
        // Total transaksi unik bulan ini
        $transaksiBlnIni = PenjualanGas::whereMonth('tanggal_transaksi', now()->month)->distinct('kode_transaksi')->count();

        // Total stok penuh per tipe gas (untuk card statistik)
        $stokPenuhPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_penuh) as total_penuh')
            ->groupBy('tipe_gas_id')
            ->with('tipeGas')
            ->get();
            
        // Total tabung terjual bulan ini per tipe gas
        $penjualanBlnIniPerTipe = PenjualanGas::selectRaw('stok_gas.tipe_gas_id, tipe_gas.nama, SUM(penjualan_gas.jumlah) as total_terjual')
           ->join('stok_gas', 'penjualan_gas.produk_id', '=', 'stok_gas.id')
           ->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')
           ->whereMonth('penjualan_gas.tanggal_transaksi', now()->month)
           ->groupBy('stok_gas.tipe_gas_id', 'tipe_gas.nama')
           ->get();

        // Data Penjualan untuk ditampilkan di tabel riwayat
        $penjualans = PenjualanGas::with(['stokGas.tipeGas', 'stokGas.vendor'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get()
            ->groupBy('kode_transaksi');

        // Kirim semua data yang dibutuhkan ke view
        return view('transaksi.penjualan.index', compact(
            'tipeGasTersedia', // <-- INI UNTUK FORM DROPDOWN (PENTING!)
            'penjualans',
            'totalPendapatanHariIni',
            'transaksiHariIni',
            'transaksiBlnIni',
            'stokPenuhPerTipe',
            'penjualanBlnIniPerTipe'
        ));
    }


    /**
     * Menyimpan transaksi penjualan baru dengan logika FIFO lintas vendor.
     */
    public function store(Request $request)
    {
        // UBAH VALIDASI DARI 'produk_id' MENJADI 'tipe_gas_id'
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.tipe_gas_id' => 'required|exists:tipe_gas,id', // Validasi tipe gas
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $kodeTransaksi = 'PJ-' . strtoupper(Str::random(8));
            $tanggalTransaksi = now(); // Gunakan waktu server untuk konsistensi

            // LOGIKA UTAMA FIFO DIMULAI DI SINI
            foreach ($request->items as $item) {
                $tipeGasId = $item['tipe_gas_id'];
                $jumlahDibutuhkan = (int) $item['jumlah'];

                // 1. Cek ketersediaan total stok untuk tipe gas ini
                $totalStokTipeIni = StokGas::where('tipe_gas_id', $tipeGasId)->sum('jumlah_penuh');
                if ($totalStokTipeIni < $jumlahDibutuhkan) {
                    $namaGas = TipeGas::find($tipeGasId)->nama;
                    throw new \Exception("Stok {$namaGas} tidak mencukupi. Stok tersedia: {$totalStokTipeIni}, dibutuhkan: {$jumlahDibutuhkan}.");
                }

                // 2. Ambil semua batch stok yang tersedia, urutkan berdasarkan yang paling lama (FIFO)
                $stokTersedia = StokGas::where('tipe_gas_id', $tipeGasId)
                    ->where('jumlah_penuh', '>', 0)
                    ->orderBy('tanggal_masuk', 'asc')
                    ->get();
                
                $sisaKebutuhan = $jumlahDibutuhkan;

                // 3. Loop melalui setiap batch stok dan kurangi sesuai kebutuhan
                foreach ($stokTersedia as $stok) {
                    if ($sisaKebutuhan <= 0) break;

                    $stokAwalBatch = $stok->jumlah_penuh;
                    $jumlahDiambilDariBatch = min($sisaKebutuhan, $stok->jumlah_penuh);

                    // Buat record penjualan untuk setiap batch stok yang diambil
                    PenjualanGas::create([
                        'kode_transaksi' => $kodeTransaksi,
                        'nama_pembeli' => $request->nama_pembeli,
                        'no_kk' => $request->no_kk,
                        'no_telp' => $request->no_telp,
                        'produk_id' => $stok->id, // Merujuk ke batch stok spesifik (stok_gas.id)
                        'jumlah' => $jumlahDiambilDariBatch,
                        'harga_jual_satuan' => $stok->harga_jual, // Harga dinamis dari batch stok ini
                        'total_harga' => $jumlahDiambilDariBatch * $stok->harga_jual,
                        'tanggal_transaksi' => $tanggalTransaksi,
                        'keterangan' => $request->keterangan,
                    ]);

                    // Kurangi stok di batch ini
                    $stok->decrement('jumlah_penuh', $jumlahDiambilDariBatch);
                    
                    // Catat Mutasi menggunakan method private Anda
                    $this->catatMutasi([
                        'produk_id' => $stok->id,
                        'tipe_id' => $stok->tipe_gas_id,
                        'stok_awal' => $stokAwalBatch,
                        'stok_masuk' => 0,
                        'stok_keluar' => $jumlahDiambilDariBatch,
                        'stok_akhir' => $stok->jumlah_penuh,
                        'total_harga' => $jumlahDiambilDariBatch * $stok->harga_jual,
                        'kode_mutasi' => 'K',
                        'ket_mutasi' => 'Penjualan - ' . $kodeTransaksi,
                        'tanggal' => $tanggalTransaksi,
                    ]);

                    $sisaKebutuhan -= $jumlahDiambilDariBatch;
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi penjualan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk mencatat mutasi stok (Tidak ada perubahan)
     */
    private function catatMutasi($data)
    {
        try {
            MutasiGas::create($data);
        } catch (\Exception $e) {
            Log::error('Error saat mencatat mutasi: ' . $e->getMessage());
            throw $e;
        }
    }
}