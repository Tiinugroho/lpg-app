@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="page-wrapper">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Dashboard & Transaksi</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Stat Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Pendapatan Hari Ini</h5>
                                    <h3 class="mb-0">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cash-multiple display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Transaksi Bulan Ini</h5>
                                    <h3 class="mb-0">{{ $transaksiBlnIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-chart-line display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-danger text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Penjualan Bulan Ini</h5>
                                    <h3 class="mb-0">{{ $penjualanBlnIni }} Tabung</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-trending-up display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-dark text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Pengembalian Bulan Ini</h5>
                                    <h3 class="mb-0">{{ $pengembalianBlnIni }} Tabung</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-backup-restore display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Stok Gas Penuh</h5>
                                    <h3 class="mb-0">{{ $totalStokPenuh }} Tabung</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-secondary text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Stok Gas Kosong</h5>
                                    <h3 class="mb-0">{{ $totalStokKosong }} Tabung</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cylinder display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white;">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Stok Gas Rusak</h5>
                                    <h3 class="mb-0">{{ $totalStokRusak }} Tabung</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-alert-circle display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (in_array(Auth::user()->role, ['super-admin', 'owner']))
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body"
                                style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Total Vendor</h5>
                                        <h3 class="mb-0">{{ $totalVendor }}</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-account-group display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Laporan Section -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #17a2b8, #138496); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-file-document me-2"></i>Laporan Harian</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('laporan.harian') }}" method="GET" target="_blank">
                                <div class="form-group">
                                    <label><i class="mdi mdi-calendar me-1"></i>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <button type="submit" class="btn btn-info shadow">
                                    <i class="mdi mdi-file-document"></i> Lihat Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #ffc107, #e0a800); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-file-chart me-2"></i>Laporan Mingguan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('laporan.mingguan') }}" method="GET" target="_blank">
                                <div class="form-group">
                                    <label><i class="mdi mdi-calendar-week me-1"></i>Minggu</label>
                                    <input type="week" name="minggu" class="form-control"
                                        value="{{ date('Y-\WW') }}" required>
                                </div>
                                <button type="submit" class="btn btn-warning shadow">
                                    <i class="mdi mdi-file-document"></i> Lihat Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #6c757d, #545b62); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-file-table me-2"></i>Laporan Bulanan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('laporan.bulanan') }}" method="GET" target="_blank">
                                <div class="form-group">
                                    <label><i class="mdi mdi-calendar-month me-1"></i>Bulan</label>
                                    <input type="month" name="bulan" class="form-control"
                                        value="{{ date('Y-m') }}" required>
                                </div>
                                <button type="submit" class="btn btn-secondary shadow">
                                    <i class="mdi mdi-file-document"></i> Lihat Laporan
                                </button>
                            </form>
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
            let penjualanIndex = 1;
            let pengembalianIndex = 1;
            let pembelianIndex = 1;

            // Initialize Select2 dengan pengecekan yang lebih ketat
            function initSelect2(container) {
                container = container || $(document);
                container.find('.select2, .select2-item').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        const placeholder = $(this).data('placeholder') || 'Pilih...';
                        console.log('Initializing Select2 with placeholder:', placeholder);
                        $(this).select2({
                            placeholder: placeholder,
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $(this).closest('.card-body')
                        });
                    }
                });
            }

            // Auto fill harga jual pada penjualan
            $(document).on('change', 'select[name*="[produk_id]"]', function() {
                const selectedOption = $(this).find('option:selected');
                const harga = selectedOption.data('harga');
                const stok = selectedOption.data('stok');
                const container = $(this).closest('.penjualan-item');

                if (harga) {
                    container.find('.harga-display').val('Rp ' + new Intl.NumberFormat('id-ID').format(
                        harga));
                    container.find('.jumlah-input').attr('max', stok);
                }
            });

            // Penjualan Repeater
            $('#add-penjualan').click(function() {
                const template = `
            <div class="penjualan-item border p-3 mb-3 rounded shadow-sm" data-item="${penjualanIndex}" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #${penjualanIndex + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-penjualan">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><i class="mdi mdi-gas-cylinder me-1"></i>Produk Gas</label>
                        <select name="items[${penjualanIndex}][produk_id]" class="select2 form-control" data-placeholder="Pilih Produk Gas" required>
                            <option value=""></option>
                            @foreach ($stokGas as $stok)
                                @if ($stok->jumlah_penuh > 0)
                                    <option value="{{ $stok->id }}" data-harga="{{ $stok->harga_jual }}" data-stok="{{ $stok->jumlah_penuh }}">
                                        {{ $stok->tipeGas->nama }} (Stok: {{ $stok->jumlah_penuh }}) - Rp {{ number_format($stok->harga_jual, 0, ',', '.') }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                        <input type="number" name="items[${penjualanIndex}][jumlah]" class="form-control jumlah-input" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label><i class="mdi mdi-currency-usd me-1"></i>Harga Satuan</label>
                        <input type="text" class="form-control harga-display" readonly>
                    </div>
                </div>
            </div>
        `;

                $('#penjualan-container').append(template);
                const newItem = $('.penjualan-item').last();
                initSelect2(newItem);
                penjualanIndex++;
                updatePenjualanButtons();
            });

            $(document).on('click', '.remove-penjualan', function() {
                if ($('.penjualan-item').length > 1) {
                    $(this).closest('.penjualan-item').remove();
                    updatePenjualanButtons();
                }
            });

            function updatePenjualanButtons() {
                if ($('.penjualan-item').length > 1) {
                    $('.remove-penjualan').show();
                } else {
                    $('.remove-penjualan').hide();
                }
            }

            // Pengembalian Repeater
            $('#add-pengembalian').click(function() {
                const template = `
            <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm" data-item="${pengembalianIndex}" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-danger"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #${pengembalianIndex + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-pengembalian">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><i class="mdi mdi-gas-cylinder me-1"></i>Produk Gas</label>
                        <select name="items[${pengembalianIndex}][produk_id]" class="select2 form-control" data-placeholder="Pilih Produk Gas" required>
                            <option value=""></option>
                            @foreach ($stokGas as $stok)
                                <option value="{{ $stok->id }}">{{ $stok->tipeGas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                        <input type="number" name="items[${pengembalianIndex}][jumlah]" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label><i class="mdi mdi-alert-circle me-1"></i>Jumlah Rusak</label>
                        <input type="number" name="items[${pengembalianIndex}][jumlah_rusak]" class="form-control" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="mdi mdi-alert-circle me-1"></i>Kondisi Rusak</label>
                    <div class="d-flex">
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="rusak_${pengembalianIndex}" name="items[${pengembalianIndex}][kondisi_rusak]" value="1">
                            <label class="custom-control-label" for="rusak_${pengembalianIndex}">Ada yang rusak</label>
                        </div>
                    </div>
                </div>
            </div>
        `;

                $('#pengembalian-container').append(template);
                const newItem = $('.pengembalian-item').last();
                initSelect2(newItem);
                pengembalianIndex++;
                updatePengembalianButtons();
            });

            $(document).on('click', '.remove-pengembalian', function() {
                if ($('.pengembalian-item').length > 1) {
                    $(this).closest('.pengembalian-item').remove();
                    updatePengembalianButtons();
                }
            });

            function updatePengembalianButtons() {
                if ($('.pengembalian-item').length > 1) {
                    $('.remove-pengembalian').show();
                } else {
                    $('.remove-pengembalian').hide();
                }
            }

            // Pembelian Repeater
            $('#add-pembelian').click(function() {
                const template = `
            <div class="pembelian-item border p-3 mb-3 rounded shadow-sm" data-item="${pembelianIndex}" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-primary"><i class="mdi mdi-numeric-${pembelianIndex + 1}-circle me-1"></i>Produk #${pembelianIndex + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-pembelian">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label><i class="mdi mdi-gas-cylinder me-1"></i>Produk Gas</label>
                        <select name="items[${pembelianIndex}][tipe_id]" class="form-control select2-item" data-placeholder="Pilih Produk Gas" required>
                            <option value="">Pilih Produk Gas</option>
                            @foreach ($tipeGas as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                        <input type="number" name="items[${pembelianIndex}][jumlah]" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label><i class="mdi mdi-currency-usd me-1"></i>Harga Beli Satuan(Rp)</label>
                        <input type="number" name="items[${pembelianIndex}][harga_beli]" class="form-control" min="0" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label><i class="mdi mdi-calendar me-1"></i>Tanggal Masuk</label>
                        <input type="date" name="items[${pembelianIndex}][tanggal_masuk]" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>
        `;

                $('#pembelian-container').append(template);
                const newItem = $('.pembelian-item').last();
                initSelect2(newItem);
                pembelianIndex++;
                updatePembelianButtons();
            });

            $(document).on('click', '.remove-pembelian', function() {
                if ($('.pembelian-item').length > 1) {
                    $(this).closest('.pembelian-item').remove();
                    updatePembelianButtons();
                }
            });

            function updatePembelianButtons() {
                if ($('.pembelian-item').length > 1) {
                    $('.remove-pembelian').show();
                } else {
                    $('.remove-pembelian').hide();
                }
            }

            // Initialize everything
            initSelect2();
            updatePenjualanButtons();
            updatePengembalianButtons();
            updatePembelianButtons();

            console.log('Dashboard initialized successfully');
            console.log('Vendors available:', {{ $vendors->count() }});
        });
    </script>
@endpush
