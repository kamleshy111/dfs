<?php

namespace App\Console\Commands;

use App\Events\NotificationAlert;
use App\Events\NotificationCreate;
use App\Models\Customer;
use App\Models\Device;
use App\Services\AlertService;
use App\Services\DeviceInfoService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Alert;

class SyncDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-device-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deviceIds = Device::pluck('device_id')->implode(',');
        $courseSyncService = app(DeviceInfoService::class);
        $deviceCurrentStatus = $courseSyncService->getDeviceStatus($deviceIds);

        if (isset($deviceCurrentStatus['result']) && $deviceCurrentStatus['result'] === 0) {
            $deviceAlertResult = collect($deviceCurrentStatus['status']);
            $currentTime = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s.0');

            // Batch update devices
            $deviceAlertResult->each(function ($data) use($currentTime) {
               $newData = [
                    'status' => $data['ol'],
                    'latitude' => $data['mlat'],
                    'longitude' => $data['mlng'],
                ];

                if (!empty($data['ol'])) {
                    $newData['last_active'] =  $currentTime;
                }

                // Count alerts for this device in the last 2 minutes
                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                $alertCount = Alert::where('device_id', $data['id'])
                    ->where('created_at', '>=', $twoMinutesAgo)
                    ->count();

                if ($alertCount === 0) {
                    $newData['alert_status'] = 0; // Normal
                } elseif ($alertCount > 0 && $alertCount <= 5) {
                    $newData['alert_status'] = 1; // Warning
                } elseif ($alertCount > 5 && $alertCount <= 10) {
                    $newData['alert_status'] = 2; // Critical
                } else {
                    $newData['alert_status'] = 3; // Danger
                }

                Device::where('device_id', $data['id'])
                    ->update($newData);
            });

            /*$devices = Device::all();
            $active_device_count = $devices->where('status', 1)->count();
            $expired_device_count = $devices->where('status', 2)->count();
            $inactive_device_count = $devices->where('status', 0)->count();
            $all_device_count = $devices->count();

             $adminStats = [
                'all_device_count'      => $all_device_count,
                'active_device_count'   => $active_device_count,
                'expired_device_count'  => $expired_device_count,
                'inactive_device_count' => $inactive_device_count
                ];

            // Fetch updated devices and prepare locations in a single loop
            $locations = $devices->map(function ($device) {
                $deviceStatus = match ($device->status) {
                    2 => 'expired',
                    1 => 'online',
                    default => 'Offline',
                };

                $alertStatus = match ($device->status) {
                    3 => 'Danger',
                    2 => 'Critical',
                    1 => 'Warning',
                    default => 'Normal',
                };

                return [
                    "position" => [
                        'lat' => floatval($device->latitude ?? 0),
                        'lng' => floatval($device->longitude ?? 0),
                    ],
                    "title" => $device->device_id,
                    "content" => [
                        "device_id" => $device->device_id,
                        'device_name' => 'Device Name ' . $device->device_id,
                        'device_status' => $deviceStatus,
                        'alert_status' => $alertStatus,
                        'last_active' => $device->last_active ?? "",
                        'lat_lng' => [
                            'lat' => floatval($device->latitude ?? 0),
                            'lng' => floatval($device->longitude ?? 0),
                        ],
                    ],
                ];
            })->all();

            $result = [
                'adminStats' =>$adminStats,
                'locations' =>$locations
            ];*/
            $result = [
                true
            ];

            // Trigger notification event
            event(new NotificationCreate($result));
        }

    }
}
