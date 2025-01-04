<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Models\Customer;
use App\Models\User;
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

    public function getMapDevices(Request $request, $userId = 0)
    {
        $status = in_array($request->status, [0, 1, 2]) ? $request->status : 'all';

        if (!empty($userId)) {
            $user = User::find($userId);

            if ($user && $user->customer) {
                $devices = $user->customer->devices;
                if ($status !== 'all') {
                    $devices = $devices->filter(fn($device) => $device->status == $status);
                }
            }
        } else {
            if (!empty($request->device_id)) {
                $devices = Device::where('id', $request->device_id);

                if (isset($status) && $status !== 'all') {
                    $devices->where('status', $status);
                }

                $devices = $devices->get();
            } elseif (!empty($request->customer_id)) {
                $customer = Customer::with('devices')->find($request->customer_id);
                $devices = $customer ? $customer->devices : collect();

                if (isset($status) && $status !== 'all') {
                    $devices = $devices->filter(fn($device) => $device->status == $status);
                }
            } else {
                $devices = Device::query();

                if (isset($status) && $status !== 'all') {
                    $devices->where('status', $status);
                }

                $devices = $devices->get();
            }
        }





        $locations = $devices->map(function ($device) {

            $messages = [
                2 => ['message' => "Device not found", 'type' => "danger"],
                1 => ['message' => "Device is online", 'type' => "normal"],
                0 => ['message' => "Device is offline", 'type' => "inactive"],
            ];

            $deviceStatus = $device->status ?? null;
            $message = $messages[$deviceStatus]['message'] ?? '';
            $messageType = $messages[$deviceStatus]['type'] ?? 'alert';

            return [
                "position" => [
                    'lat' => floatval($device->latitude ?? 0),
                    'lng' => floatval($device->longitude ?? 0),
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
                        'lng' => floatval($device->longitude ?? 0),
                    ],
                ]
            ];
        });

        return response()->json([
            'locations' => $locations->toArray(),
        ]);

    }
}
