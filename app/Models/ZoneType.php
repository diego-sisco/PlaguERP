<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneType extends Model
{
    use HasFactory;
    protected $table = 'zone_type';

    protected $fillable = [
        'name',
    ];
}