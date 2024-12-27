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
                    } else if ($device->status == 1){
                        $messageType = "normal";
                    } else if ($device->status == 0){
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

                    $device_id = $device->device_id;

                    $lat = floatval($device->latitude);
                    $long = floatval($device->longitude);

                    $deviceId = $device->id;

                    $deviceAlertItem = $deviceAlertResult->first(function ($item) use ( $device_id ) {
                        return $item['id'] === $device_id;
                    });

                    $alertType = "Normal";
                    $alermAlert = '';

                    $alertMessages = [
                        'aq' => 'aq Alerm message',
                        'adas1' => 'adas1 Alerm message',
                        'adas2' => 'adas2 Alerm message',
                        'dsm1' => 'dsm1 Alerm message',
                        'dsm2' => 'dsm2 Alerm message',
                        'bsd1' => 'bsd1 Alerm message',
                        'bsd2' => 'bsd2 Alerm message',
                        'cet' => 'cet Alerm message',
                        'wc' => 'wc Alerm message',
                        'p1' => 'p1 Alerm message',
                    ];
                    
                    foreach ($alertMessages as $key => $message) {

                        if (!empty($deviceAlertItem) && isset($deviceAlertItem[$key]) && $deviceAlertItem[$key] > 0) {
                            $alermAlert = $message;
                            $alertType = 'Danger';
                            break;
                        }
                    }
                    

                    if($alermAlert){

                        $alert = Alert::create([
                            'device_id' => $deviceId,
                            'latitude' => $lat ?? 0,
                            'longitude' => $long ?? 0,
                            'location' =>  '',
                            'read_unread_status' => 0,
                            'captures' => $deviceAlertItem["capture"] ?? '' ,
                            'message' => $alermAlert ?? '--',
                            'alert_type' => $alertType ?? '',
                            
                        ]);

                        $allAlert[] = [
                            "alertId" => $alert->id ?? 0,
                            "deviceId" => $alert->device_id ?? 0,
                            "latitude" => $alert->latitude ?? '',
                            "longitude" => $alert->longitude ?? '',
                            "location" => $alert->location ?? '',
                            "read_unread_status" => $alert->read_unread_status ?? 0,
                            "captures" => $alert->captures ?? '---',
                            "message" => $alermAlert  ?? '',
                            "alert_type" => $alert->alert_type ?? '',
                        ];  
                        
                    }
                    
                }

            event(new NotificationCreate($locations));

            event(new NotificationAlert($allAlert));
        }
    }
}
