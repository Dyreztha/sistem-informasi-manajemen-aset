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
    
    // Auto-generate code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($asset) {
            if (!$asset->code) {
                $year = date('Y');
                $lastAsset = static::whereYear('created_at', $year)->latest('id')->first();
                $number = $lastAsset ? intval(substr($lastAsset->code, -3)) + 1 : 1;
                $asset->code = 'AST-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
            
            // Generate QR Code
            if (!$asset->qr_code) {
                $asset->qr_code = $asset->code;
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
        
        $yearsOwned = now()->diffInYears($this->purchase_date);
        
        if ($this->category->depreciation_method === 'straight_line') {
            $annualDepreciation = ($this->purchase_price * $this->category->depreciation_rate) / 100;
            $totalDepreciation = $annualDepreciation * $yearsOwned;
        } else { // double_declining
            $rate = ($this->category->depreciation_rate / 100) * 2;
            $currentValue = $this->purchase_price;
            
            for ($i = 0; $i < $yearsOwned; $i++) {
                $currentValue -= ($currentValue * $rate);
            }
            
            $totalDepreciation = $this->purchase_price - $currentValue;
        }
        
        return min($totalDepreciation, $this->purchase_price);
    }
    
    public function updateCurrentValue()
    {
        $this->depreciation_value = $this->calculateDepreciation();
        $this->current_value = max(0, $this->purchase_price - $this->depreciation_value);
        $this->save();
    }
    
    public function getQrCodeImageAttribute()
    {
        return QrCode::size(200)->generate($this->qr_code);
    }
}
