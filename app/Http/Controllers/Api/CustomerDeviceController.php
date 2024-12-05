<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerDevice;
use App\Models\Customer;
use App\Models\VehicleInstallationPhoto;


class CustomerDeviceController extends Controller
{
    public function getDevices()
    {


        $userId = auth('sanctum')->user()->id;

       $customerId = Customer::where('user_id',$userId)->pluck('id')->first();

        $userDevices = CustomerDevice::select('customer_devices.customer_id', 'devices.id','devices.device_id as deviceId')
                    ->leftJoin('devices', 'customer_devices.device_id', '=', 'devices.id')
                    ->where('customer_devices.customer_id', $customerId)->get();
       
        return response()->json([
            'userDevices' => $userDevices,
        ]);

    }

    public function getDevicesByCustomer($customerId){

        $devices = Customer::select('devices.id', 'devices.device_id')
                    ->join('customer_devices','customers.id', '=', 'customer_devices.customer_id')
                    ->join('devices','customer_devices.device_id', '=', 'devices.id')
                    ->where('customers.id', $customerId)
                    ->get();

        return response()->json([
            'devices' => $devices,
        ]);
    }

    public function addInstalltionPhoto(Request $request){
        $thisData   =	$request->all();
		$validator  = Validator::make($thisData,
			array(
                'vehicle_id' => 'required',
                'image' => 'required'
            )
		);

		if ($validator->fails()) {
            return $this->sendError("Validation failed.", $validator->errors());
		}

        $gallery = new VehicleInstallationPhoto;
        $gallery->vehicle_id = $request->input("vehicle_id");
        if($request->hasFile('image')){
            $file = $request->file('image');
            $path = $file->store('vehicleInstallation', 'public');
            $imageUrl = Storage::url($path);
            $gallery->photo_path	=	$imageUrl;
        }
        $gallery->save();

        if ($gallery) {
            $data = [
                    'id' => $gallery->id ?? '0',
                    'vehicle_id' => $gallery->vehicle_id ?? '',
                    'photo_path' => $gallery->photo_path ? asset($gallery->photo_path) : '',
                    ];

            return response()->json([
                'data' => $data,
            ]);
        } else {
            return $this->sendError("Something Went Wrong.");
        }
    }
}
