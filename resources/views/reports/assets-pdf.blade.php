<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        .status-tersedia { color: #059669; }
        .status-digunakan { color: #2563eb; }
        .status-maintenance { color: #d97706; }
        .status-disposal { color: #dc2626; }
        .condition-baik { color: #059669; }
        .condition-rusak { color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal: {{ $date }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Harga Beli</th>
                <th>Nilai Sekarang</th>
                <th>Status</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->code }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->category?->name ?? '-' }}</td>
                <td>{{ $asset->location?->name ?? '-' }}</td>
                <td>Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($asset->current_value, 0, ',', '.') }}</td>
                <td class="status-{{ $asset->status }}">{{ ucfirst($asset->status) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $asset->condition)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 15px;">
        <strong>Total Aset:</strong> {{ $assets->count() }}<br>
        <strong>Total Nilai Beli:</strong> Rp {{ number_format($assets->sum('purchase_price'), 0, ',', '.') }}<br>
        <strong>Total Nilai Sekarang:</strong> Rp {{ number_format($assets->sum('current_value'), 0, ',', '.') }}
    </div>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
