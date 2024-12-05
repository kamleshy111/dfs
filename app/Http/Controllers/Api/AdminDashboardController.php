<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function getAdminStats() {
        $customers = Customer::select('amount')->get();
        $devices = Device::select('status', 'date_time')->get();
        $device_sold_by_month = DB::table('devices')->selectRaw('MONTH(date_time) as month, COUNT(*) as total')->groupByRaw('MONTH(date_time)')->orderByRaw('MONTH(date_time)')->get();

        $customers_amount = $customers->sum('amount');
        $customer_count = $customers->count();
        $active_device_count = $devices->where('status', 'active')->count();
        $expired_device_count = $devices->where('status', 'expired')->count();
        $inactive_device_count = $devices->where('status', 'inactive')->count();
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
}
