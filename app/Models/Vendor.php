<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name', 'code', 'email', 'phone', 'address', 'contact_person', 'notes'
    ];
    
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
