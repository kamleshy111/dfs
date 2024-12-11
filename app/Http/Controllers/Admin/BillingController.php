<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Vehicle;



class BillingController extends Controller
{

    public function index() {

        $data = Vehicle::select('vehicles.*','customers.first_name', 'customers.last_name', 'devices.device_id as deviceName')
                    ->join('customers', 'vehicles.customer_id', '=', 'customers.id')
                    ->join('devices', 'vehicles.device_id', '=', 'devices.id')
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
                'customerName' => ($item->first_name ?? '') . ' ' . ($item->last_name ?? ''),
                'deviceName' => $item->deviceName ?? '',
                'startData' => $startDate ?? '',
                'duration' => ($item->duration ?? '') . ' ' . ($item->durationUnit ?? ''),
                'expiresDate' => $formattedExpirationDate ?? '',  
            ];
        });

        // dd($devices);
        return Inertia::render('Billing/Billing',[
            'devices' => $devices->toArray()
        ]);
    }
}
