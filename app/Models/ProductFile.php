<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFile extends Model
{
    use HasFactory;

    protected $table = 'product_files';

    protected $fillable = [
        'rp_specification',
        'techical_specification',
        'segurity_specification',
        'register_specification',
        'sanitary_register'
    ];
}
