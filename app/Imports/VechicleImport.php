<?php

namespace App\Imports;

use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\CustomerDevice;
use App\Models\VehicleDevice;
use App\Models\Device;

class VechicleImport implements ToModel, WithHeadingRow
{
    protected $customerId;

    public function __construct($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

       
        $VehicleExists = Vehicle::where('Chassis_number', $row['chassis_number'])
            ->where('license_number', $row['license_number'])
            ->exists();

        if(empty($VehicleExists)){

            $devicesId = $row["device_id"];

            $devicesIdArray = explode(',', $devicesId);

            $allDevices = Device::pluck('id')->toArray();

            $matchedDevices = array_intersect($devicesIdArray, $allDevices); //device table in add device_id

            $notAssignedDevices = Device::select('devices.id')
                            ->join('customer_devices', 'customer_devices.device_id', '!=', 'devices.id')
                            ->whereIn('devices.id', $matchedDevices) 
                            ->pluck('devices.id')
                            ->toArray();

            if($notAssignedDevices){
                foreach ($notAssignedDevices as $deviceId) {
                    CustomerDevice::create([
                        'customer_id' => $this->customerId,
                        'device_id' => $deviceId,
                    ]);
                }
            }

            return new Vehicle([
                'customer_id' => $this->customerId,
                'company_name' => $row['company_name'],
                'model'      => $row['model'],
                'fuel_type'      => $row['fuel_type'],
                'Chassis_number' => $row['chassis_number'],
                'color'      => $row['color'],
                'driver_name'      => $row['driver_name'],
                'license_number'      => $row['license_number'],
                'license_expiry_date' =>  $row['license_expiry_date'],
                'driver_contact'      => $row['driver_contact'],
                'driver_address'      => $row['driver_address'],
                'year' =>  $row['year'],

            ]);
        }

        return null;
    }
}
