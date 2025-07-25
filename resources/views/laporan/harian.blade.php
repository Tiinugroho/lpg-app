@extends('layouts.master')
@section('title', 'Laporan Harian')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Laporan Harian</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Laporan Harian</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter Tanggal -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.harian') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Pilih Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
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
                                        <a href="{{ route('laporan.export.harian') }}?tanggal={{ $tanggal }}" 
                                           class="btn btn-success" target="_blank">
                                            <i class="mdi mdi-file-pdf"></i> Export PDF
                                        </a>
                                        <button type="button" class="btn btn-info" onclick="window.print()">
                                            <i class="mdi mdi-printer"></i> Print
                                        </button>
                                        <a href="{{ route('laporan.harian') }}" class="btn btn-secondary">
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
            <div class="col-lg-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <i class="mdi mdi-cash-multiple display-6"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h3 class="mb-0">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</h3>
                                <span>Total Penjualan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <i class="mdi mdi-gas-cylinder display-6"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h3 class="mb-0">{{ $summary['total_tabung_terjual'] }}</h3>
                                <span>Tabung Terjual</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <i class="mdi mdi-backup-restore display-6"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h3 class="mb-0">{{ $summary['total_tabung_kembali'] }}</h3>
                                <span>Tabung Kembali</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <i class="mdi mdi-alert-circle display-6"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h3 class="mb-0">{{ $summary['total_tabung_rusak'] }}</h3>
                                <span>Tabung Rusak</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Penjualan -->
        @if($penjualan->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-cart"></i> Detail Penjualan</h5>
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
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->nama_pembeli }}</td>
                                        <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->tanggal_transaksi)->format('H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Pengembalian -->
        @if($pengembalian->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="mdi mdi-backup-restore"></i> Detail Pengembalian</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pengembalianTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Tipe Gas</th>
                                        <th>Jumlah</th>
                                        <th>Rusak</th>
                                        <th>Kondisi</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengembalian as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama_pembeli }}</td>
                                        <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ $item->jumlah_rusak }}</td>
                                        <td>
                                            @if($item->kondisi_rusak)
                                                <span class="badge bg-danger">Ada Rusak</span>
                                            @else
                                                <span class="badge bg-success">Baik</span>
                                            @endif
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($item->tanggal_pengembalian)->format('H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Pembelian -->
        @if($pembelian->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="mdi mdi-shopping"></i> Detail Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pembelianTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pembelian</th>
                                        <th>Vendor</th>
                                        <th>Tipe Gas</th>
                                        <th>Jumlah</th>
                                        <th>Harga Beli</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_pembelian }}</td>
                                        <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                                        <td>{{ $item->tipeGas->nama ?? '-' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Penjualan per Tipe Gas -->
        @if($penjualanPerTipe->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-chart-pie"></i> Penjualan per Tipe Gas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="penjualanPerTipeTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tipe Gas</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Total Penjualan</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualanPerTipe as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['tipe'] }}</td>
                                        <td>{{ $item['jumlah'] }} tabung</td>
                                        <td>Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $persentase = $summary['total_penjualan'] > 0 ? ($item['total_harga'] / $summary['total_penjualan']) * 100 : 0;
                                            @endphp
                                            {{ number_format($persentase, 1) }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    .btn, .breadcrumb, .page-breadcrumb {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#penjualanTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    $('#pengembalianTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    $('#pembelianTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    $('#penjualanPerTipeTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });
});
</script>
@endpush
