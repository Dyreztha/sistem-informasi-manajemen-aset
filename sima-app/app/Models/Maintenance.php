<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'asset_id', 'ticket_number', 'type', 'title', 'scheduled_date', 'completed_date',
        'description', 'action_taken', 'cost', 'estimated_cost', 'actual_cost', 
        'status', 'priority', 'vendor_name', 'technician_name', 'technician_notes',
        'reported_by', 'assigned_to', 'notes', 'start_date'
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'start_date' => 'date',
        'cost' => 'decimal:2',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
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
    
    // Aliases for compatibility
    public function requestedBy()
    {
        return $this->reporter();
    }
    
    public function assignedTo()
    {
        return $this->assignee();
    }
    
    /**
     * Check if maintenance is active (pending or in_progress)
     */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }
    
    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
    
    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'scheduled' => 'Terjadwal',
            'repair' => 'Perbaikan',
            'inspection' => 'Inspeksi',
            default => $this->type,
        };
    }
    
    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
            default => $this->priority,
        };
    }
}
