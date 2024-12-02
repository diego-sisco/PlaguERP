<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIncidents extends Model
{
    use HasFactory;

    protected $table = 'order_incidents';

    protected $fillable = [
        'id',
        'order_id',
        'question_id',
        'device_id',
        'answer',
        'unids'
    ];

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
