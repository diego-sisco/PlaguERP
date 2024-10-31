<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInput extends Model
{
    use HasFactory;

    protected $table = 'product_input';

    protected $fillable = [
        'id',
        'product_id',
        'application_method_id',
        'zone_m2',
        'cant',
        'cost'
    ];

    public function product() {
        return $this->belongsTo(ProductCatalog::class ,'product_id');
    }

    public function appMethod() {
        return $this->belongsTo(ApplicationMethod::class ,'application_method_id');
    }
}
