<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class FloorPlans extends Model
{
    use HasFactory;

    protected $table = 'floorplans';

    protected $fillable = [
        'id',
        'customer_id',
        'service_id',
        'filename',
        'path'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function versions()
    {
        return $this->hasMany(FloorplanVersion::class, 'floorplan_id', 'id')->orderBy('created_at', 'desc');
    }

    public function version()
    {
        //$version = $this->versions()->whereDate('created_at', '<=', Carbon::parse($date)->toDateString())->first();
        //return $version ? $version->version : null;
        $floorplan_version = $this->versions()->first();
        return $floorplan_version ? $floorplan_version->version : null;
    }


    /*public function version($date, $time) {
        $datetime = Carbon::parse($date . ' ' . $time);
        $version = $this->versions()->where('created_at', '<=', $datetime)->first();
        return $version ? $version->version : null;
    }*/

    public function devices($version)
    {
        return $this->hasMany(Device::class, 'floorplan_id', 'id')->where('version', $version)->orderBy('type_control_point_id', 'asc');
    }

}
