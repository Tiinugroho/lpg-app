<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan - {{ $startDate->format('F Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
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
            margin-bottom: 20px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 16.66%;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f8f9fa;
        }
        .summary-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
            font-size: 10px;
        }
        .summary-value {
            font-size: 12px;
            font-weight: bold;
            color: #007bff;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 15px 0 8px 0;
            padding: 6px;
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
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
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .column:last-child {
            padding-right: 0;
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">SISTEM MANAJEMEN GAS LPG</div>
        <div class="report-title">LAPORAN BULANAN</div>
        <div class="report-date">{{ $startDate->format('F Y') }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="section-title">RINGKASAN BULANAN</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="summary-label">Total Penjualan</div>
                    <div class="summary-value">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">Total Pembelian</div>
                    <div class="summary-value">Rp {{ number_format($summary['total_pembelian'], 0, ',', '.') }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">Keuntungan Kotor</div>
                    <div class="summary-value">Rp {{ number_format($summary['keuntungan_kotor'], 0, ',', '.') }}</div>
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

    <!-- Two Column Layout -->
    <div class="two-column">
        <div class="column">
            <!-- Penjualan per Minggu -->
            <div class="section-title">PENJUALAN PER MINGGU</div>
            <table>
                <thead>
                    <tr>
                        <th>Minggu</th>
                        <th>Periode</th>
                        <th>Penjualan</th>
                        <th>Tabung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeklyData as $week)
                    <tr>
                        <td>{{ $week['minggu'] }}</td>
                        <td>{{ $week['periode'] }}</td>
                        <td class="text-right">Rp {{ number_format($week['penjualan'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ $week['tabung_terjual'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="column">
            <!-- Top 5 Tipe Gas -->
            <div class="section-title">TOP 5 TIPE GAS TERLARIS</div>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Tipe Gas</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topTipeGas as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['tipe'] }}</td>
                        <td class="text-center">{{ $item['jumlah'] }}</td>
                        <td class="text-right">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Analisis Performa -->
    <div class="section-title">ANALISIS PERFORMA</div>
    <table>
        <thead>
            <tr>
                <th>Metrik</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Margin Keuntungan</td>
                <td class="text-center">{{ number_format(($summary['keuntungan_kotor'] / $summary['total_penjualan']) * 100, 1) }}%</td>
                <td>Persentase keuntungan dari total penjualan</td>
            </tr>
            <tr>
                <td>Rata-rata Penjualan Harian</td>
                <td class="text-right">Rp {{ number_format($summary['total_penjualan'] / $endDate->day, 0, ',', '.') }}</td>
                <td>Total penjualan dibagi jumlah hari</td>
            </tr>
            <tr>
                <td>Rata-rata Tabung per Hari</td>
                <td class="text-center">{{ number_format($summary['total_tabung_terjual'] / $endDate->day, 1) }}</td>
                <td>Total tabung terjual dibagi jumlah hari</td>
            </tr>
            <tr>
                <td>Harga Rata-rata per Tabung</td>
                <td class="text-right">
                    @if($summary['total_tabung_terjual'] > 0)
                        Rp {{ number_format($summary['total_penjualan'] / $summary['total_tabung_terjual'], 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td>Total penjualan dibagi total tabung</td>
            </tr>
            <tr>
                <td>Tingkat Kerusakan</td>
                <td class="text-center">
                    @if($summary['total_tabung_kembali'] > 0)
                        {{ number_format(($summary['total_tabung_rusak'] / $summary['total_tabung_kembali']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
                <td>Persentase tabung rusak dari total pengembalian</td>
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
