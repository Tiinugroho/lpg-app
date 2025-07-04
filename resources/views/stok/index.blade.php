@extends('layouts.master')

@section('title', 'Stok Gas')

@section('content')

    <div class="page-wrapper">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h4 class="page-title">Master Stok Gas</h4>
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

                {{-- Total Stok Penuh --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-success text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Total Stok Penuh</h5>
                                    <h3>{{ $totalStokPenuh }}</h3>
                                    <small>Tabung Siap Jual</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Stok Pengembalian --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-info text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Stok Pengembalian</h5>
                                    <h3>{{ $totalStokPengembalian }}</h3>
                                    <small>Tabung Kosong</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-backup-restore display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Stok Rusak --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-danger text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Stok Rusak</h5>
                                    <h3>{{ $totalStokRusak }}</h3>
                                    <small>Tabung Rusak</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-alert-circle display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Nilai Stok --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body bg-gradient-warning text-white rounded">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5>Nilai Stok</h5>
                                    <h3>Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</h3>
                                    <small>Total Investasi</small>
                                </div>
                                <div class="ms-3">
                                    <i class="mdi mdi-currency-usd display-4 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stok per Tipe Gas --}}
                @foreach ($stokPerTipe as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-success text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>{{ $item->nama }}</h5>
                                        <h3>{{ $item->total_penuh + $item->total_pengembalian }} Tabung</h3>
                                        <small>Penuh: {{ $item->total_penuh }} | Kosong: {{ $item->total_pengembalian }}</small>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-gas-cylinder display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Stok per Vendor --}}
                @foreach ($stokPerVendor as $item)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-body bg-gradient-secondary text-white rounded">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5>{{ $item->nama_vendor }}</h5>
                                        <h3>{{ $item->total_penuh + $item->total_pengembalian }} Tabung</h3>
                                        <small>Nilai: Rp {{ number_format($item->nilai_stok, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="ms-3">
                                        <i class="mdi mdi-store display-4 opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Alert Stok Minimum -->
            @if($stokMinimum->count() > 0)
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <h5><i class="mdi mdi-alert-triangle me-2"></i>Peringatan Stok Minimum!</h5>
                            <p>Beberapa produk memiliki stok di bawah minimum (10 tabung):</p>
                            <ul class="mb-0">
                                @foreach($stokMinimum as $stok)
                                    <li>
                                        <strong>{{ $stok->tipeGas->nama }}</strong> ({{ $stok->vendor->nama_vendor }}) - 
                                        Sisa: {{ $stok->jumlah_penuh + $stok->jumlah_pengembalian }} tabung
                                    </li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                            <h5 class="mb-0"><i class="mdi mdi-database me-2"></i>Data Stok Gas</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="stokTable" class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Tipe Gas</th>
                                            <th>Vendor</th>
                                            <th>Stok Penuh</th>
                                            <th>Stok Kosong</th>
                                            <th>Stok Rusak</th>
                                            <th>Total Stok</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Terakhir Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($stokGas as $stok)
                                            <tr class="{{ ($stok->jumlah_penuh + $stok->jumlah_pengembalian) < 10 ? 'table-warning' : '' }}">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $stok->kode }}</td>
                                                <td>{{ $stok->tipeGas->nama ?? '-' }}</td>
                                                <td>{{ $stok->vendor->nama_vendor ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-success">{{ $stok->jumlah_penuh ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $stok->jumlah_pengembalian ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">{{ $stok->jumlah_rusak ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ ($stok->jumlah_penuh ?? 0) + ($stok->jumlah_pengembalian ?? 0) }}</strong>
                                                </td>
                                                <td>Rp {{ number_format($stok->harga_beli ?? 0, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($stok->harga_jual ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ $stok->tanggal_masuk ? date('d M Y', strtotime($stok->tanggal_masuk)) : '-' }}</td>
                                                <td>
                                                    @if($stok->updated_at && $stok->updated_at != $stok->created_at)
                                                        <span class="badge bg-warning">{{ date('d M Y H:i', strtotime($stok->updated_at)) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#adjustmentModal{{ $stok->id }}">
                                                        <i class="mdi mdi-pencil"></i> Adjustment
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $stok->id }}">
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

    <!-- Modal Adjustment untuk setiap stok -->
    @foreach ($stokGas as $stok)
        <div class="modal fade" id="adjustmentModal{{ $stok->id }}" tabindex="-1" aria-labelledby="adjustmentModalLabel{{ $stok->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="adjustmentModalLabel{{ $stok->id }}">Adjustment Stok: {{ $stok->kode }}</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form action="{{ route('stok.adjustment') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="stok_id" value="{{ $stok->id }}">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Produk:</strong> {{ $stok->tipeGas->nama ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Vendor:</strong> {{ $stok->vendor->nama_vendor ?? '-' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Stok Penuh:</strong> {{ $stok->jumlah_penuh ?? 0 }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Stok Kosong:</strong> {{ $stok->jumlah_pengembalian ?? 0 }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Stok Rusak:</strong> {{ $stok->jumlah_rusak ?? 0 }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Harga Beli Saat Ini:</strong> Rp {{ number_format($stok->harga_beli ?? 0, 0, ',', '.') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Harga Jual Saat Ini:</strong> Rp {{ number_format($stok->harga_jual ?? 0, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Tipe Adjustment</label>
                                <select name="tipe_adjustment" class="form-control adjustment-type" required>
                                    <option value="">Pilih Tipe Adjustment</option>
                                    <option value="penuh">Stok Penuh</option>
                                    <option value="pengembalian">Stok Kosong/Pengembalian</option>
                                    <option value="rusak">Stok Rusak</option>
                                    <option value="harga">Edit Harga</option>
                                </select>
                            </div>

                            <!-- Form untuk adjustment stok -->
                            <div class="stok-adjustment-form">
                                <div class="form-group mb-3">
                                    <label>Jumlah Adjustment</label>
                                    <input type="number" name="jumlah_adjustment" class="form-control">
                                    <small class="text-muted">Gunakan angka positif untuk menambah, negatif untuk mengurangi</small>
                                </div>
                            </div>

                            <!-- Form untuk adjustment harga -->
                            <div class="harga-adjustment-form" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Harga Beli Baru</label>
                                            <input type="number" name="harga_beli" class="form-control harga-beli-input" value="{{ $stok->harga_beli }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Margin (%)</label>
                                            <input type="number" name="margin_persen" class="form-control margin-input" step="0.1" min="0" max="100">
                                            <small class="text-muted">Contoh: 15 untuk margin 15%</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Preview Harga Jual:</strong> 
                                            <span class="harga-jual-preview">Rp {{ number_format($stok->harga_jual ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" required placeholder="Alasan adjustment..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Simpan Adjustment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Detail -->
        <div class="modal fade" id="detailModal{{ $stok->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $stok->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content text-dark">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="detailModalLabel{{ $stok->id }}">Detail Stok: {{ $stok->kode }}</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kode Stok:</strong> {{ $stok->kode }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal Masuk:</strong> {{ $stok->tanggal_masuk ? date('d M Y', strtotime($stok->tanggal_masuk)) : '-' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tipe Gas:</strong> {{ $stok->tipeGas->nama ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Vendor:</strong> {{ $stok->vendor->nama_vendor ?? '-' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Stok Penuh:</strong> 
                                <span class="badge bg-success fs-7">{{ $stok->jumlah_penuh ?? 0 }} Tabung</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Stok Kosong:</strong> 
                                <span class="badge bg-info fs-7">{{ $stok->jumlah_pengembalian ?? 0 }} Tabung</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Stok Rusak:</strong> 
                                <span class="badge bg-danger fs-7">{{ $stok->jumlah_rusak ?? 0 }} Tabung</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Harga Beli:</strong> Rp {{ number_format($stok->harga_beli ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Harga Jual:</strong> Rp {{ number_format($stok->harga_jual ?? 0, 0, ',', '.') }}
                                @if($stok->harga_beli > 0 && $stok->harga_jual > 0)
                                    @php
                                        $margin = (($stok->harga_jual - $stok->harga_beli) / $stok->harga_beli) * 100;
                                    @endphp
                                    <small class="text-muted">(Margin: {{ number_format($margin, 1) }}%)</small>
                                @endif
                            </div>
                        </div>
                        @if($stok->updated_at && $stok->updated_at != $stok->created_at)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Terakhir Diupdate:</strong> 
                                <span class="badge bg-warning fs-7">{{ date('d M Y H:i', strtotime($stok->updated_at)) }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Total Stok Tersedia:</strong> 
                                <span class="badge bg-primary fs-7">{{ ($stok->jumlah_penuh ?? 0) + ($stok->jumlah_pengembalian ?? 0) }} Tabung</span>
                            </div>
                        </div>
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
            $('#stokTable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Belum ada data stok",
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

            // Handle tipe adjustment change
            $(document).on('change', '.adjustment-type', function() {
                let modal = $(this).closest('.modal');
                let stokForm = modal.find('.stok-adjustment-form');
                let hargaForm = modal.find('.harga-adjustment-form');
                
                if ($(this).val() === 'harga') {
                    stokForm.hide();
                    hargaForm.show();
                    modal.find('input[name="jumlah_adjustment"]').removeAttr('required');
                    modal.find('input[name="harga_beli"]').attr('required', true);
                    modal.find('input[name="margin_persen"]').attr('required', true);
                } else {
                    stokForm.show();
                    hargaForm.hide();
                    modal.find('input[name="jumlah_adjustment"]').attr('required', true);
                    modal.find('input[name="harga_beli"]').removeAttr('required');
                    modal.find('input[name="margin_persen"]').removeAttr('required');
                }
            });

            // Calculate harga jual preview
            $(document).on('input', '.harga-beli-input, .margin-input', function() {
                let modal = $(this).closest('.modal');
                let hargaBeli = parseFloat(modal.find('.harga-beli-input').val()) || 0;
                let margin = parseFloat(modal.find('.margin-input').val()) || 0;
                
                if (hargaBeli > 0 && margin > 0) {
                    let marginAmount = (hargaBeli * margin) / 100;
                    let hargaJual = hargaBeli + marginAmount;
                    modal.find('.harga-jual-preview').text('Rp ' + formatRupiah(hargaJual));
                }
            });

            // Format currency function
            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        });
    </script>
@endpush
