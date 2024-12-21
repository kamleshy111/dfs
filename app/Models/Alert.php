<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['device_id','latitude','longitude','location','status','captures','message', 'alert_type'];

}
