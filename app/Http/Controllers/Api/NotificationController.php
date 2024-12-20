<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;



class NotificationController extends Controller
{
    public function index(Request $request){

        $query = Notification::query()
                ->select(
                    'notifications.id',
                    'notifications.title',
                    'notifications.vehicle_id',
                    'notifications.status',
                    'notifications.body',
                    'notifications.created_at as date',
                    'customers.first_name',
                    'customers.last_name',
                    'devices.device_id as deviceId',
                    'vehicles.vehicle_register_number',
                )
                ->leftJoin('vehicles', 'notifications.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('devices', 'vehicles.device_id', '=', 'devices.id')
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
            $query->whereBetween('notifications.created_at', [
                Carbon::parse($request->startDate)->startOfDay(),
                Carbon::parse($request->endDate)->endOfDay(),
            ]);
        }

        // Order by date and paginate
        $notifications = $query->orderBy('notifications.created_at', 'desc')
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
                'vehicleId' => $item->vehicle_id ?? 0,
                'title' => $item->title ?? '',
                'body' => $item->body ?? '---',
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
       
        $data = Notification::where('status', 0)
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
                    'vehicleId' => $item->vehicle_id ?? 0,
                    'title' => $item->title ?? '',
                    'body' => $item->body ?? '---',
                    'date' => $comment_date,
                ];
            });

            $adminUnreadCount = Notification::where('status', 0)->count();
    
           return response()->json([
                'notifications' => $notifications,
                'adminUnreadCount' => $adminUnreadCount,
            ]);
    }

    public function markAsRead($id) {

        $notification = Notification::findOrFail($id);
        $notification->update(['status' => 1]);
        return response()->json(['message' => 'Notification marked as read.']);
    }
    
}
