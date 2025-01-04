<?php

namespace App\Console\Commands;

use App\Events\NotificationAlert;
use App\Events\NotificationCreate;
use App\Models\Customer;
use App\Models\Device;
use App\Services\AlertService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Alert;
use Illuminate\Support\Facades\Cache;

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
        // Retrieve the last end time from the cache or set it to now if not found
        $lastEndTime = Cache::get('device_alert_last_end_time', Carbon::now('Asia/Kolkata')->subSeconds(10)->toDateTimeString());
        $startTime = $lastEndTime;
        $endTime = Carbon::now('Asia/Kolkata')->toDateTimeString();

        $deviceCurrentStatus = $courseSyncService->getDeviceAlert($deviceIds, $startTime, $endTime);

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
        Cache::put('device_alert_last_end_time', $endTime, 60); // Cache duration in seconds
    }
}
