<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicePest extends Model
{
    use HasFactory;

    protected $table = 'device_pest';
    
    protected $fillable = [
        'order_id',
        'device_id',
        'pest_id',
        'total',
        'created_at',
        'updated_at'
    ];

    public function device() {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function pest() {
        return $this->belongsTo(PestCatalog::class, 'pest_id');
    }
}
