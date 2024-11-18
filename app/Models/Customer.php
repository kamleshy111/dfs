<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['first_name','last_name','email','password','phone','profile_picture',
    'date_of_birth','booking_id','device_id','address','file','is_verified' ];
}