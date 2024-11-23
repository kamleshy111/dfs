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
            'companyName' => 'required',
            'model' => 'required',
            'fuelType'=> 'required',
            'chassisNumber' => 'required',
            'color' => 'required',
        ]);

            
        // Create a new Vehicle
        $vehicle = Vehicle::create([
            'company_name' => $validatedData['companyName'],
            'model' => $validatedData['model'],
            'fuel_type' => $validatedData['fuelType'],
            'Chassis_number' => $validatedData['chassisNumber'],
            'color' => $validatedData['color'],
        ]);
            
        if($vehicle){
            return redirect()->route('vehicle-type')->with('success', 'Vehicle added successfully.');
        } else {
            return back()->with('error', 'An error occurred while adding the device.');
        }

    }

    public function edit($id) {

        $data = Vehicle::where('id',$id)->first();
   
        if (!$data) {
            return redirect()->route('vehicle-type')->with('error', 'Vehicle not found.');
        }

        $vehicleDetail = [
            'id'   => $data->id ?? 0,
            'company_name' => $data->company_name ?? '',
            'model' => $data->model ?? '',
            'fuel_type' => $data->fuel_type ?? '',
            'Chassis_number' => $data->Chassis_number ?? '',
            'color' => $data->color ?? '',
        ];

        return Inertia::render('Vehicle/Edit',[
            'vehicleDetail' => $vehicleDetail,
        ]);
    }

    public function update(Request $request, $id) {

        $validatedData = $request->validate([
            'company_name' => 'required',
            'model' => 'required',
            'fuel_type' => 'required',
            'Chassis_number'=> 'required',
            'color' => 'required',
        ]);


        $vehicle = Vehicle::where('id',$id)->first();
        $vehicle->company_name = $validatedData["company_name"];
        $vehicle->model = $validatedData["model"];
        $vehicle->fuel_type = $validatedData["fuel_type"];
        $vehicle->Chassis_number  = $validatedData["Chassis_number"];
        $vehicle->color  = $validatedData["color"];
        $vehicle->save();

        if($vehicle){
            return redirect()->route('vehicle-type')->with([
                'success' => 'Vehicle updated successfully.',
            ]);
        }else{
            return redirect()->route('vehicle-type')->with([
                'error' => 'Vehicle ID not found..',
            ]);
        }

    }

}
