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

            {{-- Statistik Utama --}}
            <div class="row-fluid">
                <div class="span7">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-pencil"></i></span>
                            <h5>Pendapatan</h5>
                        </div>
                        <div class="widget-content">
                            <div class="row-fluid">
                                <div class="span6 text-center">
                                    <h3 style="color:#5bb75b;">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
                                    <p>Pendapatan Hari Ini</p>
                                </div>
                                <div class="span6 text-center">
                                    <h3 style="color:#49afcd;">Rp {{ number_format($penjualanBlnIni, 0, ',', '.') }}</h3>
                                    <p>Pendapatan Bulan Ini</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row-fluid">
                                <div class="span6 text-center">
                                    <h3 style="color:#faa732;">{{ $transaksiBlnIni }}</h3>
                                    <p>Transaksi Bulan Ini</p>
                                </div>
                                <div class="span6 text-center">
                                    <h3 style="color:#da4f49;">{{ $pengembalianHariIni }}</h3>
                                    <p>Pengembalian Hari Ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistik Stok --}}
                <div class="span5">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-fire"></i></span>
                            <h5>Statistik LPG</h5>
                        </div>
                        <div class="widget-content">
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="card statistik-card">
                                        <div class="card-body text-center">
                                            <h4>{{ $stokTerkini->jumlah_tabung_penuh ?? 0 }}</h4>
                                            <p class="text-muted">Tabung Penuh</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="card statistik-card">
                                        <div class="card-body text-center">
                                            <h4>{{ $stokTerkini->jumlah_tabung_kosong ?? 0 }}</h4>
                                            <p class="text-muted">Tabung Kosong</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row-fluid" style="margin-top: 10px;">
                                <div class="span6">
                                    <div class="card statistik-card">
                                        <div class="card-body text-center">
                                            <h4>{{ $stokTerkini->jumlah_tabung_rusak ?? 0 }}</h4>
                                            <p class="text-muted">Tabung Rusak</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="card statistik-card">
                                        <div class="card-body text-center">
                                            <h4>{{ $transaksiHariIni }}</h4>
                                            <p class="text-muted">Transaksi Hari Ini</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            @if (Auth::check() && in_array(Auth::user()->role, ['super-admin', 'owner']))
                <hr>

                <!-- isi khusus super-admin -->
                <div class="row-fluid">
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-signal"></i></span>
                                <h5>Grafik Penjualan 7 Hari Terakhir</h5>
                            </div>
                            <div class="widget-content">
                                <div id="grafikContainer">
                                    <canvas id="grafikPenjualan"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-signal"></i></span>
                                <h5>Grafik Stok LPG</h5>
                            </div>
                            <div class="widget-content">
                                <canvas id="grafikStokLPG" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="span4">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"><i class="icon-group"></i></span>
                                <h5>Vendor Aktif per Tipe Gas</h5>
                            </div>
                            <div class="widget-content">
                                <canvas id="grafikVendor" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <hr>

            {{-- Informasi Sistem --}}
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-info-sign"></i></span>
                            <h5>Status Sistem</h5>
                        </div>
                        <div class="widget-content">
                            @if ($stokTerkini && $stokTerkini->jumlah_tabung_penuh < 10)
                                <div class="alert alert-warning">Stok tabung penuh menipis!</div>
                            @endif
                            @if ($stokTerkini && $stokTerkini->jumlah_tabung_rusak > 5)
                                <div class="alert alert-danger">Banyak tabung rusak! Segera tindak.</div>
                            @endif
                            <div class="alert alert-success">Sistem berjalan normal</div>
                        </div>
                    </div>
                </div>


                {{-- Vendor Aktif --}}
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-user"></i></span>
                            <h5>Vendor Aktif</h5>
                        </div>
                        <div class="widget-content">
                            <ul>
                                @foreach (\App\Models\Vendor::where('status_aktif', true)->take(5)->get() as $vendor)
                                    <li>{{ $vendor->nama_vendor }} - {{ $vendor->telepon }}</li>
                                @endforeach
                            </ul>
                            @if (Auth::check() && in_array(Auth::user()->role, ['super-admin', 'owner']))
                                <a href="{{ url('/manajemen-user/vendor') }}" class="btn btn-info btn-mini">Lihat Semua</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .statistik-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .statistik-card h4 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        .statistik-card p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #777;
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

            const ctxStok = document.getElementById('grafikStokLPG');
            if (ctxStok) {
                new Chart(ctxStok.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Penuh', 'Kosong', 'Rusak'],
                        datasets: [{
                            data: [
                                {{ $stokTerkini->jumlah_tabung_penuh ?? 0 }},
                                {{ $stokTerkini->jumlah_tabung_kosong ?? 0 }},
                                {{ $stokTerkini->jumlah_tabung_rusak ?? 0 }}
                            ],
                            backgroundColor: ['#5bb75b', '#faa732', '#da4f49'],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Distribusi Stok LPG'
                            }
                        }
                    }
                });
            }

            const ctxVendor = document.getElementById('grafikVendor');
            if (ctxVendor) {
                new Chart(ctxVendor.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($vendorPerTipe->pluck('tipeGas.nama')) !!},
                        datasets: [{
                            label: 'Vendor Aktif',
                            data: {!! json_encode($vendorPerTipe->pluck('jumlah')) !!},
                            backgroundColor: '#3498db',
                            borderColor: '#2980b9',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Vendor Aktif per Tipe Gas'
                            }
                        }
                    }
                });
            }

        });
    </script>

    
@endsection
