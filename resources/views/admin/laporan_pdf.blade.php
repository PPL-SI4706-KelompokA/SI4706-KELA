<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Distribusi Makanan - FoodShare</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #6B630C;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 26px;
            font-weight: 800;
            color: #6B630C;
            margin: 0;
        }
        .subtitle {
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 2px 0 0 0;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 5px 0;
            color: #2E3015;
        }
        .date {
            font-size: 11px;
            color: #666;
            margin-bottom: 25px;
        }
        .summary-card {
            background-color: #F8F8EC;
            border: 1px solid #D5D6A8;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 30px;
            width: 300px;
        }
        .summary-title {
            font-size: 10px;
            font-weight: bold;
            color: #6B630C;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .summary-desc {
            font-size: 10px;
            color: #555;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #6B630C;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #EAEBCA;
            font-size: 11px;
        }
        tr:nth-child(even) td {
            background-color: #FAFBF0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #EAEBCA;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="logo">FoodShare</h1>
        <p class="subtitle">Sistem Manajemen & Distribusi Makanan Komunitas</p>
    </div>

    <h2 class="report-title">Laporan Penyaluran Distribusi</h2>
    @if(!empty($startDate) || !empty($endDate))
        <div style="font-size: 12px; color: #6B630C; font-weight: bold; margin-bottom: 8px;">
            Periode: 
            @if(!empty($startDate))
                {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
            @else
                Awal
            @endif
            s/d
            @if(!empty($endDate))
                {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            @else
                Kini
            @endif
        </div>
    @endif
    <div class="date">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>

    <div class="summary-card">
        <div class="summary-title">Total Penyaluran</div>
        <div class="summary-value">{{ number_format($totalPenyaluran, 0, ',', '.') }}</div>
        <div class="summary-desc">Porsi makanan telah sukses didistribusikan.</div>
    </div>

    <h3 style="color: #6B630C; margin-bottom: 10px; font-size: 13px;">Daftar Detail Distribusi</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Penerima</th>
                <th style="width: 20%;">Donatur</th>
                <th style="width: 30%;">Nama Makanan</th>
                <th style="width: 10%; text-align: right;">Jumlah</th>
                <th style="width: 15%; text-align: right;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribusiData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->user->nama ?? '-' }}</strong></td>
                    <td>{{ $item->donasi->user->nama ?? '-' }}</td>
                    <td>{{ $item->donasi->nama_makanan ?? '-' }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $item->jumlah_permintaan }} porsi</td>
                    <td style="text-align: right;">{{ $item->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Belum ada riwayat distribusi penyaluran makanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dihasilkan secara otomatis oleh Sistem Informasi FoodShare. &copy; {{ date('Y') }} FoodShare.
    </div>

</body>
</html>
