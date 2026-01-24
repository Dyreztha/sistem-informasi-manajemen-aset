<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpname extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'opname_number', 'title', 'location_id', 'opname_date', 'start_date', 'end_date',
        'completed_at', 'status', 'total_expected', 'total_assets', 'scanned_assets', 
        'found_count', 'found_assets', 'missing_count', 'not_found_assets', 
        'notes', 'created_by', 'conducted_by'
    ];
    
    protected $casts = [
        'opname_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'completed_at' => 'datetime',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($opname) {
            if (!$opname->opname_number) {
                $year = date('Y');
                $lastOpname = static::whereYear('created_at', $year)->latest('id')->first();
                $number = $lastOpname ? intval(substr($lastOpname->opname_number, -3)) + 1 : 1;
                $opname->opname_number = 'OPN-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function conductedBy()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
    
    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }
}
