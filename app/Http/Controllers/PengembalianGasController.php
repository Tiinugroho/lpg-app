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
    public function index()
    {
        // Data untuk dropdown di form. Cukup kirim Tipe Gas saja.
        $tipeGas = TipeGas::orderBy('nama', 'asc')->get();

        // [SISANYA SAMA SEPERTI KODE ANDA, TIDAK ADA PERUBAHAN DI SINI]
        $stokGas = StokGas::with(['tipeGas', 'vendor'])->get();
        $totalPengembalianHariIni = PengembalianGas::whereDate('tanggal_pengembalian', now())->count();
        $totalPengembalianBlnIni = PengembalianGas::whereMonth('tanggal_pengembalian', now()->month)->count();
        $totalRusakHariIni = PengembalianGas::whereDate('tanggal_pengembalian', now())->sum('jumlah_rusak');
        $totalRusakBlnIni = PengembalianGas::whereMonth('tanggal_pengembalian', now()->month)->sum('jumlah_rusak');
        $pengembalianBlnIniPerTipe = PengembalianGas::selectRaw('stok_gas.tipe_gas_id, tipe_gas.nama, SUM(pengembalian_gas.jumlah) as total_kembali')
            ->join('stok_gas', 'pengembalian_gas.produk_id', '=', 'stok_gas.id')
            ->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')
            ->whereMonth('pengembalian_gas.tanggal_pengembalian', now()->month)
            ->groupBy('stok_gas.tipe_gas_id', 'tipe_gas.nama')
            ->get();
        $stokPengembalianPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_pengembalian) as total_pengembalian')
            ->groupBy('tipe_gas_id')
            ->with('tipeGas')
            ->get();
        $stokRusakPerTipe = StokGas::selectRaw('tipe_gas_id, SUM(jumlah_rusak) as total_rusak')
            ->groupBy('tipe_gas_id')
            ->with('tipeGas')
            ->get();
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
            'stokGas', // ini masih dipakai untuk tabel dan stat cards
            'tipeGas'  // Kirim ini ke view untuk form
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tanggal_pengembalian' => 'date',
            'items' => 'required|array|min:1',
            // --- PERUBAHAN VALIDASI ---
            'items.*.tipe_gas_id' => 'required|exists:tipe_gas,id', // Validasi tipe_gas_id
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.kondisi_rusak' => 'boolean',
            'items.*.jumlah_rusak' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $kodeItem = 'PG-' . strtoupper(Str::random(8));
            foreach ($request->items as $item) {
                // Generate kode unik untuk setiap item

                // --- PERUBAHAN LOGIKA INTI ---
                // Cari record StokGas PERTAMA yang sesuai dengan Tipe Gas yang dipilih.
                // Semua pengembalian untuk tipe ini akan ditampung di record stok ini.
                $stok = StokGas::where('tipe_gas_id', $item['tipe_gas_id'])->first();

                // Jika tidak ada record stok sama sekali untuk tipe gas ini, lemparkan error.
                if (!$stok) {
                    $tipeGasNama = TipeGas::find($item['tipe_gas_id'])->nama ?? 'yang dipilih';
                    throw new \Exception("Tidak ada data stok sama sekali untuk Tipe Gas {$tipeGasNama}.");
                }
                
                $kondisiRusak = isset($item['kondisi_rusak']) && $item['kondisi_rusak'];
                $jumlahRusak = $kondisiRusak ? ($item['jumlah_rusak'] ?? 0) : 0;
                $jumlahBaik = $item['jumlah'] - $jumlahRusak;

                if ($jumlahRusak > $item['jumlah']) {
                    throw new \Exception("Jumlah rusak tidak boleh lebih dari jumlah total untuk produk {$stok->tipeGas->nama}");
                }

                $stokPengembalianAwal = $stok->jumlah_pengembalian ?? 0;
                $stokRusakAwal = $stok->jumlah_rusak ?? 0;

                PengembalianGas::create([
                    'kode' => $kodeItem,
                    'nama_pembeli' => $request->nama_pembeli,
                    'no_kk' => $request->no_kk ?? null,
                    'no_telp' => $request->no_telp ?? null,
                    'produk_id' => $stok->id, // Gunakan ID dari stok yang kita temukan
                    'kondisi_rusak' => $kondisiRusak,
                    'jumlah' => $item['jumlah'],
                    'jumlah_rusak' => $jumlahRusak,
                    'tanggal_pengembalian' => Carbon::now(),
                    'keterangan' => $request->keterangan ?? null,
                ]);

                if ($jumlahBaik > 0) {
                    $stok->jumlah_pengembalian = ($stok->jumlah_pengembalian ?? 0) + $jumlahBaik;
                }

                if ($jumlahRusak > 0) {
                    $stok->jumlah_rusak = ($stok->jumlah_rusak ?? 0) + $jumlahRusak;
                }

                $stok->save();

                // Catat mutasi untuk tabung yang baik
                if ($jumlahBaik > 0) {
                    $this->catatMutasi([
                        'produk_id' => $stok->id,
                        'tipe_id' => $stok->tipe_gas_id,
                        'stok_awal' => $stokPengembalianAwal,
                        'stok_masuk' => $jumlahBaik,
                        'stok_keluar' => 0,
                        'stok_akhir' => $stok->jumlah_pengembalian,
                        'total_harga' => 0,
                        'kode_mutasi' => 'M',
                        'ket_mutasi' => 'Pengembalian Baik - ' . $kodeItem,
                        'tanggal' => Carbon::now(),
                    ]);
                }

                // Catat mutasi untuk tabung rusak
                if ($jumlahRusak > 0) {
                    $this->catatMutasi([
                        'produk_id' => $stok->id,
                        'tipe_id' => $stok->tipe_gas_id,
                        'stok_awal' => $stokRusakAwal,
                        'stok_masuk' => $jumlahRusak,
                        'stok_keluar' => 0,
                        'stok_akhir' => $stok->jumlah_rusak,
                        'total_harga' => 0,
                        'kode_mutasi' => 'R',
                        'ket_mutasi' => 'Pengembalian Rusak - ' . $kodeItem,
                        'tanggal' => Carbon::now(),
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

    // method catatMutasi() tetap sama, tidak perlu diubah
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