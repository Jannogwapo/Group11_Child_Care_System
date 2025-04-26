<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\CalendarHearing;
use App\Models\Activity;
use App\Models\User;
use App\Models\Gender;
use App\Models\Cases;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        if ($role === 'Admin') {
            $data = [
                'totalClients' => Client::count(),
                'upcomingHearings' => CalendarHearing::where('hearing_date', '>=', now())->count(),
                'activeEvents' => Activity::where('activity_date', '>=', now())->count(),
                'totalUsers' => User::count(),
                'clientStats' => $clientStats,
                'dischargeStats' => $dischargeStats,
                'caseStatusStats' => $caseStatusStats,
                'currentMonth' => $calendarData['currentMonth'],
                'previousMonth' => $calendarData['previousMonth'],
                'nextMonth' => $calendarData['nextMonth'],
                'calendarDays' => $calendarData['calendarDays']
            ];
            return view('admin.dashboard', $data);
        } else {
            // For Social Workers and House Parents
            $clientCount = Client::where('user_id', $user->id)->count();
            
            // If user is a social worker, get clients based on their gender
            if ($user->role_id == 1) { // Assuming 1 is the role_id for social workers
                $userGender = Gender::find($user->clientgender);
                if ($userGender) {
                    $clientCount = Client::where('clientgender', $user->clientgender)->count();
                }
            }

            $data = [
                'myClients' => $clientCount,
                'myHearings' => CalendarHearing::whereHas('client', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('hearing_date', '>=', now())->count(),
                'myEvents' => Activity::where('user_id', $user->id)
                    ->where('activity_date', '>=', now())
                    ->count(),
                'role' => $role,
                'clientStats' => $clientStats,
                'dischargeStats' => $dischargeStats,
                'caseStatusStats' => $caseStatusStats,
                'currentMonth' => $calendarData['currentMonth'],
                'previousMonth' => $calendarData['previousMonth'],
                'nextMonth' => $calendarData['nextMonth'],
                'calendarDays' => $calendarData['calendarDays']
            ];
            return view('dashboard', $data);
        }
    }

    private function getCaseStatusStats($user)
    {
        $query = Client::query();
        
        // If user is a social worker, filter by their gender
        if ($user->role_id == 1) {
            $query->where('clientgender', $user->clientgender);
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
            
            $count = Client::where('status_id', 2) // Assuming 2 is the status ID for discharged
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
        $hearings = CalendarHearing::whereYear('hearing_date', $date->year)
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