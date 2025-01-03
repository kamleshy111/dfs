<?php

namespace App\Console\Commands;

use App\Events\NotificationAlert;
use App\Events\NotificationCreate;
use App\Models\Customer;
use App\Models\Device;
use App\Services\AlertService;
use Illuminate\Console\Command;
use App\Models\Alert;

class SyncDeviceAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-device-alert';

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
        $courseSyncService = app(AlertService::class);
        $deviceCurrentStatus = $courseSyncService->getDeviceAlert($deviceIds);

        if (isset($deviceCurrentStatus['result']) && $deviceCurrentStatus['result'] === 0) {
            $deviceAlertResult = collect($deviceCurrentStatus['infos']);

            if (!empty($deviceAlertResult) && $deviceAlertResult->isNotEmpty()) {
                $allAlert = $deviceAlertResult->map(function ($item) {
                    $alert = Alert::create([
                        'device_id' => $item['devIdno'],
                        'latitude' => $item['jingDu'] ?? 0,
                        'longitude' => $item['weiDu'] ?? 0,
                        'location' =>  $item['position'] ?? '',
                        'read_unread_status' => 0,
                        'captures' => $item["downloadUrl"] ?? '' ,
                        'message' => alarmTypeDescription($item['alarmType']),
                        'alert_type' => $item['alarmType'] ?? '',
                        'created_at' => $item['fileTimeStr'] ?? '',
                        'updated_at' => $item['updateTimeStr'] ?? '',
                    ]);

                    return [
                        "alertId" => $alert->id ?? 0,
                        "deviceId" => $alert->device_id ?? 0,
                        "latitude" => $alert->latitude ?? '',
                        "longitude" => $alert->longitude ?? '',
                        "location" => $alert->location ?? '',
                        "read_unread_status" => $alert->read_unread_status ?? 0,
                        "captures" => $alert->captures ?? '---',
                        "message" => $alert->message  ?? '',
                        "alert_type" => $alert->alert_type ?? '',
                    ];
                })->toArray();

                event(new NotificationAlert($allAlert));
            }
        }
    }
}
