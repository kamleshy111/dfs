<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInstallationPhoto extends Model
{
    protected $fillable = [
        'vehicle_id',
        'photo_path',
    ];
}
