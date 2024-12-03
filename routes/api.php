<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CustomerDeviceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/dashboard', [DashboardController::class, 'getDashboardData'])->name('dashboard');
Route::get('/customer-devices', [CustomerDeviceController::class, 'getDevices']);

Route::get('/get-devices', [DashboardController::class, 'getMapDevices'])->name('get-devices');
