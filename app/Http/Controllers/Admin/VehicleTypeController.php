<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\CustomerDevice;
use App\Models\VehicleDevice;
use App\Models\Customer;
use App\Models\Device;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VechicleImport;


class VehicleTypeController extends Controller
{
    public function index(){

        $vehicles = Vehicle::paginate(10);
        return Inertia::render('Vehicle/Vehicle',[
            'user' => Auth::user(),
            'vehicles' => $vehicles,
        ]);

    }

    public function uploadVehicle(Request $request){

        $userId = Auth::user()->id;

        $customer = Customer::where('user_id', $userId)->select('id')->first();
        $customerId = $customer->id;

        if ($request->file('file')) {
            Excel::import(new VechicleImport($customerId), $request->file('file'));
        }
    }

    public function create(){
        // $userId = Auth::user()->id;

        // $customerId = Customer::where('user_id', $userId)->pluck('id')->first();

        // $assignedCustomerDevices = CustomerDevice::where('customer_id', $customerId)->pluck('device_id')->toArray(); 
        
        // $assignedVehicleDevices = VehicleDevice::pluck('device_id')->toArray(); 

        // $devices = Device::whereIn('id', $assignedCustomerDevices)
        //                 ->whereNotIn('id', $assignedVehicleDevices)
        //                 ->select('id as device_id', 'device_id as device_name') 
        //                 ->get();

        $devices = Device::select('id as device_id', 'device_id as device_name')->get(); 

        return Inertia::render('Vehicle/Create',[
            'devices' => $devices,
        ]);
    }

