<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian - {{ Carbon\Carbon::parse($tanggal)->format('d M Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .report-date {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .summary-section {
            margin-bottom: 25px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f8f9fa;
        }
        .summary-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding: 8px;
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">SISTEM MANAJEMEN GAS LPG</div>
        <div class="report-title">LAPORAN HARIAN</div>
        <div class="report-date">{{ Carbon\Carbon::parse($tanggal)->format('d F Y') }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="section-title">RINGKASAN TRANSAKSI</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="summary-label">Total Penjualan</div>
                    <div class="summary-value">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">Tabung Terjual</div>
                    <div class="summary-value">{{ $summary['total_tabung_terjual'] }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">Tabung Kembali</div>
                    <div class="summary-value">{{ $summary['total_tabung_kembali'] }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">Tabung Rusak</div>
                    <div class="summary-value">{{ $summary['total_tabung_rusak'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Penjualan -->
    @if($penjualan->count() > 0)
    <div class="section-title">DETAIL PENJUALAN</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nama Pembeli</th>
                <th>Tipe Gas</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->nama_pembeli }}</td>
                <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_jual_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ Carbon\Carbon::parse($item->tanggal_transaksi)->format('H:i') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-center">TOTAL</td>
                <td class="text-right">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Detail Pengembalian -->
    @if($pengembalian->count() > 0)
    <div class="section-title">DETAIL PENGEMBALIAN</div>
    <table>
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
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama_pembeli }}</td>
                <td>{{ $item->stokGas->tipeGas->nama ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-center">{{ $item->jumlah_rusak }}</td>
                <td class="text-center">
                    @if($item->kondisi_rusak)
                        Ada Rusak
                    @else
                        Baik
                    @endif
                </td>
                <td class="text-center">{{ Carbon\Carbon::parse($item->tanggal_pengembalian)->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Detail Pembelian -->
    @if($pembelian->count() > 0)
    <div class="section-title">DETAIL PEMBELIAN</div>
    <table>
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
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode_pembelian }}</td>
                <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                <td>{{ $item->tipeGas->nama ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah * $item->harga_beli, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-center">TOTAL</td>
                <td class="text-right">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Penjualan per Tipe Gas -->
    @if($penjualanPerTipe->count() > 0)
    <div class="section-title">PENJUALAN PER TIPE GAS</div>
    <table>
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
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item['tipe'] }}</td>
                <td class="text-center">{{ $item['jumlah'] }} tabung</td>
                <td class="text-right">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                <td class="text-center">
                    @php
                        $persentase = $summary['total_penjualan'] > 0 ? ($item['total_harga'] / $summary['total_penjualan']) * 100 : 0;
                    @endphp
                    {{ number_format($persentase, 1) }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>Sistem Manajemen Gas LPG</p>
    </div>
</body>
</html>
