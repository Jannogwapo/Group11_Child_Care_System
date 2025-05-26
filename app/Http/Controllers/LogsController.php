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

        // Get the current month
        $currentMonth = now()->month;

        // Fetch data based on the filter
        $recentClients = $filter === 'all' || $filter === 'clients'
            ? Client::whereMonth('created_at', $currentMonth)->latest('created_at')->get()
            : collect();

        $recentHearings = $filter === 'all' || $filter === 'hearings'
            ? Hearing::whereMonth('created_at', $currentMonth)->latest('created_at')->get()
            : collect();

        $recentEvents = $filter === 'all' || $filter === 'events'
            ? Event::whereMonth('created_at', $currentMonth)->latest('created_at')->get()
            : collect();

        $recentIncidents = $filter === 'all' || $filter === 'incidents'
            ? Incident::whereMonth('created_at', $currentMonth)->latest('created_at')->get()
            : collect();

        return view('admin.logs', compact(
            'filter',
            'recentClients',
            'recentHearings',
            'recentEvents',
            'recentIncidents'
        ));
    }
}