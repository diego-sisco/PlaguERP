<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseMovementProduct extends Model
{
    use HasFactory;

    protected $table = 'warehouse_movement_product';

    protected $fillable = [
        'warehouse_movement_id',
        'product_id',
        'lot_id',
        'amount',
    ];

    // Relaciones
    public function warehouseMovement()
    {
        return $this->belongsTo(WarehouseMovement::class, 'warehouse_movement_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
    public function getProductName() {
        return $this->product ? $this->product->name : 'N/A';
    }
    public function getLotNumber() {
        return $this->lot ? $this->lot->lot_number : 'N/A';
    }
    public function getExpirationDate() {
        return $this->lot ? $this->lot->expiration_date : 'N/A';
    }
    
}
