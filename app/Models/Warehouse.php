<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouse';

    protected $fillable = [
        'id',
        'branch_id',
        'name',
        'receive_material',
        'active',
        'address',
        'zip_code',
        'city',
        'country',
        'state',
        'phone',
        'observations'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function movements() {
        return $this->hasMany(WarehouseMovement::class,'warehouse_id', 'id');
    }
}
