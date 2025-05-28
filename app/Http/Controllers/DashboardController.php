<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Client;
use App\Models\Hearing;
use App\Models\Activity;
use App\Models\User;
use App\Models\Gender;
use App\Models\Cases;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Incident;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = DB::table('user_role')->where('id', $user->role_id)->value('role_name');
        
        // Get client statistics by case type
        $clientStats = [
            'labels' => Cases::pluck('case_name')->toArray(),
            'data' => Cases::withCount('clients')->pluck('clients_count')->toArray()
        ];

        // Get discharge statistics for the last 5 months
        $dischargeStats = $this->getDischargeStats();

        // Get case status statistics
        $caseStatusStats = $this->getCaseStatusStats($user);

        // Get calendar data
        $calendarData = $this->getCalendarData();

        // Initialize client count
        $clientCount = 0;
        $totalClients = 0; // Initialize total clients as 0

        // If user is admin, show total count of all clients
        if (Gate::allows('isAdmin')) { // Admin role is 1
            $clientCount = Client::count();
            $totalClients = $clientCount; // Only admin gets to see total count
        } 
        // If user is social worker, show only clients with same gender
        else if (!Gate::allows('isAdmin')) { // Social Worker role is 2
            $clientCount = Client::where('clientgender', $user->gender_id)->count();
            $totalClients = $clientCount; // Social workers only see their gender-matched clients
        }

        // Get total number of users
        $totalUsers = User::count();

        // Get the count of active events (combined activities and incidents)
        $activeEvents = Activity::count() + Incident::count();

        // Get weekly hearings
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyHearings = Hearing::whereBetween('hearing_date', [$startOfWeek, $endOfWeek])
            ->with('client')
            ->orderBy('hearing_date')
            ->get();

        $upcomingHearings = Hearing::where('hearing_date', '>=', now())->count();

        $data = [
            'myClients' => $clientCount,
            'totalClients' => $totalClients,
            'myHearings' => Hearing::where('hearing_date', '>=', now())->count(),
            'activeEvents' => $activeEvents, // Pass active events count
            'role' => $role,
            'isAdmin' => Gate::allows('isAdmin'),
            'clientStats' => $clientStats,
            'dischargeStats' => $dischargeStats,
            'caseStatusStats' => $caseStatusStats,
            'currentMonth' => $calendarData['currentMonth'],
            'previousMonth' => $calendarData['previousMonth'],
            'nextMonth' => $calendarData['nextMonth'],
            'calendarDays' => $calendarData['calendarDays'],
            'totalUsers' => $totalUsers,
            'weeklyHearings' => $weeklyHearings, // Add weekly hearings to data
            'upcomingHearings' => $upcomingHearings,
        ];

        // Calculate start of week and days array
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        // Get hearings for the week (adjust model/relation as needed)
        $now = Carbon::now();

        $weeklyHearings = Hearing::with('client')
            ->whereBetween('hearing_date', [
                $days[0]->copy()->startOfDay(),
                $days[6]->copy()->endOfDay()
            ])
            ->whereIn('status', ['pending', 'scheduled'])
            ->where(function($query) use ($now) {
                $query->where('hearing_date', '>', $now->toDateString())
                      ->orWhere(function($q) use ($now) {
                          $q->where('hearing_date', $now->toDateString())
                            ->where('time', '>=', $now->format('H:i:s'));
                      });
            })
            ->get();

        $data['startOfWeek'] = $startOfWeek;
        $data['days'] = $days;
        return view('dashboard', $data);
    }

    private function getCaseStatusStats($user)
    {
        $query = Client::query();
        
        // If user is a social worker, filter by their gender
        if (!Gate::allows('isAdmin')) { // Social Worker role is 2
            $query->where('clientgender', $user->gender_id);
        }
        
        // Get status counts
        $statusCounts = $query->with('status')
            ->get()
            ->groupBy('status.status_name')
            ->map->count()
            ->toArray();

        // Get all possible statuses
        $allStatuses = Status::pluck('status_name')->toArray();
        
        // Initialize data array with all statuses
        $data = array_fill_keys($allStatuses, 0);
        
        // Update with actual counts
        foreach ($statusCounts as $status => $count) {
            $data[$status] = $count;
        }

        return [
            'labels' => array_keys($data),
            'data' => array_values($data)
        ];
    }

    private function getDischargeStats()
    {
        $months = collect([]);
        $counts = collect([]);

        // Get last 5 months of discharge data
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->format('M'));
            
            $count = Client::whereHas('location', function($query) {
                $query->where('location', 'DISCHARGED');
            })
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month)
            ->count();
                
            $counts->push($count);
        }

        return [
            'labels' => $months->toArray(),
            'data' => $counts->toArray()
        ];
    }

    private function getCalendarData()
    {
        $date = request('month') ? Carbon::createFromFormat('Y-m', request('month')) : now();
        
        // Get all hearings for the month
        $hearings = Hearing::whereYear('hearing_date', $date->year)
            ->whereMonth('hearing_date', $date->month)
            ->pluck('hearing_date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        return [
            'currentMonth' => $date->format('F Y'),
            'previousMonth' => $date->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
            'calendarDays' => $hearings
        ];
    }

}