<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id','order_id','company_name','date_time', 'invoice_photos'];

}
