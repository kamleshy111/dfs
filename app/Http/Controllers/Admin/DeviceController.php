<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index(){
        return Inertia::render('Device/Device',[
            'user' => Auth::user(),
        ]);
    }

    public function create(){
        return Inertia::render('Device/Create');
    }

    public function store(){
        
    }

    public function edit(){
        return Inertia::render('Device/Device',[
            'user' => Auth::user(),
        ]);
    }
}
