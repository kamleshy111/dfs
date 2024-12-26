<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\NotificationCreate;
use App\Mail\VehicleRenewal;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Alert;
use App\Models\User;
use Carbon\Carbon;


class NotificationController extends Controller
{
    public function notification(Request $request,$vehicleId){

      
        $vehicle = Vehicle::select('vehicles.id', 'vehicles.device_id','vehicles.vehicle_register_number as vehicleRegisterNumber')
                    ->where('vehicles.id', '=', $vehicleId)
                    ->first();

        $message = "My vehicle Register Number {$vehicle->vehicleRegisterNumber} is due for subscription renewal.";


        $data = Alert::create([
            'device_id' => $vehicle->device_id,
            'latitude' => '999.89',
            'longitude' => '-68886.77',
            'location' => 'jaipur',
            'status' => 'inactive',
            'read_unread_status' => '0',
            'captures' => '/storage/InvoicePhotos/67616b5176aa0.jpg',
            'alert_type' => 'emergency',
            'message' => $message,

            
        ]);

        event(new NotificationCreate([
          'vehicleId' => $data->vehicle_id,
        ]));
    
        
            return response()->json(['message' => 'Vehicle Renewal Reminder']);
    }

    public function sendEmailVehicleRenewal()
    {
        $user = Auth::user();

        $data = Customer::select('customers.id','customers.first_name','customers.last_name','customers.email','customers.phone', 'vehicles.installation_date',
                            'vehicles.vehicle_register_number','vehicles.sim_card_number','vehicles.start_date','vehicles.duration','vehicles.duration_unit','devices.device_id') 
                        ->join('vehicles', 'customers.id', '=', 'vehicles.customer_id')
                        ->join('devices', 'vehicles.device_id', '=', 'devices.id')
                        ->where('customers.user_id', $user->id)
                        ->get();
    
        $vehicles = $data->map(function ($item) {
            
            $startDate = Carbon::parse($item->start_date);
            $duration = (int) $item->duration;

            $installationDate = Carbon::parse($item->installation_date);
    
            switch ($item->duration_unit) {
                case 'days':
                    $expirationDate = $startDate->addDays($duration);
                    break;
                case 'months':
                    $expirationDate = $startDate->addMonths($duration);
                    break;
                case 'years':
                    $expirationDate = $startDate->addYears($duration);
                    break;
                default:
                    return null;
            }
    
            
            $currentDate = now()->addDay(5);
            if ($currentDate >= $expirationDate) {
               
                return [
                   
                    'id' => $item->id,
                    'customerName' => ($item->first_name) . ' ' . ($item->last_name),
                    'email' => $item->email,
                    'phone' => $item->phone,
                    'vehicleRegisterNumber' => $item->vehicle_register_number,
                    'deviceId' => $item->device_id,
                    'installationDate' => $installationDate->format('d-m-Y'),
                    'startDate' => Carbon::parse($item->start_date)->format('d-m-Y'),
                    'expirationDate' => $expirationDate->format('d-m-Y'),
                ];
            }
    
            return null;
        })->filter();

        $adminEmails = User::where('role', 'admin')->pluck('email');

        // Send email
        Mail::to($adminEmails)->send(new VehicleRenewal($vehicles));

        return response()->json(['message' => 'Email Send Vehicle Renewal Reminder']);
    }
    
}
