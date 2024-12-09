<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\CustomerDeviceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData'])->name('dashboard');
    Route::get('/customer-devices', [CustomerDeviceController::class, 'getDevices']);

#Customer Create select devices
    Route::get('/customers/{customer}/devices', [CustomerDeviceController::class, 'getDevicesByCustomer']);

# Role:User -> Devies
    Route::get('/get-devices', [DashboardController::class, 'getMapDevices'])->name('get-devices');

# Role:User -> Monitor
    Route::get('/get-device-notifications', [MonitorController::class, 'getNotifications'])->name('get-device-notifications');

    Route::get('/get-billing-invoices', [BillingController::class, 'getBillingInvoices'])->name('get-device-notifications');
    Route::get('/get-devices-1', [MonitorController::class, 'getDevices'])->name('get-devices');

## START: Admin Routes
    Route::get('/get-admin-stats', [AdminDashboardController::class, 'getAdminStats']);
## END: Admin Routes
});

