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

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified',  'role:admin'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return Inertia::render('UserDashboard');
// })->middleware(['auth', 'verified',  'role:user'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return Inertia::render('Dashboard', [
                'role' => Auth::user()->role,  // Make sure 'role' is a field in the User model
            ]);
        } else {
            return Inertia::render('UserDashboard', [
                'role' => Auth::user()->role,  // Make sure 'role' is a field in the User model
            ]);
        }
    })->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Clients Routes
    Route::group(['prefix' => 'clients'], function() {
        Route::get('/', [ClientsController::class, 'index'])->name('clients');
        Route::get('/create', [ClientsController::class, 'create'])->name('clients.create');
        Route::post('/store',[ClientsController::class, 'store'])->name('clients.store');
        Route::get('/{id}/view', [ClientsController::class, 'view'])->name('clients.view');
        Route::get('/{id}/edit',[ClientsController::class, 'edit'])->name('clients.edit');
        Route::post('/update/{id}', [ClientsController::class, 'update'])->name('clients.update');
        
        Route::delete('/destroy/{id}', [ClientsController::class, 'destroy'])->name('clients.destroy');

    });

    //Device Routes
    Route::group(['prefix' => 'devices'], function() {
        Route::get('/', [DeviceController::class, 'index'])->name('devices');
        Route::get('/create', [DeviceController::class, 'create'])->name('devices.create');
        Route::post('/store',[DeviceController::class, 'store'])->name('devices.store');
        Route::get('/{id}/view', [DeviceController::class, 'view'])->name('devices.view');
        Route::get('/{id}/edit',[DeviceController::class, 'edit'])->name('devices.edit');
        Route::post('/update/{id}', [DeviceController::class, 'update'])->name('devices.update');
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


        Route::get('/create', [VehicleTypeController::class, 'create'])->name('vehicle-type.create');
        Route::post('/store',[VehicleTypeController::class, 'store'])->name('vehicle-type.store');
        Route::get('/{id}/edit',[VehicleTypeController::class, 'edit'])->name('vehicle-type.edit');
        Route::post('/update/{id}', [VehicleTypeController::class, 'update'])->name('vehicle-type.update');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
