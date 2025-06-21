<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Event;
use App\Models\Hearing;
use App\Models\Incident;
use Illuminate\Support\Facades\Gate;

class LogsController extends Controller
{
    public function logs(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Get the filter from the request (default to 'all')
        $filter = $request->get('filter', 'all');

        // Initialize all collections
        $allLogs = collect();
        $recentClients = collect();
        $recentHearings = collect();
        $recentEvents = collect();
        $recentIncidents = collect();

        if ($filter === 'all') {
            $allLogs = \App\Models\Notification::latest()->get();
        }

        if ($filter === 'clients') {
            $recentClients = \App\Models\Notification::where('data', 'like', '%"title":"%Client %')
                ->latest()
                ->get();
        }

        if ($filter === 'hearings') {
            // Assuming hearings also create notifications. If not, this needs adjustment.
            $recentHearings = \App\Models\Notification::where('data', 'like', '%"title":"%Hearing%')
                ->latest()
                ->get();
        }

        if ($filter === 'events') {
            $recentEvents = \App\Models\Notification::where('data', 'like', '%"title":"%Event %')
                ->latest()
                ->get();
        }

        if ($filter === 'incidents') {
            $recentIncidents = \App\Models\Notification::where('data', 'like', '%"title":"%Incident %')
                ->latest()
                ->get();
        }

        return view('admin.logs', compact(
            'filter',
            'allLogs',
            'recentClients',
            'recentHearings',
            'recentEvents',
            'recentIncidents'
        ));
    }
}

