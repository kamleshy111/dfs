<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id','order_id','company_name','date_time', 'invoice_photos', 'status', 'latitude', 'longitude', 'last_active'];

    // Device model
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_devices', 'device_id', 'customer_id');
    }

}
