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
                $devices = Device::where('device_id', $request->device_id)
                        ->leftJoin('vehicles','devices.id', '=', 'vehicles.device_id');

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



        dd($devices);

        $locations = $devices->map(function ($device) {

            $deviceStatus = match ($device->status ?? null) {
                2 => 'expired',
                1 => 'online',
                default => 'offline',
            };

            $alertStatus = match ($device->alert_status ?? null) {
                3 => 'danger',
                2 => 'critical',
                1 => 'warning',
                default => 'normal',
            };

            // Messages with a combined check for device status and alert status
            $messages = [
                'expired' => [
                    'message' => "Device not found",
                    'type' => "danger",
                    'background_color' => "#FF6347", // Yellow-red for expired
                    'icon_color' => "#FFFFFF", // White for contrast
                ],
                'normal' => [
                    'message' => "Device is normal",
                    'type' => "normal",
                    'background_color' => "#007BFF", // Blue for online
                    'icon_color' => "#FFFFFF", // White for contrast
                ],
                'online' => [
                    'message' => "Device is online",
                    'type' => "normal",
                    'background_color' => "#007BFF", // Blue for online
                    'icon_color' => "#FFFFFF", // White for contrast
                ],
                'offline' => [
                    'message' => "Device is offline",
                    'type' => "inactive",
                    'background_color' => "#808080", // Gray for offline
                    'icon_color' => "#FFFFFF", // Black for contrast
                ],
                'danger' => [
                    'message' => "Device in danger state",
                    'type' => "critical",
                    'background_color' => "#FF0000", // Red for danger
                    'icon_color' => "#FFFFFF", // White for contrast
                ],
                'critical' => [
                    'message' => "Device in critical state",
                    'type' => "critical",
                    'background_color' => "#FF4500", // Bright orange for critical
                    'icon_color' => "#FFFFFF", // White for contrast
                ],
                'warning' => [
                    'message' => "Device warning",
                    'type' => "warning",
                    'background_color' => "#FFD700", // Yellow for warning
                    'icon_color' => "#000000", // Black for contrast
                ],
            ];


            if ($deviceStatus === 'expired') {
                $messageKey = 'expired'; // Always prioritize "expired" status
            } elseif ($deviceStatus === 'online') {
                $messageKey = $alertStatus; // Show alert status if device is online
            } else {
                $messageKey = $deviceStatus; // Default to device status
            }

            $message = $messages[$messageKey]['message'] ?? 'Unknown status';
            $messageType = $messages[$messageKey]['type'] ?? 'alert';
            $backgroundColor = $messages[$messageKey]['background_color'] ?? '#000000'; // Default to black if not set
            $iconColor = $messages[$messageKey]['icon_color'] ?? '#FFFFFF'; // Default to white if not set

            // Prepare response data
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
                    'message_type' => $messageType,
                    'background_color' => $backgroundColor,
                    'icon_color' => $iconColor,
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
