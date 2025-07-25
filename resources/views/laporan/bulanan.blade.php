@extends('layouts.master')
@section('title', 'Laporan Bulanan')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Laporan Bulanan</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Laporan Bulanan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter Bulan -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.bulanan') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Pilih Bulan</label>
                                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">
                                        <i class="mdi mdi-magnify"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-6 text-end">
                                    <label>&nbsp;</label>
                                    <div class="d-block">
                                        <a href="{{ route('laporan.export.bulanan') }}?bulan={{ $bulan }}" 
                                           class="btn btn-success" target="_blank">
                                            <i class="mdi mdi-file-pdf"></i> Export PDF
                                        </a>
                                        <button type="button" class="btn btn-info" onclick="window.print()">
                                            <i class="mdi mdi-printer"></i> Print
                                        </button>
                                        <a href="{{ route('laporan.bulanan') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-arrow-left"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-2 col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="mdi mdi-cash-multiple display-6"></i>
                        <h4 class="mt-2">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</h4>
                        <span>Total Penjualan</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="mdi mdi-shopping display-6"></i>
                        <h4 class="mt-2">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</h4>
                        <span>Total Pembelian</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="mdi mdi-trending-up display-6"></i>
                        <h4 class="mt-2">Rp {{ number_format($summary['keuntungan_kotor'], 0, ',', '.') }}</h4>
                        <span>Keuntungan Kotor</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="mdi mdi-gas-cylinder display-6"></i>
                        <h4 class="mt-2">{{ $summary['total_tabung_terjual'] }}</h4>
                        <span>Tabung Terjual</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <i class="mdi mdi-backup-restore display-6"></i>
                        <h4 class="mt-2">{{ $summary['total_tabung_kembali'] }}</h4>
                        <span>Tabung Kembali</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="mdi mdi-alert-circle display-6"></i>
                        <h4 class="mt-2">{{ $summary['total_tabung_rusak'] }}</h4>
                        <span>Tabung Rusak</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penjualan per Minggu -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-chart-bar"></i> Penjualan per Minggu</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="weeklyTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Minggu</th>
                                        <th>Periode</th>
                                        <th>Total Penjualan</th>
                                        <th>Tabung Terjual</th>
                                        <th>Rata-rata Harian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($weeklyData as $index => $week)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $week['minggu'] }}</td>
                                        <td>{{ $week['periode'] }}</td>
                                        <td>Rp {{ number_format($week['penjualan'], 0, ',', '.') }}</td>
                                        <td>{{ $week['tabung_terjual'] }}</td>
                                        <td>Rp {{ number_format($week['penjualan'] / 7, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="mdi mdi-trophy"></i> Top 5 Tipe Gas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="topGasTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Tipe Gas</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topTipeGas as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['tipe'] }}</td>
                                        <td>{{ $item['jumlah'] }}</td>
                                        <td>Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analisis Performa -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="mdi mdi-chart-pie"></i> Analisis Performa</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Margin Keuntungan</strong></td>
                                        <td class="text-end">{{ number_format($summary['margin_keuntungan'], 1) }}%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rata-rata Penjualan Harian</strong></td>
                                        <td class="text-end">Rp {{ number_format($summary['rata_rata_harian'], 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rata-rata Tabung per Hari</strong></td>
                                        <td class="text-end">{{ number_format($summary['rata_rata_tabung_harian'], 1) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Harga Rata-rata per Tabung</strong></td>
                                        <td class="text-end">Rp {{ number_format($summary['harga_rata_rata_tabung'], 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="mdi mdi-alert-circle"></i> Ringkasan Pengembalian</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Total Pengembalian</strong></td>
                                        <td class="text-end">{{ $summary['total_pengembalian'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tabung Rusak</strong></td>
                                        <td class="text-end">{{ $summary['total_tabung_rusak'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tingkat Kerusakan</strong></td>
                                        <td class="text-end">{{ number_format($summary['tingkat_kerusakan'], 1) }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Penjualan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-cart"></i> Detail Penjualan Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="penjualanTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Pembeli</th>
                                        <th>Tipe Gas</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['penjualan'] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->nama_pembeli }}</td>
                                        <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#weeklyTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 10,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    $('#topGasTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 10,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    $('#penjualanTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 7, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });
});
</script>
@endpush
