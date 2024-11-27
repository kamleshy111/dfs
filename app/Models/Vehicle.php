<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    
    protected $fillable = ['customer_id','company_name','model','fuel_type', 'Chassis_number', 'color', 'driver_name', 'license_number',
                            'license_expiry_date', 'driver_contact', 'driver_address', 'year' ];

    public function devices(){

        return $this->belongsToMany(Device::class, 'vehicle_devices', 'vehicle_id', 'device_id');
    }

}
