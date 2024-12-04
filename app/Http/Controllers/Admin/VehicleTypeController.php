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
        $customers = Customer::all();
        return Inertia::render('Vehicle/Create',[
            'devices' => $devices,
            'customers' => $customers,
        ]);
    }

    public function store(Request $request){

        $validated = $request->validate([
            'vehicleRegisterNumber' => 'required',
            'customerId' => 'required',
            'deviceId' =>  'required',
            'imeiNumber' => 'required',
            'simCardNumber' => 'required',
        ], [
            'vehicleRegisterNumber' => 'Vehicle Register Number id required',
            'customerId.required' => 'Customer Name is required.',
            'deviceId.required' => 'Device is required.',
            'imeiNumber.required' => 'IMEI Number is required.',
            'simCardNumber.required' => 'SIM Card Number is required.',
  
        ]);

        if (!$validated) {
            return response()->json(["message" => $validated]);
        }

        // Create a new Vehicle
        $vehicle = Vehicle::create([
            'customer_id' => $request->input('customerId'),
            'device_id' => $request->input('deviceId'),
            'vehicle_register_number' => $request->input('vehicleRegisterNumber'),
            'vehicle_name' => $request->input('vehicleName'),
            'vehicle_type' => $request->input('vehicleType'),
            'imei_number' => $request->input('imeiNumber'),
            'sim_card_number' => $request->input('simCardNumber'),
            'installation_date' => $request->input('installationDate'),
            'start_date' => $request->input('startDate'),
            'duration' => $request->input("duration"),
            'sim_operator' => $request->input('simOperator'),
        ]);

        if($vehicle) {
            return response()->json(["message" => 'Vehicle added successfully.']);
        } else {
            return response()->json(['message' => 'An error occurred while adding the vehicle.']);
        }
    }

    public function view($id) {


        $data = Vehicle::select('vehicles.*','customers.first_name', 'customers.last_name', 'devices.device_id as deviceName')
                    ->join('customers', 'vehicles.customer_id', '=', 'customers.id')
                    ->join('devices', 'vehicles.device_id', '=', 'devices.id')
                    ->where('vehicles.id', $id)
                    ->first();

        $vehicleDetails = [
            'id' => $data->id ?? 0,
            'vehicle_register_number' => $data->vehicle_register_number ?? '--',
            'vehicle_name' => $data->vehicle_name ?? '--',
            'vehicle_type' => $data->vehicle_type ?? '--',
            'imei_number' => $data->imei_number ?? '--',
            'sim_card_number' => $data->sim_card_number ?? '--',
            'installation_date' => $data->installation_date ?? '',
            'start_date' => $data->start_date ?? '--',
            'duration' => $data->duration ?? '--',
            'sim_operator' => $data->sim_operator ?? '--',
            'first_name' => $data->first_name ?? '--',
            'last_name' => $data->last_name ?? '--',
            'deviceName' => $data->deviceName ?? '--',
   
        ];

        return Inertia::render('Vehicle/View',[
            'Vehicles' => $vehicleDetails,
        ]);
    }

    public function edit($id) {

        $customers = Customer::all();
        $devices = Device::all();
        $data = Vehicle::find($id);
   
        if (!$data) {
            return response()->json(["message" => 'Vehicle not found.']);
        }

        $vehicleDetail = [
            'id'   => $data->id ?? 0,
            'customer_id' => $data->customer_id ?? 0,
            'device_id' => $data->device_id ?? 0,
            'vehicle_register_number' => $data->vehicle_register_number ?? '',
            'vehicle_name' => $data->vehicle_name ?? '',
            'vehicle_type' => $data->vehicle_type ?? '',
            'imei_number' => $data->imei_number ?? '',
            'sim_card_number' => $data->sim_card_number ?? '',
            'installation_date' => $data->installation_date ?? '',
            'start_date' => $data->start_date ?? '',
            'duration' => $data->duration ?? '',
            'sim_operator' => $data->sim_operator ?? '',
        ];


        // $userId = Auth::user()->id;
        // $customerId = Customer::where('user_id', $userId)->pluck('id')->first();

        // $assignedCustomerDevices = CustomerDevice::where('customer_id', $customerId)->pluck('device_id')->toArray(); 
       
        // $useVehicleDevice = VehicleDevice::where('vehicle_id', $id)->pluck('device_id')->toArray(); 

        // $assignedVehicleDevices = VehicleDevice::pluck('device_id')->toArray(); 

        // $devices = Device::whereIn('id', $assignedCustomerDevices)
        //                 ->when($id, function ($query) use ($id, $assignedVehicleDevices, $useVehicleDevice) {
        //                     return $query->where(function ($query) use ($assignedVehicleDevices, $useVehicleDevice) {
        //                         $query->whereNotIn('id', $assignedVehicleDevices)
        //                             ->orWhereIn('id', $useVehicleDevice);
        //                     });
        //                 })
        //                 ->select('id', 'device_id')
        //                 ->get();

        return Inertia::render('Vehicle/Edit',[
            'devices' => $devices,
            'customers' => $customers,
            'vehicleDetail' => $vehicleDetail,
        ]);
    }

    public function update(Request $request, $id) {

        $validated = $request->validate([
            'customer_id' => 'required',
            'device_id' => 'required',
            'vehicle_register_number' => 'required',
        ], [
            'customer_id.required' => 'Customer Name is required.',
            'device_id.required' => 'Device is required.',
            'vehicle_register_number.required' => 'Vehicle Register Number is required.',
        ]);

        if (!$validated) {
        
            return response()->json(["message" => $validated]);
        }

        $vehicle = Vehicle::where('id',$id)->first();

        if($vehicle) {

            $vehicle->customer_id = $request->input("customer_id");
            $vehicle->device_id = $request->input("device_id");
            $vehicle->vehicle_register_number = $request->input('vehicle_register_number');
            $vehicle->vehicle_name = $request->input("vehicle_name");
            $vehicle->vehicle_type  = $request->input("vehicle_type");
            $vehicle->imei_number  = $request->input("imei_number");
            $vehicle->sim_card_number  = $request->input("sim_card_number");
            $vehicle->installation_date  = $request->input("installation_date");
            $vehicle->start_date  = $request->input("start_date");
            $vehicle->duration  = $request->input("duration");
            $vehicle->sim_operator  = $request->input("sim_operator");
            $vehicle->save();

            // Redirect with success message
            return response()->json(["message" => 'Vehicle updated successfully.']);

        } else{

            return response()->json(['error' => 'An error occurred while deleting the Vehicle..']);
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
