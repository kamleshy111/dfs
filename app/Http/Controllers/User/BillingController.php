<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class BillingController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;

        $customer = Customer::where('user_id', $userId)->select('id')->first();

        if($customer){
            return Inertia::render('User/Billing/Billing');
        }
        
        return redirect()->back();
    }
}
