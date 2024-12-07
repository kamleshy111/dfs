<?php

namespace App\Http\Controllers\Api;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function getMapDevices()
    {
        $locations = [
            [
                "position" => ["lat" => 26.58438493088465, "lng" => 75.5223685841688],
                "title" => 'LADY LIBERTY',
                "content" => [
                    "device_id" => 1,
                    'device_name' => 'Device Name',
                    'message' => 'This is test device',
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
            ],
            [
                "position" => ["lat" => 23.884151095659767, "lng" =>  78.80170558707565],
                "title" => 'MARKER 2',
                "content" => [
                    "device_id" => 2,
                    'device_name' => 'Device Name',
                    'message' => 'This is test device',
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
            ],
            [
                "position" => ["lat" => 20.34289749781598, "lng" => 77.52729166478011],
                "title" => 'MARKER 3',
                "content" => [
                    "device_id" => 3,
                    'device_name' => 'Device Name',
                    'message' => 'This is test device',
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
            ]
        ];

        return response()->json([
            'locations' => $locations,
            'customer_id' => 1
        ]);
    }
}
