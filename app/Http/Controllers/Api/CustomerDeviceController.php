<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerDevice;
use App\Models\Customer;
use App\Models\Vehicle;


class CustomerDeviceController extends Controller
{

    public function getDevicesByCustomer($customerId){

        $useVehicles = Vehicle::where('customer_id', $customerId)->pluck('device_id')->toArray();

        $allVehicle = Customer::select('devices.id', 'devices.device_id')
            ->join('customer_devices', 'customers.id', '=', 'customer_devices.customer_id')
            ->join('devices', 'customer_devices.device_id', '=', 'devices.id')
            ->where('customers.id', $customerId)
            ->get();


        $allDeviceIds = $allVehicle->pluck('id')->toArray();
        $unusedDeviceIds = array_diff($allDeviceIds, $useVehicles);


        $unusedDevices = $allVehicle->whereIn('id', $unusedDeviceIds);

        $unusedDeviceData = $unusedDevices->map(function ($device) {
            return [
                'id' => $device['id'],
                'device_id' => $device['device_id'],
            ];
        })->values()->toArray();

        return response()->json([
            'devices' => $unusedDeviceData,
        ]);
    }

    public function getDevicesByEditCustomer($customerId,$deviceId){

        $useVehicles = Vehicle::where('customer_id', $customerId)->pluck('device_id')->toArray();

        $allVehicle = Customer::select('devices.id', 'devices.device_id')
            ->join('customer_devices', 'customers.id', '=', 'customer_devices.customer_id')
            ->join('devices', 'customer_devices.device_id', '=', 'devices.id')
            ->where('customers.id', $customerId)
            ->get();


        $allDeviceIds = $allVehicle->pluck('id')->toArray();

        $unusedDeviceIds = array_diff($allDeviceIds, $useVehicles);


         $resultDeviceIds = array_unique(array_merge($unusedDeviceIds, [$deviceId]));

         $unusedDevices = $allVehicle->whereIn('id', $resultDeviceIds);

         $unusedDeviceData = $unusedDevices->map(function ($device) {
            return [
                'id' => $device['id'],
                'device_id' => $device['device_id'],
            ];
        })->values()->toArray();

        return response()->json([
            'devices' => $unusedDeviceData,
        ]);
    }

}
