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

class PengembalianGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokGas = StokGas::with(['tipeGas', 'vendor'])->get();
        $tipeGas = TipeGas::all();

        // Total pengembalian hari ini
        $totalPengembalianHariIni = PengembalianGas::whereDate('tanggal_pengembalian', now())
            ->count();

        // Total pengembalian bulan ini
        $totalPengembalianBlnIni = PengembalianGas::whereMonth('tanggal_pengembalian', now()->month)
            ->count();

        // Total tabung rusak hari ini
        $totalRusakHariIni = PengembalianGas::whereDate('tanggal_pengembalian', now())
            ->sum('jumlah_rusak');

        // Total tabung rusak bulan ini
        $totalRusakBlnIni = PengembalianGas::whereMonth('tanggal_pengembalian', now()->month)
            ->sum('jumlah_rusak');

        // Total pengembalian per tipe gas bulan ini
        $pengembalianBlnIniPerTipe = PengembalianGas::selectRaw('stok_gas.tipe_gas_id, tipe_gas.nama, SUM(pengembalian_gas.jumlah) as total_kembali')
            ->join('stok_gas', 'pengembalian_gas.produk_id', '=', 'stok_gas.id')
            ->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')
            ->whereMonth('pengembalian_gas.tanggal_pengembalian', now()->month)
            ->groupBy('stok_gas.tipe_gas_id', 'tipe_gas.nama')
            ->get();

        // Total stok pengembalian per tipe gas
        $stokPengembalianPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_pengembalian) as total_pengembalian')
            ->groupBy('tipe_gas_id')
            ->with('tipeGas')
            ->get();

        // Total stok rusak per tipe gas
        $stokRusakPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_rusak) as total_rusak')
            ->groupBy('tipe_gas_id')
            ->with('tipeGas')
            ->get();

        // Data pengembalian - tidak perlu group karena setiap record terpisah
        $pengembalians = PengembalianGas::with('stokGas.tipeGas')
            ->orderBy('tanggal_pengembalian', 'desc')
            ->get();

        return view('transaksi.pengembalian.index', compact(
            'pengembalians',
            'totalPengembalianHariIni',
            'totalPengembalianBlnIni',
            'totalRusakHariIni',
            'totalRusakBlnIni',
            'pengembalianBlnIniPerTipe',
            'stokPengembalianPerTipe',
            'stokRusakPerTipe',
            'stokGas',
            'tipeGas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tanggal_pengembalian' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:stok_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.kondisi_rusak' => 'boolean',
            'items.*.jumlah_rusak' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                // Generate kode unik untuk setiap item
                $kodeItem = 'PG-' . strtoupper(Str::random(8));
                
                // Ambil data stok
                $stok = StokGas::findOrFail($item['produk_id']);
                
                $kondisiRusak = isset($item['kondisi_rusak']) && $item['kondisi_rusak'];
                $jumlahRusak = $kondisiRusak ? ($item['jumlah_rusak'] ?? 0) : 0;
                $jumlahBaik = $item['jumlah'] - $jumlahRusak;

                // Validasi jumlah rusak tidak boleh lebih dari jumlah total
                if ($jumlahRusak > $item['jumlah']) {
                    throw new \Exception("Jumlah rusak tidak boleh lebih dari jumlah total untuk produk {$stok->tipeGas->nama}");
                }

                // Simpan stok awal sebelum ditambah
                $stokPengembalianAwal = $stok->jumlah_pengembalian ?? 0;
                $stokRusakAwal = $stok->jumlah_rusak ?? 0;

                // Simpan pengembalian - setiap item adalah record terpisah
                PengembalianGas::create([
                    'kode' => $kodeItem,
                    'nama_pembeli' => $request->nama_pembeli,
                    'no_kk' => $request->no_kk ?? null,
                    'no_telp' => $request->no_telp ?? null,
                    'produk_id' => $item['produk_id'],
                    'kondisi_rusak' => $kondisiRusak,
                    'jumlah' => $item['jumlah'],
                    'jumlah_rusak' => $jumlahRusak,
                    'tanggal_pengembalian' => $request->tanggal_pengembalian,
                    'keterangan' => $request->keterangan ?? null,
                ]);

                // Update stok - tambah jumlah_pengembalian untuk tabung yang baik
                if ($jumlahBaik > 0) {
                    $stok->jumlah_pengembalian = ($stok->jumlah_pengembalian ?? 0) + $jumlahBaik;
                }

                // Tambah jumlah_rusak untuk tabung yang rusak
                if ($jumlahRusak > 0) {
                    $stok->jumlah_rusak = ($stok->jumlah_rusak ?? 0) + $jumlahRusak;
                }

                $stok->save();

                // Catat mutasi untuk tabung yang baik (masuk ke stok pengembalian)
                if ($jumlahBaik > 0) {
                    $this->catatMutasi([
                        'produk_id' => $stok->id,
                        'tipe_id' => $stok->tipe_gas_id,
                        'stok_awal' => $stokPengembalianAwal,
                        'stok_masuk' => $jumlahBaik,
                        'stok_keluar' => 0,
                        'stok_akhir' => $stok->jumlah_pengembalian,
                        'total_harga' => 0, // Pengembalian tidak ada nilai uang
                        'kode_mutasi' => 'M', // M = Masuk
                        'ket_mutasi' => 'Pengembalian Baik - ' . $kodeItem,
                        'tanggal' => $request->tanggal_pengembalian,
                    ]);
                }

                // Catat mutasi untuk tabung rusak jika ada
                if ($jumlahRusak > 0) {
                    $this->catatMutasi([
                        'produk_id' => $stok->id,
                        'tipe_id' => $stok->tipe_gas_id,
                        'stok_awal' => $stokRusakAwal,
                        'stok_masuk' => $jumlahRusak,
                        'stok_keluar' => 0,
                        'stok_akhir' => $stok->jumlah_rusak,
                        'total_harga' => 0,
                        'kode_mutasi' => 'R', // R = Rusak
                        'ket_mutasi' => 'Pengembalian Rusak - ' . $kodeItem,
                        'tanggal' => $request->tanggal_pengembalian,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pengembalian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan pengembalian: ' . $e->getMessage());
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
