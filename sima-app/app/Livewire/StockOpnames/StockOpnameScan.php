<?php

namespace App\Livewire\StockOpnames;

use Livewire\Component;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;

class StockOpnameScan extends Component
{
    public StockOpname $stockOpname;
    
    public $manualCode = '';
    public $lastScannedAsset = null;
    public $scanCondition = 'baik';
    public $scanNotes = '';
    public $showConditionModal = false;
    
    protected $listeners = ['assetScanned' => 'processScannedCode'];
    
    public function mount(StockOpname $stockOpname)
    {
        $this->stockOpname = $stockOpname;
        
        // Update status to in_progress if planned
        if ($this->stockOpname->status === 'planned') {
            $this->stockOpname->update([
                'status' => 'in_progress',
                'start_date' => $this->stockOpname->start_date ?? now()->toDateString(),
            ]);
        }

        $this->updateProgressTotals();
    }

    protected function updateProgressTotals(): void
    {
        $scannedCount = $this->stockOpname->details()->count();

        $expectedQuery = Asset::whereNotIn('status', ['disposal']);
        if ($this->stockOpname->location_id) {
            $expectedQuery->where('location_id', $this->stockOpname->location_id);
        }
        $totalExpected = $expectedQuery->count();

        $this->stockOpname->update([
            'total_expected' => $totalExpected,
            'total_assets' => $totalExpected,
            'scanned_assets' => $scannedCount,
        ]);
    }
    
    public function processScannedCode($code)
    {
        // Handle both array (from JS dispatch) and string (from emit)
        if (is_array($code) && isset($code['code'])) {
            $code = $code['code'];
        }
        
        $this->manualCode = $code;
        $this->processAsset();
    }
    
    public function processAsset()
    {
        $code = trim($this->manualCode);
        
        if (empty($code)) {
            session()->flash('error', 'Kode aset tidak boleh kosong.');
            return;
        }
        
        // Find asset by code
        $asset = Asset::where('code', $code)->first();
        
        if (!$asset) {
            session()->flash('error', 'Aset dengan kode "' . $code . '" tidak ditemukan.');
            return;
        }
        
        // Check if already scanned in this opname
        $existing = StockOpnameDetail::where('stock_opname_id', $this->stockOpname->id)
            ->where('asset_id', $asset->id)
            ->first();
            
        if ($existing) {
            session()->flash('warning', 'Aset sudah di-scan sebelumnya: ' . $asset->name);
            $this->lastScannedAsset = $asset;
            $this->manualCode = '';
            return;
        }
        
        // Create stock opname detail
        StockOpnameDetail::create([
            'stock_opname_id' => $this->stockOpname->id,
            'asset_id' => $asset->id,
            'expected_location_id' => $asset->location_id,
            'actual_location_id' => $this->stockOpname->location_id ?? $asset->location_id,
            'expected_condition' => $asset->condition,
            'actual_condition' => $this->scanCondition,
            'status' => 'found',
            'notes' => $this->scanNotes,
            'scanned_by' => Auth::id(),
            'scanned_at' => now(),
        ]);

        $this->updateProgressTotals();
        
        // Update asset condition if different
        if ($asset->condition !== $this->scanCondition) {
            $asset->update(['condition' => $this->scanCondition]);
        }
        
        $this->lastScannedAsset = $asset;
        session()->flash('message', 'Aset berhasil di-scan: ' . $asset->name);
        
        // Reset form
        $this->manualCode = '';
        $this->scanNotes = '';
        $this->scanCondition = 'baik';
    }

    public function saveWithCondition($condition)
    {
        $this->scanCondition = $condition;
        $this->showConditionModal = false;
        $this->processAsset();
    }
    
    public function completeOpname()
    {
        // Count scanned vs expected
        $scannedCount = $this->stockOpname->details()->count();
        
        // Get expected assets (filter by location if set)
        $expectedQuery = Asset::whereNotIn('status', ['disposal']);
        if ($this->stockOpname->location_id) {
            $expectedQuery->where('location_id', $this->stockOpname->location_id);
        }
        $expectedAssets = $expectedQuery->get();
        
        $foundCount = $scannedCount;
        $missingCount = 0;
        
        // Mark missing assets
        foreach ($expectedAssets as $asset) {
            $exists = $this->stockOpname->details()->where('asset_id', $asset->id)->exists();
            if (!$exists) {
                StockOpnameDetail::create([
                    'stock_opname_id' => $this->stockOpname->id,
                    'asset_id' => $asset->id,
                    'expected_location_id' => $asset->location_id,
                    'actual_location_id' => null,
                    'expected_condition' => $asset->condition,
                    'actual_condition' => null,
                    'status' => 'missing',
                    'scanned_by' => Auth::id(),
                    'scanned_at' => now(),
                ]);
                $missingCount++;
            }
        }
        
        // Update stock opname
        $this->stockOpname->update([
            'status' => 'completed',
            'completed_at' => now(),
            'end_date' => now()->toDateString(),
            'found_count' => $foundCount,
            'missing_count' => $missingCount,
            'total_expected' => $expectedAssets->count(),
            'total_assets' => $expectedAssets->count(),
            'scanned_assets' => $scannedCount,
            'found_assets' => $foundCount,
            'not_found_assets' => $missingCount,
        ]);
        
        session()->flash('message', 'Stock opname selesai! Ditemukan: ' . $foundCount . ', Hilang: ' . $missingCount);
        
        return $this->redirect(route('stock-opnames.index'), navigate: true);
    }
    
    public function render()
    {
        $scannedAssets = $this->stockOpname->details()
            ->with('asset')
            ->orderBy('scanned_at', 'desc')
            ->take(20)
            ->get();
        
        $scannedCount = $this->stockOpname->details()->count();
        
        // Expected count (total assets)
        $expectedQuery = Asset::whereNotIn('status', ['disposal']);
        if ($this->stockOpname->location_id) {
            $expectedQuery->where('location_id', $this->stockOpname->location_id);
        }
        $totalAssets = $expectedQuery->count();
            
        return view('livewire.stock-opnames.stock-opname-scan', [
            'scannedAssets' => $scannedAssets,
            'scannedCount' => $scannedCount,
            'totalAssets' => $totalAssets,
        ])->layout('layouts.app');
    }
}
