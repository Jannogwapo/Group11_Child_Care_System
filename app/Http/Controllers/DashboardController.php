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

        // Get location-based statistics
        $locationStats = $this->getLocationStats($user);

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

        // Calculate total events (activities + incidents)
        $activityCount = Event::count();
        $incidentCount = Incident::count();
        $activeEvents = $activityCount + $incidentCount;

        // Log the counts for debugging
        \Log::info('Dashboard Events Debug:', [
            'activityCount' => $activityCount,
            'incidentCount' => $incidentCount,
            'activeEvents' => $activeEvents,
        ]);

        // Calculate start of week and days array
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek();
        $now = Carbon::now();

        // Initialize days array
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        // Get weekly hearings
        $weeklyHearings = Hearing::with(['client', 'client.gender'])
            ->whereBetween('hearing_date', [
                $days[0]->copy()->startOfDay(),
                $days[6]->copy()->endOfDay()
            ])
            ->where('status', 'scheduled')
            ->where(function($query) use ($now) {
                $query->where(function($q) use ($now) {
                    // Future dates
                    $q->where('hearing_date', '>', $now->toDateString());
                })
                ->orWhere(function($q) use ($now) {
                    // Today's date but future time
                    $q->where('hearing_date', $now->toDateString())
                      ->whereRaw('TIME(time) > TIME(?)', [$now->format('H:i:s')]);
                });
            })
            ->whereHas('client', function($query) use ($user) {
                if (!Gate::allows('isAdmin')) {
                    $query->where('clientgender', $user->gender_id);
                }
            })
            ->orderBy('hearing_date')
            ->orderBy('time')
            ->get();

        // Transform the hearings data for easier JavaScript handling
        $weeklyHearings = $weeklyHearings->map(function ($hearing) {
            $hearing->hearing_date_formatted = $hearing->hearing_date->format('Y-m-d');
            $hearing->time_formatted = Carbon::parse($hearing->time)->format('H:i:s');
            return $hearing;
        });

        // Debug log the hearings
        \Log::info('Weekly Hearings:', [
            'count' => $weeklyHearings->count(),
            'data' => $weeklyHearings->toArray()
        ]);

        // Get count of upcoming hearings with proper conditions
        $upcomingHearings = Hearing::where('status', 'scheduled')
            ->where(function($query) use ($now) {
                $query->where(function($q) use ($now) {
                    // Future dates
                    $q->where('hearing_date', '>=', $now->toDateString());
                })
                ->orWhere(function($q) use ($now) {
                    // Today's date but future time
                    $q->where('hearing_date', $now->toDateString())
                      ->whereRaw('TIME(time) > TIME(?)', [$now->format('H:i:s')]);
                });
            })
            ->when(!Gate::allows('isAdmin'), function($query) use ($user) {
                $query->whereHas('client', function($q) use ($user) {
                    $q->where('clientgender', $user->gender_id);
                });
            })
            ->count();

        $data = [
            'myClients' => $clientCount,
            'totalClients' => $totalClients,
            'myHearings' => $upcomingHearings,
            'activeEvents' => $activeEvents,
            'activeActivities' => Activity::count(),
            'activeIncidents' => Incident::count(),
            'activeEvents' => $activeEvents,
            'role' => $role,
            'isAdmin' => Gate::allows('isAdmin'),
            'clientStats' => $clientStats,
            'dischargeStats' => $dischargeStats,
            'caseStatusStats' => $caseStatusStats,
            'locationStats' => $locationStats,
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
     * Admin: all clients by case type, separated by gender.
     * Social worker: only clients of the same gender by case type.
     */
    private function getClientStats($user)
    {
        $isAdmin = Gate::allows('isAdmin');
        $labels = Cases::pluck('case_name')->toArray();
        $boys = [];
        $girls = [];

        foreach (Cases::all() as $case) {
            if ($isAdmin) {
                // For admin, get counts for both genders
                $boys[] = $case->clients()->where('clientgender', 1)->count();
                $girls[] = $case->clients()->where('clientgender', 2)->count();
            } else {
                // For social worker, only get their assigned gender
                if ($user->gender_id == 1) {
                    $boys[] = $case->clients()->where('clientgender', 1)->count();
                    $girls[] = array_fill(0, count($labels), 0);
                } else {
                    $boys[] = array_fill(0, count($labels), 0);
                    $girls[] = $case->clients()->where('clientgender', 2)->count();
                }
            }
        }

        return [
            'labels' => $labels,
            'boys' => $boys,
            'girls' => $girls
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
     * Admin: returns discharge count per month for both genders.
     * Social worker: returns discharge count per month for their assigned gender.
     */
    private function getDischargeStats($user)
    {
        $isAdmin = Gate::allows('isAdmin');
        $months = collect([]);
        $boysCount = collect([]);
        $girlsCount = collect([]);

        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->format('M'));

            $baseQuery = Client::whereHas('location', function($query) {
                $query->where('location', 'DISCHARGED');
            })
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month);

            if ($isAdmin) {
                // For admin, get counts for both genders
                $boysCount->push($baseQuery->clone()->where('clientgender', 1)->count());
                $girlsCount->push($baseQuery->clone()->where('clientgender', 2)->count());
            } else {
                // For social worker, only get their assigned gender
                if ($user->gender_id == 1) {
                    $boysCount->push($baseQuery->where('clientgender', 1)->count());
                    $girlsCount->push(0);
                } else {
                    $boysCount->push(0);
                    $girlsCount->push($baseQuery->where('clientgender', 2)->count());
                }
            }
        }

        return [
            'labels' => $months->toArray(),
            'boys' => $boysCount->toArray(),
            'girls' => $girlsCount->toArray()
        ];
    }

    /**
     * Get client statistics by location.
     * Admin: shows all clients in location_id 1 by gender.
     * Social worker: shows only clients of their gender in location_id 1.
     */
    private function getLocationStats($user)
    {
        $isAdmin = Gate::allows('isAdmin');
        $query = Client::query()->where('location_id', 1);

        if (!$isAdmin) {
            $query->where('clientgender', $user->gender_id);
        }

        $stats = $query->select('clientgender', DB::raw('count(*) as count'))
            ->groupBy('clientgender')
            ->get();

        $boys = $stats->where('clientgender', 1)->sum('count');
        $girls = $stats->where('clientgender', 2)->sum('count');

        return [
            'labels' => ['Boys', 'Girls'],
            'data' => [$boys, $girls]
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
