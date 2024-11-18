<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\ManageDocumentsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ReviewRatingController;
use App\Http\Controllers\Admin\VehicleTypeController;


// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Clients Routes
    Route::group(['prefix' => 'clients'], function() {
        Route::get('/', [ClientsController::class, 'index'])->name('clients');
    });

    //Device Routes
    Route::group(['prefix' => 'devices'], function() {
        Route::get('/', [DeviceController::class, 'index'])->name('devices');
        Route::get('/add', [DeviceController::class, 'add'])->name('add-devices');
        Route::get('/edit', [DeviceController::class, 'edit'])->name('edit-devices');
    });

    //Payments Routes
    Route::group(['prefix' => 'payments'], function() {
        Route::get('/', [PaymentsController::class, 'index'])->name('payments');
    });

    //ManageDocument Routes
    Route::group(['prefix' => 'manage-documents'], function() {
        Route::get('/', [ManageDocumentsController::class, 'index'])->name('manage-documents');
    });

    //ReviewRating Routes
    Route::group(['prefix' => 'review-rating'], function() {
        Route::get('/', [ReviewRatingController::class, 'index'])->name('review-rating');
    });

    //VehicleType Routes
    Route::group(['prefix' => 'vehicle-type'], function() {
        Route::get('/', [VehicleTypeController::class, 'index'])->name('vehicle-type');
    });
});

require __DIR__.'/auth.php';
