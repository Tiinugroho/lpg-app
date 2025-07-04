@extends('layouts.master')

@section('title', 'Penjualan Gas')

@section('content')
    <div class="page-wrapper">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Transaksi Penjualan Gas </h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stat Cards -->
            <div class="row">
                {{-- Total Pendapatan Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pendapatan Hari Ini</h5>
                                    <h3>Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-cash-multiple display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transaksi Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Transaksi Hari Ini</h5>
                                    <h3>{{ $transaksiHariIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-chart-line display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transaksi Bulan Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Transaksi Bulan Ini</h5>
                                    <h3>{{ $transaksiBlnIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-calendar display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok Penuh per Tipe --}}
                @foreach ($stokPenuhPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-warning text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Stok Penuh: {{ $item->tipeGas->nama }}</h5>
                                        <h3>{{ $item->total_penuh }} Tabung</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Penjualan Bulan Ini per Tipe --}}
                @foreach ($penjualanBlnIniPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-danger text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Penjualan Bulan Ini: {{ $item->nama }}</h5>
                                        <h3>{{ $item->total_terjual }} Tabung</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-trending-up display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #17a2b8, #00c6ff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Data Transaksi Penjualan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="penjualanTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Transaksi</th>
                                            <th>Nama Pembeli</th>
                                            <th>No KK</th>
                                            <th>No Telp</th>
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($penjualans as $kode => $items)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $kode }}</td>
                                                <td>{{ $items->first()->nama_pembeli }}</td>
                                                <td>{{ $items->first()->no_kk }}</td>
                                                <td>{{ $items->first()->no_telp }}</td>
                                                <td>{{ $items->sum('jumlah') }} Tabung</td>
                                                <td>Rp {{ number_format($items->sum('total_harga'), 0, ',', '.') }}</td>
                                                <td>{{ date('d M Y', strtotime($items->first()->tanggal_transaksi)) }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $kode }}">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </button>
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

            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                        <h5 class="mb-0"><i class="mdi mdi-cash-multiple me-2"></i>Form Penjualan Gas ke Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transaksi.penjualan.store') }}" method="POST" id="form-penjualan">
                            @csrf
                            <!-- Data Pembeli (Di luar repeater) -->
                            <div class="border p-3 mb-3 rounded shadow-sm"
                                style="background: linear-gradient(135deg, #e3f2fd, #f3e5f5);">
                                <h6 class="mb-3 text-primary"><i class="mdi mdi-account-circle me-1"></i>Data Pembeli</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-account me-1"></i>Nama Pembeli</label>
                                            <input type="text" name="nama_pembeli" class="form-control"
                                                value="{{ old('nama_pembeli') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-card-account-details me-1"></i>No KK</label>
                                            <input type="text" name="no_kk" class="form-control"
                                                value="{{ old('no_kk') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-phone me-1"></i>No Telepon</label>
                                            <input type="text" name="no_telp" class="form-control"
                                                value="{{ old('no_telp') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-calendar me-1"></i>Tanggal Transaksi</label>
                                            <input type="date" name="tanggal_transaksi" class="form-control"
                                                value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><i class="mdi mdi-note-text me-1"></i>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>

                            <!-- Produk Gas (Repeater) -->
                            <div id="penjualan-container">
                                <div class="penjualan-item border p-3 mb-3 rounded shadow-sm" data-item="0"
                                    style="background: linear-gradient(135deg, #ffffff, #f1f8e9);">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #1
                                        </h6>
                                        <button type="button" class="btn btn-danger btn-sm remove-penjualan"
                                            style="display: none;">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-gas-cylinder me-1"></i>Produk Gas</label>
                                                <select name="items[0][produk_id]" class="select2 form-control"
                                                    data-placeholder="Pilih Produk Gas" required>
                                                    <option value=""></option>
                                                    @foreach ($stokGas as $stok)
                                                        @if ($stok->jumlah_penuh > 0)
                                                            <option value="{{ $stok->id }}"
                                                                data-harga="{{ $stok->harga_jual }}"
                                                                data-stok="{{ $stok->jumlah_penuh }}">
                                                                {{ $stok->tipeGas->nama }} (Stok:
                                                                {{ $stok->jumlah_penuh }}) - Rp
                                                                {{ number_format($stok->harga_jual, 0, ',', '.') }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                                                <input type="number" name="items[0][jumlah]"
                                                    class="form-control jumlah-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-currency-usd me-1"></i>Harga Satuan</label>
                                                <input type="text" class="form-control harga-display" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-cash me-1"></i>Total Harga</label>
                                                <input type="text" class="form-control total-harga-display" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" id="add-penjualan" class="btn btn-primary btn-sm shadow">
                                    <i class="mdi mdi-plus"></i> Tambah Produk
                                </button>
                            </div>

                            <button type="submit" class="btn btn-success shadow">
                                <i class="mdi mdi-cash-multiple"></i> Simpan Penjualan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($penjualans as $kode => $items)
        <div class="modal fade" id="detailModal{{ $kode }}" tabindex="-1"
            aria-labelledby="modalLabel{{ $kode }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalLabel{{ $kode }}">Detail Transaksi: {{ $kode }}
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->stokGas->tipeGas->nama }}</td>
                                        <td>{{ $item->jumlah }} Tabung</td>
                                        <td>Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#penjualanTable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Belum ada data penjualan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari total _MAX_ data)",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Data tidak ditemukan",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "order": [
                    [0, "asc"]
                ]
            });

            let index = 1;

            // Initialize Select2 for existing elements
            $('.select2').select2({
                placeholder: 'Pilih Produk Gas'
            });

            // Add new item
            $('#add-penjualan').click(function() {
                // Create new HTML string manually
                let newItemHtml = `
            <div class="penjualan-item border p-3 mb-3 rounded shadow-sm" data-item="${index}" style="background: linear-gradient(135deg, #ffffff, #f1f8e9);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #${index + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-penjualan">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="mdi mdi-gas-cylinder me-1"></i>Produk Gas</label>
                            <select name="items[${index}][produk_id]" class="select2 form-control" data-placeholder="Pilih Produk Gas" required>
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
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><i class="mdi mdi-counter me-1"></i>Jumlah</label>
                            <input type="number" name="items[${index}][jumlah]" class="form-control jumlah-input" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><i class="mdi mdi-currency-usd me-1"></i>Harga Satuan</label>
                            <input type="text" class="form-control harga-display" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><i class="mdi mdi-cash me-1"></i>Total Harga</label>
                            <input type="text" class="form-control total-harga-display" readonly>
                        </div>
                    </div>
                </div>
            </div>
        `;

                // Append to container
                $('#penjualan-container').append(newItemHtml);

                // Initialize Select2 for the new select element only
                $('#penjualan-container .penjualan-item:last .select2').select2({
                    placeholder: 'Pilih Produk Gas'
                });

                index++;
            });

            // Remove item
            $(document).on('click', '.remove-penjualan', function() {
                $(this).closest('.penjualan-item').remove();
            });

            // Handle product selection change
            $(document).on('change', '.select2', function() {
                let harga = $(this).find(':selected').data('harga') || 0;
                let container = $(this).closest('.penjualan-item');

                container.find('.harga-display').val(formatRupiah(harga));
                container.find('.jumlah-input').val('');
                container.find('.total-harga-display').val('');
            });

            // Handle quantity input change
            $(document).on('input', '.jumlah-input', function() {
                let container = $(this).closest('.penjualan-item');
                let harga = parseInt(container.find('.select2 :selected').data('harga')) || 0;
                let jumlah = parseInt($(this).val()) || 0;
                let total = harga * jumlah;

                container.find('.total-harga-display').val(formatRupiah(total));
            });

            // Format currency function
            function formatRupiah(angka) {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        });
    </script>
@endpush
