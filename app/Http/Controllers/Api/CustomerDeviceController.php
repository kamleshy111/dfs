<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerDevice;


class CustomerDeviceController extends Controller
{
    public function getDevices()
    {


        $user = auth('sanctum')->user();
   
       $userId = $user ? $user->id : null;

        $userDevices = CustomerDevice::select('customer_devices.customer_id', 'devices.id','devices.device_id as deviceId')
                    ->leftJoin('devices', 'customer_devices.device_id', '=', 'devices.id')
                    ->where('customer_devices.customer_id', $userId)->get();
       
        return response()->json([
            'userDevices' => $userDevices,
        ]);

    }
}
