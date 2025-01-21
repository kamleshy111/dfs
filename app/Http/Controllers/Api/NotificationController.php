<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationRead;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\Alert;
use Carbon\Carbon;



class NotificationController extends Controller
{
    public function index(Request $request){

        $userId = Auth::user()->id;
        $user = Auth::user()->role === 'user';

        $todayCount = Alert::select('alerts.id')
                        ->when($user, function ($query) use ($userId) {
                            return $query->leftJoin('devices', 'alerts.device_id', '=', 'devices.device_id')
                                ->where('devices.user_id', $userId)
                                ->whereDate('alerts.created_at', today());
                        }, function ($query) {
                            return $query->whereDate('alerts.created_at', today());
                        })
                        ->count();


        $query = Alert::query()->select(
                            'alerts.id','alerts.device_id','alerts.latitude','alerts.longitude','alerts.location',
                            'alerts.status','alerts.read_unread_status','alerts.user_re_un_status','alerts.captures',
                            'alerts.message','alerts.alert_type','alerts.created_at as date','customers.first_name',
                            'customers.last_name','devices.device_id as deviceId','vehicles.vehicle_register_number',
                        )
                        ->leftJoin('devices', 'alerts.device_id', '=', 'devices.device_id')
                        ->leftJoin('vehicles', 'devices.id', '=', 'vehicles.device_id')
                        ->leftJoin('customers', 'vehicles.customer_id', '=', 'customers.id');

        if ($user) {
            $query->where('customers.user_id', $userId);
        }

        // Filter by search vehicle registerSearch
        if ($request->filled('vehicleRegisterSearch')) {
            $query->where('vehicles.vehicle_register_number', 'like', '%' . $request->vehicleRegisterSearch . '%');
        }

        // Filter by device ID
        if ($request->filled('device_id')) {
            $query->where('devices.device_id', 'like', '%' . $request->device_id . '%');
        }

        // Filter by customer name
        if ($request->filled('customerSearch')) {
            $query->whereRaw("CONCAT(customers.first_name, ' ', customers.last_name) LIKE ?", ['%' . $request->customerSearch . '%']);
        }

        // Date range filter
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('alerts.created_at', [
                Carbon::parse($request->startDate, 'Asia/Kolkata')->setTimezone('UTC'),
                Carbon::parse($request->endDate, 'Asia/Kolkata')->setTimezone('UTC'),
            ]);
        }

        // Order by date and paginate
        $notifications = $query->orderBy('alerts.created_at', 'desc')
            ->paginate(10);


        // Format the data
        $notifications->getCollection()->transform(function ($item) {
            $createdDate = Carbon::create($item->date);
            $currentDate = Carbon::now();

            $formatted_date = $createdDate->format('Y-m-d');
            $formatted_time =  $createdDate->timezone('Asia/Kolkata')->format('h:i A');

            $minutesDifference = $createdDate->diffInMinutes($currentDate);

            if ($minutesDifference < 1440 && $minutesDifference >= 60) {
                $formattedDate = round($minutesDifference / 60) . ' hours ago';
            } elseif ($minutesDifference < 60) {
                $formattedDate = round($minutesDifference) . ' minutes ago';
            } else {
                $formattedDate = $createdDate->format('j F, Y');
            }

            return [
                'id' => $item->id ?? 0,
                'deviceId' => $item->deviceId ?? 0,
                'customerName' => ($item->first_name ?? '') . ' ' . ($item->last_name ?? ''),
                'message' => $item->message ?? '',
                'readUnreadStatus' => $item->read_unread_status ?? '',
                'userReUnStatus' => $item->user_re_un_status ?? '',
                'alertType' => $item->alert_type ?? '',
                'vehicleRegisterNumber' => $item->vehicle_register_number ?? '',
                'captures' => $item->captures ? asset($item->captures) : '',
                'location' => $item->location ??  '',
                'coordinates' => ('Lat:  '.$item->latitude ?? ' ') . ' ' . ('Lon:  '.$item->longitude ?? ' '),
                'status' => $item->status ?? '',
                'date' => $formattedDate,
                'dateFormatted' => $formatted_date,
                'timeFormatted' => $formatted_time,
            ];
        });


        return response()->json([
            'notifications' => $notifications,
            // 'unreadCount' => $unreadCount,
            'todayCount' => $todayCount,
            'totalCount' => $notifications->total(),
        ]);


    }

    public function getNotification(){

        $userId = Auth::id();
        $user = Auth::user()->role === 'user';

        $data = Alert::select('alerts.*')
                    ->when($user, function ($query) use ($userId) {
                        return $query->leftJoin('devices', 'alerts.device_id', '=', 'devices.device_id')
                                    ->where('alerts.user_re_un_status', 0)
                                    ->where('devices.user_id', $userId);
                    }, function ($query) {
                        return $query->where('alerts.read_unread_status', 0);
                    })
                    ->orderByDesc('alerts.created_at')
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
                    'deviceId' => $item->device_id ?? 0,
                    'message' => $item->message ?? '---',
                    'date' => $comment_date,
                ];
            });

           return response()->json([
                'notifications' => $notifications,
                'adminUnreadCount' => $notifications->count(),
            ]);
    }

    public function markAsRead($id) {


        $notification = Alert::findOrFail($id);

        $user = Auth::user()->role === 'user';

        if (!empty($user)) {
            $notification->update(['user_re_un_status' => 1]);
        } else {
            $notification->update(['read_unread_status' => 1]);
        }

        event(new NotificationRead([
           'title' =>  'Notification read',
          ]));

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function userNotifications(){

        $userId = Auth::user()->id;

        $customerId = Customer::where('user_id', $userId)->value('id');

        $data = Alert::select('alerts.id', 'alerts.device_id', 'alerts.latitude', 'alerts.longitude',  'alerts.location', 'alerts.status', 'alerts.read_unread_status',
                   'alerts.captures', 'alerts.message', 'alerts.alert_type', 'alerts.created_at as date', 'devices.device_id as deviceId', 'customers.id as customerId',
                    )
                    ->leftJoin('devices', 'alerts.device_id', '=', 'devices.id')
                    ->leftJoin('vehicles', 'devices.id', '=', 'vehicles.device_id')
                    ->leftJoin('customers', 'vehicles.customer_id', '=', 'customers.id')
                    ->where('customers.Id', $customerId)
                    ->get();

        $notifications = $data->map(function($item) {
            return [
                'id' => $item->id ?? 0,
                'deviceId' => $item->deviceId ?? 0,
                'latitude' => $item->latitude ?? '',
                'longitude' => $item->longitude ?? '',
                'location' => $item->location ?? '',
                'status' => $item->status ?? '',
                'captures' => $item->captures ? asset($item->captures) : '',
                'message' => $item->message ?? '---',
                'alerts' => $item->alert_type ?? '',
            ];
        });

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

}
