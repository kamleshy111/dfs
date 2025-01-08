<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Alert;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index(Request $request){

        $notificationsId = $request->query('id');
        $notification = Alert::find($notificationsId);

        if ($notification) {
            $notification->read_unread_status = 1;
            $notification->save();

            $notificationsId = $notification->id;
        }

        $view = Auth::user()->role === 'admin' ? 'Report/Report' : 'User/Report/Report';

        return Inertia::render($view, [
            'notificationsId' => $notificationsId,
        ]);
    }
}
