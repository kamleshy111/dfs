<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerDevice;
use App\Models\Customer;


class CustomerDeviceController extends Controller
{
    public function getDevices()
    {


        $userId = auth('sanctum')->user()->id;

       $customerId = Customer::where('user_id',$userId)->pluck('id')->first();

        $userDevices = CustomerDevice::select('customer_devices.customer_id', 'devices.id','devices.device_id as deviceId')
                    ->leftJoin('devices', 'customer_devices.device_id', '=', 'devices.id')
                    ->where('customer_devices.customer_id', $customerId)->get();
       
        return response()->json([
            'userDevices' => $userDevices,
        ]);

    }
}
