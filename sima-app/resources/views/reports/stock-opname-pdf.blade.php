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
        .info-box {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .info-row {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
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
        .status-found { color: #059669; }
        .status-missing { color: #dc2626; }
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>{{ $stockOpname->opname_number }}</p>
    </div>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Tanggal Opname:</span>
            {{ $stockOpname->opname_date->format('d F Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Lokasi:</span>
            {{ $stockOpname->location?->name ?? 'Semua Lokasi' }}
        </div>
        <div class="info-row">
            <span class="info-label">Pelaksana:</span>
            {{ $stockOpname->conductedBy?->name ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            {{ ucfirst(str_replace('_', ' ', $stockOpname->status)) }}
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Lokasi Tercatat</th>
                <th>Kondisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockOpname->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->asset->code }}</td>
                <td>{{ $detail->asset->name }}</td>
                <td>{{ $detail->asset->location?->name ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $detail->actual_condition ?? '-')) }}</td>
                <td class="status-{{ $detail->status }}">{{ ucfirst($detail->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <strong>Ringkasan:</strong><br>
        Total Aset Diharapkan: {{ $stockOpname->total_expected ?? 0 }}<br>
        Aset Ditemukan: <span class="status-found">{{ $stockOpname->found_count ?? 0 }}</span><br>
        Aset Hilang: <span class="status-missing">{{ $stockOpname->missing_count ?? 0 }}</span>
    </div>
    
    <div style="margin-top: 40px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; text-align: center;">
                    <p>Pelaksana</p>
                    <br><br><br>
                    <p>( {{ $stockOpname->conductedBy?->name ?? '.....................' }} )</p>
                </td>
                <td style="width: 50%; border: none; text-align: center;">
                    <p>Mengetahui</p>
                    <br><br><br>
                    <p>( .............................. )</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
