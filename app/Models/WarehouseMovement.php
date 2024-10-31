<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseMovement extends Model
{
    use HasFactory;

    protected $table = 'warehouse_movements';

    protected $fillable = [
        'id',
        'warehouse_id',
        'destination_warehouse_id',
        'movement_id',
        'lot_id',
        'product_id',
        'user_id',
        'date',
        'time',
        'observations',
        'amount'
    ];
    public function movementType()
    {
        return $this->belongsTo(MovementType::class, 'movement_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class,'product_id');
    }
}
