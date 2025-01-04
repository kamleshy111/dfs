<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['user_id','first_name','last_name','email','phone','address','is_verified', 'secondary_email', 'secondary_phone'];

    // Customer model
    public function devices()
    {
        return $this->belongsToMany(Device::class, 'customer_devices', 'customer_id', 'device_id');
    }

}
