<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Models\Customer;
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

        $devices = [];

        if( ! empty( $request->device_id ) ) {
            $devices = Device::where('id', $request->device_id)->get();
        } else {
            $devices = Device::get();
        }

        if( ! empty( $request->customer_id ) ) {
            $customer = Customer::where("id",$request->customer_id);
            $devices = $customer->devices;
        }

        $locations = [];
        foreach ($devices as $index => $device) {
            if( $status == 'all' || $status == $device->status ) {
                $locations[] = [
                    "position" => $this->getRandomCoordinatesInIndia(),
                    "title" => 'LADY LIBERTY',
                    "content" => [
                        "device_id" => mt_rand(1000000, 9999999),
                        'device_name' => 'Device Name' . $index,
                        'message' => 'This is test device' . $index,
                        'url' => '',
                        'message_type' => 'normal',
                        'last_active' => date("Y-m-d h:i:s a", time()),
                        'photo' => 'https://img.freepik.com/free-vector/truck_53876-34798.jpg',
                        'lat_lng' => [
                            ["lat" => 40.62201182900588, "lng" =>  -74.19500694401374],
                            ["lat" => 40.62201182900588, "lng" =>  -74.19500694401374],
                            ["lat" => 40.62201182900588, "lng" =>  -74.19500694401374]
                        ]
                    ]
                ];
            }
        }

        return response()->json([
            'locations' => $locations,
            'customer_id' => 1
        ]);
    }

    public function getRandomCoordinatesInIndia()
    {
        // Define India's approximate latitude and longitude bounds
        $minLat = 20.0;  // Approximate southern bound of central India
        $maxLat = 25.0;  // Approximate northern bound of central India
        $minLng = 75.0;  // Approximate western bound of central India
        $maxLng = 85.0;  // Approximate eastern bound of central India

        // Generate random latitude and longitude within the bounds
        $latitude = $minLat + mt_rand() / mt_getrandmax() * ($maxLat - $minLat);
        $longitude = $minLng + mt_rand() / mt_getrandmax() * ($maxLng - $minLng);

        return [
            'lat' => $latitude,
            'lng' => $longitude
        ];
    }
}
