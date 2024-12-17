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

        $devices = [];

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
                $courseSyncService = app(AlertService::class);
                $deviceCurrentStatus = $courseSyncService->getDeviceAlert($device->device_id);
                $message = '';
                $messageType = 'inactive';
                $deviceAlert = [];
                if ((!empty($deviceCurrentStatus) && $deviceCurrentStatus['result'] != 0) || (isset($device['status']) && $device['status'] == 2)) {
                    $message = $deviceCurrentStatus['message'];
                    $messageType = "danger";
                } else if (isset($device['status']) && $device['status'] == 1){
                    $deviceAlert = $deviceCurrentStatus['status'] ?? [];
                    $messageType = "normal";
                } else {
                    $deviceAlert = $deviceCurrentStatus['status'] ?? [];
                }

                $lat_lng = [];
                if (!empty($deviceAlert) && !empty($deviceAlert[0])) {
                    $lat_lng = collect($deviceAlert)->map(function ($item){
                        return [
                            'lat' => floatval($item['mlat'] ?? 0),
                            'lng' => floatval($item['mlng'] ?? 0)
                        ];
                    });

                    $deviceAlert = collect($deviceAlert)->first();
                }

                $locations[] = [
                    "position" => $this->getCoordinates($deviceAlert),
                    "title" => $device->device_id,
                    "content" => [
                        "device_id" => $device->device_id,
                        'device_name' => 'Device Name' . $device->device_id,
                        'message' => $message,
                        'url' => '',
                        'message_type' => $messageType,
                        'last_active' => $deviceAlert['gt'] ?? "",
                        'photo' => 'https://img.freepik.com/free-vector/truck_53876-34798.jpg',
                        'lat_lng' => $lat_lng,
                    ]
                ];
            }
        }

        return response()->json([
            'locations' => $locations,
            'customer_id' => 1
        ]);
    }

    public function getCoordinates($deviceAlert)
    {

        return [
            'lat' => floatval($deviceAlert['mlat'] ?? 0),
            'lng' => floatval($deviceAlert['mlng'] ?? 0)
        ];
    }
}
