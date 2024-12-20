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
        $deviceIds = $devices->pluck('device_id')->implode(',');
        $courseSyncService = app(AlertService::class);
        $deviceCurrentStatus = $courseSyncService->getDeviceAlert($deviceIds);

        $deviceAlertResult = collect([]);
        if (isset($deviceCurrentStatus['result']) && $deviceCurrentStatus['result'] === 0) {
            $deviceAlertResult = collect($deviceCurrentStatus['status']);
        }

        foreach ($devices as $index => $device) {
            if( $status == 'all' || $status == $device->status ) {
                $message = '';
                $messageType = 'alert';
                $deviceAlert = [];
                $device_id = $device->device_id;

                $deviceAlertItem = $deviceAlertResult->first(function ($item) use ($device_id) {
                    return $item['id'] === $device_id;
                });

                if (empty($deviceAlertItem) || (isset($device['status']) && $device['status'] == 2)) {
                    $message = $deviceAlertItem['message']?? "Device not found";
                    $messageType = "danger";
                } else if (isset($device['status']) && $device['status'] == 1 && $deviceAlertItem['ol'] == 1){
                    $deviceAlert = $deviceAlertItem;
                    $messageType = "normal";
                } else if (isset($device['status']) && $device['status'] == 1 && $deviceAlertItem['ol'] == 0){
                    $deviceAlert = $deviceAlertItem;
                    $messageType = "inactive";
                }

                $lat_lng = [];
                if (!empty($deviceAlert)) {
                    $lat_lng = [
                        'lat' => floatval($deviceAlert['mlat'] ?? 0),
                        'lng' => floatval($deviceAlert['mlng'] ?? 0)
                    ];
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
