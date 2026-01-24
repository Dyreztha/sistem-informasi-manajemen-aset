<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\AssetMovement;
use App\Models\StockOpname;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalAssets = 0;
    public $totalValue = 0;
    public $currentValue = 0;
    public $assetsCondition = [];
    public $maintenanceAlerts = [];
    public $recentMovements = [];
    public $assetsByCategory = [];
    public $assetsByLocation = [];
    
    public function mount()
    {
        $this->loadStatistics();
    }
    
    public function loadStatistics()
    {
        // Total Aset
        $this->totalAssets = Asset::count();
        
        // Total Nilai
        $this->totalValue = Asset::sum('purchase_price');
        $this->currentValue = Asset::sum('current_value');
        
        // Kondisi Aset
        $this->assetsCondition = Asset::select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->pluck('total', 'condition')
            ->toArray();
        
        // Maintenance Alerts (H-7 atau sudah jatuh tempo)
        $this->maintenanceAlerts = Maintenance::with('asset')
            ->where('status', '!=', 'completed')
            ->where(function($query) {
                $query->whereDate('scheduled_date', '<=', now()->addDays(7))
                      ->orWhereNull('scheduled_date');
            })
            ->orderBy('scheduled_date')
            ->limit(5)
            ->get();
        
        // Recent Movements
        $this->recentMovements = AssetMovement::with(['asset', 'toLocation', 'toUser'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Assets by Category
        $this->assetsByCategory = Category::withCount('assets')
            ->having('assets_count', '>', 0)
            ->get()
            ->map(function($cat) {
                return [
                    'name' => $cat->name,
                    'count' => $cat->assets_count
                ];
            })
            ->toArray();
        
        // Assets by Location
        $this->assetsByLocation = Location::withCount('assets')
            ->having('assets_count', '>', 0)
            ->limit(5)
            ->get()
            ->map(function($loc) {
                return [
                    'name' => $loc->name,
                    'count' => $loc->assets_count
                ];
            })
            ->toArray();
    }
    
    public function getConditionLabel($condition)
    {
        return match($condition) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang',
            default => $condition
        };
    }
    
    public function getConditionColor($condition)
    {
        return match($condition) {
            'baik' => 'bg-green-500',
            'rusak_ringan' => 'bg-yellow-500',
            'rusak_berat' => 'bg-red-500',
            'hilang' => 'bg-gray-500',
            default => 'bg-blue-500'
        };
    }
    
    public function render()
    {
        return view('livewire.dashboard');
    }
}
