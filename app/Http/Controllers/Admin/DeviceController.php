<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Device;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DevicesImport;



class DeviceController extends Controller
{
    public function index(){

        // $devices = Device::paginate(10);


        $devices = Device::select('devices.id', 'devices.device_id', 'devices.order_id', 'devices.date_time', 'customers.first_name', 'customers.last_name')
        ->leftJoin('customer_devices','devices.id', '=', 'customer_devices.device_id')
        ->leftjoin('customers','customer_devices.customer_id', '=', 'customers.id')
        ->paginate(10);


        return Inertia::render('Device/Device',[
            'user' => Auth::user(),
            'devices' => $devices,
        ]);
    }

    public function create(){
        return Inertia::render('Device/Create');
    }

    
    public function uploadDevice(Request $request){


        if ($request->file('file')) {
            Excel::import(new DevicesImport, $request->file('file'));
        }
    }

    public function store(Request $request){

        $validated = $request->validate([
            'deviceId' => 'required|string|max:255|unique:devices,device_id',
            'orderId' => 'required|numeric',
            'purchaseDate' => 'required|date',
        ], [
            'deviceId.unique' => 'The device ID must be unique. Please choose a different ID.',
            'deviceId.required' => 'Device ID is required.',
            'orderId.required' => 'Order ID is required.',
            'purchaseDate.required' => 'Purchase Date is required.',
        ]);

        if (!$validated) {
            // Return validation errors as a JSON response
            return response()->json(["message" => $validated]);
        }

        // Handle file upload
        $imageUrl = null;
        if($request->hasFile('invoicePhotos')){
            $file = $request->file('invoicePhotos');
            $path = $file->store('InvoicePhotos', 'public');
            $imageUrl = Storage::url($path);
        }    
       
        // Create a new Device
        Device::create([
            'device_id' => $request->input('deviceId'),
            'order_id' => $request->input('orderId'),
            'company_name' => $request->input('companyName') ?? '',
            'date_time' => $request->input('purchaseDate'),
            'invoice_photos' => $imageUrl,
        ]);

        return response()->json(['message' => 'Device added successfully!']);
    }


    public function view($id) {

        $data = Device::where('id',$id)->first();

        $deviceDetails = [
            'id' => $data->id ?? 0,
            'device_id' => $data->device_id ?? '--',
            'order_id' => $data->order_id ?? 0,
            'company_name' => $data->company_name ?? '--',
            'date_time' => $data->date_time ?? '--',
            'invoice_photos' => $data->invoice_photos ? asset($data->invoice_photos) : '',
        ];

        return Inertia::render('Device/View',[
            'devices' => $deviceDetails,
        ]);

    }

    public function edit($id) {

        $data = Device::where('id',$id)->first();
   
        if (!$data) {
            return response()->json(["message" => 'Device not found.']);
        }

        $deviceDetail = [
            'id'   => $data->id ?? 0,
            'device_id' => $data->device_id ?? '',
            'order_id' => $data->order_id ?? '',
            'company_name' => $data->company_name ?? '',
            'date_time' => $data->date_time ?? '',
            'invoice_photos' => $data->invoice_photos ? asset($data->invoice_photos) : '',
        ];

        return Inertia::render('Device/Edit',[
            'deviceDetail' => $deviceDetail,
        ]);
    }

    public function update(Request $request, $id) {

        $validated = $request->validate([
            'device_id' => 'required|string|max:255|unique:devices,device_id,' . $id,
        ], [
            'device_id.unique' => 'The device ID must be unique. Please choose a different ID.',
            'device_id.required' => 'Device ID is required.',
        ]);

        if (!$validated) {
            // Return validation errors as a JSON response
            return response()->json(["message" => $validated]);
        }

        $device = Device::where('id',$id)->first();
        $device->device_id = $request->input("device_id");
        $device->order_id = $request->input("order_id");
        $device->company_name = $request->input("company_name");
        $device->date_time  = $request->input("date_time");
        if($request->hasFile('invoice_photos')){
            $file = $request->file('invoice_photos');
            $path = $file->store('InvoicePhotos', 'public');
            $device->invoice_photos = Storage::url($path);
        }   
        $device->save();

        return response()->json(['message' => 'Device updated successfully.']);
    }

    public function destroy($id)
    {
         $devicwe = Device::findOrFail($id);
        if($devicwe){
            $devicwe->delete();
            return response()->json(["message" => 'Devicwe deleted successfully.']);
        }
        return response()->json(['message' => 'An error occurred while deleting the device.']);
    }
}
