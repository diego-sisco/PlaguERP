<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseLog extends Model
{
    use HasFactory;

    protected $table = 'database_log';

    protected $fillable = [
        'id',
        'user_id',
        'changetype',
        'change',
        'sql_command',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
