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
            foreach ($deviceAlertResult as $data) {
                Device::updateOrCreate(
                    [
                        'device_id' => $data['id']
                    ],
                    [
                        'status' => $data['ol'],
                        'latitude' => $data['mlat'],
                        'longitude' => $data['mlng'],
                        'last_active' => $data['gt'],
                    ]
                );
            }

            $updatedDevices = Device::get();
            $locations = [];
            foreach ($updatedDevices as $index => $device) {
                $messageType = 'alert';
                if ($device->status == 2) {
                    $messageType = "danger";
                } else if ($device->status == 1) {
                    $messageType = "normal";
                } else if ($device->status == 0) {
                    $messageType = "inactive";
                }

                $locations[] = [
                    "position" => [
                        'lat' => floatval($device->latitude ?? 0),
                        'lng' => floatval($device->longitude ?? 0)
                    ],
                    "title" => $device->device_id,
                    "content" => [
                        "device_id" => $device->device_id,
                        'device_name' => 'Device Name' . $device->device_id,
                        'message_type' => $messageType,
                        'last_active' => $device->last_active ?? "",
                        'lat_lng' => [
                            'lat' => floatval($device->latitude ?? 0),
                            'lng' => floatval($device->longitude ?? 0)
                        ]
                    ]
                ];

                event(new NotificationCreate($locations));
            }
        }
    }
}
