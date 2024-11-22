<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    
    protected $fillable = ['company_name','model','fuel_type', 'Chassis_number', 'color'];

}
