<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mingguan - {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</title>
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">SISTEM MANAJEMEN GAS LPG</div>
        <div class="report-title">LAPORAN MINGGUAN</div>
        <div class="report-date">{{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="section-title">RINGKASAN MINGGUAN</div>
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
                    <div class="summary-label">Total Pembelian</div>
                    <div class="summary-value">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjualan per Hari -->
    <div class="section-title">PENJUALAN PER HARI</div>
    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Total Penjualan</th>
                <th>Tabung Terjual</th>
                <th>Rata-rata per Tabung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyData as $day)
            <tr>
                <td>{{ $day['hari'] }}</td>
                <td>{{ Carbon\Carbon::parse($day['tanggal'])->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($day['penjualan'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $day['tabung_terjual'] }}</td>
                <td class="text-right">
                    @if($day['tabung_terjual'] > 0)
                        Rp {{ number_format($day['penjualan'] / $day['tabung_terjual'], 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-center">TOTAL</td>
                <td class="text-right">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $summary['total_tabung_terjual'] }}</td>
                <td class="text-right">
                    @if($summary['total_tabung_terjual'] > 0)
                        Rp {{ number_format($summary['total_penjualan'] / $summary['total_tabung_terjual'], 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Ringkasan Transaksi -->
    <div class="section-title">RINGKASAN TRANSAKSI</div>
    <table>
        <thead>
            <tr>
                <th>Jenis Transaksi</th>
                <th>Jumlah Transaksi</th>
                <th>Total Nilai</th>
                <th>Rata-rata per Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Penjualan</td>
                <td class="text-center">{{ $penjualan->count() }}</td>
                <td class="text-right">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($penjualan->count() > 0)
                        Rp {{ number_format($summary['total_penjualan'] / $penjualan->count(), 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Pengembalian</td>
                <td class="text-center">{{ $pengembalian->count() }}</td>
                <td class="text-center">{{ $summary['total_tabung_kembali'] }} tabung</td>
                <td class="text-center">
                    @if($pengembalian->count() > 0)
                        {{ number_format($summary['total_tabung_kembali'] / $pengembalian->count(), 1) }} tabung
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Pembelian</td>
                <td class="text-center">{{ $pembelian->count() }}</td>
                <td class="text-right">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($pembelian->count() > 0)
                        Rp {{ number_format($summary['total_pembelian'] / $pembelian->count(), 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>Sistem Manajemen Gas LPG</p>
    </div>
</body>
</html>
