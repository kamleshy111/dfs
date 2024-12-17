<?php
namespace App\Services;

use App\Models\AppSettings;
use App\Repositories\CourseRepository;
use Exception;
use Illuminate\Support\Facades\Http;

class DeviceInfoService
{
    protected $apiTokenService;
    protected $courseRepository;
    protected $apiBaseUrl;

    public function __construct(ApiTokenService $apiTokenService, CourseRepository $courseRepository)
    {
        $this->apiTokenService = $apiTokenService;
        $this->courseRepository = $courseRepository;
        $this->apiBaseUrl = AppSettings::where('key', 'ispring-api-base-url')->value('value');
    }

    public function index()
    {
        try {
            $accessToken = $this->apiTokenService->getAccessToken();
            $response = Http::withToken($accessToken)->get($this->apiBaseUrl . '/department');

            if ($response->successful()) {
                $xmlContent = $response->body();
                // Parse the XML
                $xml = simplexml_load_string($xmlContent);
                $json = json_encode($xml);
                $jsonDepartmentData = json_decode($json, true);
                $departments = $jsonDepartmentData['department'] ?? [];
                return collect($departments);
            } else {
                throw new \Exception('Failed to sync courses: ' . $response->body());
            }
        } catch (Exception $e) {
            logger()->error('Failed to sync courses: ' . $e->getMessage());
        }
    }

    public function dropDownOptions() {
        if (!empty($this->index())) {
            return $this->index()->pluck('name', 'departmentId');
        }
    }
}
