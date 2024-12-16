<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ReportController extends Controller
{
    public function index(){

        return Inertia::render('Report/Report',[
            'user' => Auth::user(),

        ]);
    }
}
