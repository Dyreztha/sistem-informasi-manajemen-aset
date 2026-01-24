<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'asset_id', 'ticket_number', 'type', 'scheduled_date', 'completed_date',
        'description', 'action_taken', 'cost', 'status', 'priority',
        'vendor_name', 'technician_name', 'reported_by', 'assigned_to', 'notes'
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'cost' => 'decimal:2',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($maintenance) {
            if (!$maintenance->ticket_number) {
                $year = date('Y');
                $lastTicket = static::whereYear('created_at', $year)->latest('id')->first();
                $number = $lastTicket ? intval(substr($lastTicket->ticket_number, -4)) + 1 : 1;
                $maintenance->ticket_number = 'MNT-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
    
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
