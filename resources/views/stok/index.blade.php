@extends('layouts.master')
@section('title', 'Stok Gas')

@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="#" title="Go to Dashboard" class="tip-bottom"><i class="icon-home"></i> Dashboard</a>
                <a href="#" class="current">Data Stok Gas</a>
            </div>
            <h1>Data Stok Gas</h1>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div class="text-left">
                        <a href="{{ url('stok/create') }}" class="btn btn-primary">Tambah</a>
                    </div>
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-th"></i></span>
                            <h5>Data Stok Gas</h5>
                        </div>

                        <div class="widget-content nopadding">
                            <table id="stokGasTable" class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Gas</th>
                                        <th>Tabung Penuh</th>
                                        <th>Tabung Kosong</th>
                                        <th>Tabung Rusak</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Tanggal Terakhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stokGas as $stok)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stok->nama_gas }}</td>
                                            <td>{{ $stok->jumlah_tabung_penuh }}</td>
                                            <td>{{ $stok->jumlah_tabung_kosong }}</td>
                                            <td>{{ $stok->jumlah_tabung_rusak }}</td>
                                            <td>Rp {{ number_format($stok->harga_beli_per_tabung, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($stok->harga_jual_per_tabung, 0, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stok->tanggal_update)->format('d-m-Y') }}</td>
                                            <td>-</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Data Kosong</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
