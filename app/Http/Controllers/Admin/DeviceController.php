<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index(){

        $devices = Device::paginate(10);
        return Inertia::render('Device/Device',[
            'user' => Auth::user(),
            'devices' => $devices,
        ]);
    }

    public function create(){
        return Inertia::render('Device/Create');
    }

    public function store(Request $request){

        $validatedData = $request->validate([
            'deviceId' => 'required',
            'orderId' => 'required',
            'companyName'=> 'required',
            'purchaseDate' => 'required|date',
        ]);

        $data = Device::where('device_id', $validatedData['deviceId'])->first();

        if(empty($data)){
            
       
            // Create a new Device
            $device = Device::create([
                'device_id' => $validatedData['deviceId'],
                'order_id' => $validatedData['orderId'],
                'company_name' => $validatedData['companyName'],
                'date_time' => $validatedData['purchaseDate'],
            ]);
                
            if($device){
                return redirect()->route('devices')->with('success', 'Device added successfully.');
            } else {
                return back()->with('error', 'An error occurred while adding the device.');
            }
        } else {
            return redirect()->route('devices.create')->with('error', 'device id must be unique.');
        }

    }


    public function view($id) {

        $data = Device::where('id',$id)->first();

        $deviceDetails = [
            'id' => $data->id ?? 0,
            'device_id' => $data->device_id ?? '--',
            'order_id' => $data->order_id ?? 0,
            'company_name' => $data->company_name ?? '--',
            'date_time' => $data->date_time ?? '--',
        ];

        return Inertia::render('Device/View',[
            'devices' => $deviceDetails,
        ]);

    }

    public function edit($id) {

        $data = Device::where('id',$id)->first();
   
        if (!$data) {
            return redirect()->route('devices')->with('error', 'Device not found.');
        }

        $deviceDetail = [
            'id'   => $data->id ?? 0,
            'device_id' => $data->device_id ?? '',
            'order_id' => $data->order_id ?? '',
            'company_name' => $data->company_name ?? '',
            'date_time' => $data->date_time ?? '',
        ];

        return Inertia::render('Device/Edit',[
            'deviceDetail' => $deviceDetail,
        ]);
    }

    public function update(Request $request, $id) {

        $validatedData = $request->validate([
            'device_id' => 'required',
            'order_id' => 'required',
            'company_name'=> 'required',
            'date_time' => 'required|date',
        ]);


        $device = Device::where('id',$id)->first();
        $device->device_id = $validatedData["device_id"];
        $device->order_id = $validatedData["order_id"];
        $device->company_name = $validatedData["company_name"];
        $device->date_time  = $validatedData["date_time"];
        $device->save();

        if($device){
            return redirect()->route('devices')->with([
                'success' => 'Device updated successfully.',
            ]);
        }else{
            return redirect()->route('devices')->with([
                'error' => 'Device ID not found..',
            ]);
        }

    }
}
