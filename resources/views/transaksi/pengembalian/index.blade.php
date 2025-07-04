@extends('layouts.master')

@section('title', 'Pengembalian Gas')

@section('content')

    <div class="page-wrapper">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Transaksi Pengembalian Gas</h4>
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

                {{-- Total Pengembalian Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengembalian Hari Ini</h5>
                                    <h3>{{ $totalPengembalianHariIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-backup-restore display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Pengembalian Bulan Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Pengembalian Bulan Ini</h5>
                                    <h3>{{ $totalPengembalianBlnIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-calendar-check display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Rusak Hari Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-danger text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Tabung Rusak Hari Ini</h5>
                                    <h3>{{ $totalRusakHariIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-alert-circle display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Rusak Bulan Ini --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Tabung Rusak Bulan Ini</h5>
                                    <h3>{{ $totalRusakBlnIni }}</h3>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-alert-octagon display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok Pengembalian per Tipe --}}
                @foreach ($stokPengembalianPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-secondary text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Stok Pengembalian: {{ $item->tipeGas->nama }}</h5>
                                        <h3>{{ $item->total_pengembalian }} Tabung</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Stok Rusak per Tipe --}}
                @foreach ($stokRusakPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-dark text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Stok Rusak: {{ $item->tipeGas->nama }}</h5>
                                        <h3>{{ $item->total_rusak }} Tabung</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-delete display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Pengembalian Bulan Ini per Tipe --}}
                @foreach ($pengembalianBlnIniPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-success text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>Kembali Bulan Ini: {{ $item->nama }}</h5>
                                        <h3>{{ $item->total_kembali }} Tabung</h3>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-trending-down display-4 opacity-75"></i>
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
                        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Data Transaksi Pengembalian</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pengembalianTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Pembeli</th>
                                            <th>No KK</th>
                                            <th>No Telp</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Jumlah Rusak</th>
                                            <th>Kondisi</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($pengembalians as $pengembalian)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $pengembalian->kode }}</td>
                                                <td>{{ $pengembalian->nama_pembeli }}</td>
                                                <td>{{ $pengembalian->no_kk }}</td>
                                                <td>{{ $pengembalian->no_telp }}</td>
                                                <td>{{ $pengembalian->stokGas->tipeGas->nama }}</td>
                                                <td>{{ $pengembalian->jumlah }} Tabung</td>
                                                <td>{{ $pengembalian->jumlah_rusak ?? 0 }} Tabung</td>
                                                <td>
                                                    @if($pengembalian->kondisi_rusak)
                                                        <span class="badge bg-danger">Ada Rusak</span>
                                                    @else
                                                        <span class="badge bg-success">Semua Baik</span>
                                                    @endif
                                                </td>
                                                <td>{{ date('d M Y', strtotime($pengembalian->tanggal_pengembalian)) }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $pengembalian->id }}">
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
                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8, #20c997); color: white;">
                        <h5 class="mb-0"><i class="mdi mdi-backup-restore me-2"></i>Form Pengembalian Gas dari Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transaksi.pengembalian.store') }}" method="POST" id="form-pengembalian">
                            @csrf

                            <!-- Data Pembeli (Di luar repeater) -->
                            <div class="border p-3 mb-3 rounded shadow-sm" style="background: linear-gradient(135deg, #e3f2fd, #f3e5f5);">
                                <h6 class="mb-3 text-primary"><i class="mdi mdi-account-circle me-1"></i>Data Pembeli</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-account me-1"></i>Nama Pembeli</label>
                                            <input type="text" name="nama_pembeli" class="form-control" value="{{ old('nama_pembeli') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-card-account-details me-1"></i>No KK</label>
                                            <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-phone me-1"></i>No Telepon</label>
                                            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="mdi mdi-calendar me-1"></i>Tanggal Pengembalian</label>
                                            <input type="date" name="tanggal_pengembalian" class="form-control" value="{{ old('tanggal_pengembalian', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><i class="mdi mdi-note-text me-1"></i>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>

                            <!-- Produk Gas (Repeater) -->
                            <div id="pengembalian-container">
                                <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm" data-item="0" style="background: linear-gradient(135deg, #ffffff, #e8f5e8);">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #1</h6>
                                        <button type="button" class="btn btn-danger btn-sm remove-pengembalian" style="display: none;">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-gas-cylinder me-1"></i>Produk Gas</label>
                                                <select name="items[0][produk_id]" class="select2 form-control" data-placeholder="Pilih Produk Gas" required>
                                                    <option value=""></option>
                                                    @foreach ($stokGas as $stok)
                                                        <option value="{{ $stok->id }}">
                                                            {{ $stok->tipeGas->nama }} (Vendor: {{ $stok->vendor->nama_vendor }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-counter me-1"></i>Jumlah Total</label>
                                                <input type="number" name="items[0][jumlah]" class="form-control jumlah-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-alert-circle me-1"></i>Ada Rusak?</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input kondisi-rusak-check" type="checkbox" name="items[0][kondisi_rusak]" value="1">
                                                    <label class="form-check-label">Ya</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><i class="mdi mdi-alert-octagon me-1"></i>Jumlah Rusak</label>
                                                <input type="number" name="items[0][jumlah_rusak]" class="form-control jumlah-rusak-input" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="button" id="add-pengembalian" class="btn btn-primary btn-sm shadow">
                                    <i class="mdi mdi-plus"></i> Tambah Produk
                                </button>
                            </div>

                            <button type="submit" class="btn btn-success shadow">
                                <i class="mdi mdi-backup-restore"></i> Simpan Pengembalian
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @foreach ($pengembalians as $pengembalian)
        <div class="modal fade" id="detailModal{{ $pengembalian->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $pengembalian->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalLabel{{ $pengembalian->id }}">Detail Pengembalian: {{ $pengembalian->kode }}</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama Pembeli:</strong> {{ $pengembalian->nama_pembeli }}
                            </div>
                            <div class="col-md-6">
                                <strong>No KK:</strong> {{ $pengembalian->no_kk ?? '-' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>No Telepon:</strong> {{ $pengembalian->no_telp ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal:</strong> {{ date('d M Y H:i', strtotime($pengembalian->tanggal_pengembalian)) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Produk:</strong> {{ $pengembalian->stokGas->tipeGas->nama }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Jumlah Total:</strong> {{ $pengembalian->jumlah }} Tabung
                            </div>
                            <div class="col-md-6">
                                <strong>Jumlah Rusak:</strong> {{ $pengembalian->jumlah_rusak ?? 0 }} Tabung
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kondisi:</strong>
                                @if($pengembalian->kondisi_rusak)
                                    <span class="badge bg-danger">Ada Rusak</span>
                                @else
                                    <span class="badge bg-success">Semua Baik</span>
                                @endif
                            </div>
                        </div>
                        @if($pengembalian->keterangan)
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Keterangan:</strong> {{ $pengembalian->keterangan }}
                            </div>
                        </div>
                        @endif
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
            $('#pengembalianTable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Belum ada data pengembalian",
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
                "order": [[0, "asc"]]
            });

            let index = 1;

            // Initialize Select2 for existing elements
            $('.select2').select2({
                placeholder: 'Pilih Produk Gas'
            });

            // Add new item
            $('#add-pengembalian').click(function() {
                let newItemHtml = `
                <div class="pengembalian-item border p-3 mb-3 rounded shadow-sm" data-item="${index}" style="background: linear-gradient(135deg, #ffffff, #e8f5e8);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 text-success"><i class="mdi mdi-gas-cylinder me-1"></i>Produk #${index + 1}</h6>
                        <button type="button" class="btn btn-danger btn-sm remove-pengembalian">
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
                                        <option value="{{ $stok->id }}">
                                            {{ $stok->tipeGas->nama }} (Vendor: {{ $stok->vendor->nama_vendor }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="mdi mdi-counter me-1"></i>Jumlah Total</label>
                                <input type="number" name="items[${index}][jumlah]" class="form-control jumlah-input" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="mdi mdi-alert-circle me-1"></i>Ada Rusak?</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input kondisi-rusak-check" type="checkbox" name="items[${index}][kondisi_rusak]" value="1">
                                    <label class="form-check-label">Ya</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="mdi mdi-alert-octagon me-1"></i>Jumlah Rusak</label>
                                <input type="number" name="items[${index}][jumlah_rusak]" class="form-control jumlah-rusak-input" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                $('#pengembalian-container').append(newItemHtml);

                // Initialize Select2 for the new select element only
                $('#pengembalian-container .pengembalian-item:last .select2').select2({
                    placeholder: 'Pilih Produk Gas'
                });

                index++;
            });

            // Remove item
            $(document).on('click', '.remove-pengembalian', function() {
                $(this).closest('.pengembalian-item').remove();
            });

            // Handle kondisi rusak checkbox change
            $(document).on('change', '.kondisi-rusak-check', function() {
                let container = $(this).closest('.pengembalian-item');
                let jumlahRusakInput = container.find('.jumlah-rusak-input');
                
                if ($(this).is(':checked')) {
                    jumlahRusakInput.prop('disabled', false);
                } else {
                    jumlahRusakInput.prop('disabled', true).val('');
                }
            });
        });
    </script>
@endpush
