<?php

namespace Database\Seeders;

use App\Models\FloorPlans;
use App\Models\Customer;
use App\Models\Device;
use App\Models\ControlPoint;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCode;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefactDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $customer = Customer::find(331);
        $floorplanIds = $customer->floorplans()->get()->pluck('id');
        $devices = Device::whereIn('floorplan_id', $floorplanIds)->orderBy('floorplan_id')->get();
        Device::whereIn('floorplan_id', $floorplanIds)->delete();
        $aux = 0;
        $count = 1;
        $index = 1;
        foreach ($devices as $device) {
            if($aux != $device->floorplan_id){
                $index = 1;
            }
            $code = $device->code ?? (ControlPoint::find($device->type_control_point_id)->code . '-' . $count);
            $deviceCreated = Device::create([
                'id' => $count,
                'type_control_point_id' => $device->type_control_point_id,
                'floorplan_id' => $device->floorplan_id,
                'application_area_id' => $device->application_area_id,
                'product_id' => $device->product_id,
                'itemnumber' => $count,
                'nplan' => $index,
                'version' => 1,
                'latitude' => null,
                'longitude' => null,
                'map_x' => $device->map_x,
                'map_y' => $device->map_y,
                'img_tamx' => $device->img_tamx,
                'img_tamy' => $device->img_tamy,
                'color' => $device->color,
                'code' => $code,
                'created_at' => $device->created_at,
                'updated_at' => now(),
            ]);

            $deviceCreated->qr = QrCode::format('png')->size(200)->generate($code);
            $deviceCreated->save();
            $aux = $device->floorplan_id;
            $index++;
            $count++;
        }
    }
}
