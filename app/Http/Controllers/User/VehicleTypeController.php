<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\CustomerDevice;
use App\Models\VehicleDevice;


class VehicleTypeController extends Controller
{
    public function index(){

        $vehicles = Vehicle::paginate(10);
        return Inertia::render('User/Vehicle/Vehicle',[
            'user' => Auth::user(),
            'vehicles' => $vehicles,
        ]);
    }

    public function create(){
            $userId = Auth::user()->id;
     
        $devices = CustomerDevice::select('devices.id as device_id', 'devices.device_id as deviceName')
                    ->leftJoin('devices','customer_devices.device_id', '=', 'devices.id')
                    ->where('customer_id', $userId)->get();
   
        return Inertia::render('User/Vehicle/Create',[
            'devices' => $devices,
        ]);
    }

    public function store(Request $request){

        // $data = $request->all();
        // dd($data);
        $validatedData = $request->validate([
            'companyName' => 'required',
            'model' => 'required',
            'fuelType'=> 'required',
            'chassisNumber' => 'required',
            'color' => 'required',
            'device_id' => 'array', 
        ]);

        /*------- Start DB Transaction --------*/
        DB::beginTransaction();

        try {

            
            // Create a new Vehicle
            $vehicle = Vehicle::create([
                'company_name' => $validatedData['companyName'],
                'model' => $validatedData['model'],
                'fuel_type' => $validatedData['fuelType'],
                'Chassis_number' => $validatedData['chassisNumber'],
                'color' => $validatedData['color'],
            ]);

            $vehicleId = $vehicle->id;
            $devices = $validatedData["device_id"];

            foreach ($devices as $device) {
                $deviceId = $device['device_id'];
                VehicleDevice::create([
                    'vehicle_id' => $deviceId,
                    'device_id' => $vehicleId,
                ]);
            }

            DB::commit();

            // Redirect with success message
            return redirect()->route('vehicle-type.create')->with(['success' => 'Vehicle added successfully.']);
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
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

        return Inertia::render('User/Vehicle/Edit',[
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

    public function destroy($id)
    {
         $vehicle = Vehicle::findOrFail($id);
        if($vehicle){
            $vehicle->delete();
            return redirect()->route('vehicle-type')->with(['success' => 'Vehicle deleted successfully.']);
        }else{
            return back()->withErrors(['error' => 'An error occurred while deleting the Vehicle.']);
        }
    }

}
