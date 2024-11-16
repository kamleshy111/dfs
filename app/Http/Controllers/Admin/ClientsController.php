<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ClientsController extends Controller
{
    public function index(){
        return Inertia::render('Clients/Clients',[
            'user' => Auth::user(),
        ]);
    }
}
