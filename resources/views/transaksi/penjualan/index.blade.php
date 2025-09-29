@extends('layouts.master')

@section('title', 'Penjualan Gas')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Transaksi Penjualan Gas </h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
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
                
                {{-- Stok Penuh per Tipe --}}
                @foreach ($stokPenuhPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-warning text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Stok {{ $item->tipeGas->nama }}</h5>
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
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-cart-plus me-2"></i>Form Penjualan Gas</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transaksi.penjualan.store') }}" method="POST" id="form-penjualan">
                                @csrf
                                <div class="border p-3 mb-3 rounded shadow-sm" style="background-color: #f8f9fa;">
                                    <h6 class="mb-3 text-primary"><i class="mdi mdi-account-circle me-1"></i>Data Pembeli</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nama_pembeli">Nama Pembeli</label>
                                                <input id="nama_pembeli" type="text" name="nama_pembeli" class="form-control" value="{{ old('nama_pembeli') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="no_kk">No KK (Opsional)</label>
                                                <input id="no_kk" type="text" name="no_kk" class="form-control" value="{{ old('no_kk') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="no_telp">No Telepon (Opsional)</label>
                                                <input id="no_telp" type="text" name="no_telp" class="form-control" value="{{ old('no_telp') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="keterangan">Keterangan (Opsional)</label>
                                        <textarea id="keterangan" name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                                    </div>
                                </div>

                                <div id="penjualan-container">
                                    <div class="penjualan-item border p-3 mb-3 rounded shadow-sm" style="background-color: #f1f8e9;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #1</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tipe Gas</label>
                                                    <select name="items[0][tipe_gas_id]" class="form-control select2 tipe-gas-select" required>
                                                        <option value="" selected disabled>Pilih Tipe Gas...</option>
                                                        @foreach ($tipeGasTersedia as $tipe)
                                                            <option value="{{ $tipe['id'] }}"
                                                                    data-harga-jual="{{ $tipe['harga_jual'] }}"
                                                                    data-stok-total="{{ $tipe['total_stok'] }}">
                                                                {{ $tipe['nama'] }} (Total Stok: {{ $tipe['total_stok'] }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Jumlah</label>
                                                    <input type="number" name="items[0][jumlah]" class="form-control jumlah-input" required min="1" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Harga Satuan</label>
                                                    <input type="text" class="form-control harga-display" readonly placeholder="Rp 0">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Total Harga</label>
                                                    <input type="text" class="form-control total-harga-display" readonly placeholder="Rp 0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" id="add-penjualan" class="btn btn-primary btn-sm shadow">
                                        <i class="mdi mdi-plus"></i> Tambah Produk Lain
                                    </button>
                                    <button type="submit" class="btn btn-success shadow">
                                        <i class="mdi mdi-content-save"></i> Simpan Transaksi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, #17a2b8, #00c6ff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-history me-2"></i>Riwayat Transaksi Penjualan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="penjualanTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Transaksi</th>
                                            <th>Nama Pembeli</th>
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
                                                <td>{{ $items->sum('jumlah') }} Tabung</td>
                                                <td>Rp {{ number_format($items->sum('total_harga'), 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($items->first()->tanggal_transaksi)->format('d M Y, H:i') }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal-{{ Str::slug($kode) }}">
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
        </div>
    </div>

    @foreach ($penjualans as $kode => $items)
        <div class="modal fade" id="detailModal-{{ Str::slug($kode) }}" tabindex="-1" aria-labelledby="modalLabel-{{ Str::slug($kode) }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalLabel-{{ Str::slug($kode) }}">Detail Transaksi: {{ $kode }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Pembeli:</strong> {{ $items->first()->nama_pembeli }} | <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($items->first()->tanggal_transaksi)->format('d M Y, H:i') }}</p>
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
        // Inisialisasi DataTable
        $('#penjualanTable').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" },
            "order": [[0, "desc"]]
        });

        let index = 1;

        // Inisialisasi Select2
        function initSelect2(element) {
            $(element).select2({
                placeholder: 'Pilih Tipe Gas...'
            });
        }
        initSelect2('.select2');

        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            if (isNaN(angka) || angka === null) return 'Rp 0';
            return 'Rp ' + parseFloat(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi untuk menghitung total
        function calculateTotal(container) {
            const selectedOption = container.find('.tipe-gas-select :selected');
            const harga = parseFloat(selectedOption.data('harga-jual')) || 0;
            let jumlah = parseInt(container.find('.jumlah-input').val()) || 0;
            
            const maxStok = parseInt(selectedOption.data('stok-total')) || 0;
            if(jumlah > maxStok) {
                alert('Jumlah melebihi total stok yang tersedia (' + maxStok + '). Dibatasi menjadi ' + maxStok);
                jumlah = maxStok;
                container.find('.jumlah-input').val(maxStok);
            }

            const total = harga * jumlah;
            container.find('.harga-display').val(formatRupiah(harga));
            container.find('.total-harga-display').val(formatRupiah(total));
        }

        // Event listener untuk perubahan dropdown tipe gas
        $(document).on('change', '.tipe-gas-select', function() {
            const container = $(this).closest('.penjualan-item');
            container.find('.jumlah-input').val('1'); // Set default jumlah ke 1
            calculateTotal(container);
        });

        // Event listener untuk input jumlah
        $(document).on('input', '.jumlah-input', function() {
            const container = $(this).closest('.penjualan-item');
            calculateTotal(container);
        });

        // Event listener untuk tombol "Tambah Produk"
        $('#add-penjualan').click(function() {
            const newItemHtml = `
            <div class="penjualan-item border p-3 mb-3 rounded shadow-sm" style="background-color: #f1f8e9;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #${index + 1}</h6>
                    <button type="button" class="btn btn-danger btn-sm remove-penjualan">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe Gas</label>
                            <select name="items[${index}][tipe_gas_id]" class="form-control select2 tipe-gas-select" required>
                                <option value="" selected disabled>Pilih Tipe Gas...</option>
                                @foreach ($tipeGasTersedia as $tipe)
                                    <option value="{{ $tipe['id'] }}"
                                            data-harga-jual="{{ $tipe['harga_jual'] }}"
                                            data-stok-total="{{ $tipe['total_stok'] }}">
                                        {{ $tipe['nama'] }} (Total Stok: {{ $tipe['total_stok'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="items[${index}][jumlah]" class="form-control jumlah-input" required min="1" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Harga Satuan</label>
                            <input type="text" class="form-control harga-display" readonly placeholder="Rp 0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="text" class="form-control total-harga-display" readonly placeholder="Rp 0">
                        </div>
                    </div>
                </div>
            </div>`;
            $('#penjualan-container').append(newItemHtml);
            initSelect2(`#penjualan-container .penjualan-item:last .select2`);
            index++;
        });

        // Event listener untuk tombol "Hapus"
        $(document).on('click', '.remove-penjualan', function() {
            if ($('.penjualan-item').length > 1) {
                $(this).closest('.penjualan-item').remove();
            } else {
                alert('Minimal harus ada satu produk.');
            }
        });
    });
</script>
@endpush