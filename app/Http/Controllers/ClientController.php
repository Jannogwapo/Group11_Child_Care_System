<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Gender;
use App\Models\Cases;
use App\Models\Status;
use App\Models\IsAStudent;
use App\Models\IsAPwd;
use App\Models\Branch;
use App\Models\Location;
use Illuminate\Support\Facades\Gate;


use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{

    public function showClient(Request $request)
    {
        $currentFilter = $request->input('filter', 'ALL');
        $genderFilter = $request->input('gender', null);
        
        $query = Client::with([
            'gender',
            'status',
            'case',
            'isAStudent',
            'isAPwd',
            'branch',
            'location'
        ]);

        // Get the current user's role and gender
        $user = auth()->user();
        $isAdmin = Gate::allows('isAdmin');
        $userGender = $user->gender_id; // Get the gender_id from user

        // If not admin, filter by user's gender
        if (!Gate::allows('isAdmin')) {
            $query->where('clientgender', $userGender);
        }
        
        // Apply gender filter if specified (only for admin)
        if (Gate::allows('isAdmin')&& $genderFilter && $genderFilter !== 'all') {
            $genderId = $genderFilter === 'male' ? 1 : 2; // Assuming 1 is male, 2 is female
            $query->where('clientgender', $genderId);
        }
        
        // Apply filters
        if ($currentFilter !== 'ALL') {
            switch ($currentFilter) {
                case 'CICL':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'CICL');
                    });
                    break;
                case 'VAW C':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'VAW C');
                    });
                    break;
                case 'SA':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'SA');
                    });
                    break;
                case 'CAR':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'CAR');
                    });
                    break;
                case 'ABANDONED':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'ABANDONED');
                    });
                    break;
                case 'STUDENTS':
                    $query->where('isAStudent', 1);
                    break;
                case 'ESCAPE':
                    $query->where('location_id', 2);
                    break;
                case 'IN-HOUSE':
                    $query->where('location_id', 1);
                    break;
                case 'DISCHARGED':
                    $query->where('location_id', 3);
                    break;
            }
        }
        
        $clients = $query->get();
        $cases = Cases::all();
        
        return view('viewClient', compact(
            'clients',
            'currentFilter',
            'genderFilter',
            'isAdmin',
            'cases'
        ));
    }

    public function index(Request $request)
    {
        $currentFilter = $request->input('filter', 'ALL');
        $genderFilter = $request->input('gender', null);
        
        $query = Client::with([
            'gender',
            'status',
            'case',
            'isAStudent',
            'isAPwd',
            'branch'
        ]);

        // Get the current user's role and gender
        $user = auth()->user(); 
        $userGender = $user->gender_id; // Get the gender_id from user

        // If not admin, filter by user's gender
        if (!Gate::allows('isAdmin')) {
            $query->where('clientgender', $userGender);
        }
        
        // Apply gender filter if specified (only for admin)
        if (Gate::allows('isAdmin') && $genderFilter && $genderFilter !== 'all') {
            $genderId = $genderFilter === 'male' ? 1 : 2; // Assuming 1 is male, 2 is female
            $query->where('clientgender', $genderId);
        }
        
        // Apply filters
        if ($currentFilter !== 'ALL') {
            switch ($currentFilter) {
                case 'CICL':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'CICL');
                    });
                    break;
                case 'VAW C':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'VAW C');
                    });
                    break;
                case 'SA':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'SA');
                    });
                    break;
                case 'CAR':
                    $query->whereHas('case', function($q) {
                        $q->where('case_name', 'CAR');
                    });
                    break;
                case 'ABANDONED':
                    $query->where('case_name', 'ABANDONED');
                    break;
                case 'DISCHARGED':
                    $query->where('location_id', 3);
                    break;
                case 'STUDENTS':
                    $query->where('isAStudent', 1);
                    break;
                case 'IN-HOUSE':
                    $query->where('location_id', 1);
                    break;
                case 'ESCAPE':
                    $query->where('location_id', 2);
                    break;
            }
        }
        
        $clients = $query->get();
        
        // Group clients by status and add badges
        $groupedClients = $clients->groupBy(function($client) use ($currentFilter) {
            if ($currentFilter === 'STUDENTS') {
                return $client->created_at->gt(now()->subDays(7)) ? 'NEW' : 'OLD';
            }
            
            if ($client->created_at->gt(now()->subDays(7))) {
                return 'NEW';
            } elseif ($client->status) {
                return strtoupper($client->status->status_name);
            };
        });

        // Get all necessary data for the view
        $genders = Gender::all();
        $cases = Cases::all();
        $status = Status::all();
        $isAStudent = IsAStudent::all();
        $isAPwd = IsAPwd::all();
        $branches = Branch::all();
        
        // Get statistics
        $statistics = [
            'total' => $clients->count(),
            'new' => $clients->where('created_at', '>', now()->subDays(7))->count(),
            'inHouse' => $clients->whereNull('status_id')->count(),
            'abandoned' => $clients->where('status_id', 2)->count(),
            'discharged' => $clients->where('status_id', 3)->count(),
            'students' => $clients->where('isAStudent', 1)->count(),
        ];
        
        return view('viewClient', compact(
            'groupedClients', 
            'currentFilter', 
            'genderFilter',
            'genders',
            'cases',
            'status',
            'isAStudent',
            'isAPwd',
            'branches',
            'statistics',
            'isAdmin'
        ));
    }

    public function create()
    {
        $genders = Gender::all();
        $cases = Cases::all();
        $statuses = Status::all();
        $branches = Branch::all();
        $locations = Location::all();
        
        return view('client.addClient', compact(
            'genders',
            'cases',
            'statuses',
            'branches',
            'locations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clientFirstName' => 'required|string|max:255',
            'clientLastName' => 'required|string|max:255',
            'clientgender' => 'required|exists:genders,id',
            'clientdateofadmission' => 'required|date',
            'clientaddress' => 'required|string',
            'guardianphonenumber' => 'required|string',
            'case_id' => 'required|exists:cases,id',
            'status_id' => 'required|exists:statuses,id',
            'branch_id' => 'required|exists:branches,id',
            'location_id' => 'required|exists:locations,id',
            'isAPwd' => 'required|boolean',
            'isAStudent' => 'required|boolean'
        ]);

        try {
            $client = Client::create($validated);
            return redirect()->route('viewClient')->with('success', 'Client added successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error adding client: ' . $e->getMessage());
        }
    }

    public function show(Client $client)
    {
        return view('client.show', compact('client'));
    }

    public function edit(Client $client)
    {
        // Check if user is authorized to edit this client
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Unauthorized access.');
        }

        $genders = Gender::all();
        $cases = Cases::all();
        $statuses = Status::all();
        $isAStudent = IsAStudent::all();
        $isAPwd = IsAPwd::all();
        $branches = Branch::all();
        $locations = Location::all();

        return view('client.edit', compact(
            'client',
            'genders',
            'cases',
            'statuses',
            'isAStudent',
            'isAPwd',
            'branches',
            'locations'
        ));
    }
} 