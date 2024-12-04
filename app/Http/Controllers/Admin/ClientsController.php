<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendLoginDetail;
use App\Models\Customer;
use App\Models\Device;
use App\Models\CustomerDevice;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerDeviceImport;
use Illuminate\Validation\ValidationException;
use App\Models\Vehicle;
use App\Models\VehicleDevice;

class ClientsController extends Controller
{
    public function index() {

        $customers = Customer::select('id','first_name','last_name','email','phone','created_at')->paginate(10);

        return Inertia::render('Clients/Clients',[
            'user' => Auth::user(),
            'customers' => $customers,
        ]);
    }

    public function create() {

        $useDevice =  CustomerDevice::select('device_id')->get();

        $devices = Device::whereNotIn('id', $useDevice)->select('id', 'device_id')->get();

        return Inertia::render('Clients/Create', [
            'devices' => $devices,
        ]);
    }

    public function store(Request $request){

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ], [
            'first_name.required' => 'First Name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'The customer email ID must be unique. Please choose a different email ID.',
            'phone.required' => 'Phone Number is required.',
            'address.required' => 'Address is required.',
        ]);

        if (!$validated) {
            // Return validation errors as a JSON response
            return response()->json(["message" => $validated]);
        }
        

        /*------- Start DB Transaction --------*/
        DB::beginTransaction();
    
        try {
    
            $password = rand(10000000, 99999999);

           $user = User::create([
                        'name' => $request->input("first_name") . ' ' . $request->input("last_name"),
                        'email' => $request->input("email"),
                        'password' => Hash::make($password),
                        'role' => 'user',
                    ]);

            // // Prepare email data
            $loginData = [
                'title' => 'Your Account  Has Been Create Successfully ',
                'body' => 'Pless find Account Login.',
                'email' => $user->email,
                'password' => $password,
            ];

            // Send email
            Mail::to($user->email)->send(new SendLoginDetail($loginData));


            // Create a new Customer
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->first_name = $request->input("first_name");
            $customer->last_name = $request->input("last_name");
            $customer->email = $request->input("email");
            $customer->secondary_email = $request->input("secondary_email");
            $customer->phone  = $request->input("phone");
            $customer->secondary_phone = $request->input("secondary_phone");
            $customer->invoice_number = $request->input("invoice_number");
            $customer->amount = $request->input("amount");
            $customer->address  = $request->input("address");
            $customer->quantity  = $request->input("quantity") ?? '0';
            $customer->save();
    
            $customerId = $customer->id;

    
            $devices = $request->input("device_id", []);

            if($devices){
                foreach ($devices as $device_id) {
                    CustomerDevice::create([
                        'device_id' => $device_id,
                        'customer_id' => $customerId,
                    ]);
                }
            }

            if ($request->file('file')) {
                Excel::import(new CustomerDeviceImport($customerId), $request->file('file'));
            }
    
            DB::commit();
            return response()->json(["message" => 'Client added successfully.']);
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return response()->json(["message" => 'An error occurred: ' . $e->getMessage()]);

        }
    }

    public function view($id) {
        
        $data = Customer::with('devices')->find($id);

        $customerDetails = [
            'id' => $data->id ?? '0',
            'first_name' => $data->first_name ?? '--',
            'last_name' => $data->last_name ?? '--',
            'email' => $data->email ?? '--',
            'secondary_email' => $data->secondary_email ?? '--',
            'address' => $data->address ?? '--',
            'quantity' => $data->quantity ?? '',
            'phone' => $data->phone ?? '--',
            'secondary_phone' => $data->secondary_phone ?? '--',
            'invoice_number' => $data->invoice_number  ?? '',
            'amount'    => $data->amount ?? 0.00,
            'devices' => $data->devices->toArray(),
        ];
        return Inertia::render('Clients/View',[
            'customers' => $customerDetails,
        ]);

    }

    public function edit($id) {

        $data = Customer::with('devices')->find($id);
   
        if (!$data) {
            return response()->json(["message" => 'Customer not found.']);
        }

        $customerDetail = [
            'id'   => $data->id ?? 0,
            'first_name' => $data->first_name ?? '',
            'last_name' => $data->last_name ?? '',
            'email' => $data->email ?? '',
            'secondary_email' => $data->secondary_email ?? '',
            'quantity' => $data->quantity ?? '',
            'phone' => $data->phone ?? '',
            'secondary_phone' => $data->secondary_phone ?? '',
            'invoice_number'    => $data->invoice_number ?? '',
            'amount'    => $data->amount ?? 0,
            'address' => $data->address ?? '',
            'device_id' => $data->devices->select('id','device_id')->toArray(),
        ];

        $allDevices = Device::pluck('id')->toArray();

        $assignedDevice =  CustomerDevice::pluck('device_id')->toArray();

        $currentCustomerDevice = CustomerDevice::where('customer_id', $id)->pluck('device_id')->toArray();

        $unusedDevices = array_diff($allDevices, $assignedDevice);


        $unusedDevicesList = Device::whereIn('id', $unusedDevices)
                            ->select('id', 'device_id')
                            ->get();

       
        $currentDevicesList = Device::whereIn('id', $currentCustomerDevice)
                             ->select('id', 'device_id')
                             ->get();

        $devices = $unusedDevicesList->merge($currentDevicesList);


        return Inertia::render('Clients/Edit',[
            'devices' => $devices,
            'customerDetail' => $customerDetail,
        ]);

    }

    public function update(Request $request, $id) {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $id,
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ], [
            'first_name.required' => 'First Name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'The customer email ID must be unique. Please choose a different email ID.',
            'phone.required' => 'Phone Number is required.',
            'address.required' => 'Address is required.',
        ]);

        if (!$validated) {
            // Return validation errors as a JSON response
            return response()->json(["message" => $validated]);
        }

        /*------- Start DB Transaction --------*/
        DB::beginTransaction();
    
        try {

            // Retrieve the customer to be updated
            $customer = Customer::findOrFail($id);
    
            // Update customer information
            $customer->first_name = $request->input("first_name");
            $customer->last_name = $request->input("last_name");
            $customer->email = $request->input("email");
            $customer->secondary_email = $request->input("secondary_email");
            $customer->phone  = $request->input("phone");
            $customer->secondary_phone = $request->input("secondary_phone");
            $customer->invoice_number = $request->input("invoice_number");
            $customer->amount   = $request->input("amount");
            $customer->address  = $request->input("address");
            $customer->quantity  = $request->input("quantity") ?? '0';
            $customer->save(); // Save the updated customer
    
            $customerId = $customer->id;

            $userID = $customer->user_id;

            $user = User::where('id',$userID)->first();
            $user->name = $request->input("first_name") . ' ' . $request->input("last_name");
            $user->email = $request->input("email");
            $user->save();


            CustomerDevice::where('customer_id',$customerId)->delete();

            $devices = $request->input("device_id", []);

            if($devices){
                foreach ($devices as $device_id) {
                    CustomerDevice::create([
                        'device_id' => $device_id,
                        'customer_id' => $customerId,
                    ]);
                }
            }

            if ($request->file('file')) {
                Excel::import(new CustomerDeviceImport($customerId), $request->file('file'));
            }
     
            DB::commit();
    
            // Redirect with success message
            return response()->json(['message' => 'Client updated successfully..']);
           
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return response()->json(["message" => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            
            $userId = Customer::where('id', $id)->value('user_id');

            if ($userId) {
            
                User::where('id', $userId)->delete();

                Customer::where('id', $id)->delete();

                CustomerDevice::where('customer_id', $id)->delete();

                $allVehicleId = Vehicle::where('customer_id', $id)->pluck('id')->toArray();

                VehicleDevice::whereIn('vehicle_id', $allVehicleId)->delete();

                Vehicle::where('customer_id', $id)->delete();

                // Commit the transaction
                DB::commit();

                return response()->json(['message' => 'Client deleted successfully.']);
            } else {
                return response()->json(['message' => 'Client not found.'], 404);
            }
        } catch (\Exception $e) {
            // Rollback the transaction if any operation fails
            DB::rollBack();

            return response()->json(['message' => 'An error occurred while deleting the Client.', 'error' => $e->getMessage()], 500);
        }
    }
    
    
}
