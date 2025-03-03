<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function getAdminStats(Request $request, $userId = 0) {
        $devices = collect();
        if (!empty($userId)) {
            $user = User::find($userId);

            if ($user && $user->customer) {
                $devices = $user->customer->devices;
            }
        } else {
            $devices = Device::select('status', 'date_time')->get();
        }
        //$device_sold_by_month = DB::table('devices')->selectRaw('MONTH(date_time) as month, COUNT(*) as total')->groupByRaw('MONTH(date_time)')->orderByRaw('MONTH(date_time)')->get();

        /*$customers_amount = 0;
        $customer_count = 0;*/

        $active_device_count = $devices->where('status', 1)->count();
        $expired_device_count = $devices->where('status', 2)->count();
        $inactive_device_count = $devices->where('status', 0)->count();
        $all_device_count = $devices->count();

        return response()->json([
            'all_device_count'      => $all_device_count,
            'active_device_count'   => $active_device_count,
            'expired_device_count'  => $expired_device_count,
            'inactive_device_count' => $inactive_device_count,
           /* 'customer_amounts'      => formatAmount($customers_amount),
            'device_sold_by_month'  => $device_sold_by_month*/
        ]);
    }

    public function getDeviceStatus(Request $request){
   
        $devices = Device::select('status', 'device_id', 'date_time')->get();

        $deviceStatus = $devices->map(function ($device) {
            return [
                'deviceId' => $device->device_id,
                'status' => $device->status,
                'dateTime' => $device->date_time,
            ];
        });

        $deviceCount = $devices->count();

        return response()->json([
            'deviceCount' => $deviceCount,
            'deviceStatus' => $deviceStatus,
        ]);
    }

}
