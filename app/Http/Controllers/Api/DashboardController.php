<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function getDashboardData(){

        $customers = Customer::all();

        return response()->json([
            'customers' => $customers,
        ]);
    }
}
