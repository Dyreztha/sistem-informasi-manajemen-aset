<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AssetDocument extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'asset_id', 'title', 'type', 'file_path', 'file_name',
        'mime_type', 'file_size', 'description', 'uploaded_by'
    ];
    
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    public function getFileUrl()
    {
        return Storage::url($this->file_path);
    }
}
