<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    use HasFactory;
    protected $table = 'order_service';
    protected $fillable = [
        'service_id',
        'order_id',
        'created_at',
        'updated_at'
    ];
}
