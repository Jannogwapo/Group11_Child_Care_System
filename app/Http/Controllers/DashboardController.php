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

        // Get client statistics by case type (filtered for social worker)
        $clientStats = $this->getClientStats($user);

        // Get discharge statistics for the last 5 months (per gender for social worker, overall for admin)
        $dischargeStats = $this->getDischargeStats($user);

        // Get case status statistics (filtered by gender for social worker)
        $caseStatusStats = $this->getCaseStatusStats($user);

        // Get calendar data
        $calendarData = $this->getCalendarData();

        // Initialize client count
        $clientCount = 0;
        $totalClients = 0;

        // If user is admin, show total count of all clients
        if (Gate::allows('isAdmin')) {
            $clientCount = Client::count();
            $totalClients = $clientCount;
        } 
        // If user is social worker, show only clients with same gender
        else {
            $clientCount = Client::where('clientgender', $user->gender_id)->count();
            $totalClients = $clientCount;
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

        $upcomingHearings = Hearing::where('hearing_date', '>', now())->count();

        // Calculate start of week and days array
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

        $data = [
            'myClients' => $clientCount,
            'totalClients' => $totalClients,
            'myHearings' => Hearing::where('hearing_date', '>=', now())->count(),
            'activeEvents' => $activeEvents,
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
            'weeklyHearings' => $weeklyHearings,
            'upcomingHearings' => $upcomingHearings,
            'startOfWeek' => $startOfWeek,
            'days' => $days,
        ];

        return view('dashboard', $data);
    }

    /**
     * Get client statistics by case type.
     * Admin: all clients by case type.
     * Social worker: only clients of the same gender by case type.
     */
    private function getClientStats($user)
    {
        $isAdmin = Gate::allows('isAdmin');
        $labels = Cases::pluck('case_name')->toArray();
        $data = [];

        foreach (Cases::all() as $case) {
            $clientsQuery = $case->clients();
            if (!$isAdmin) {
                $clientsQuery->where('clientgender', $user->gender_id);
            }
            $data[] = $clientsQuery->count();
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get case status statistics.
     * Admin: sees all clients.
     * Social worker: sees only clients with same gender.
     */
    private function getCaseStatusStats($user)
    {
        $isAdmin = Gate::allows('isAdmin');
        $query = Client::query();

        // If user is not admin (i.e., social worker), filter by their gender
        if (!$isAdmin) {
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

    /**
     * Get discharge stats for the last 5 months.
     * Admin: returns overall discharge count per month.
     * Social worker: returns discharge count per month, filtered by user's gender.
     */
    private function getDischargeStats($user)
    {
        $isAdmin = Gate::allows('isAdmin');
        $months = collect([]);
        $counts = collect([]);

        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->format('M'));

            $query = Client::whereHas('location', function($query) {
                $query->where('location', 'DISCHARGED');
            })
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month);

            // If social worker, filter by gender
            if (!$isAdmin) {
                $query->where('clientgender', $user->gender_id);
            }

            $count = $query->count();
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

    // Start and end of the month
    $startOfMonth = $date->copy()->startOfMonth();
    $endOfMonth = $date->copy()->endOfMonth();

    // Get all hearings for the month
    $hearings = Hearing::whereBetween('hearing_date', [$startOfMonth, $endOfMonth])
        ->get()
        ->groupBy(function($hearing) {
            return Carbon::parse($hearing->hearing_date)->format('Y-m-d');
        });

    // Prepare an array of all days in the month with hearings (if any)
    $calendarDays = [];
    for ($day = $startOfMonth->copy(); $day->lte($endOfMonth); $day->addDay()) {
        $dateStr = $day->format('Y-m-d');
        $calendarDays[] = [
            'date' => $dateStr,
            'hearings' => $hearings->has($dateStr) ? $hearings[$dateStr] : collect()
        ];
    }

    return [
        'currentMonth' => $date->format('F Y'),
        'previousMonth' => $date->copy()->subMonth()->format('Y-m'),
        'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
        'calendarDays' => $calendarDays
    ];
}
}