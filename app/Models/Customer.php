<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['user_id','first_name','last_name','email','password','phone','profile_picture',
    'date_of_birth','booking_id','device_id','address','quantity','file','is_verified' ];

    public function devices(){

        return $this->belongsToMany(Device::class, 'customer_devices', 'customer_id', 'device_id');
    }
}