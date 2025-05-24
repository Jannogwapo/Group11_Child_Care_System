<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hearing;
use App\Models\Client;
use App\Models\Judge;
use App\Models\Branch;
use App\Models\Status;
use Carbon\Carbon;
use App\Providers\AuthServiceProvider;

class HearingController extends Controller
{
    public function create()
    {
        // Get clients that are in-house and not abandoned
        $clients = Client::whereHas('location', function($query) {
            $query->where('location', 'IN-HOUSE');
        })
        ->whereHas('status', function($query) {
            $query->where('status_name', '!=', 'ABANDONED');
        })
        ->orderBy('clientLastName')
        ->get();

        // Get all required data from database
        $branches = Branch::orderBy('branchName')->get();

        $statuses = Status::orderBy('status_name')->get();

        // Use the correct view for adding a hearing
        return view('client.addHearing', compact('clients', 'branches', 'statuses'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'branch_id' => 'required|exists:branch,id',
            'hearing_date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'status' => 'required|in:scheduled,completed,postponed,cancelled,rescheduled',
            'notes' => 'nullable|string'
        ]);
        
        try {
            $validated['user_id'] = auth()->id();
            $hearing = Hearing::create($validated);
            
            // Get client name for notification
            $client = Client::find($validated['client_id']);
            $clientName = $client ? $client->clientFirstName . ' ' . $client->clientLastName : 'Unknown Client';
            
            // Create notification message
            $notification = auth()->user()->name . ' added a hearing for ' . $clientName . ' on ' . 
                           Carbon::parse($validated['hearing_date'])->format('F j, Y') . ' at ' . 
                           Carbon::parse($validated['time'])->format('g:i A');
            

            
            // Add note to client's profile
            if ($client) {
                $client->notes()->create([
                    'content' => 'Hearing scheduled on ' . Carbon::parse($validated['hearing_date'])->format('F j, Y') . 
                               ' at ' . Carbon::parse($validated['time'])->format('g:i A') . 
                               ' - Status: ' . ucfirst($validated['status']),
                    'user_id' => auth()->id()
                ]);
            }
            
            
            return redirect()->route('calendar.index')
                ->with('success', 'Hearing added successfully!')
                ->with('notification', $notification);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error adding hearing: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $currentDate = Carbon::parse($currentMonth);
        $previousMonth = Carbon::parse($currentMonth)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($currentMonth)->addMonth()->format('Y-m');

        // Only get hearings for the current month that are today or in the future
        $hearings = Hearing::with(['client', 'judge', 'branch'])
            ->whereYear('hearing_date', $currentDate->year)
            ->whereMonth('hearing_date', $currentDate->month)
            ->where('hearing_date', '>=', Carbon::today()) // <-- Only upcoming
            ->get()
            ->groupBy(function($hearing) {
                return $hearing->hearing_date->format('Y-m-d');
            });

        // Get all upcoming hearings for the list view
        $allHearings = Hearing::with(['client', 'judge'])
            ->where('hearing_date', '>=', Carbon::today()) // <-- Only upcoming
            ->orderBy('hearing_date', 'asc')
            ->get();

        return view('calendar', compact(
            'currentDate',
            'previousMonth',
            'nextMonth',
            'hearings',
            'allHearings',
            'currentMonth'
        ));
    }

    public function upcoming()
    {
        $hearings = Hearing::where('hearing_date', '>=', now())
            ->with(['client', 'judge'])
            ->orderBy('hearing_date', 'asc')
            ->get();

        $currentMonth = now()->format('Y-m');
        $currentDate = now();
        $previousMonth = now()->copy()->subMonth()->format('Y-m');
        $nextMonth = now()->copy()->addMonth()->format('Y-m');

        return view('calendar', compact(
            'hearings',
            'currentMonth',
            'currentDate',
            'previousMonth',
            'nextMonth'
        ));
    }

    public function completed()
    {
        $hearings = Hearing::where('hearing_date', '<', now())
            ->with(['client', 'judge'])
            ->orderBy('hearing_date', 'desc')
            ->get();

        $currentMonth = now()->format('Y-m');
        $currentDate = now();
        $previousMonth = now()->copy()->subMonth()->format('Y-m');
        $nextMonth = now()->copy()->addMonth()->format('Y-m');

        return view('calendar', compact(
            'hearings',
            'currentMonth',
            'currentDate',
            'previousMonth',
            'nextMonth'
        ));
    }

    public function edit(Hearing $hearing)
    {
        // Get clients that are in-house and not abandoned
        $clients = Client::whereHas('location', function($query) {
            $query->where('location', 'IN-HOUSE');
        })
        ->whereHas('status', function($query) {
            $query->where('status_name', '!=', 'ABANDONED');
        })
        ->orderBy('clientLastName')
        ->get();

        // Get all required data from database
        $branches = Branch::orderBy('branchName')->get();
        $statuses = Status::orderBy('status_name')->get();

        return view('client.editHearing', compact('hearing', 'clients', 'branches', 'statuses'));
    }

    public function update(Request $request, Hearing $hearing)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'branch_id' => 'required|exists:branch,id',
            'hearing_date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:scheduled,completed,postponed,cancelled,rescheduled',
            'notes' => 'nullable|string',
        ]);

        try {
            $hearing->update($validated);
            return redirect()->route('calendar.index')->with('success', 'Hearing updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating hearing: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Hearing $hearing)
    {
        try {
            $hearing->delete();
            return redirect()->route('calendar.index')->with('success', 'Hearing deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting hearing: ' . $e->getMessage());
        }
    }

    public function getUpcomingHearings()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        return Hearing::whereBetween('hearing_date', [$startOfWeek, $endOfWeek])
                     ->where('status', 'scheduled')
                     ->with(['client', 'judge'])
                     ->orderBy('hearing_date')
                     ->orderBy('time')
                     ->get();
    }
}