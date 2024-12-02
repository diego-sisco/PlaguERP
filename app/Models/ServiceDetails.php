<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDetails extends Model
{
    use HasFactory;

    protected $table = 'service_details';

    protected $fillable = [
        'id',
        'service_id',
        'contract_id',
        'details'
    ];
}
