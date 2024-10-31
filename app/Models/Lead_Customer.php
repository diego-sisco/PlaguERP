<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead_Customer extends Model
{
    use HasFactory;

    protected $table = 'lead_customer';

    protected $fillable = [
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
}
