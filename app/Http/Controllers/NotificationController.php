<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Events\NotificationCreate;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Vehicle;


class NotificationController extends Controller
{
    public function notification(Request $request,$vehicleId){

        $vehicle = Vehicle::select('vehicles.id','vehicles.vehicle_register_number as vehicleRegisterNumber')
                    ->where('vehicles.id', '=', $vehicleId)
                    ->first();

        $title = 'Vehicle Renewal Reminder';
        $body = "My vehicle Register Number {$vehicle->vehicleRegisterNumber} is due for subscription renewal.";


        $data = Notification::create([
            'vehicle_id' => $vehicleId ,
            'title' => $title,
            'body' => $body
        ]);

        event(new NotificationCreate([
          'vehicleId' => $data->vehicle_id,
         'title' =>  $data->title,
         'body' => $data->body,
        ]));
      
        return redirect('/billing');
    }
}
