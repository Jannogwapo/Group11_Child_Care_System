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

        // Determine if the user is an admin
        $isAdmin = Gate::allows('isAdmin');

        $user = auth()->user();

        if (!$isAdmin) {
            $query->where('clientgender', $user->gender_id);
        }

        if ($isAdmin && $genderFilter && $genderFilter !== 'all') {
            $genderId = $genderFilter === 'male' ? 1 : 2;
            $query->where('clientgender', $genderId);
        }

        // Define location-based filters
        $locationFilters = ['DISCHARGED', 'ESCAPED', 'TRANSFER'];

        if ($currentFilter === 'ALL') {
            // For ALL filter, show all clients
            $query->whereHas('location', function($q) {
                $q->whereIn('location', ['DISCHARGED', 'ESCAPED', 'IN-HOUSE', 'TRANSFER']);
            });
        }
        // Handle location-based filters (DISCHARGED, ESCAPED, TRANSFER)
        else if (in_array($currentFilter, $locationFilters)) {
            $query->whereHas('location', function($q) use ($currentFilter) {
                $q->where('location', $currentFilter);
            });
        }
        // Handle case-based filters (CICL, VAWC, etc.)
        else if ($currentFilter !== 'STUDENTS') {
            // For case filters, only show IN-HOUSE clients
            $query->whereHas('case', function($q) use ($currentFilter) {
                $q->where('case_name', $currentFilter);
            })
            ->whereHas('location', function($q) {
                $q->where('location', 'IN-HOUSE');
            });
        }
        // Handle STUDENTS filter
        else if ($currentFilter === 'STUDENTS') {
            // For students, only show IN-HOUSE students
            $query->where('isAStudent', 1)
                  ->whereHas('location', function($q) {
                      $q->where('location', 'IN-HOUSE');
                  });
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
            'branch',
            'location'
        ]);

        // Get the current user's role and gender
        $user = auth()->user(); 
        $userGender = $user->gender_id;

        // If not admin, filter by user's gender
        if (!Gate::allows('isAdmin')) {
            $query->where('clientgender', $userGender);
        }
        
        // Apply gender filter if specified (only for admin)
        if (Gate::allows('isAdmin') && $genderFilter && $genderFilter !== 'all') {
            $genderId = $genderFilter === 'male' ? 1 : 2;
            $query->where('clientgender', $genderId);
        }

        // Define location-based filters
        $locationFilters = ['DISCHARGED', 'ESCAPED', 'TRANSFER'];
        
        if ($currentFilter === 'ALL') {
            // For ALL filter, show all clients
            $query->whereHas('location', function($q) {
                $q->whereIn('location', ['DISCHARGED', 'ESCAPED', 'IN-HOUSE', 'TRANSFER']);
            });
        }
        // Handle location-based filters (DISCHARGED, ESCAPED, TRANSFER)
        else if (in_array($currentFilter, $locationFilters)) {
            $query->whereHas('location', function($q) use ($currentFilter) {
                $q->where('location', $currentFilter);
            });
        }
        // Handle case-based filters (CICL, VAWC, etc.)
        else if ($currentFilter !== 'STUDENTS') {
            // For case filters, only show IN-HOUSE clients
            $query->whereHas('case', function($q) use ($currentFilter) {
                $q->where('case_name', $currentFilter);
            })
            ->whereHas('location', function($q) {
                $q->where('location', 'IN-HOUSE');
            });
        }
        // Handle STUDENTS filter
        else if ($currentFilter === 'STUDENTS') {
            // For students, only show IN-HOUSE students
            $query->where('isAStudent', 1)
                  ->whereHas('location', function($q) {
                      $q->where('location', 'IN-HOUSE');
                  });
        }
        
        $clients = $query->get();
        
        // Group clients by their primary category
        $groupedClients = $clients->groupBy(function($client) use ($currentFilter) {
            // For ALL view, determine primary category based on location and case type
            if ($currentFilter === 'ALL') {
                // If client is IN-HOUSE, group by case type
                if ($client->location->location === 'IN-HOUSE') {
                    return $client->case->case_name . ' - IN-HOUSE';
                }
                // Otherwise group by location
                return $client->location->location;
            }
            // For location-based filters, group by location
            else if (in_array($currentFilter, $locationFilters)) {
                return $client->location->location;
            }
            // For case-based filters, group by case type
            else if ($currentFilter !== 'STUDENTS') {
                return $client->case->case_name . ' - IN-HOUSE';
            }
            // For STUDENTS filter, group by new/old
            else {
                return $client->created_at->gt(now()->subDays(7)) ? 'NEW' : 'OLD';
            }
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
            'inHouse' => $clients->whereHas('location', function($q) {
                $q->where('location', 'IN-HOUSE');
            })->count(),
            'abandoned' => $clients->whereHas('case', function($q) {
                $q->where('case_name', 'ABANDONED');
            })->whereHas('location', function($q) {
                $q->where('location', 'IN-HOUSE');
            })->count(),
            'discharged' => $clients->whereHas('location', function($q) {
                $q->where('location', 'DISCHARGED');
            })->count(),
            'transferred' => $clients->whereHas('location', function($q) {
                $q->where('location', 'TRANSFER');
            })->count(),
            'students' => $clients->where('isAStudent', 1)
                                ->whereHas('location', function($q) {
                                    $q->where('location', 'IN-HOUSE');
                                })->count(),
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
        // Prevent admin users from accessing edit functionality
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Admin users cannot edit clients.');
        }

        $user = auth()->user();
        
        // Check if the client belongs to the user
            if ($client->user_id !== $user->id) {
                return redirect()->route('clients.view')->with('error', 'You can only edit your own clients.');
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

    public function update(Request $request, Client $client)
    {
        // Prevent admin users from accessing update functionality
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Admin users cannot edit clients.');
        }

        $user = auth()->user();
        
        // Check if the client belongs to the user
            if ($client->user_id !== $user->id) {
                return redirect()->route('clients.view')->with('error', 'You can only edit your own clients.');
        }

        $validated = $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'age' => 'required|integer',
            'gender' => 'required|exists:genders,id',
            'address' => 'required|string',
            'guardian' => 'required|string|max:255',
            'guardianRelationship' => 'required|string|max:255',
            'parentContact' => 'required|string|max:11',
            'case_id' => 'required|exists:case,id',
            'admissionDate' => 'required|date',
            'status_id' => 'required|exists:status,id',
            'isAStudent' => 'required|boolean',
            'isAPwd' => 'required|boolean',
            'location_id' => 'required|exists:location,id'
        ]);

        try {
            $oldLocationId = $client->location_id;
            $oldCaseId = $client->case_id;
            
            $client->update([
                'clientLastName' => $validated['lname'],
                'clientFirstName' => $validated['fname'],
                'clientMiddleName' => $validated['mname'],
                'clientBirthdate' => $validated['birthdate'],
                'clientAge' => $validated['age'],
                'clientgender' => $validated['gender'],
                'clientaddress' => $validated['address'],
                'clientguardian' => $validated['guardian'],
                'clientguardianrelationship' => $validated['guardianRelationship'],
                'guardianphonenumber' => $validated['parentContact'],
                'case_id' => $validated['case_id'],
                'clientdateofadmission' => $validated['admissionDate'],
                'status_id' => $validated['status_id'],
                'isAStudent' => $validated['isAStudent'],
                'isAPwd' => $validated['isAPwd'],
                'location_id' => $validated['location_id']
            ]);

            // Get the new location
            $newLocation = Location::find($validated['location_id']);
            
            // Determine the appropriate filter based on the new location
            if (in_array($newLocation->location, ['DISCHARGED', 'ESCAPED', 'TRANSFER'])) {
                // If location is DISCHARGED, ESCAPED, or TRANSFER, use that as the filter
                $filter = $newLocation->location;
            } else {
                // For IN-HOUSE clients, use case type as filter
                $case = Cases::find($validated['case_id']);
                $filter = strtoupper($case->case_name);
            }

            return redirect()->route('clients.view', ['filter' => $filter])->with('success', 'Client updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating client: ' . $e->getMessage());
        }
    }
}