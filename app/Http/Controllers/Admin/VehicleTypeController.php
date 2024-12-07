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
use Illuminate\Support\Facades\Storage;
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
            'duration' => 'required_with:durationUnit',
            'durationUnit' => 'required_with:duration',
           
        ], [
            'customerId.required' => 'Customer Name is required.',
            'vehicleRegisterNumber' => 'Vehicle Register Number id required',
            'vehicleType.required' => 'Vehicle Type is required.',
            'vehicleName' => 'Vehicle Name is required.',
            'simCardNumber.required' => 'SIM Card Number is required.',
            'imeiNumber.required' => 'IMEI Number is required.',
            'duration.required' => 'durationUnit is required.',
            'durationUnit.required' => 'duration is required.',
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
            'duration-unit' => $request->input("durationUnit") ?? '',
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

        $installationPhoto = VehicleInstallationPhoto::where('vehicle_id', $id)
                            ->pluck('photo_path')
                            ->map(function ($photoPath) {
                                return asset($photoPath);
                            });

                    if($data->duration_unit == 'days'){
                       $startDate =  Carbon::parse($data->start_date);
                       $duration = (int) $data->duration;
                       $expirationDate = $startDate->addDays($duration); 

                       $formattedExpirationDate = $expirationDate->format('d-m-Y'); 
                    }

                    if($data->duration_unit == 'months'){
                        $startDate = Carbon::parse($data->start_date);
                        $duration = (int) $data->duration;
                        $expirationDate = $startDate->addMonths($duration);

                        $formattedExpirationDate = $expirationDate->format('d-m-Y'); 
                    }

                    if($data->duration_unit == 'years'){
                        $startDate = Carbon::parse($data->start_date);
                        $duration = (int) $data->duration;
                        $expirationDate = $startDate->addYears($duration);

                        $formattedExpirationDate = $expirationDate->format('d-m-Y'); 
                    }
                   

             $startDate =     Carbon::parse($data->start_date)->format('d-m-Y');

               $installationDate =     Carbon::parse($data->installation_date)->format('d-m-Y');
     

        $vehicleDetails = [
            'id' => $data->id ?? 0,
            'vehicle_register_number' => $data->vehicle_register_number ?? '--',
            'vehicle_name' => $data->vehicle_name ?? '--',
            'vehicle_type' => $data->vehicle_type ?? '--',
            'imei_number' => $data->imei_number ?? '--',
            'sim_card_number' => $data->sim_card_number ?? '--',
            'installation_date' => $installationDate ?? '',
            'start_date' => $startDate ?? '--',
            'duration' => $data->duration ?? '--',
            'expirationDate' => $formattedExpirationDate ?? '--', 
            'sim_operator' => $data->sim_operator ?? '--',
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'deviceName' => $data->deviceName ?? '--',
   
        ];

        return Inertia::render('Vehicle/View',[
            'Vehicles' => $vehicleDetails,
            'installationPhotos' => $installationPhoto,
        ]);
    }

    public function photos($id){

        $data = VehicleInstallationPhoto::where('vehicle_id', $id)->get();

        $vehicleInstalltionPhotos = $data->map(function($item) {
            return [
                'id' => $item->id ?? 0,
                'vehicle_id' => $item->vehicle_id ?? 0,
                'photo_path' => $item->photo_path ? asset($item->photo_path) : '',
            ];
        });


        return Inertia::render('Vehicle/InstalltionPhoto',[
            'vehicleInstalltionPhotos' => $vehicleInstalltionPhotos,
            'vehicleId' => $id,
        ]);
    }

    public function uploadInstallationPhoto(Request $request){

        // Handle file upload
        $imageUrl = null;
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $path = $file->store('vehicle_installation_photo', 'public');
            $imageUrl = Storage::url($path);
        }

        VehicleInstallationPhoto::create([
            'vehicle_id' => $request->vehicle_id,
            'photo_path' => $imageUrl,
        ]);

        return response()->json(['message' => 'Vehicle Installation Photo added successfully!']);
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
            'duration_unit' => $data->duration_unit ?? '',
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
            'duration' => 'required_with:duration_unit',
            'duration_unit' => 'required_with:duration',
        ], [
            'customer_id.required' => 'Customer Name is required.',
            'vehicle_register_number' => 'Vehicle Register Number id required',
            'vehicle_type.required' => 'Vehicle Type is required.',
            'vehicle_name' => 'Vehicle Name is required.',
            'sim_card_number.required' => 'SIM Card Number is required.',
            'imei_number.required' => 'IMEI Number is required.',
            'duration.required' => 'duration_unit is required.',
            'duration_unit.required' => 'duration is required.',
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
            $vehicle->duration_unit = $request->input("duration_unit") ?? '';
            $vehicle->sim_operator  = $request->input("sim_operator") ?? '';
            $vehicle->save();

            // Redirect with success message
            return response()->json(["message" => 'Vehicle updated successfully.']);

        } else{

            return response()->json(['error' => 'An error occurred while deleting the Vehicle..']);
        }
    }

    public function deleteInstallationPhoto($id){

        $photo = VehicleInstallationPhoto::findOrFail($id);
        if($photo){
            $photo->delete();
            return response()->json(["message" => 'Vehic leInstallation Photo deleted successfully.']);
        }else{
            return response()->json(["message" => 'An error occurred while deleting the Vehicle..']);
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
