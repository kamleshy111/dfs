<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class AdminDashboardController extends Controller
{
    public function getAdminStats() {
        $devices = Device::select('status', 'date_time')->get();
        $device_sold_by_month = DB::table('devices')->selectRaw('MONTH(date_time) as month, COUNT(*) as total')->groupByRaw('MONTH(date_time)')->orderByRaw('MONTH(date_time)')->get();

        $customers_amount = 0;
        $customer_count = 0;
        $active_device_count = $devices->where('status', 1)->count();
        $expired_device_count = $devices->where('status', 2)->count();
        $inactive_device_count = $devices->where('status', 0)->count();
        $all_device_count = $devices->count();

        return response()->json([
            'customer_count'        => $customer_count,
            'all_device_count'      => $all_device_count,
            'active_device_count'   => $active_device_count,
            'expired_device_count'  => $expired_device_count,
            'inactive_device_count' => $inactive_device_count,
            'customer_amounts'      => formatAmount($customers_amount),
            'device_sold_by_month'  => $device_sold_by_month
        ]);
    }
    public function getNotification(){
       
    
       $data = Notification::where('status', 0)->get();

        $notifications = $data->map(function($item) {
            return [
                'id' => $item->id ?? 0,
                'vehicleId' => $item->vehicle_id ?? 0,
                'title' => $item->title ?? '',
                'body' => $item->body ?? '---',
                'date' => \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') ?? '',
            ];
        });

       return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
