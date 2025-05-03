<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Event;
use App\Models\CalendarHearing;
use Illuminate\Support\Facades\Gate;

class LogsController extends Controller
{
    public function logs()
    {
        return view('admin.logs');
    }

    public function index()
    {
        if (!Gate::allows('isAdmin')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Get recent activities
        $recentUsers = User::latest()->take(5)->get();
        $recentClients = Client::latest()->take(5)->get();
        $recentEvents = Event::latest()->take(5)->get();
        $recentHearings = CalendarHearing::latest()->take(5)->get();

        return view('admin.logs', compact(
            'recentUsers',
            'recentClients',
            'recentEvents',
            'recentHearings'
        ));
    }
}
