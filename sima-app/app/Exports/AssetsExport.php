<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class AssetsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function collection()
    {
        return Asset::with(['category', 'location', 'vendor', 'currentUser'])
            ->when($this->request->category_id, fn($q) => $q->where('category_id', $this->request->category_id))
            ->when($this->request->status, fn($q) => $q->where('status', $this->request->status))
            ->when($this->request->condition, fn($q) => $q->where('condition', $this->request->condition))
            ->orderBy('code')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Kategori',
            'Lokasi',
            'Vendor',
            'Merk',
            'Model',
            'Serial Number',
            'Harga Beli',
            'Nilai Sekarang',
            'Tanggal Beli',
            'Status',
            'Kondisi',
            'Pengguna',
        ];
    }
    
    public function map($asset): array
    {
        return [
            $asset->code,
            $asset->name,
            $asset->category?->name ?? '-',
            $asset->location?->name ?? '-',
            $asset->vendor?->name ?? '-',
            $asset->brand ?? '-',
            $asset->model ?? '-',
            $asset->serial_number ?? '-',
            $asset->purchase_price,
            $asset->current_value,
            $asset->purchase_date?->format('d/m/Y') ?? '-',
            ucfirst($asset->status),
            ucfirst(str_replace('_', ' ', $asset->condition)),
            $asset->currentUser?->name ?? '-',
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
