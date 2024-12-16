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
use Carbon\Carbon;



class DeviceController extends Controller
{
    public function index(){

        // $devices = Device::paginate(10);


        $data = Device::select('devices.id', 'devices.device_id', 'devices.order_id', 'devices.date_time', 'customers.first_name', 'customers.last_name')
                        ->leftJoin('customer_devices','devices.id', '=', 'customer_devices.device_id')
                        ->leftjoin('customers','customer_devices.customer_id', '=', 'customers.id')
                        ->get();

        $devices = $data->map(function($item) {
            return [
                'id' => $item->id ?? 0,
                'deviceId' => $item->device_id ?? '',
                'orderId' => $item->order_id ?? '',
                'customerName' => ($item->first_name ?? '') . ' ' . ($item->last_name ?? ''),
               'startData' => \Carbon\Carbon::parse($item->start_data)->format('d-m-Y h:i a'),
            ];
        });

        return Inertia::render('Device/Device',[
            'user' => Auth::user(),
            'devices' => $devices->toArray(),
        ]);
    }

    public function create(){
        return Inertia::render('Device/Create');
    }


    public function importDevice(Request $request) {
        $file = $request->file('file');

        // Parse the Excel file
        $data = Excel::toArray([], $file)[0];

        // removing headings
        array_shift($data);

        $chunks = array_chunk($data, 10); // Divide data into chunks of 100

        return response()->json([
            'success' => true,
            'totalChunks' => count($chunks),
        ]);

        return response()->json([
            'message' => 'device Import',
            'totalChunks' => count($chunks),
        ]);
    }

    public function importChunk(Request $request) {

        $file = $request->file('file');
        $data = Excel::toArray([], $file)[0];
        array_shift($data);
        $chunks = array_chunk($data, 10);

        $chunkIndex = $request->input('chunkIndex');

        $errors = [];
        if (isset($chunks[$chunkIndex])) {
            $success_count = 0;
            $failed_count = 0;
            foreach ($chunks[$chunkIndex] as $row) {
                $thisData = [
                    "device_id" => (($row[0]) ? $row[0] : ''), //0 => "Device Id"
                    "order_id" => (($row[1]) ? $row[1] : ''), //1 => "Order Id"
                    "company_name" => (($row[2]) ? $row[2] : ''), //2 => "Company Name"
                    "date_time" => (($row[3]) ? $row[3] : ''), //3 => "data"
                    "invoicePhotos" => (($row[4]) ? $row[4] : ''), //4 => "Invoice Photos"
                ];

                $created_bool = $this->create_device($thisData, null);

                if( $created_bool ) {
                    $success_count++;
                    $errors[] = ["success" => 1];
                } else {
                    $failed_count++;
                    $errors[] = ["success" => 0, "msg" => "Unable to import data."];
                }
            }

            return response()->json([
                'message' => 'Successfully imported!',
                'failed_count' => $failed_count,
                'success_count' => $success_count
            ]);
        }

        return response()->json([
            'message' => 'Unable to import the Devices!',
            'failed_count' => count($chunks[$chunkIndex]),
            'success_count' => 0
        ]);
    }

    private function create_device($thisData, $request = null ) {

            $date_string = $thisData["date_time"];
            $date_string = preg_replace('/\s\([^)]+\)$/', '', $date_string);
            $formatted_date = Carbon::parse($date_string)->format('Y-m-d H:i:s');

            
            $device = new Device;
            $device->device_id = $thisData["device_id"] ?? '';
            $device->order_id = $thisData["order_id"] ?? '';
            $device->company_name = $thisData["company_name"] ?? '';
            $device->date_time = $formatted_date ?? '';
            
            if (!empty($thisData["invoicePhotos"])) {

                $imageContent = @file_get_contents($thisData["invoicePhotos"]);
              
                $extension = pathinfo(parse_url($thisData["invoicePhotos"], PHP_URL_PATH), PATHINFO_EXTENSION);
                $filename = 'InvoicePhotos/' . uniqid() . '.' . $extension;
                Storage::disk('public')->put($filename, $imageContent);
                $storedImageUrl = Storage::url($filename);
                $device->invoice_photos = $storedImageUrl;
            }

            $device->save();
  
        if ($device) {
            return response()->json(['message' => 'Device added successfully!']);
        } else {
            return response()->json(['message' => 'An error occurred while adding the device.']);
        }
    }

