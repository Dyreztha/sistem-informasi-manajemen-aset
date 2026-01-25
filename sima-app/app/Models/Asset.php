<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Asset extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'code', 'name', 'category_id', 'location_id', 'vendor_id',
        'brand', 'model', 'serial_number', 'description',
        'purchase_price', 'purchase_date', 'current_value', 'depreciation_value',
        'status', 'condition', 'assigned_to', 'assigned_date',
        'qr_code', 'warranty_end_date', 'notes'
    ];
    
    protected $casts = [
        'purchase_date' => 'date',
        'assigned_date' => 'date',
        'warranty_end_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'depreciation_value' => 'decimal:2',
    ];
    
    // Status constants
    const STATUS_TERSEDIA = 'tersedia';
    const STATUS_DIGUNAKAN = 'digunakan';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_DISPOSAL = 'disposal';
    
    // Condition constants
    const CONDITION_BAIK = 'baik';
    const CONDITION_RUSAK_RINGAN = 'rusak_ringan';
    const CONDITION_RUSAK_BERAT = 'rusak_berat';
    const CONDITION_HILANG = 'hilang';
    
    // Auto-generate code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($asset) {
            if (!$asset->code) {
                $year = date('Y');
                $lastAsset = static::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $number = $lastAsset ? intval(substr($lastAsset->code, -3)) + 1 : 1;
                $asset->code = 'AST-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
            
            // Generate QR Code
            if (!$asset->qr_code) {
                $asset->qr_code = $asset->code;
            }
            
            // Set initial current_value to purchase_price
            if (!$asset->current_value) {
                $asset->current_value = $asset->purchase_price;
            }
        });
        
        static::created(function ($asset) {
            // Calculate depreciation after creation
            $asset->updateCurrentValue();
        });
        
        static::updating(function ($asset) {
            // Recalculate if purchase_price or purchase_date or category changed
            if ($asset->isDirty(['purchase_price', 'purchase_date', 'category_id'])) {
                $depreciation = $asset->calculateDepreciation();
                $asset->depreciation_value = $depreciation;
                $asset->current_value = max(0, $asset->purchase_price - $depreciation);
            }
        });
    }
    
    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function movements()
    {
        return $this->hasMany(AssetMovement::class);
    }
    
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
    
    public function documents()
    {
        return $this->hasMany(AssetDocument::class);
    }
    
    public function stockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }
    
    // Helper methods
    public function calculateDepreciation()
    {
        if (!$this->purchase_date || !$this->category) {
            return 0;
        }
        
        // Calculate years owned (fractional)
        $yearsOwned = $this->purchase_date->diffInMonths(now()) / 12;
        
        if ($yearsOwned <= 0) {
            return 0;
        }
        
        $depreciationRate = $this->category->depreciation_rate ?? 0;
        
        if ($depreciationRate <= 0) {
            return 0;
        }
        
        if ($this->category->depreciation_method === 'straight_line') {
            // Straight Line: (Purchase Price × Rate%) × Years
            $annualDepreciation = ($this->purchase_price * $depreciationRate) / 100;
            $totalDepreciation = $annualDepreciation * $yearsOwned;
        } else { 
            // Double Declining Balance
            $rate = ($depreciationRate / 100) * 2;
            $currentValue = $this->purchase_price;
            $fullYears = floor($yearsOwned);
            $partialYear = $yearsOwned - $fullYears;
            
            // Full years depreciation
            for ($i = 0; $i < $fullYears; $i++) {
                $currentValue -= ($currentValue * $rate);
                if ($currentValue < 0) {
                    $currentValue = 0;
                    break;
                }
            }
            
            // Partial year depreciation
            if ($partialYear > 0 && $currentValue > 0) {
                $currentValue -= ($currentValue * $rate * $partialYear);
            }
            
            $currentValue = max(0, $currentValue);
            $totalDepreciation = $this->purchase_price - $currentValue;
        }
        
        // Depreciation cannot exceed purchase price
        return min($totalDepreciation, $this->purchase_price);
    }
    
    public function updateCurrentValue()
    {
        $this->depreciation_value = $this->calculateDepreciation();
        $this->current_value = max(0, $this->purchase_price - $this->depreciation_value);
        $this->saveQuietly(); // Save without triggering events
    }
    
    /**
     * Check if asset is available for borrowing/movement
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_TERSEDIA 
            && $this->condition !== self::CONDITION_HILANG;
    }
    
    /**
     * Check if asset is currently being used/borrowed
     */
    public function isInUse(): bool
    {
        return $this->status === self::STATUS_DIGUNAKAN;
    }
    
    /**
     * Check if asset is under maintenance
     */
    public function isUnderMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }
    
    /**
     * Check if asset has active maintenance ticket (pending or in_progress)
     */
    public function hasActiveMaintenance(): bool
    {
        return $this->maintenances()
            ->whereIn('status', ['pending', 'in_progress'])
            ->exists();
    }
    
    /**
     * Check if asset has active movement/loan (pending or active)
     */
    public function hasActiveMovement(): bool
    {
        return $this->movements()
            ->where(function ($query) {
                $query->where('status', 'pending')
                    ->orWhere(function ($q) {
                        $q->where('status', 'approved')
                          ->where('type', 'peminjaman')
                          ->whereNull('actual_return_date');
                    });
            })
            ->exists();
    }
    
    /**
     * Get reason why asset is not available
     */
    public function getUnavailabilityReason(): ?string
    {
        if ($this->condition === self::CONDITION_HILANG) {
            return 'Aset tercatat hilang';
        }
        
        if ($this->status === self::STATUS_MAINTENANCE) {
            return 'Aset sedang dalam pemeliharaan';
        }
        
        if ($this->status === self::STATUS_DIGUNAKAN) {
            $user = $this->assignedUser ? $this->assignedUser->name : 'seseorang';
            return "Aset sedang digunakan oleh {$user}";
        }
        
        if ($this->status === self::STATUS_DISPOSAL) {
            return 'Aset sudah dihapuskan/disposal';
        }
        
        return null;
    }
    
    public function getQrCodeImageAttribute()
    {
        return QrCode::size(200)->generate($this->qr_code);
    }
}
