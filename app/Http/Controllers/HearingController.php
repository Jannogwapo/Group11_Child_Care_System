<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Hearing;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Status;
use Carbon\Carbon;

class HearingController extends Controller
{
    public function create():View{
        $user = Auth::user();
        $isAdmin = Gate::allows('isAdmin');

        $clientsQuery = Client::query();

        if (!$isAdmin) {
            $userGender = $user->gender_id;
            $clientsQuery->where('clientgender', $userGender);
        }

        $clients = $clientsQuery->orderBy('clientLastName')->get();
        
        $branches = Branch::orderBy('branchName')->get();
        $statuses = Status::orderBy('status_name')->get();
        return view('client.addHearing', compact('clients', 'branches', 'statuses'));
    }

    public function store(Request $request):RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'branch_id' => 'required|exists:branch,id',
            'hearing_date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
            'judge_name' => 'nullable|string|max:255'
        ]);
        $validated['edit_count'] =1;
        $validated['status'] = 'scheduled';
        $validated['user_id'] = auth()->id();
        $hearing = Hearing::create($validated);
        return redirect()->route('calendar.index')
                ->with('success', 'Hearing added successfully!');
    }

    public function index(Request $request) : View {
    $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
    $currentDate = Carbon::parse($currentMonth);
    $previousMonth = Carbon::parse($currentMonth)->subMonth()->format('Y-m');
    $nextMonth = Carbon::parse($currentMonth)->addMonth()->format('Y-m');
    $filter = $request->input('filter', 'upcoming');
    $user = Auth::user();
    $isAdmin = Gate::allows('isAdmin');

    $baseQuery = Hearing::whereYear('hearing_date', $currentDate->year)
        ->whereMonth('hearing_date', $currentDate->month)
        ->with(['client.gender', 'client.case', 'branch']);

    if (!$isAdmin) {
        $baseQuery->whereHas('client', function ($query) use ($user) {
            $query->where('clientgender', $user->gender_id);
        });
    }

    $now = Carbon::now();
    if ($filter === 'upcoming') {
        $baseQuery->where('status', 'scheduled')
            ->where(function($query) use ($now) {
                $query->where('hearing_date', '>', $now->toDateString())
                      ->orWhere(function($q) use ($now) {
                          $q->where('hearing_date','>=', $now->toDateString())
                            ->where('time', '>', $now->format('H:i:s'));
                      });
            });
    
    } elseif ($filter === 'editable') {
        $baseQuery->where('status', 'scheduled')
            ->where(function($query) use ($now) {
                $query->where('hearing_date', '<=', $now->toDateString())  
                      ->orWhere(function($q) use ($now) {
                          $q->where('hearing_date', $now->toDateString()) 
                            ->where('time', '<=', $now->format('H:i:s')); 
                      });
            });
    
    } elseif ($filter === 'finished') {
        $baseQuery->where('status', 'completed');
    
    } elseif ($filter === 'postponed') {
        $baseQuery->where('status', 'postponed');
    
    } elseif ($filter === 'all') {
        // No additional where clause (show all hearings)
    }
    
    $hearings = $baseQuery->orderBy('hearing_date', 'desc')
                         ->orderBy('time', 'desc')
                         ->get()
                         ->groupBy(function($hearing) {
                             return $hearing->hearing_date->format('Y-m-d');
                         });

    $allHearings = $hearings->flatten();

    $maleHearings = collect();
    $femaleHearings = collect();

    if ($isAdmin) {
        $maleHearings = $allHearings->filter(function ($hearing) {
            return optional($hearing->client)->gender_id === 1; // Assuming 1 for male
        });
        $femaleHearings = $allHearings->filter(function ($hearing) {
            return optional($hearing->client)->gender_id === 2; // Assuming 2 for female
        });
    } elseif ($user->gender_id === 1) { // If social worker is male
        $maleHearings = $allHearings;
    } elseif ($user->gender_id === 2) { // If social worker is female
        $femaleHearings = $allHearings;
    }

    return view('calendar', compact(
        'currentDate',
        'previousMonth',
        'nextMonth',
        'hearings',
        'allHearings',
        'maleHearings',
        'femaleHearings',
        'currentMonth',
        'isAdmin'
    ));
}

    public function edit(Request $request, Hearing $hearing) :View {
        $client = Client::find($hearing->client_id);
        $branch = Branch::find($hearing->branch_id);
        
        // Handle initial status choice for first edit
        if ($hearing->edit_count === 1 && $request->has('initial_status')) {
            $hearing->status = 'postponed'; // Default to postponed for the form
        }
        
        return view('client.editHearing', compact('hearing', 'client', 'branch'));
    }

    public function update(Request $request, Hearing $hearing): RedirectResponse
    {
        try {
            // Basic validation
            $request->validate([
                'client_id' => 'required',
                'branch_id' => 'required',
                'hearing_date' => 'required',
                'time' => 'required',
                'status' => 'required|in:completed,postponed',
                'judge_name' => 'nullable|string|max:255'
            ]);

            // Update basic fields
            $hearing->client_id = $request->client_id;
            $hearing->branch_id = $request->branch_id;
            $hearing->hearing_date = $request->hearing_date;
            $hearing->time = $request->time;
            $hearing->status = $request->status;
            $hearing->judge_name = $request->judge_name;
            $hearing->edit_count += 1;

            // Clear any next hearing fields if they exist
            $hearing->next_hearing_date = null;
            $hearing->next_hearing_time = null;

            $hearing->save();
            return redirect()->route('calendar.index')->with('success', 'Hearing updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Could not update hearing. Please try again.')->withInput();
        }
    }

    public function destroy(Hearing $hearing): RedirectResponse {
        try {
            $hearing->delete();
            return redirect()->route('calendar.index')->with('success', 'Hearing deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting hearing: ' . $e->getMessage());
        }
    }

    public function show(Hearing $hearing): View
    {
        $hearing->load(['client', 'branch']); // Eager load relationships
        return view('client.viewHearing', compact('hearing'));
    }
}