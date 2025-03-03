<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\CustomerDeviceController;
use App\Http\Controllers\Api\NotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData'])->name('dashboard');

#Customer Create select devices
    Route::get('/customers/{customer}/devices', [CustomerDeviceController::class, 'getDevicesByCustomer']);

#Customer Edite select devices
    Route::get('/editCustomers/{customerID}/devices/{deviceId?}', [CustomerDeviceController::class, 'getDevicesByEditCustomer']);

# Role:User -> Devies mapDevices
    Route::get('/get-devices/{userId?}', [DashboardController::class, 'getMapDevices'])->name('get-devices');

# Role:User -> Monitor
    // Route::get('/get-device-notifications', [MonitorController::class, 'getNotifications'])->name('get-device-notifications');

    // Route::get('/get-billing-invoices', [BillingController::class, 'getBillingInvoices'])->name('get-device-notifications');
    // Route::get('/get-devices-1', [MonitorController::class, 'getDevices'])->name('get-devices');

## START: Admin Routes
    Route::get('/get-admin-stats/{userId?}', [AdminDashboardController::class, 'getAdminStats']);

## Device Status Routes
Route::get('/get-device-status', [AdminDashboardController::class, 'getDeviceStatus']);

# user device notification get
Route::get('/user-notifications', [NotificationController::class, 'userNotifications']);

## END: Admin Routes
});


Route::middleware('auth:sanctum')->group(function(){

# admin notification show header
    Route::get('/get-notification', [NotificationController::class, 'getNotification']);

# report Export
    Route::get('/notificationsExport', [NotificationController::class, 'getnotificationsExport']);

#read notification
    Route::post('/notifications/{id?}/mark-as-read', [NotificationController::class, 'markAsRead']);

#all notification
    Route::get('allNotifications', [NotificationController::class, 'index'])->name('allNotification');

});
