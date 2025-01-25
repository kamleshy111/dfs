<?php
namespace App\Services;

use App\Models\AppSettings;
use App\Repositories\CourseRepository;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
                'begintime' => date('Y-m-d H:i:s', strtotime($begintime)),
                'endtime' => $endtime,
            ]);
/*
            $baseUrl = 'http://119.23.104.221:8080/StandardApiAction_queryPhoto.action';

            // Parameters
                $parameters = [
                    'jsession' => $accessToken,
                    'devIdno' => $this->deviceId,
                    'begintime' => date('Y-m-d H:i:s', strtotime($begintime)),
                    'endtime' => $endtime,
                ];

                // Generate query string
               $queryString = http_build_query($parameters);

                // Combine URL with query string
                $fullUrl = $baseUrl . '?' . $queryString;

                // Log the full URL
                Log::info('Full API URL:', ['url' => $fullUrl]);


                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $fullUrl); // Set the URL
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
                curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed (useful for testing)
                curl_setopt($ch, CURLOPT_HTTPGET, true); // Use HTTP GET

                // Execute cURL request
                $responseCurl = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                    Log::error('cURL Error:', ['error' => $error_msg]);
                } else {
                    // Log the response (for debugging)
                    Log::info('API Response:', ['response' => $responseCurl]);
                }

                // Close cURL
                curl_close($ch);
*/

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
