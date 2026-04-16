<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code - {{ $asset->code }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        .container {
            text-align: center;
            padding: 20px;
            border: 2px solid #000;
            width: 300px;
            margin: 0 auto;
        }
        .qr-code {
            margin: 15px 0;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .asset-code {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .asset-name {
            font-size: 14px;
            color: #333;
        }
        .org-name {
            font-size: 10px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="org-name">SISTEM INFORMASI MANAJEMEN ASET</div>
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
        </div>
        <div class="asset-code">{{ $asset->code }}</div>
        <div class="asset-name">{{ $asset->name }}</div>
    </div>
</body>
</html>