    public function store(Request $request){

        $validated = $request->validate([
            'companyName' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required',
            'fuelType' => 'required|string|max:255',
            'chassisNumber' => 'required|numeric',
        ], [
            'companyName.required' => 'Company Name is required.',
            'model.required' => 'Model is required.',
            'year.required' => 'Year is required.',
            'fuelType.required' => 'Fuel Type is required.',
            'chassisNumber.required' => 'Chassis Number is required.',
        ]);

        if (!$validated) {
        
            return response()->json(["message" => $validated]);
        }

        /*------- Start DB Transaction --------*/
        DB::beginTransaction();

        try {

            $userId = Auth::user()->id;

            $customer = Customer::where('user_id', $userId)->select('id')->first();
            $customerId = $customer->id;
            
            // Create a new Vehicle
            $vehicle = Vehicle::create([
                'customer_id' => $customerId,
                'company_name' => $request->input('companyName'),
                'model' => $request->input('model'),
                'year' => $request->input('year'),
                'fuel_type' => $request->input('fuelType'),
                'Chassis_number' => $request->input('chassisNumber'),
                'color' => $request->input("color"),
                'driver_name' => $request->input('driverName'),
                'license_number' => $request->input('licenseNumber'),
                'license_expiry_date' => $request->input('licenseExpiryDate'),
                'driver_contact' => $request->input('driverContact'),
                'driver_address' => $request->input('driverAddress'),
            ]);

            $vehicleId = $vehicle->id;

            $devices = $request->input('device_id', []);

            if($devices){
                foreach ($devices as $device) {
                    $deviceId = $device['device_id'];
                    VehicleDevice::create([
                        'vehicle_id' => $vehicleId, // Vehicle ID
                        'device_id' => $deviceId,   // Device ID
                    ]);
                }
            }

            DB::commit();

            // Redirect with success message
            return response()->json(["message" => 'Vehicle added successfully.']);
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()]);
        }

    }

    public function view($id) {

        $data = Vehicle::with('devices')->find($id);

        $vehicleDetails = [
            'id' => $data->id ?? '0',
            'company_name' => $data->company_name ?? '--',
            'model' => $data->model ?? '--',
            'year' => $data->year ?? '--',
            'fuel_type' => $data->fuel_type ?? '--',
            'Chassis_number' => $data->Chassis_number ?? '--',
            'color' => $data->color ?? '',
            'driver_name' => $data->driver_name ?? '--',
            'license_number' => $data->license_number ?? '--',
            'license_expiry_date' => $data->license_expiry_date ?? '--',
            'driver_contact' => $data->driver_contact ?? '--',
            'driver_address' => $data->driver_address ?? '--',
            'devices' => $data->devices->toArray(),
        ];
        return Inertia::render('User/Vehicle/View',[
            'Vehicles' => $vehicleDetails,
        ]);
    }

    public function edit($id) {

        $data = Vehicle::with('devices')->find($id);
   
        if (!$data) {
            return response()->json(["message" => 'Vehicle not found.']);
        }

        $vehicleDetail = [
            'id'   => $data->id ?? 0,
            'company_name' => $data->company_name ?? '',
            'model' => $data->model ?? '',
            'year' => $data->year ?? '',
            'fuel_type' => $data->fuel_type ?? '',
            'Chassis_number' => $data->Chassis_number ?? '',
            'driver_name' => $data->driver_name ?? '',
            'license_number' => $data->license_number ?? '',
            'license_expiry_date' => $data->license_expiry_date ?? '',
            'driver_contact' => $data->driver_contact ?? '',
            'color' => $data->color ?? '',
            'driver_address' => $data->driver_address ?? '',
            'device_id' => $data->devices->select('id','device_id')->toArray(),
        ];


        $userId = Auth::user()->id;
        $customerId = Customer::where('user_id', $userId)->pluck('id')->first();

        $assignedCustomerDevices = CustomerDevice::where('customer_id', $customerId)->pluck('device_id')->toArray(); 
       
        $useVehicleDevice = VehicleDevice::where('vehicle_id', $id)->pluck('device_id')->toArray(); 

        $assignedVehicleDevices = VehicleDevice::pluck('device_id')->toArray(); 

        $devices = Device::whereIn('id', $assignedCustomerDevices)
                        ->when($id, function ($query) use ($id, $assignedVehicleDevices, $useVehicleDevice) {
                            return $query->where(function ($query) use ($assignedVehicleDevices, $useVehicleDevice) {
                                $query->whereNotIn('id', $assignedVehicleDevices)
                                    ->orWhereIn('id', $useVehicleDevice);
                            });
                        })
                        ->select('id', 'device_id')
                        ->get();

        return Inertia::render('User/Vehicle/Edit',[
            'devices' => $devices,
            'vehicleDetail' => $vehicleDetail,
        ]);
    }

    public function update(Request $request, $id) {

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required',
            'fuel_type' => 'required|string|max:255',
            'Chassis_number' => 'required|numeric',
        ], [
            'company_name.required' => 'Company Name is required.',
            'model.required' => 'Model is required.',
            'year.required' => 'Year is required.',
            'fuel_type.required' => 'Fuel Type is required.',
            'Chassis_number.required' => 'Chassis Number is required.',
        ]);

        if (!$validated) {
        
            return response()->json(["message" => $validated]);
        }

        /*------- Start DB Transaction --------*/
        DB::beginTransaction();

        try {

            $vehicle = Vehicle::where('id',$id)->first();
            $vehicle->company_name = $request->input("company_name");
            $vehicle->model = $request->input("model");
            $vehicle->year = $request->input('year');
            $vehicle->fuel_type = $request->input("fuel_type");
            $vehicle->Chassis_number  = $request->input("Chassis_number");
            $vehicle->color  = $request->input("color");
            $vehicle->driver_name  = $request->input("driver_name");
            $vehicle->license_number  = $request->input("license_number");
            $vehicle->license_expiry_date  = $request->input("license_expiry_date");
            $vehicle->driver_contact  = $request->input("driver_contact");
            $vehicle->driver_address  = $request->input("driver_address");
            $vehicle->save();

            $vehicleId = $vehicle->id;
            $devices = $request->input('device_id', []);

            VehicleDevice::where('vehicle_id', $vehicleId)->delete();

            foreach ($devices as $device) {
                $deviceId = $device['id'];
                VehicleDevice::create([
                    'vehicle_id' => $vehicleId,
                    'device_id' => $deviceId, 
                ]);
            }

            DB::commit();

            // Redirect with success message
            return response()->json(["message" => 'Vehicle updated successfully.']);
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()]);
        }

    }

    public function destroy($id)
    {
         $vehicle = Vehicle::findOrFail($id);
        if($vehicle){
            VehicleDevice::where('vehicle_id',$id)->delete();
            $vehicle->delete();
            return response()->json(["message" => 'Vehicle deleted successfully.']);
        }else{
            return response()->json(["message" => 'An error occurred while deleting the Vehicle..']);
        }
    }

}
