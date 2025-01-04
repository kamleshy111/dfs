<?php

namespace App\Console\Commands;

use App\Events\NotificationAlert;
use App\Events\NotificationCreate;
use App\Models\Customer;
use App\Models\Device;
use App\Services\AlertService;
use App\Services\DeviceInfoService;
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

            // Batch update devices
            $deviceAlertResult->each(function ($data) {
                Device::where('device_id', $data['id'])
                    ->where('status', '!=', $data['ol'])
                    ->update([
                        'status' => $data['ol'],
                        'latitude' => $data['mlat'],
                        'longitude' => $data['mlng'],
                        'last_active' => $data['gt'],
                    ]);
            });

            $devices = Device::all();
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
                $messageType = match ($device->status) {
                    2 => 'danger',
                    1 => 'normal',
                    default => 'inactive',
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
                        'message_type' => $messageType,
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
            ];


            // Trigger notification event
            event(new NotificationCreate($result));
        }

    }
}
