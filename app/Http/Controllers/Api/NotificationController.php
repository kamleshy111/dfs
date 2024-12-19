<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;



class NotificationController extends Controller
{
    public function index(Request $request){


    
    

        // // $data = Notification::orderBy('status', 0)->orderBy('created_at', 'desc')->paginate(10);
        // $data = Notification::orderBy('created_at', 'desc')
        //             ->paginate(10);


        // $notifications = $data->map(function($item){
        //                     $assignedDate = Carbon::create($item->created_at);
        //                     $currentDate = Carbon::now();
                    
        //                     $minutesDifference = $assignedDate->diffInMinutes($currentDate);
        //                     if($minutesDifference < 1440 && $minutesDifference >= 60) {
        //                         $comment_date = round($minutesDifference/60) . ' hours ago';
        //                     } elseif ($minutesDifference < 60) {
        //                         $comment_date = round($minutesDifference) . ' minutes ago';
        //                     } else {
        //                         $comment_date = $item->created_at->format('j F, Y');
        //                     }
        //                     return [
        //                         'id' => $item->id ?? 0,
        //                         'vehicleId' => $item->vehicle_id ?? 0,
        //                         'title' => $item->title ?? '',
        //                         'body' => $item->body ?? '---',
        //                         'status' => $item->status ?? '',
        //                         'date' => $comment_date,
        //                     ];
        //                 });

        // $data->setCollection($notifications);

        // return response()->json($data);

    

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
                    'devices.device_id as deviceId'
                )
                ->leftJoin('vehicles', 'notifications.vehicle_id', '=', 'vehicles.id')
                ->leftJoin('devices', 'vehicles.device_id', '=', 'devices.id')
                ->leftJoin('customers', 'vehicles.customer_id', '=', 'customers.id');

        // Filter by search query
        if ($request->filled('search')) { // Check if 'search' is provided and not empty
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('notifications.title', 'like', '%' . $request->search . '%')
                    ->orWhere('notifications.body', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by device ID
        if ($request->filled('device_id')) { // Check if 'device_id' is provided and not empty
            $query->where('devices.device_id', 'like', '%' . $request->device_id . '%'); // Correct field reference
        }

        // Filter by customer name
        if ($request->filled('customerSearch')) { // Check if 'device_id' is provided and not empty
            $query->where('customers.first_name', 'like', '%' . $request->customerSearch . '%'); // Correct field reference
        }

        // Order by date and paginate
        $notifications = $query->orderBy('notifications.created_at', 'desc') // Use correct column name
            ->paginate(10);

        return response()->json($notifications);

    }
}