    public function store(Request $request){

        $validated = $request->validate([
            'deviceId' => 'required|string|max:255|unique:devices,device_id',
            'orderId' => 'required|numeric',
            'purchaseDate' => 'required',
        ], [
            'deviceId.unique' => 'The device ID must be unique. Please choose a different ID.',
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

        $date_string = $request->input('purchaseDate');
        $date_string = preg_replace('/\s\([^)]+\)$/', '', $date_string);
        $formatted_date = Carbon::parse($date_string)->format('Y-m-d H:i:s');

        // Create a new Device
        Device::create([
            'device_id' => $request->input('deviceId'),
            'order_id' => $request->input('orderId'),
            'company_name' => $request->input('companyName') ?? '',
            'date_time' => $formatted_date,
            'invoice_photos' => $imageUrl,
        ]);

        return response()->json(['message' => 'Device added successfully!']);
    }


    public function view($id) {

        $data = Device::select('devices.*', 'customers.first_name', 'customers.last_name', 'vehicles.vehicle_register_number', 'vehicles.vehicle_type', 'vehicles.imei_number',
                                'vehicles.sim_card_number', 'vehicles.installation_date', 'vehicles.start_date', 'vehicles.duration', 'vehicles.duration_unit', 'vehicles.sim_operator')
                    ->leftJoin('customer_devices','devices.id', '=', 'customer_devices.device_id')
                    ->leftjoin('customers','customer_devices.customer_id', '=', 'customers.id')
                    ->leftjoin('vehicles','devices.id', '=', 'vehicles.device_id')
                    ->where('devices.id', $id)
                    ->first();

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

        $deviceDetails = [
            'id' => $data->id ?? 0,
            'device_id' => $data->device_id ?? '--',
            'order_id' => $data->order_id ?? 0,
            'company_name' => $data->company_name ?? '--',
            'date_time' =>  \Carbon\Carbon::parse($data->date_time)->format('d-m-Y') ?? '',
            'invoice_photos' => $data->invoice_photos ? asset($data->invoice_photos) : '',
            'customerFirstName' => $data->first_name ?? '',
            'customerLsatName' => $data->last_name ?? '',
            'vehicleRegisterNumber' => $data->vehicle_register_number ?? '',
            'vehicleType' => $data->vehicle_type ?? '',
            'imeiNumber' => $data->imei_number ?? '',
            'simCardNumber' => $data->sim_card_number ?? '',
            'installationDate' => \Carbon\Carbon::parse($data->installation_date)->format('d-m-Y') ?? '',
            'startDate' => \Carbon\Carbon::parse($data->start_data)->format('d-m-Y') ?? '',
            'duration' => $data->duration ?? '',
            'durationUnit' => $data->duration_unit ?? '',
            'expirationDate' => $formattedExpirationDate ?? '',
            'simOperator' => $data->sim_operator ?? '',

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
        $date_string = $request->input('date_time');
        $date_string = preg_replace('/\s\([^)]+\)$/', '', $date_string);
        $formatted_date = Carbon::parse($date_string)->format('Y-m-d H:i:s');

        $device = Device::where('id',$id)->first();
        $device->device_id = $request->input("device_id");
        $device->order_id = $request->input("order_id");
        $device->company_name = $request->input("company_name");
        $device->date_time  = $formatted_date;
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

    public function viewMap(Request $request) {
        return Inertia::render('AdminMapView',[
            'device_id' => $request->id
        ]);
    }
}
