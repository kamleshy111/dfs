<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\User\MonitorController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\ManageDocumentsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ReviewRatingController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\User\BillingController;


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


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    //Clients Routes
    Route::group(['prefix' => 'clients'], function () {
        Route::get('/', [ClientsController::class, 'index'])->name('clients');
        Route::get('/create', [ClientsController::class, 'create'])->name('clients.create');
        Route::post('/store', [ClientsController::class, 'store'])->name('clients.store');
        Route::get('/{id}/view', [ClientsController::class, 'view'])->name('clients.view');
        Route::get('/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
        Route::post('/update/{id}', [ClientsController::class, 'update'])->name('clients.update');

        Route::delete('/destroy/{id}', [ClientsController::class, 'destroy'])->name('clients.destroy');
    });

    //Device Routes
    Route::group(['prefix' => 'devices'], function () {
        Route::get('/', [DeviceController::class, 'index'])->name('devices');
        Route::get('/create', [DeviceController::class, 'create'])->name('devices.create');
        Route::post('/upload-device', [DeviceController::class, 'uploadDevice'])->name('devices.upload-device');
        Route::post('/store',[DeviceController::class, 'store'])->name('devices.store');
        Route::get('/{id}/view', [DeviceController::class, 'view'])->name('devices.view');
        Route::get('/{id}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
        Route::post('/update/{id}', [DeviceController::class, 'update'])->name('devices.update');
        Route::delete('/destroy/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    });

    //VehicleType Routes
    Route::group(['prefix' => 'vehicle-type'], function () {
        Route::get('/', [VehicleTypeController::class, 'index'])->name('vehicle-type');
        Route::get('/create', [VehicleTypeController::class, 'create'])->name('vehicle-type.create');
        Route::post('/store', [VehicleTypeController::class, 'store'])->name('vehicle-type.store');
        Route::get('/{id}/view', [VehicleTypeController::class, 'view'])->name('vehicle-type.view');
        Route::get('/{id}/photos', [VehicleTypeController::class, 'photos'])->name('vehicle-type.photos');
        Route::post('/upload-installation-photo', [VehicleTypeController::class, 'uploadInstallationPhoto'])->name('upload-installation-photo ');
        Route::delete('/delete-installation-photo/{id}', [VehicleTypeController::class, 'deleteInstallationPhoto'])->name('delete-installation-photo');
        Route::get('/{id}/edit',[VehicleTypeController::class, 'edit'])->name('vehicle-type.edit');
        Route::post('/update/{id}', [VehicleTypeController::class, 'update'])->name('vehicle-type.update');
        Route::delete('/destroy/{id}', [VehicleTypeController::class, 'destroy'])->name('vehicle-type.destroy');
    });

    //Payments Routes
    Route::group(['prefix' => 'payments'], function () {
        Route::get('/', [PaymentsController::class, 'index'])->name('payments');
    });

    //ManageDocument Routes
    Route::group(['prefix' => 'manage-documents'], function () {
        Route::get('/', [ManageDocumentsController::class, 'index'])->name('manage-documents');
    });

    //ReviewRating Routes
    Route::group(['prefix' => 'review-rating'], function () {
        Route::get('/', [ReviewRatingController::class, 'index'])->name('review-rating');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    //Device Routes
    Route::group(['prefix' => 'billing'], function () {
        Route::get('/', [BillingController::class, 'index'])->name('billing');
    });

    # Monitor
    Route::group(['prefix' => 'monitor'], function () {
        Route::get('/{device_id}', [MonitorController::class, 'index'])->name('monitor');
    });

});



require __DIR__ . '/auth.php';
