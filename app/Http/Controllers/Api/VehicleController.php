<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\Customer;

class VehicleController extends Controller
{
    public function getVehicles(){

        $user = Auth::user();
        

        $customer = Customer::where('user_id', $user->id)->first();


        $vehicles = Vehicle::select('id', 'vehicle_register_number', 'customer_id')
        ->when($user && $user->role === 'user' && $customer, function ($query) use ($customer) {
            return $query->where('customer_id', $customer->id);
        })
        ->get();

        $getVeches = $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'vehicle_register_number' => $vehicle->vehicle_register_number,
            ];
        });

  

        return response()->json([
            'vehicles' => $getVeches,
        ]);
    }
}
