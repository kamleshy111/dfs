<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    
    protected $fillable = ['customer_id', 'device_id', 'vehicle_register_number', 'vehicle_name', 'vehicle_type', 'imei_number',
                             'sim_card_number', 'installation_date', 'start_date', 'duration', 'sim_operator', 'status' ];

    public function devices(){

        return $this->belongsToMany(Device::class, 'vehicle_devices', 'vehicle_id', 'device_id');
    }

}
