<?php
namespace App\Services;

use App\Models\AppSettings;
use App\Repositories\CourseRepository;
use Exception;
use Illuminate\Support\Facades\Http;

class AlertService
{
    protected ApiTokenService $apiTokenService;
    public string $apiBaseUrl;
    public string $deviceId;

    public function __construct(ApiTokenService $apiTokenService)
    {
        $this->apiTokenService = $apiTokenService;
        $this->apiBaseUrl = $apiTokenService->apiBaseUrl;
    }

    public function getDeviceAlert($deviceId = 0, $begintime = '2024-11-12 16:00:00', $endtime = '2024-12-13 12:00:00')
    {
        $this->deviceId = $deviceId;
        try {
            $accessToken = $this->apiTokenService->getAccessToken();
            $response = Http::get($this->apiBaseUrl . '/StandardApiAction_queryPhoto.action', [
                'jsession' => $accessToken,
                'devIdno' => $this->deviceId,
                'begintime' => $begintime,
                'endtime' => $endtime,
            ]);
            /*$response = Http::get($this->apiBaseUrl . '/StandardApiAction_getDeviceStatus.action',[
                'jsession' => $accessToken,
                'devIdno' => $this->deviceId,
                'toMap' => '1',
                'driver' => '0',
                'language' => 'zh',
            ]);*/


            if ($response->successful()) {
                $xmlContent = $response->body();
                return json_decode($xmlContent, true);
            } else {
                throw new \Exception('Failed to get alert: ' . $response->body());
            }
        } catch (Exception $e) {
            logger()->error('Failed to get alert: ' . $e->getMessage());
        }
    }
}
