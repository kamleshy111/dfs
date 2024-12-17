<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Notification;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index(){

        // $data = Notification::all();
        $data = Notification::orderBy('created_at', 'desc')->get();


        $notifications = $data->map(function($item){
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
                                'status' => $item->status ?? '',
                                'date' => $comment_date,
                            ];
                        });

        $notificationCount = Notification::count();

        return Inertia::render('Report/Report',[
            'user' => Auth::user(),
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
        ]);
    }
}
