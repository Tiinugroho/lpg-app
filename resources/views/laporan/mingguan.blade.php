@extends('layouts.master')
@section('title', 'Laporan Mingguan')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Laporan Mingguan</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Laporan Mingguan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter Minggu -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.mingguan') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Pilih Minggu</label>
                                    <input type="week" name="minggu" class="form-control" value="{{ $minggu }}" required>
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
                                        <a href="{{ route('laporan.export.mingguan') }}?minggu={{ $minggu }}" 
                                           class="btn btn-success" target="_blank">
                                            <i class="mdi mdi-file-pdf"></i> Export PDF
                                        </a>
                                        <button type="button" class="btn btn-info" onclick="window.print()">
                                            <i class="mdi mdi-printer"></i> Print
                                        </button>
                                        <a href="{{ route('laporan.mingguan') }}" class="btn btn-secondary">
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
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="align-self-center">
                                <i class="mdi mdi-shopping display-6"></i>
                            </div>
                            <div class="ms-auto text-end">
                                <h3 class="mb-0">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</h3>
                                <span>Total Pembelian</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Penjualan Harian -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-chart-line"></i> Penjualan per Hari</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dailyTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hari</th>
                                        <th>Tanggal</th>
                                        <th>Total Penjualan</th>
                                        <th>Tabung Terjual</th>
                                        <th>Rata-rata per Tabung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyData as $index => $day)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $day['hari'] }}</td>
                                        <td>{{ Carbon\Carbon::parse($day['tanggal'])->format('d M Y') }}</td>
                                        <td>Rp {{ number_format($day['penjualan'], 0, ',', '.') }}</td>
                                        <td>{{ $day['tabung_terjual'] }}</td>
                                        <td>
                                            @if($day['tabung_terjual'] > 0)
                                                Rp {{ number_format($day['penjualan'] / $day['tabung_terjual'], 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
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

        <!-- Detail Penjualan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="mdi mdi-cart"></i> Detail Penjualan Mingguan</h5>
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

        <!-- Detail Pengembalian -->
        @if($data['pengembalian']->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="mdi mdi-backup-restore"></i> Detail Pengembalian Mingguan</h5>
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
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['pengembalian'] as $index => $item)
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
                                        <td>{{ Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}</td>
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
        @if($data['pembelian']->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="mdi mdi-shopping"></i> Detail Pembelian Mingguan</h5>
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
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['pembelian'] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_pembelian }}</td>
                                        <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                                        <td>{{ $item->tipeGas->nama ?? '-' }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</td>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#dailyTable').DataTable({
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

    $('#pengembalianTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 7, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    $('#pembelianTable').DataTable({
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
