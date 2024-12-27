<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $view = $user->role === 'admin' ? 'Dashboard' : 'UserDashboard';

        return Inertia::render($view, [
            'role' => $user->role, // Ensure 'role' exists in the User model
        ]);
    }

}
