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

class StokGasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::all();
        $tipeGas = TipeGas::all();

        // Total stok penuh
        $totalStokPenuh = StokGas::sum('jumlah_penuh');

        // Total stok pengembalian
        $totalStokPengembalian = StokGas::sum('jumlah_pengembalian');

        // Total stok rusak
        $totalStokRusak = StokGas::sum('jumlah_rusak');

        // Total nilai stok (berdasarkan harga beli)
        $totalNilaiStok = StokGas::selectRaw('SUM((jumlah_penuh + jumlah_pengembalian) * harga_beli) as total')
            ->value('total') ?? 0;

        // Stok per tipe gas
        $stokPerTipe = StokGas::selectRaw('
                tipe_gas_id, 
                tipe_gas.nama,
                SUM(jumlah_penuh) as total_penuh,
                SUM(jumlah_pengembalian) as total_pengembalian,
                SUM(jumlah_rusak) as total_rusak,
                SUM((jumlah_penuh + jumlah_pengembalian) * harga_beli) as nilai_stok
            ')
            ->join('tipe_gas', 'stok_gas.tipe_gas_id', '=', 'tipe_gas.id')
            ->groupBy('tipe_gas_id', 'tipe_gas.nama')
            ->get();

        // Stok per vendor
        $stokPerVendor = StokGas::selectRaw('
                vendor_id, 
                vendor.nama_vendor,
                SUM(jumlah_penuh) as total_penuh,
                SUM(jumlah_pengembalian) as total_pengembalian,
                SUM(jumlah_rusak) as total_rusak,
                SUM((jumlah_penuh + jumlah_pengembalian) * harga_beli) as nilai_stok
            ')
            ->join('vendor', 'stok_gas.vendor_id', '=', 'vendor.id')
            ->groupBy('vendor_id', 'vendor.nama_vendor')
            ->get();

        // Stok dengan stok minimum (contoh: kurang dari 10)
        $stokMinimum = StokGas::whereRaw('(jumlah_penuh + jumlah_pengembalian) < 10')
            ->with(['tipeGas', 'vendor'])
            ->get();

        // Data stok lengkap
        $stokGas = StokGas::with(['tipeGas', 'vendor'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('stok.index', compact(
            'stokGas',
            'totalStokPenuh',
            'totalStokPengembalian',
            'totalStokRusak',
            'totalNilaiStok',
            'stokPerTipe',
            'stokPerVendor',
            'stokMinimum',
            'vendors',
            'tipeGas'
        ));
    }

    /**
     * Store adjustment stok
     */
    public function adjustment(Request $request)
    {
        $request->validate([
            'stok_id' => 'required|exists:stok_gas,id',
            'tipe_adjustment' => 'required|in:penuh,pengembalian,rusak,harga',
            'jumlah_adjustment' => 'nullable|integer',
            'harga_beli' => 'nullable|numeric|min:0',
            'margin_persen' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $stok = StokGas::findOrFail($request->stok_id);
            $tipeAdjustment = $request->tipe_adjustment;

            // Simpan nilai awal untuk mutasi
            $stokAwal = 0;
            $stokAkhir = 0;
            $kodeAdjustment = 'ADJ-' . strtoupper(Str::random(8));

            if ($tipeAdjustment === 'harga') {
                // Adjustment harga
                $hargaBeliLama = $stok->harga_beli;
                $hargaJualLama = $stok->harga_jual;

                if ($request->harga_beli) {
                    $stok->harga_beli = $request->harga_beli;
                }

                if ($request->margin_persen && $request->harga_beli) {
                    $marginAmount = ($request->harga_beli * $request->margin_persen) / 100;
                    $stok->harga_jual = $request->harga_beli + $marginAmount;
                }

                // Laravel akan otomatis update updated_at
                $stok->save();

                // Catat mutasi harga
                $this->catatMutasi([
                    'kode' => $kodeAdjustment,
                    'tipe_id' => $stok->tipe_gas_id,
                    'produk_id' => $stok->id,
                    'stok_awal' => 0,
                    'stok_masuk' => 0,
                    'stok_keluar' => 0,
                    'stok_akhir' => 0,
                    'total_harga' => 0,
                    'kode_mutasi' => 'H', // H = Harga
                    'ket_mutasi' => 'Adjustment Harga - Beli: ' . number_format($hargaBeliLama) . ' → ' . number_format($stok->harga_beli) . 
                                   ', Jual: ' . number_format($hargaJualLama) . ' → ' . number_format($stok->harga_jual) . 
                                   ' (' . $request->margin_persen . '%) - ' . $request->keterangan,
                    'tanggal' => now(),
                ]);

            } else {
                // Adjustment stok
                $jumlahAdjustment = $request->jumlah_adjustment;
                $stokAwal = $stok->{$tipeAdjustment === 'penuh' ? 'jumlah_penuh' : ($tipeAdjustment === 'pengembalian' ? 'jumlah_pengembalian' : 'jumlah_rusak')};

                // Update stok berdasarkan tipe adjustment
                switch ($tipeAdjustment) {
                    case 'penuh':
                        $stok->jumlah_penuh = max(0, $stok->jumlah_penuh + $jumlahAdjustment);
                        break;
                    case 'pengembalian':
                        $stok->jumlah_pengembalian = max(0, $stok->jumlah_pengembalian + $jumlahAdjustment);
                        break;
                    case 'rusak':
                        $stok->jumlah_rusak = max(0, $stok->jumlah_rusak + $jumlahAdjustment);
                        break;
                }

                // Laravel akan otomatis update updated_at
                $stok->save();

                $stokAkhir = $stok->{$tipeAdjustment === 'penuh' ? 'jumlah_penuh' : ($tipeAdjustment === 'pengembalian' ? 'jumlah_pengembalian' : 'jumlah_rusak')};

                // Catat mutasi stok
                $this->catatMutasi([
                    'kode' => $kodeAdjustment,
                    'tipe_id' => $stok->tipe_gas_id,
                    'produk_id' => $stok->id,
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $jumlahAdjustment > 0 ? $jumlahAdjustment : 0,
                    'stok_keluar' => $jumlahAdjustment < 0 ? abs($jumlahAdjustment) : 0,
                    'stok_akhir' => $stokAkhir,
                    'total_harga' => 0,
                    'kode_mutasi' => 'A', // A = Adjustment
                    'ket_mutasi' => 'Adjustment ' . ucfirst($tipeAdjustment) . ' - ' . $request->keterangan,
                    'tanggal' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Adjustment berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk mencatat mutasi stok
     */
    private function catatMutasi($data)
    {
        try {
            MutasiGas::create([
                'kode' => $data['kode'], // Tambahkan ini - field yang hilang
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
