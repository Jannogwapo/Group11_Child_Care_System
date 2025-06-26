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
use App\Traits\CreatesNotifications;

class HearingController extends Controller
{
    use CreatesNotifications;

    public function create():View{
        $user = Auth::user();
        $isAdmin = Gate::allows('isAdmin');

        $clientsQuery = Client::query()->where('location_id', 1);

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
        $client = $hearing->client;
        $hearing->reminder_code = random_int(100000, 999999);
        $hearing->save();
        $this->notifyAdmins(
            'New Hearing Scheduled',
            "A new hearing has been scheduled for client {$client->clientFirstName} {$client->clientLastName} by {$request->user()->name}.",
            route('admin.logs', ['filter' => 'hearings'])
        );

        return redirect()->route('calendar.index')
                ->with('success', 'Hearing added successfully!');
    }

    public function index(Request $request) : View {
   $user = Auth::user();
   $isAdmin = Gate::allows('isAdmin');
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
    if ($filter === 'upcoming' && $filter === 'ongoing-upcoming'){
        $baseQuery->where('status', 'scheduled')
                  ->where(function ($query) use ($now) {
                      $query->where('hearing_date', '>', $now->toDateString())
                            ->orWhere(function ($q) use ($now) {
                                $q->where('hearing_date', $now->toDateString())
                                   ->where('time', '>=', $now->format('H:i:s'));
                            });
                  });
    
    } elseif ($filter === 'editable') {
        $baseQuery->where('status', 'scheduled')
                  ->where(function ($query) use ($now) {
                      $query->where('hearing_date', '<', $now->toDateString())
                            ->orWhere(function ($q) use ($now) {
                                $q->where('hearing_date',  $now->toDateString())
                                   ->where('time', '<=', $now->format('H:i:s'));
                            });
                  });
    } elseif ($filter === 'finished') {
        $baseQuery->where('status', 'completed');
    
    } elseif ($filter === 'postponed') {
        $baseQuery->where('status', 'postponed');
    
    } elseif ($filter === 'ongoing') {
        $baseQuery->where('status', 'ongoing');
    
    }elseif ($filter === 'all') {
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
        // Validation
        $request->validate([
            'client_id' => 'required|exists:clients,id', // Add exists validation
            'branch_id' => 'required|exists:branch,id', // Add exists validation
            'hearing_date' => 'required|date', // Specify date format if needed
            'time' => 'required', // Consider adding time format validation
            'status' => 'required|in:completed,postponed,ongoing,ongoing-upcoming',
            'notes' => 'nullable|string',
            'reminder_code' => 'nullable|string',
        ]);

        // Update hearing data. Use mass assignment for better readability and security.
        $hearing->update([
            'client_id' => $request->client_id,
            'branch_id' => $request->branch_id,
            'hearing_date' => $request->hearing_date,
            'time' => $request->time,
            'status' => $request->status,
            'judge_name' => null,
            'edit_count' => $hearing->edit_count + 1,
            'reminder_code' => $request->status === 'completed' ? null : $request->reminder_code,
            'notes' => $request->notes,
        ]);

        // If status is 'ongoing' or 'postponed', create a new hearing with the same reminder_code, client, branch, and judge, but new date and time
        if (in_array($request->status, ['ongoing', 'postponed'])) {
            // Mark the current hearing as postponed or ongoing
            $hearing->status = $request->status;
            $hearing->save();

            // Create a new hearing for the next date/time (if provided)
            if ($request->next_hearing_date && $request->next_hearing_time) {
                $newHearing = $hearing->replicate();
                $newHearing->hearing_date = $request->next_hearing_date;
                $newHearing->time = $request->next_hearing_time;
                $newHearing->status = 'ongoing-upcoming';// New hearing starts as ongoing
                $newHearing->edit_count = 1;
                $newHearing->reminder_code = $hearing->reminder_code;
                $newHearing->notes = $request->next_hearing_notes ?? null;
                $newHearing->save();
            }
        }

        return redirect()->route('calendar.index')->with('success', 'Hearing updated successfully!');

    } catch (ValidationException $e) {
        return back()->withErrors($e->errors())->withInput(); // Return specific validation errors
    } catch (QueryException $e) {
        // Handle database errors more specifically.  Log the error for debugging.
        \Log::error("Database error updating hearing: " . $e->getMessage());
        return back()->with('error', 'A database error occurred. Please contact support.')->withInput();
    } catch (\Exception $e) {
        // Log the exception for debugging purposes
        \Log::error("Error updating hearing: " . $e->getMessage());
        dd($e);
        return back()->with('error', 'An unexpected error occurred. Please try again later.')->withInput();
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
        $relatedHearings = Hearing::where('reminder_code', $hearing->reminder_code)
            ->orderBy('hearing_date', 'asc')
            ->get();
        return view('client.viewHearing', compact('hearing', 'relatedHearings'));
    }
}