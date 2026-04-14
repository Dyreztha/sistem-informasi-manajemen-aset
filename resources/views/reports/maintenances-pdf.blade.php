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
        .priority-low { color: #6b7280; }
        .priority-medium { color: #d97706; }
        .priority-high { color: #ea580c; }
        .priority-critical { color: #dc2626; }
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
                <th>Tiket</th>
                <th>Aset</th>
                <th>Tipe</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Biaya</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenances as $index => $maintenance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $maintenance->ticket_number }}</td>
                <td>{{ $maintenance->asset->code }} - {{ $maintenance->asset->name }}</td>
                <td>{{ ucfirst($maintenance->type) }}</td>
                <td class="priority-{{ $maintenance->priority }}">{{ ucfirst($maintenance->priority) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}</td>
                <td>Rp {{ number_format($maintenance->actual_cost ?? $maintenance->estimated_cost ?? 0, 0, ',', '.') }}</td>
                <td>{{ $maintenance->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 15px;">
        <strong>Total Tiket:</strong> {{ $maintenances->count() }}<br>
        <strong>Total Biaya:</strong> Rp {{ number_format($totalCost, 0, ',', '.') }}
    </div>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
