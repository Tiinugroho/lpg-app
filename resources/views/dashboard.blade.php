@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="{{ url('/') }}" title="Go to Home" class="tip-bottom">
                    <i class="icon-home"></i> Home
                </a>
            </div>
        </div>
        <div class="container-fluid">

            <!-- Statistik Utama LPG -->

            <div class="row-fluid">
                <div class="span7">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"> <i class="icon-pencil"></i> </span>
                            <h5>Pendapatan</h5>
                        </div>
                        <div class="widget-content">
                            <div class="row-fluid">
                                <div class="span6 text-center">
                                    <div class="stat-box">
                                        <h3 style="color: #5bb75b; margin: 0;">Rp
                                            {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
                                        <p style="margin: 5px 0;">Pendapatan Hari Ini</p>
                                    </div>
                                </div>
                                <div class="span6 text-center">
                                    <div class="stat-box">
                                        <h3 style="color: #49afcd; margin: 0;">Rp
                                            {{ number_format($penjualanBlnIni, 0, ',', '.') }}</h3>
                                        <p style="margin: 5px 0;">Pendapatan Bulan Ini</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row-fluid">
                                <div class="span6 text-center">
                                    <div class="stat-box">
                                        <h3 style="color: #faa732; margin: 0;">{{ $transaksiBlnIni }}</h3>
                                        <p style="margin: 5px 0;">Transaksi Bulan Ini</p>
                                    </div>
                                </div>
                                <div class="span6 text-center">
                                    <div class="stat-box">
                                        <h3 style="color: #da4f49; margin: 0;">{{ $pengembalianHariIni }}
                                        </h3>
                                        <p style="margin: 5px 0;">Pengembalian Hari Ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span5">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-fire"></i></span>
                            <h5>Statistik Sistem LPG</h5>
                            <div class="buttons">
                                <a href="{{ url('/') }}" class="btn btn-mini btn-success">
                                    <i class="icon-refresh"></i> Update Stats
                                </a>
                            </div>
                        </div>
                        <div class="widget-content">
                            <div class="row-fluid">

                                <ul class="stat-boxes2">
                                    <li>
                                        <div class="left peity_bar_good">
                                            <span
                                                class="badge badge-success">+{{ $stokTerkini->jumlah_tabung_penuh ?? 0 }}</span>
                                        </div>
                                        <div class="right">
                                            <strong>{{ $stokTerkini->jumlah_tabung_penuh ?? 0 }}</strong> Tabung Penuh
                                        </div>
                                    </li>
                                    <li>
                                        <div class="left peity_line_neutral">
                                            <span
                                                class="badge badge-warning">{{ $stokTerkini->jumlah_tabung_kosong ?? 0 }}</span>
                                        </div>
                                        <div class="right">
                                            <strong>{{ $stokTerkini->jumlah_tabung_kosong ?? 0 }}</strong> Tabung Kosong
                                        </div>
                                    </li>
                                    <li>
                                        <div class="left peity_bar_bad">
                                            <span
                                                class="badge badge-important">-{{ $stokTerkini->jumlah_tabung_rusak ?? 0 }}</span>
                                        </div>
                                        <div class="right">
                                            <strong>{{ $stokTerkini->jumlah_tabung_rusak ?? 0 }}</strong> Tabung Rusak
                                        </div>
                                    </li>
                                    <li>
                                        <div class="left peity_line_good">
                                            <span class="badge badge-info">+{{ $transaksiHariIni }}</span>
                                        </div>
                                        <div class="right">
                                            <strong>{{ $transaksiHariIni }}</strong> Transaksi Hari Ini
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Quick Actions dan Pendapatan -->
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-tasks"></i></span>
                            <h5>Quick Actions</h5>
                        </div>
                        <div class="widget-content">
                            <div class="row-fluid">
                                <div class="span6">
                                    <a href="{{ url('transaksi/create') }}" class="btn btn-primary btn-large btn-block"
                                        style="margin-bottom: 10px;">
                                        <i class="icon-shopping-cart"></i><br>
                                        Transaksi Baru
                                    </a>
                                </div>
                                <div class="span6">
                                    <a href="{{ url('pengembalian/create') }}" class="btn btn-warning btn-large btn-block"
                                        style="margin-bottom: 10px;">
                                        <i class="icon-repeat"></i><br>
                                        Pengembalian
                                    </a>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <a href="{{ url('restock/create') }}" class="btn btn-success btn-large btn-block">
                                        <i class="icon-truck"></i><br>
                                        Restock
                                    </a>
                                </div>
                                <div class="span6">
                                    <a href="{{ url('laporan/harian') }}" class="btn btn-info btn-large btn-block">
                                        <i class="icon-file"></i><br>
                                        Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            <!-- Transaksi Terbaru dan Informasi Sistem -->
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-list"></i></span>
                            <h5>Transaksi Terbaru</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <ul class="recent-posts">
                                @forelse(\App\Models\TransaksiPembelian::with(['pelanggan', 'pengguna'])->latest()->take(5)->get() as $transaksi)
                                    <li>
                                        <div class="user-thumb">
                                            <div
                                                style="width: 40px; height: 40px; background: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                                                {{ substr($transaksi->pelanggan->nama_pelanggan, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="article-post">
                                            <span class="user-info">
                                                {{ $transaksi->pelanggan->nama_pelanggan }} /
                                                {{ $transaksi->tanggal_transaksi->format('d M Y') }} /
                                                {{ $transaksi->tanggal_transaksi->format('H:i') }}
                                            </span>
                                            <p>
                                                <strong>{{ $transaksi->kode_transaksi }}</strong> -
                                                {{ $transaksi->jumlah_tabung }} tabung -
                                                <span style="color: #5bb75b; font-weight: bold;">Rp
                                                    {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <div class="article-post">
                                            <p>Belum ada transaksi hari ini</p>
                                        </div>
                                    </li>
                                @endforelse
                                <li>
                                    <a href="{{ url('transaksi/') }}" class="btn btn-warning btn-mini">Lihat
                                        Semua</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Informasi Sistem</h5>
                        </div>
                        <div class="widget-content nopadding updates">
                            @if ($stokTerkini && $stokTerkini->jumlah_tabung_penuh < 10)
                                <div class="new-update clearfix">
                                    <i class="icon-warning-sign"></i>
                                    <div class="update-alert">
                                        <a title="" href="{{ url('stok.index') }}">
                                            <strong>Stok Tabung Penuh Menipis!</strong>
                                        </a>
                                        <span>Hanya tersisa {{ $stokTerkini->jumlah_tabung_penuh }} tabung</span>
                                    </div>
                                    <div class="update-date">
                                        <span class="update-day">{{ now()->format('d') }}</span>
                                        {{ now()->format('M') }}
                                    </div>
                                </div>
                            @endif

                            @if ($stokTerkini && $stokTerkini->jumlah_tabung_rusak > 5)
                                <div class="new-update clearfix">
                                    <i class="icon-remove-sign"></i>
                                    <span class="update-notice">
                                        <a title="" href="{{ url('stok.index') }}">
                                            <strong>Banyak Tabung Rusak</strong>
                                        </a>
                                        <span>{{ $stokTerkini->jumlah_tabung_rusak }} tabung perlu diperbaiki</span>
                                    </span>
                                    <span class="update-date">
                                        <span class="update-day">{{ now()->format('d') }}</span>
                                        {{ now()->format('M') }}
                                    </span>
                                </div>
                            @endif

                            <div class="new-update clearfix">
                                <i class="icon-ok-sign"></i>
                                <span class="update-done">
                                    <a title="" href="#">
                                        <strong>Sistem Berjalan Normal</strong>
                                    </a>
                                    <span>Semua fitur sistem LPG aktif</span>
                                </span>
                                <span class="update-date">
                                    <span class="update-day">{{ now()->format('d') }}</span>
                                    {{ now()->format('M') }}
                                </span>
                            </div>

                            <div class="new-update clearfix">
                                <i class="icon-user"></i>
                                <span class="update-notice">
                                    <a title="" href="#">
                                        @guest
                                            <strong>Guest</strong>
                                        @else
                                            <strong>Login sebagai {{ ucfirst(auth()->user()->role) }}</strong>
                                        @endguest
                                    </a>
                                    @guest
                                        <span>Guest</span>
                                    @else
                                        <span>{{ auth()->user()->nama_lengkap }}</span>
                                    @endguest
                                </span>
                                <span class="update-date">
                                    <span class="update-day">{{ now()->format('d') }}</span>
                                    {{ now()->format('M') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Informasi Harga dan Vendor -->
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-tags"></i></span>
                            <h5>Informasi Harga & Stok</h5>
                        </div>
                        <div class="widget-content">
                            @if ($stokTerkini)
                                <div class="row-fluid text-center" style="margin-bottom: 15px;">
                                    <div class="span12">
                                        <h4 style="margin: 0;">
                                            Harga per Tabung:
                                            <span style="color: #5bb75b;">Rp
                                                {{ number_format($stokTerkini->harga_per_tabung, 0, ',', '.') }}</span>
                                        </h4>
                                        <p style="color: #999; margin: 5px 0;">
                                            Update terakhir: {{ $stokTerkini->tanggal_update->format('d F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span4 text-center">
                                        <div class="stat-box">
                                            <h3 style="color: #5bb75b; margin: 0;">{{ $stokTerkini->jumlah_tabung_penuh }}
                                            </h3>
                                            <p style="margin: 5px 0;">Penuh</p>
                                        </div>
                                    </div>
                                    <div class="span4 text-center">
                                        <div class="stat-box">
                                            <h3 style="color: #faa732; margin: 0;">
                                                {{ $stokTerkini->jumlah_tabung_kosong }}</h3>
                                            <p style="margin: 5px 0;">Kosong</p>
                                        </div>
                                    </div>
                                    <div class="span4 text-center">
                                        <div class="stat-box">
                                            <h3 style="color: #da4f49; margin: 0;">{{ $stokTerkini->jumlah_tabung_rusak }}
                                            </h3>
                                            <p style="margin: 5px 0;">Rusak</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <strong>Perhatian!</strong> Belum ada data stok. Silakan lakukan restock terlebih
                                    dahulu.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-user"></i></span>
                            <h5>Vendor Aktif</h5>
                        </div>
                        <div class="widget-content nopadding" style="max-height: 250px; overflow-y: auto;">
                            <ul class="recent-posts">
                                @forelse(\App\Models\Vendor::where('status_aktif', true)->take(4)->get() as $vendor)
                                    <li>
                                        <div class="user-thumb">
                                            <div
                                                style="width: 40px; height: 40px; background: #e74c3c; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                                                {{ substr($vendor->nama_vendor, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="article-post">
                                            <span class="user-info">{{ $vendor->nama_vendor }}</span>
                                            <p>{{ $vendor->kontak_person }} - {{ $vendor->telepon }}</p>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <div class="article-post">
                                            <span class="user-info">Belum ada vendor</span>
                                            <p><a href="{{ url('vendor/create') }}" class="btn btn-primary">Tambah vendor baru</a></p>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-box {
            text-align: center;
            padding: 10px;
        }

        .stat-box h3 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .stat-box p {
            margin: 5px 0 0 0;
            color: #666;
        }

        #grafikContainer {
            position: relative;
            height: 300px;
        }

        #grafikPenjualan {
            max-width: 100%;
            height: auto;
        }
    </style>

    <!-- Chart.js untuk grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Penjualan 7 Hari Terakhir
            const ctx = document.getElementById('grafikPenjualan');
            if (ctx) {
                const grafikPenjualan = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_column($grafikPenjualan, 'tanggal')) !!},
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: {!! json_encode(array_column($grafikPenjualan, 'penjualan')) !!},
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3498db',
                            pointBorderColor: '#2980b9',
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Grafik Penjualan 7 Hari Terakhir',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        elements: {
                            line: {
                                borderWidth: 3
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }
        });
    </script>
@endsection
