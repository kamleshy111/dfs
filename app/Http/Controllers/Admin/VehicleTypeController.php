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
use App\Models\VehicleInstallationPhoto;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VechicleImport;
use Carbon\Carbon;


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

        $customers = Customer::all();
        return Inertia::render('Vehicle/Create',[
            'customers' => $customers,
        ]);
    }

    public function store(Request $request){

        $validated = $request->validate([
            'customerId' => 'required',
            'vehicleType' => 'required',
            'vehicleName' => 'required',
            'vehicleRegisterNumber' => 'required',
            'simCardNumber' => 'required',
            'imeiNumber' => 'required',
        ], [
            'customerId.required' => 'Customer Name is required.',
            'vehicleRegisterNumber' => 'Vehicle Register Number id required',
            'vehicleType.required' => 'Vehicle Type is required.',
            'vehicleName' => 'Vehicle Name is required.',
            'simCardNumber.required' => 'SIM Card Number is required.',
            'imeiNumber.required' => 'IMEI Number is required.',
        ]);

        if (!$validated) {
            return response()->json(["message" => $validated]);
        }

        // Create a new Vehicle
        $vehicle = Vehicle::create([
            'customer_id' => $request->input('customerId'),
            'device_id' => $request->input('deviceId') ?? 0,
            'vehicle_register_number' => $request->input('vehicleRegisterNumber'),
            'vehicle_name' => $request->input('vehicleName') ?? '',
            'vehicle_type' => $request->input('vehicleType'),
            'imei_number' => $request->input('imeiNumber'),
            'sim_card_number' => $request->input('simCardNumber'),
            'installation_date' => $request->input('installationDate')  ? Carbon::parse($request->input('installationDate'))->toDateString() : null,
            'start_date' => $request->input('startDate')  ? Carbon::parse($request->input('startDate'))->toDateString() : null,
            'duration' => $request->input("duration") ?? '',
            'sim_operator' => $request->input('simOperator') ?? '',
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
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'deviceName' => $data->deviceName ?? '--',
   
        ];

        return Inertia::render('Vehicle/View',[
            'Vehicles' => $vehicleDetails,
        ]);
    }

    public function photos($id){

        $vehicleInstalltionPhotos = VehicleInstallationPhoto::where('vehicle_id', $id)->get();

        return Inertia::render('Vehicle/InstalltionPhoto',[
            'vehicleInstalltionPhotos' => $vehicleInstalltionPhotos,
            'vehicleId' => $id,
        ]);
    }

    public function edit($id) {

        $customers = Customer::all();
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

        return Inertia::render('Vehicle/Edit',[
            'customers' => $customers,
            'vehicleDetail' => $vehicleDetail,
        ]);
    }

    public function update(Request $request, $id) {

        $validated = $request->validate([
            'customer_id' => 'required',
            'vehicle_type' => 'required',
            'vehicle_name' => 'required',
            'vehicle_register_number' => 'required',
            'sim_card_number' => 'required',
            'imei_number' => 'required',
        ], [
            'customer_id.required' => 'Customer Name is required.',
            'vehicle_register_number' => 'Vehicle Register Number id required',
            'vehicle_type.required' => 'Vehicle Type is required.',
            'vehicle_name' => 'Vehicle Name is required.',
            'sim_card_number.required' => 'SIM Card Number is required.',
            'imei_number.required' => 'IMEI Number is required.',
        ]);

        if (!$validated) {
        
            return response()->json(["message" => $validated]);
        }

        $vehicle = Vehicle::where('id',$id)->first();

        if($vehicle) {

            $vehicle->customer_id = $request->input("customer_id");
            $vehicle->device_id = $request->input("device_id") ?? 0;
            $vehicle->vehicle_register_number = $request->input('vehicle_register_number');
            $vehicle->vehicle_name = $request->input("vehicle_name");
            $vehicle->vehicle_type  = $request->input("vehicle_type");
            $vehicle->imei_number  = $request->input("imei_number");
            $vehicle->sim_card_number  = $request->input("sim_card_number");
            $vehicle->installation_date  = $request->input("installation_date") ? Carbon::parse($request->input('installation_date'))->toDateString() : null;
            $vehicle->start_date  = $request->input("start_date") ? Carbon::parse($request->input('start_date'))->toDateString() : null;
            $vehicle->duration  = $request->input("duration") ?? '';
            $vehicle->sim_operator  = $request->input("sim_operator") ?? '';
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
