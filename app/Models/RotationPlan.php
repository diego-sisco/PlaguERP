<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationPlan extends Model
{
    use HasFactory;

    protected $table = 'rotation_plan';
    
    protected $fillable = [
        'id',
        'customer_id',
        'contract_id',
        'start_date',
        'end_date',
        'name',
        'code',
        'no_review',
        'important_text',
        'notes',
        'authorization_at',
        'created_at',
        'updated_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function products() {
        return $this->hasMany(RotationPlanProduct::class, 'rotation_plan_id', 'id');
    }
}
