<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetMovement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'asset_id', 'type', 'from_location_id', 'to_location_id',
        'from_user_id', 'to_user_id', 'movement_date', 'reason', 'notes',
        'bast_number', 'bast_file', 'approved_by', 'approved_at', 'status', 'created_by'
    ];
    
    protected $casts = [
        'movement_date' => 'date',
        'approved_at' => 'datetime',
    ];
    
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    
    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }
    
    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }
    
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
