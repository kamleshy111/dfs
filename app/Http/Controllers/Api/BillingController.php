<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function getBillingInvoices()
    {
        $billing_invoices = [
            [
                "id" => '156465',
                "device_name" => 'Device Name 1',
                "status" => 'active',
                "payment_mode" => 'Credit Card',
                "payment_status" => 'paid',
            ],
            [
                "id" => '8974984',
                "device_name" => 'Device Name 2',
                "status" => 'active',
                "payment_mode" => 'Credit Card',
                "payment_status" => 'partially_paid',
            ],
            [
                "id" => '54984',
                "device_name" => 'Device Name 3',
                "status" => 'expired',
                "payment_mode" => 'Credit Card',
                "payment_status" => 'pending',
            ],
            [
                "id" => '4598645',
                "device_name" => 'Device Name 4',
                "status" => 'expired',
                "payment_mode" => 'Credit Card',
                "payment_status" => 'paid',
            ],
            [
                "id" => '47894984',
                "device_name" => 'Device Name 5',
                "status" => 'expired',
                "payment_mode" => 'Credit Card',
                "payment_status" => 'partially_paid',
            ]
        ];

        // $billing_invoices = array_map(fn($invoice) => array_values($invoice), $billing_invoices);

        return response()->json([
            'billing_invoices' => $billing_invoices
        ]);
    }
}
