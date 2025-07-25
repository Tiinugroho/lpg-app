<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\StokGas;
use App\Models\TipeGas;
use App\Models\MutasiGas;
use App\Models\PembelianGas;
use App\Models\PenjualanGas;
use Illuminate\Http\Request;
use App\Models\PengembalianGas;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{

    public function harian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        
        // Data Penjualan
        $penjualan = PenjualanGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_transaksi', $tanggal)
            ->get();
            
        // Data Pengembalian
        $pengembalian = PengembalianGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_pengembalian', $tanggal)
            ->get();
            
        // Data Pembelian
        $pembelian = PembelianGas::with(['tipeGas', 'vendor'])
            ->whereDate('tanggal_masuk', $tanggal)
            ->get();

        // Data Mutasi
        $mutasi = MutasiGas::with(['tipeGas', 'stokGas'])
            ->whereDate('tanggal', $tanggal)
            ->get();

        // Summary Data
        $summary = [
            'total_penjualan' => $penjualan->sum('total_harga'),
            'total_tabung_terjual' => $penjualan->sum('jumlah'),
            'total_pengembalian' => $pengembalian->count(),
            'total_tabung_kembali' => $pengembalian->sum('jumlah'),
            'total_tabung_rusak' => $pengembalian->sum('jumlah_rusak'),
            'total_pembelian' => $pembelian->sum(function($item) {
                return $item->jumlah * $item->harga_beli;
            }),
            'total_tabung_dibeli' => $pembelian->sum('jumlah'),
        ];

        // Penjualan per Tipe Gas
        $penjualanPerTipe = $penjualan->groupBy('stokGas.tipeGas.nama')
            ->map(function($items, $tipe) {
                return [
                    'tipe' => $tipe,
                    'jumlah' => $items->sum('jumlah'),
                    'total_harga' => $items->sum('total_harga'),
                ];
            });

        return view('laporan.harian', compact(
            'penjualan', 
            'pengembalian', 
            'pembelian', 
            'mutasi',
            'tanggal', 
            'summary',
            'penjualanPerTipe'
        ));
    }

    public function mingguan(Request $request)
    {
        $minggu = $request->minggu ?? now()->format('Y-\WW');
        $year = substr($minggu, 0, 4);
        $week = substr($minggu, 6);
        
        $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        
        $data = $this->getLaporanData($startDate, $endDate);
        
        // Summary Mingguan
        $summary = [
            'total_penjualan' => $data['penjualan']->sum('total_harga'),
            'total_tabung_terjual' => $data['penjualan']->sum('jumlah'),
            'total_pengembalian' => $data['pengembalian']->count(),
            'total_tabung_kembali' => $data['pengembalian']->sum('jumlah'),
            'total_tabung_rusak' => $data['pengembalian']->sum('jumlah_rusak'),
            'total_pembelian' => $data['pembelian']->sum(function($item) {
                return $item->jumlah * $item->harga_beli;
            }),
            'total_tabung_dibeli' => $data['pembelian']->sum('jumlah'),
        ];

        // Data per hari dalam minggu
        $dailyData = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dailyPenjualan = $data['penjualan']->filter(function($item) use ($date) {
                return Carbon::parse($item->tanggal_transaksi)->isSameDay($date);
            });
            
            $dailyData[] = [
                'tanggal' => $date->format('Y-m-d'),
                'hari' => $date->format('l'),
                'penjualan' => $dailyPenjualan->sum('total_harga'),
                'tabung_terjual' => $dailyPenjualan->sum('jumlah'),
            ];
        }
        
        return view('laporan.mingguan', compact(
            'data', 
            'minggu', 
            'startDate', 
            'endDate', 
            'summary',
            'dailyData'
        ));
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        $startDate = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $data = $this->getLaporanData($startDate, $endDate);
        
        // Summary Bulanan dengan pengecekan division by zero
        $totalPenjualan = $data['penjualan']->sum('total_harga');
        $totalPembelian = $data['pembelian']->sum(function($item) {
            return $item->jumlah * $item->harga_beli;
        });
        $totalTabungTerjual = $data['penjualan']->sum('jumlah');
        $totalTabungKembali = $data['pengembalian']->sum('jumlah');
        $totalTabungRusak = $data['pengembalian']->sum('jumlah_rusak');
        $totalPengembalian = $data['pengembalian']->count();
        
        $summary = [
            'total_penjualan' => $totalPenjualan,
            'total_tabung_terjual' => $totalTabungTerjual,
            'total_pengembalian' => $totalPengembalian,
            'total_tabung_kembali' => $totalTabungKembali,
            'total_tabung_rusak' => $totalTabungRusak,
            'total_pembelian' => $totalPembelian,
            'total_tabung_dibeli' => $data['pembelian']->sum('jumlah'),
            'keuntungan_kotor' => $totalPenjualan - $totalPembelian,
            'margin_keuntungan' => $totalPenjualan > 0 ? (($totalPenjualan - $totalPembelian) / $totalPenjualan) * 100 : 0,
            'rata_rata_harian' => $totalPenjualan > 0 ? $totalPenjualan / $endDate->day : 0,
            'rata_rata_tabung_harian' => $totalTabungTerjual > 0 ? $totalTabungTerjual / $endDate->day : 0,
            'harga_rata_rata_tabung' => $totalTabungTerjual > 0 ? $totalPenjualan / $totalTabungTerjual : 0,
            'tingkat_kerusakan' => $totalPengembalian > 0 ? ($totalTabungRusak / $totalPengembalian) * 100 : 0,
        ];

        // Data per minggu dalam bulan
        $weeklyData = [];
        $currentWeekStart = $startDate->copy()->startOfWeek();
        
        while ($currentWeekStart->lt($endDate)) {
            $weekEnd = $currentWeekStart->copy()->endOfWeek();
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate->copy();
            }
            
            $weeklyPenjualan = $data['penjualan']->filter(function($item) use ($currentWeekStart, $weekEnd) {
                $tanggal = Carbon::parse($item->tanggal_transaksi);
                return $tanggal->between($currentWeekStart, $weekEnd);
            });
            
            $weeklyData[] = [
                'minggu' => 'Minggu ' . $currentWeekStart->weekOfMonth,
                'periode' => $currentWeekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
                'penjualan' => $weeklyPenjualan->sum('total_harga'),
                'tabung_terjual' => $weeklyPenjualan->sum('jumlah'),
            ];
            
            $currentWeekStart->addWeek();
        }

        // Top 5 Tipe Gas Terlaris
        $topTipeGas = $data['penjualan']->groupBy('stokGas.tipeGas.nama')
            ->map(function($items, $tipe) {
                return [
                    'tipe' => $tipe,
                    'jumlah' => $items->sum('jumlah'),
                    'total_harga' => $items->sum('total_harga'),
                ];
            })
            ->sortByDesc('jumlah')
            ->take(5);
        
        return view('laporan.bulanan', compact(
            'data', 
            'bulan', 
            'startDate', 
            'endDate', 
            'summary',
            'weeklyData',
            'topTipeGas'
        ));
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

    public function exportHarianPdf(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        
        // Get data untuk laporan harian
        $penjualan = PenjualanGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_transaksi', $tanggal)
            ->get();
            
        $pengembalian = PengembalianGas::with(['stokGas.tipeGas'])
            ->whereDate('tanggal_pengembalian', $tanggal)
            ->get();
            
        $pembelian = PembelianGas::with(['tipeGas', 'vendor'])
            ->whereDate('tanggal_masuk', $tanggal)
            ->get();

        $summary = [
            'total_penjualan' => $penjualan->sum('total_harga'),
            'total_tabung_terjual' => $penjualan->sum('jumlah'),
            'total_pengembalian' => $pengembalian->count(),
            'total_tabung_kembali' => $pengembalian->sum('jumlah'),
            'total_tabung_rusak' => $pengembalian->sum('jumlah_rusak'),
            'total_pembelian' => $pembelian->sum(function($item) {
                return $item->jumlah * $item->harga_beli;
            }),
        ];

        $penjualanPerTipe = $penjualan->groupBy('stokGas.tipeGas.nama')
            ->map(function($items, $tipe) {
                return [
                    'tipe' => $tipe,
                    'jumlah' => $items->sum('jumlah'),
                    'total_harga' => $items->sum('total_harga'),
                ];
            });

        $data = compact('penjualan', 'pengembalian', 'pembelian', 'tanggal', 'summary', 'penjualanPerTipe');
        
        $pdf = PDF::loadView('laporan.pdf.harian', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('laporan-harian-' . $tanggal . '.pdf');
    }

    public function exportMingguanPdf(Request $request)
    {
        $minggu = $request->minggu ?? now()->format('Y-\WW');
        $year = substr($minggu, 0, 4);
        $week = substr($minggu, 6);
        
        $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        
        $data = $this->getLaporanData($startDate, $endDate);
        
        $summary = [
            'total_penjualan' => $data['penjualan']->sum('total_harga'),
            'total_tabung_terjual' => $data['penjualan']->sum('jumlah'),
            'total_pengembalian' => $data['pengembalian']->count(),
            'total_tabung_kembali' => $data['pengembalian']->sum('jumlah'),
            'total_tabung_rusak' => $data['pengembalian']->sum('jumlah_rusak'),
            'total_pembelian' => $data['pembelian']->sum(function($item) {
                return $item->jumlah * $item->harga_beli;
            }),
        ];

        $dailyData = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dailyPenjualan = $data['penjualan']->filter(function($item) use ($date) {
                return Carbon::parse($item->tanggal_transaksi)->isSameDay($date);
            });
            
            $dailyData[] = [
                'tanggal' => $date->format('Y-m-d'),
                'hari' => $date->format('l'),
                'penjualan' => $dailyPenjualan->sum('total_harga'),
                'tabung_terjual' => $dailyPenjualan->sum('jumlah'),
            ];
        }

        $pdfData = array_merge($data, compact('minggu', 'startDate', 'endDate', 'summary', 'dailyData'));
        
        $pdf = PDF::loadView('laporan.pdf.mingguan', $pdfData);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('laporan-mingguan-' . $minggu . '.pdf');
    }

    public function exportBulananPdf(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        $startDate = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $data = $this->getLaporanData($startDate, $endDate);
        
        // Summary dengan pengecekan division by zero
        $totalPenjualan = $data['penjualan']->sum('total_harga');
        $totalPembelian = $data['pembelian']->sum(function($item) {
            return $item->jumlah * $item->harga_beli;
        });
        $totalTabungTerjual = $data['penjualan']->sum('jumlah');
        $totalTabungKembali = $data['pengembalian']->sum('jumlah');
        $totalTabungRusak = $data['pengembalian']->sum('jumlah_rusak');
        $totalPengembalian = $data['pengembalian']->count();
        
        $summary = [
            'total_penjualan' => $totalPenjualan,
            'total_tabung_terjual' => $totalTabungTerjual,
            'total_pengembalian' => $totalPengembalian,
            'total_tabung_kembali' => $totalTabungKembali,
            'total_tabung_rusak' => $totalTabungRusak,
            'total_pembelian' => $totalPembelian,
            'total_tabung_dibeli' => $data['pembelian']->sum('jumlah'),
            'keuntungan_kotor' => $totalPenjualan - $totalPembelian,
            'margin_keuntungan' => $totalPenjualan > 0 ? (($totalPenjualan - $totalPembelian) / $totalPenjualan) * 100 : 0,
            'rata_rata_harian' => $totalPenjualan > 0 ? $totalPenjualan / $endDate->day : 0,
            'rata_rata_tabung_harian' => $totalTabungTerjual > 0 ? $totalTabungTerjual / $endDate->day : 0,
            'harga_rata_rata_tabung' => $totalTabungTerjual > 0 ? $totalPenjualan / $totalTabungTerjual : 0,
            'tingkat_kerusakan' => $totalPengembalian > 0 ? ($totalTabungRusak / $totalPengembalian) * 100 : 0,
        ];

        $weeklyData = [];
        $currentWeekStart = $startDate->copy()->startOfWeek();
        
        while ($currentWeekStart->lt($endDate)) {
            $weekEnd = $currentWeekStart->copy()->endOfWeek();
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate->copy();
            }
            
            $weeklyPenjualan = $data['penjualan']->filter(function($item) use ($currentWeekStart, $weekEnd) {
                $tanggal = Carbon::parse($item->tanggal_transaksi);
                return $tanggal->between($currentWeekStart, $weekEnd);
            });
            
            $weeklyData[] = [
                'minggu' => 'Minggu ' . $currentWeekStart->weekOfMonth,
                'periode' => $currentWeekStart->format('d M') . ' - ' . $weekEnd->format('d M'),
                'penjualan' => $weeklyPenjualan->sum('total_harga'),
                'tabung_terjual' => $weeklyPenjualan->sum('jumlah'),
            ];
            
            $currentWeekStart->addWeek();
        }

        $topTipeGas = $data['penjualan']->groupBy('stokGas.tipeGas.nama')
            ->map(function($items, $tipe) {
                return [
                    'tipe' => $tipe,
                    'jumlah' => $items->sum('jumlah'),
                    'total_harga' => $items->sum('total_harga'),
                ];
            })
            ->sortByDesc('jumlah')
            ->take(5);

        $pdfData = array_merge($data, compact('bulan', 'startDate', 'endDate', 'summary', 'weeklyData', 'topTipeGas'));
        
        $pdf = PDF::loadView('laporan.pdf.bulanan', $pdfData);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-bulanan-' . $bulan . '.pdf');
    }
}
