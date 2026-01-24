<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Maintenance;
use App\Models\StockOpname;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;

class ReportController extends Controller
{
    public function assetsPdf(Request $request)
    {
        $assets = Asset::with(['category', 'location', 'vendor', 'currentUser'])
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->orderBy('code')
            ->get();
        
        $pdf = Pdf::loadView('reports.assets-pdf', [
            'assets' => $assets,
            'title' => 'Laporan Daftar Aset',
            'date' => now()->format('d F Y'),
        ]);
        
        return $pdf->download('laporan-aset-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function assetsExcel(Request $request)
    {
        return Excel::download(new AssetsExport($request), 'laporan-aset-' . now()->format('Y-m-d') . '.xlsx');
    }
    
    public function movementsPdf(Request $request)
    {
        $movements = AssetMovement::with(['asset', 'fromUser', 'toUser', 'fromLocation', 'toLocation'])
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date, fn($q) => $q->whereDate('movement_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('movement_date', '<=', $request->end_date))
            ->orderBy('movement_date', 'desc')
            ->get();
        
        $pdf = Pdf::loadView('reports.movements-pdf', [
            'movements' => $movements,
            'title' => 'Laporan Sirkulasi Aset',
            'date' => now()->format('d F Y'),
        ]);
        
        return $pdf->download('laporan-sirkulasi-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function maintenancesPdf(Request $request)
    {
        $maintenances = Maintenance::with(['asset', 'requestedBy', 'assignedTo'])
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pdf = Pdf::loadView('reports.maintenances-pdf', [
            'maintenances' => $maintenances,
            'title' => 'Laporan Pemeliharaan',
            'date' => now()->format('d F Y'),
            'totalCost' => $maintenances->sum('actual_cost'),
        ]);
        
        return $pdf->download('laporan-pemeliharaan-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function stockOpnamePdf(StockOpname $stockOpname)
    {
        $stockOpname->load(['location', 'conductedBy', 'details.asset']);
        
        $pdf = Pdf::loadView('reports.stock-opname-pdf', [
            'stockOpname' => $stockOpname,
            'title' => 'Laporan Stock Opname',
            'date' => now()->format('d F Y'),
        ]);
        
        return $pdf->download('stock-opname-' . $stockOpname->opname_number . '.pdf');
    }
    
    public function assetQrPdf(Asset $asset)
    {
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($asset->code);
        
        $pdf = Pdf::loadView('reports.asset-qr-pdf', [
            'asset' => $asset,
            'qrCode' => base64_encode($qrCode),
        ]);
        
        return $pdf->download('qr-' . $asset->code . '.pdf');
    }
}
