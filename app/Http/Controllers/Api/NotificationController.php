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

        $query = Alert::query()
                        ->select(
                            'alerts.id',
                            'alerts.device_id',
                            'alerts.latitude',
                            'alerts.longitude',
                            'alerts.location',
                            'alerts.status',
                            'alerts.read_unread_status',
                            'alerts.captures',
                            'alerts.message',
                            'alerts.alert_type',
                            'alerts.created_at as date',
                            'customers.first_name',
                            'customers.last_name',
                            'devices.device_id as deviceId',
                            'vehicles.vehicle_register_number',
                        )
                        ->leftJoin('devices', 'alerts.device_id', '=', 'devices.id')
                        ->leftJoin('vehicles', 'devices.id', '=', 'vehicles.device_id')
                        ->leftJoin('customers', 'vehicles.customer_id', '=', 'customers.id');

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
                Carbon::parse($request->startDate)->startOfDay(),
                Carbon::parse($request->endDate)->endOfDay(),
            ]);
        }

        // Order by date and paginate
        $notifications = $query->orderBy('alerts.created_at', 'desc')
            ->paginate(10);


        // Format the data
        $notifications->getCollection()->transform(function ($item) {
            $createdDate = Carbon::create($item->date);
            $currentDate = Carbon::now();

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
                'alertType' => $item->alert_type ?? '',
                'vehicleRegisterNumber' => $item->vehicle_register_number ?? '',
                'captures' => $item->captures ? asset($item->captures) : '',
                'location' => $item->location ??  '',
                'coordinates' => ('Lat:  '.$item->latitude ?? ' ') . ' ' . ('Lon:  '.$item->longitude ?? ' '),
                'status' => $item->status ?? '',
                'date' => $formattedDate,
            ];
        });


        return response()->json([
            'notifications' => $notifications,
            'totalCount' => $notifications->total(),
        ]);


    }

    public function getNotification(){

        $data = Alert::where('read_unread_status', 0)
                        ->orderBy('created_at', 'desc')
                        ->take(10)
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

            $adminUnreadCount = Alert::where('read_unread_status', 0)->count();
           return response()->json([
                'notifications' => $notifications,
                'adminUnreadCount' => $adminUnreadCount,
            ]);
    }

    public function markAsRead($id) {


        $notification = Alert::findOrFail($id);

        $notification->update(['read_unread_status' => 1]);

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
