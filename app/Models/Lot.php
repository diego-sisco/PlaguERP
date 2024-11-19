<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $table = 'lot';

    // La propiedad se llama 'fillable', no 'fillablee'
    protected $fillable = [
        'id',
        'product_id',
        'warehouse_id',
        'registration_number',
        'expiration_date',
        'amount',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
