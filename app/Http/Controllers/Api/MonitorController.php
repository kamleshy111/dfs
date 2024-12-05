<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function getNotifications()
    {
        $devicesNotifications = [
            [
                "id" => 3214,
                "coordinates" => "lat: 123456789, lng: -987654321",
                "location" => 'Dwarkapuri, Delhi',
                "status" => 'Online',
                "capture" => 'https://thumbs.dreamstime.com/z/tired-man-car-sleepy-drowsy-driver-fatigue-driving-sleeping-vehicle-exhausted-bored-drunk-person-serious-upset-331634548.jpg?ct=jpeg',
                "address" => '159, Dwarkapuri, Dehli'
            ],
            [
                "id" => 3214,
                "coordinates" => "lat: 123456789, lng: -987654321",
                "location" => 'Dwarkapuri, Delhi',
                "status" => 'Online',
                "capture" => 'https://thumbs.dreamstime.com/z/tired-man-car-sleepy-drowsy-driver-fatigue-driving-sleeping-vehicle-exhausted-bored-drunk-person-serious-upset-331634548.jpg?ct=jpeg',
                "address" => '159, Dwarkapuri, Dehli'
            ],
            [
                "id" => 3214,
                "coordinates" => "lat: 123456789, lng: -987654321",
                "location" => 'Dwarkapuri, Delhi',
                "status" => 'Online',
                "capture" => 'https://thumbs.dreamstime.com/z/tired-man-car-sleepy-drowsy-driver-fatigue-driving-sleeping-vehicle-exhausted-bored-drunk-person-serious-upset-331634548.jpg?ct=jpeg',
                "address" => '159, Dwarkapuri, Dehli'
            ],
            [
                "id" => 3214,
                "coordinates" => "lat: 123456789, lng: -987654321",
                "location" => 'Dwarkapuri, Delhi',
                "status" => 'Online',
                "capture" => 'https://thumbs.dreamstime.com/z/tired-man-car-sleepy-drowsy-driver-fatigue-driving-sleeping-vehicle-exhausted-bored-drunk-person-serious-upset-331634548.jpg?ct=jpeg',
                "address" => '159, Dwarkapuri, Dehli'
            ],
            [
                "id" => 3214,
                "coordinates" => "lat: 123456789, lng: -987654321",
                "location" => 'Dwarkapuri, Delhi',
                "status" => 'Online',
                "capture" => 'https://thumbs.dreamstime.com/z/tired-man-car-sleepy-drowsy-driver-fatigue-driving-sleeping-vehicle-exhausted-bored-drunk-person-serious-upset-331634548.jpg?ct=jpeg',
                "address" => '159, Dwarkapuri, Dehli'
            ],
            [
                "id" => 3214,
                "coordinates" => "lat: 123456789, lng: -987654321",
                "location" => 'Dwarkapuri, Delhi',
                "status" => 'Online',
                "capture" => 'https://thumbs.dreamstime.com/z/tired-man-car-sleepy-drowsy-driver-fatigue-driving-sleeping-vehicle-exhausted-bored-drunk-person-serious-upset-331634548.jpg?ct=jpeg',
                "address" => '159, Dwarkapuri, Dehli'
            ]
        ];

        return response()->json([
            'device_notifications' => $devicesNotifications,
        ]);
    }

    public function getDevices() {
        $devices = Device::all();
        return response()->json([
            'devices' => $devices
        ]);
    }
}
