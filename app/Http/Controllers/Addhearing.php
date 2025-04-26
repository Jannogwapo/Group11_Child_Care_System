<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hearing;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Judge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AddHearing extends Controller
{
    public function index(Request $request)
    {
        $date = $request->month ? Carbon::createFromFormat('Y-m', $request->month) : Carbon::now();
        
        $hearings = Hearing::with(['client', 'branch', 'judge'])
            ->whereYear('hearing_date', $date->year)
            ->whereMonth('hearing_date', $date->month)
            ->orderBy('hearing_date')
            ->orderBy('time')
            ->get()
            ->groupBy(function($hearing) {
                return Carbon::parse($hearing->hearing_date)->format('Y-m-d');
            });

        return view('calendar.index', [
            'hearings' => $hearings,
            'currentMonth' => $date->format('F Y'),
            'daysInMonth' => $date->daysInMonth,
            'firstDayOfMonth' => $date->copy()->startOfMonth()->dayOfWeek,
            'previousMonth' => $date->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
        ]);
    }

    public function create()
    {
        // Get the current user's gender
        $userGender = auth()->user()->gender;

        // Get clients based on user's gender
        $clients = Client::where('clientgender', $userGender)
            ->orderBy('clientLastName')
            ->get();

        // Get all required data from database
        $branches = Branch::orderBy('name')->get();
        $judges = Judge::orderBy('name')->get();

        return view('client.addHearing', [
            'clients' => $clients,
            'branches' => $branches,
            'judges' => $judges,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'hearing_date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'judge_id' => 'required|exists:judges,id',
            'status' => 'required|in:scheduled,completed,postponed,cancelled',
            'notes' => 'nullable|string'
        ]);

        try {
            $validated['user_id'] = auth()->id();
            Hearing::create($validated);
            return redirect()->route('calendar.index')->with('success', 'Hearing scheduled successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to schedule hearing. Please try again.');
        }
    }

    public function edit(Hearing $hearing)
    {
        // Get the current user's gender
        $userGender = auth()->user()->gender;

        // Get clients based on user's gender
        $clients = Client::where('clientgender', $userGender)
            ->orderBy('clientLastName')
            ->get();

        // Get all required data from database
        $branches = Branch::orderBy('name')->get();
        $judges = Judge::orderBy('name')->get();

        return view('client.editHearing', [
            'hearing' => $hearing,
            'clients' => $clients,
            'branches' => $branches,
            'judges' => $judges,
        ]);
    }

    public function update(Request $request, Hearing $hearing)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'hearing_date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'judge_id' => 'required|exists:judges,id',
            'status' => 'required|in:scheduled,completed,postponed,cancelled',
            'notes' => 'nullable|string'
        ]);

        try {
            $hearing->update($validated);
            return redirect()->route('calendar.index')->with('success', 'Hearing updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update hearing. Please try again.');
        }
    }

    public function destroy(Hearing $hearing)
    {
        try {
            $hearing->delete();
            return redirect()->route('calendar.index')->with('success', 'Hearing cancelled successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel hearing. Please try again.');
        }
    }

    public function date(Request $request)
    {
        $date = $request->month ? Carbon::createFromFormat('Y-m', $request->month) : Carbon::now();
        
        $hearings = Hearing::with(['client', 'branch', 'judge'])
            ->whereYear('hearing_date', $date->year)
            ->whereMonth('hearing_date', $date->month)
            ->orderBy('hearing_date')
            ->orderBy('time')
            ->get()
            ->groupBy(function($hearing) {
                return Carbon::parse($hearing->hearing_date)->format('Y-m-d');
            });

        return view('calendar.date', [
            'hearings' => $hearings,
            'currentMonth' => $date->format('F Y'),
            'daysInMonth' => $date->daysInMonth,
            'firstDayOfMonth' => $date->copy()->startOfMonth()->dayOfWeek,
            'previousMonth' => $date->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
        ]);
    }
}
