<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
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
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\User\BillingController as UserBillingController;
use App\Http\Controllers\NotificationController;
use Symfony\Component\HttpFoundation\StreamedResponse;


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

Route::get('/pusher', function () {
    return view('push');
});

Route::view('pusher1','pusher');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'report'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('report');
    });

    Route::get('devices/map/{id?}', [DeviceController::class, 'viewMap'])->name('devices.map');
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

        Route::get('/map/{id?}', [ClientsController::class, 'viewMap'])->name('clients.map');
    });

    //Device Routes
    Route::group(['prefix' => 'devices'], function () {
        Route::get('/', [DeviceController::class, 'index'])->name('devices');
        Route::get('/create', [DeviceController::class, 'create'])->name('devices.create');

        Route::post('/upload-excel', [DeviceController::class, 'importDevice'])->name('devices.upload-excel');
        Route::post('/import-chunk', [DeviceController::class, 'importChunk'])->name('devices.importChunk');

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



    //Billing Route
    Route::group(['prefix' => 'all-billing'], function () {
        Route::get('/', [BillingController::class, 'index'])->name('all-billing');
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
        Route::get('/', [UserBillingController::class, 'index'])->name('billing');
    });

    # Monitor
    Route::group(['prefix' => 'monitor'], function () {
        Route::get('/{device_id?}', [MonitorController::class, 'index'])->name('monitor');
    });

    # send email Vehicle Renewal
    Route::get('/email/vehicle-renewal',[NotificationController::class, 'sendEmailVehicleRenewal'])->name('email.vehicle-renewal');

});

Route::get('/download-proxy', function (Request $request) {
    $fileUrl = $request->query('url');

    // Validate the URL to prevent abuse
    if (!filter_var($fileUrl, FILTER_VALIDATE_URL) || strpos($fileUrl, 'http://119.23.104.221:6611/') !== 0) {
        abort(403, 'Invalid file URL.');
    }

    // Fetch the file using Laravel's HTTP client
    $response = Http::withOptions(['stream' => true])->get($fileUrl);
    if ($response->failed()) {
        abort(404, 'File not found.');
    }

    // Stream the file to the user
    return response()->streamDownload(function () use ($response) {
        echo $response->body();
    }, basename(parse_url($fileUrl, PHP_URL_PATH)), [
        'Content-Type' => $response->header('Content-Type'),
        'Content-Disposition' => 'attachment; filename="' . basename(parse_url($fileUrl, PHP_URL_PATH)) . '"',
    ]);
});



require __DIR__ . '/auth.php';
