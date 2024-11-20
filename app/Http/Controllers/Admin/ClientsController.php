<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Device;
use App\Models\CustomerDevice;
use App\Models\User;

class ClientsController extends Controller
{
    public function index() {

        $customers = Customer::paginate(10);

        return Inertia::render('Clients/Clients',[
            'user' => Auth::user(),
            'customers' => $customers,
        ]);
    }

    public function create() {
        return Inertia::render('Clients/Create', [
            'devices' => Device::select('id', 'device_id')->get(),
        ]);
    }

    public function store(Request $request){
        // Validate the incoming data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|max:500',
            'device_id' => 'required|array', // Ensure device_id is an array
            'quantity' => 'required|integer|min:1',
            'file' => 'required',
        ]);
    
        /*------- Start DB Transaction --------*/
        DB::beginTransaction();
    
        try {
            $filePath = null;
    
            // Handle file upload if present
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('uploads', 'public'); // Store file in public storage
            }
    
            $password = rand(10000000, 99999999);

           $user = User::create([
                        'name' => $validatedData["first_name"] . ' ' . $validatedData["last_name"],
                        'email' => $validatedData["email"],
                        'password' => Hash::make($password),
                        'role' => 'user',
                    ]);


            // Create a new Customer
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->first_name = $validatedData["first_name"];
            $customer->last_name = $validatedData["last_name"];
            $customer->email = $validatedData["email"];
            $customer->phone  = $validatedData["phone"];
            $customer->address  = $validatedData["address"];
            $customer->quantity  = $validatedData["quantity"];
            $customer->file  =  $filePath;
            $customer->save();
    
            $customerId = $customer->id;

            $devices = $validatedData["device_id"];

            foreach ($devices as $device) {
                $deviceId = $device['id'];
                CustomerDevice::create([
                    'device_id' => $deviceId,
                    'customer_id' => $customerId,
                ]);
            }
    
            DB::commit();
    
            // Redirect with success message
            return redirect()->route('clients')->with('success', 'Client added successfully.');
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function view($id) {
        
        $data = Customer::with('devices')->find($id);

        $customerDetails = [
            'id' => $data->id ?? '0',
            'first_name' => $data->first_name ?? '--',
            'last_name' => $data->last_name ?? '--',
            'email' => $data->email ?? '--',
            'address' => $data->address ?? '--',
            'quantity' => $data->quantity ?? '',
            'phone' => $data->phone ?? '--',
            'file' => $data->file  ? asset('storage/'.$data->file) : '',
            'devices' => $data->devices->toArray(),
        ];
        return Inertia::render('Clients/View',[
            'customers' => $customerDetails,
        ]);

    }

    public function edit($id) {

        $data = Customer::with('devices')->find($id);
   
        if (!$data) {
            return redirect()->route('clients.index')->with('error', 'Customer not found.');
        }

        $customerDetail = [
            'id'   => $data->id ?? 0,
            'first_name' => $data->first_name ?? '',
            'last_name' => $data->last_name ?? '',
            'email' => $data->email ?? '',
            'quantity' => $data->quantity ?? '',
            'phone' => $data->phone ?? '',
            'address' => $data->address ?? '',
            'file' => $data->file ? asset('storage/'.$data->file) : null,
            'device_id' => $data->devices->pluck('id','device_id')->toArray(),
        ];

        $devices = Device::select('id', 'device_id')->get();

        return Inertia::render('Clients/Edit',[
            'devices' => $devices,
            'customerDetail' => $customerDetail,
        ]);

    }

    public function update(Request $request, $id) {
        // Validate the incoming data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|max:500',
            'device_id' => 'required|array', // Ensure device_id is an array
            'quantity' => 'required|integer|min:1',
            'file' => 'required',
        ]);

        /*------- Start DB Transaction --------*/
        DB::beginTransaction();
    
        try {

            // Retrieve the customer to be updated
            $customer = Customer::findOrFail($id);
    
            // Handle file upload if a new file is uploaded
            $filePath = $customer->file; // Keep existing file if not updating
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('uploads', 'public');
            }
    
            // Update customer information
            $customer->first_name = $validatedData["first_name"];
            $customer->last_name = $validatedData["last_name"];
            $customer->email = $validatedData["email"];
            $customer->phone  = $validatedData["phone"];
            $customer->address  = $validatedData["address"];
            $customer->quantity  = $validatedData["quantity"];
            $customer->file  = $filePath; // Updated file path
            $customer->save(); // Save the updated customer
    
            $customerId = $customer->id;

            $deviceIds = array_column($validatedData['device_id'], 'id');

            CustomerDevice::where('customer_id',$customerId)->delete();
            foreach ($deviceIds as $deviceId) {
                CustomerDevice::create([
                    'device_id' => $deviceId,
                    'customer_id' => $customerId,
                ]);
            }
     
            DB::commit();
    
            // Redirect with success message
            return redirect()->route('clients')->with('success', 'Client added successfully.');
        } catch (\Exception $e) {
            /*-------- Rollback Database Entry --------*/
            DB::rollback();
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }



    public function destroy($id) {

        try {
            Customer::where('id',$id)->delete();

            CustomerDevice::where('customer_id', $id)->delete();

            return redirect()->back()->with('success', 'Customer and related devices deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete the customer: ' . $e->getMessage());
        }
    }
    
}
