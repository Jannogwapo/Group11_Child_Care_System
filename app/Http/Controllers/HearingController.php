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
        $userGender = Auth::user()->gender_id;
        $clients = Client::whereHas('location', function($query) {
            $query->where('location', 'IN-HOUSE');
        })
            ->whereHas('location', function($query) {
            $query->where('location_id', '=', 1);
        })
            ->where('clientgender', $userGender)
            ->orderBy('clientLastName')
            ->get();
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
            'notes' => 'nullable|string'
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
    $baseQuery = Hearing::whereYear('hearing_date', $currentDate->year)
        ->whereMonth('hearing_date', $currentDate->month)
        ->with(['client', 'branch']);
    if (!Gate::allows('isAdmin')) {
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
                          $q->where('hearing_date', $now->toDateString())
                            ->where('time', '>', $now->format('H:i:s'));
                      });
            });
    
    } elseif ($filter === 'editable') {
        $baseQuery->where('status', 'scheduled')
            ->where(function($query) use ($now) {
                $query->where('hearing_date', '<', $now->toDateString())
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
    
    $hearings = $baseQuery->get()->groupBy(function($hearing) {
        return $hearing->hearing_date->format('Y-m-d');
    });

    $allHearings = $hearings->flatten();
    return view('calendar', compact(
        'currentDate',
        'previousMonth',
        'nextMonth',
        'hearings',
        'allHearings',
        'currentMonth'
    ));
}

    public function edit(Hearing $hearing) :View{
        $client = Client::find($hearing->client_id);
        $branch = Branch::find($hearing->branch_id);
        return view('client.editHearing', compact('hearing', 'client', 'branch'));
    }

    public function update(Request $request, Hearing $hearing): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'branch_id' => 'required|exists:branch,id',
            'hearing_date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:completed,postponed',
            'notes' => 'nullable|string',
            
        ]);
        $hearing->edit_count = $hearing->edit_count + 1;
        try {
            $hearing->update($validated);
            return redirect()->route('calendar.index')->with('success', 'Hearing updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating hearing: ' . $e->getMessage())->withInput();
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
}