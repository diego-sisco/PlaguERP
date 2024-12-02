<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'lead';

    protected $fillable = [
        'id',
        'company_category_id',
        'administrative_id',
        'service_type_id',
        'branch_id',
        'company_id',
        'name',
        'address',
        'state',
        'city',
        'status',
        'zip_code',
        'phone',
        'email',
        'map_location_url',
        'reason',
        'tracking_at',
        'created_at',
        'updated_at'
    ];

    public function serviceType() {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function companyCategory()
    {
        return $this->belongsTo(CompanyCategory::class, 'company_category_id');
    }

    public function trackings()
    {
        return $this->morphMany(ServiceTracking::class, 'model');
    }
}
