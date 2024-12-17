<?php

namespace App\Services;

use App\Models\AppSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiTokenService
{
    public string $apiBaseUrl;
    protected string $clientId;
    protected string $clientSecret;


    public function  __construct() {
        $this->clientId = "leisureauto";
        $this->clientSecret = "000000";
        $this->apiBaseUrl = "http://119.23.104.221:8080/";
    }

    /**
     * Get the access token, either from cache or by making an API request.
     *
     * @return string
     */
    public function getAccessToken()
    {

        try {
            // Check if token is cached
            if (Cache::has('ispring_access_token')) {
                return Cache::get('ispring_access_token');
            }

            // Fetch a new token
            $response = Http::asForm()->get($this->apiBaseUrl . 'StandardApiAction_login.action', [
                'account' => $this->clientId,
                'password' => $this->clientSecret
            ]);

            $accessToken = '';
            if ($response->successful()) {
                $tokenData = $response->json();
                if (!empty($tokenData['jsession'])) {
                    $accessToken = $tokenData['jsession'];
                    // Cache the token
                    Cache::put('ispring_access_token', $accessToken);
                }

                return $accessToken;
            } else {
                throw new \Exception('Failed to retrieve access token: ' . $response->body());
            }
        } catch (Exception $e) {
            logger()->error('Failed to retrieve access token: ' . $e->getMessage());
        }
    }
}
