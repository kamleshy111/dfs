<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Vehicle;

class VehicleTypeController extends Controller
{
    public function index(){

        $vehicles = Vehicle::paginate(10);
        return Inertia::render('Vehicle/Vehicle',[
            'user' => Auth::user(),
            'vehicles' => $vehicles,
        ]);
    }

    public function create(){
        return Inertia::render('Vehicle/Create');
    }

    public function store(Request $request){

        $validatedData = $request->validate([
            'company_name' => 'required',
            'model' => 'required',
            'fuel_type'=> 'required',
            'Chassis_number' => 'required',
            'color' => 'required',
        ]);

            
        // Create a new Vehicle
        $vehicle = Vehicle::create([
            'company_name' => $validatedData['company_name'],
            'model' => $validatedData['model'],
            'fuel_type' => $validatedData['fuel_type'],
            'Chassis_number' => $validatedData['Chassis_number'],
            'color' => $validatedData['color'],
        ]);
            
        if($vehicle){
            return redirect()->route('devices')->with('success', 'Vehicle added successfully.');
        } else {
            return back()->with('error', 'An error occurred while adding the device.');
        }

    }

}
