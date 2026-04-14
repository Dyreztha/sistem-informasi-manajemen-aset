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
        .status-pending { color: #d97706; }
        .status-active { color: #059669; }
        .status-returned { color: #2563eb; }
        .status-rejected { color: #dc2626; }
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
                <th>Tanggal</th>
                <th>Aset</th>
                <th>Tipe</th>
                <th>Dari</th>
                <th>Ke</th>
                <th>Status</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $index => $movement)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $movement->movement_date->format('d/m/Y') }}</td>
                <td>{{ $movement->asset->code }} - {{ $movement->asset->name }}</td>
                <td>{{ ucfirst($movement->type) }}</td>
                <td>
                    {{ $movement->fromUser?->name ?? '-' }}<br>
                    <small>{{ $movement->fromLocation?->name ?? '-' }}</small>
                </td>
                <td>
                    {{ $movement->toUser?->name ?? '-' }}<br>
                    <small>{{ $movement->toLocation?->name ?? '-' }}</small>
                </td>
                <td class="status-{{ $movement->status }}">{{ ucfirst($movement->status) }}</td>
                <td>{{ \Str::limit($movement->reason, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 15px;">
        <strong>Total Transaksi:</strong> {{ $movements->count() }}
    </div>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
