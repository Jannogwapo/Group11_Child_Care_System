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
        $recentClients = collect();
        
        if ($filter === 'all' || $filter === 'clients') {
            // Get new clients (created this month)
            $newClients = Client::whereMonth('created_at', $currentMonth)
                ->latest('created_at')
                ->get()
                ->map(function ($client) {
                    $client->is_new = true;
                    $client->activity_time = $client->created_at;
                    return $client;
                });
                
            // Get updated clients (updated this month but created earlier)
            $updatedClients = Client::whereMonth('updated_at', $currentMonth)
                ->whereRaw('DATE(created_at) != DATE(updated_at)') // Only get actual updates
                ->latest('updated_at')
                ->get()
                ->map(function ($client) {
                    $client->is_new = false;
                    $client->activity_time = $client->updated_at;
                    return $client;
                });
                
            // Merge and sort by most recent activity
            $recentClients = $newClients->concat($updatedClients)
                ->sortByDesc('activity_time')
                ->values();
        }

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

