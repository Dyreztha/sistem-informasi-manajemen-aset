<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name', 'code', 'building', 'floor', 'room', 'address', 'parent_id'
    ];
    
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }
    
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    
    public function getFullNameAttribute()
    {
        $parts = [$this->name];
        if ($this->parent) {
            $parts[] = $this->parent->full_name;
        }
        return implode(' - ', array_reverse($parts));
    }
}
