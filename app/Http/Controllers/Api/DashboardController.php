<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Models\Customer;
use App\Services\AlertService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getDashboardData()
    {

        $customers = Customer::all();

        return response()->json([
            'customers' => $customers,
        ]);
    }

    public function getMapDevices(Request $request)
    {
        if ($request->status != '' && in_array($request->status, [0, 1, 2])) {
            $status = $request->status;
        } else {
            $status = 'all';
        }

        if( ! empty( $request->device_id ) ) {
            $devices = Device::where('id', $request->device_id)->get();
        } else {
            $devices = Device::get();
        }

        if( ! empty( $request->customer_id ) ) {
            $customer = Customer::with('devices')->find($request->customer_id);
            $devices = $customer->devices;
        }

        $locations = [];

        foreach ($devices as $index => $device) {
            if( $status == 'all' || $status == $device->status ) {
                $message = '';
                $messageType = 'alert';

                if (isset($device['status']) && $device['status'] == 2) {
                    $message = "Device not found";
                    $messageType = "danger";
                } else if (isset($device['status']) && $device['status'] == 1){
                    $messageType = "normal";
                    $message = "Device is online";
                } else if (isset($device['status']) && $device['status'] == 0){
                    $messageType = "inactive";
                    $message = "Device is offline";
                }

                $locations[] = [
                    "position" => [
                        'lat' => floatval($device->latitude ?? 0),
                        'lng' => floatval($device->longitude ?? 0)
                    ],
                    "title" => $device->device_id,
                    "content" => [
                        "device_id" => $device->device_id,
                        'device_name' => 'Device Name ' . $device->device_id,
                        'message' => $message,
                        'url' => '',
                        'message_type' => $messageType,
                        'last_active' => $device->last_active ?? "",
                        'photo' => 'https://img.freepik.com/free-vector/truck_53876-34798.jpg',
                        'lat_lng' => [
                            'lat' => floatval($device->latitude ?? 0),
                            'lng' => floatval($device->longitude ?? 0)
                        ],
                    ]
                ];
            }
        }

        return response()->json([
            'locations' => $locations
        ]);
    }
}
