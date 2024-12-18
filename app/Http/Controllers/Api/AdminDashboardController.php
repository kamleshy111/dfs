<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Carbon\Carbon;

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
       
    //    $data = Notification::where('status', 0)->get();
    $data = Notification::where('status', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $notifications = $data->map(function($item) {
  
            $assignedDate = Carbon::create($item->created_at);
            $currentDate = Carbon::now();
    
            $minutesDifference = $assignedDate->diffInMinutes($currentDate);
            if($minutesDifference < 1440 && $minutesDifference >= 60) {
                $comment_date = round($minutesDifference/60) . ' hours ago';
            } elseif ($minutesDifference < 60) {
                $comment_date = round($minutesDifference) . ' minutes ago';
            } else {
                $comment_date = $item->created_at->format('j F, Y');
            }
            return [
                'id' => $item->id ?? 0,
                'vehicleId' => $item->vehicle_id ?? 0,
                'title' => $item->title ?? '',
                'body' => $item->body ?? '---',
                'date' => $comment_date,
            ];
        });

       return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function adminUnreadNotifications() {

        $adminUnreadCount = Notification::where('status', 0)->count();
        return response()->json(['adminUnreadCount' => $adminUnreadCount]);
    }

    public function markAsRead($id) {

        $notification = Notification::findOrFail($id);
        $notification->update(['status' => 1]); // Assuming 'status' is used to track read/unread
        return response()->json(['message' => 'Notification marked as read.']);
    }

}
