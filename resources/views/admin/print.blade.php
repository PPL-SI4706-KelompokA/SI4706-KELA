<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Distribusi Makanan - {{ $periodeText }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 40px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #6b630c;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-title h1 {
            color: #6b630c;
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .logo-title p {
            margin: 5px 0 0 0;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 2px;
            color: #888;
        }
        .report-info {
            text-align: right;
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }
        .report-info p {
            margin: 2px 0;
        }
        .report-info strong {
            color: #333;
        }
        .title-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .title-section h2 {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 10px 0;
            color: #222;
            letter-spacing: -0.5px;
        }
        .title-section p {
            margin: 0;
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }
        .metrics-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
        }
        .metric-card {
            flex: 1;
            border: 1px solid #eaeaea;
            border-radius: 16px;
            padding: 20px;
            background-color: #fafaf7;
        }
        .metric-card p {
            margin: 0 0 5px 0;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .metric-card h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 50px;
            font-size: 13px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #6b630c;
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 250px;
            font-size: 13px;
        }
        .signature-box p {
            margin: 0;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: 700;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-title">
            <h1>FoodShare</h1>
            <p>Editorial Admin</p>
        </div>
        <div class="report-info">
            <p>Tanggal Cetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong></p>
            <p>Dicetak Oleh: <strong>{{ auth()->user()->nama ?? 'Admin' }}</strong></p>
        </div>
    </div>

    <div class="title-section">
        <h2>LAPORAN DISTRIBUSI MAKANAN</h2>
        <p>Periode: {{ $periodeText }}</p>
    </div>

    <div class="metrics-grid">
        <div class="metric-card">
            <p>Total Penyaluran</p>
            <h3>{{ number_format($totalPenyaluran, 0, ',', '.') }} <span style="font-size: 14px; font-weight: normal; color: #888;">Porsi</span></h3>
        </div>
        <div class="metric-card">
            <p>Total Transaksi Penyaluran</p>
            <h3>{{ $distributions->count() }} <span style="font-size: 14px; font-weight: normal; color: #888;">Distribusi</span></h3>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 120px;">ID Permintaan</th>
                <th>Nama Penerima</th>
                <th>Makanan</th>
                <th style="width: 100px; text-align: right;">Porsi</th>
                <th style="width: 150px;">Tanggal Distribusi</th>
                <th style="width: 100px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distributions as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>#{{ $item->id_permintaan }}</td>
                <td>{{ $item->user->nama ?? 'N/A' }}</td>
                <td>{{ $item->donasi->nama_makanan ?? 'N/A' }}</td>
                <td style="text-align: right; font-weight: 700;">{{ $item->jumlah_permintaan }}</td>
                <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                <td><span style="font-weight: 600; color: #4CAF50;">{{ $item->status }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #888; padding: 30px;">Tidak ada data distribusi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Jakarta, {{ now()->format('d M Y') }}</p>
            <p style="margin-top: 5px; font-weight: 600;">Mengetahui,</p>
            <p style="color: #666; font-size: 11px;">Administrator FoodShare</p>
            <div class="signature-line">
                {{ auth()->user()->nama ?? 'Admin' }}
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
