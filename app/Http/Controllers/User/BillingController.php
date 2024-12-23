<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Vehicle;

class BillingController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;

        $customerId = Customer::where('user_id', $userId)->value('id');
  
        $data = Vehicle::select('vehicles.*', 'customers.first_name', 'customers.last_name', 'devices.device_id as deviceName')
            ->join('customers', 'vehicles.customer_id', '=', 'customers.id')
            ->join('devices', 'vehicles.device_id', '=', 'devices.id')
            ->where('customer_id', $customerId)
            ->get();

        $devices = $data->map(function($item) {

        if($item->duration_unit == 'days'){
            $startDate =  Carbon::parse($item->start_date);
            $duration = (int) $item->duration;
            $expirationDate = $startDate->addDays($duration); 

            $formattedExpirationDate = $expirationDate->format('d-m-Y'); 
        }

        if($item->duration_unit == 'months'){
            $startDate = Carbon::parse($item->start_date);
            $duration = (int) $item->duration;
            $expirationDate = $startDate->addMonths($duration);

            $formattedExpirationDate = $expirationDate->format('d-m-Y'); 
        }

        if($item->duration_unit == 'years'){
            $startDate = Carbon::parse($item->start_date);
            $duration = (int) $item->duration;
            $expirationDate = $startDate->addYears($duration);

            $formattedExpirationDate = $expirationDate->format('d-m-Y'); 
        }

        $startDate =     Carbon::parse($item->start_date)->format('d-m-Y');
        return [
            'id' => $item->id ?? 0,
            'vehicleNumber' => ($item->vehicle_register_number ?? ''),
            'deviceName' => $item->deviceName ?? '',
            'startData' => $startDate ?? '',
            'duration' => ($item->duration ?? '') . ' ' . ($item->duration_unit ?? ''),
            'expiresDate' => $formattedExpirationDate ?? '',  
        ];
        });

        $expirationData = Vehicle::select('vehicles.start_date','vehicles.duration','vehicles.duration_unit') 
                        ->where('vehicles.customer_id', $customerId)
                        ->get();

        $expirationVehicles = $expirationData->map(function ($data) {
            
            $startDate = Carbon::parse($data->start_date);
            $duration = (int) $data->duration;
    
            switch ($data->duration_unit) {
                case 'days':
                    $expiration = $startDate->addDays($duration);
                    break;
                case 'months':
                    $expiration = $startDate->addMonths($duration);
                    break;
                case 'years':
                    $expiration = $startDate->addYears($duration);
                    break;
                default:
                    return null;
            }
    
        
            $currentDate = now()->addDay(5);
     
            if ($currentDate >= $expiration) {
                return [
                    'id' => $data->id,
                    'startDate' => $startDate->format('d-m-Y'),
                    'expirationDate' => $expiration->format('d-m-Y'),
                ];
            }
    
            return null;
        })->filter();



        return Inertia::render('User/Billing/Billing',[
            'devices' => $devices->toArray(),
            'expirationVehicles' => $expirationVehicles->values()->toArray(),
        ]);

    }
}
