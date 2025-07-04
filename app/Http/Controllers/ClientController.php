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
use App\Traits\CreatesNotifications;

use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    use CreatesNotifications;

    public function showClient(Request $request)
    {
        $currentFilter = $request->input('filter', 'ALL');
        $genderFilter = $request->input('gender', null);
        $searchTerm = $request->input('search');

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

        // Apply search filter if searchTerm is present
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('clientFirstName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('clientMiddleName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('clientLastName', 'like', '%' . $searchTerm . '%');
            });
        }

        $clients = $query->get();
        $cases = Cases::all();

        // Check if it's an AJAX request
        if ($request->ajax()) {
            // If AJAX, return only the client grid HTML
            return view('components.client_grid', compact('clients'));
        }

        // Otherwise, return the full view
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
        $searchTerm = $request->input('search');

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

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('clientFirstName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('clientMiddleName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('clientLastName', 'like', '%' . $searchTerm . '%');
            });
        }

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
            'isAdmin',
            'searchTerm'
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
            'location_id' => 'required|exists:locations,id',
            'isAPwd' => 'required|boolean',
            'isAStudent' => 'required|boolean'
        ]);

        try {
            $client = Client::create($validated);
            $this->notifyAdmins(
                'Client Added',
                "Client {$client->clientFirstName} {$client->clientLastName} has been added by {$request->user()->name}.",
                route('clients.show', $client->id)
            );
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
        $user = auth()->user();

        // If not admin, allow editing only if the client matches the user's gender
        if (!Gate::allows('isAdmin')) {
            if ($client->clientgender !== $user->gender_id) {
                return redirect()->route('viewClient')->with('error', 'You can only edit clients of your gender.');
            }
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
        try {
            // Get the original client data for comparison
            $originalClient = Client::find($client->id);

            // Prepare update data
            $updateData = [
                'clientLastName' => $request->input('lname'),
                'clientFirstName' => $request->input('fname'),
                'clientMiddleName' => $request->input('mname', ''),
                'clientBirthdate' => $request->input('birthdate'),
                'clientAge' => $request->input('age'),
                'clientaddress' => $request->input('address'),
                'clientguardian' => $request->input('guardian'),
                'clientguardianrelationship' => $request->input('guardianRelationship'),
                'guardianphonenumber' => $request->filled('parentContact') ? $request->input('parentContact') : '',
                'case_id' => $request->input('case_id'),
                'cicl_case_details' => $request->input('cicl_case_details'),
                'clientdateofadmission' => $request->input('admissionDate'),
                'status_id' => $request->input('status_id'),
                'location_id' => $request->input('location_id'),
            ];

            // Check if there are actual changes
            $hasChanges = false;
            foreach ($updateData as $key => $value) {
                if ($originalClient->$key != $value) {
                    $hasChanges = true;
                    break;
                }
            }

            // Only update the updated_at timestamp if there are actual changes
            if ($hasChanges) {
                $updateData['updated_at'] = now();
            }

            \Log::info('Attempting to update with data:', $updateData);

            // Force update using DB facade
            $updated = DB::table('clients')
                ->where('id', $client->id)
                ->update($updateData);

            \Log::info('Update result:', ['success' => $updated]);

            if ($updated && $hasChanges) {
                // Create notification for admins about the client update
                $this->notifyAdmins(
                    'Client Updated',
                    "Client {$updateData['clientFirstName']} {$updateData['clientLastName']} has been updated by {$request->user()->name}.",
                    route('clients.show', $client->id),
                    'client'
                );

                return redirect()->route('clients.view')
                    ->with('success', 'Client updated successfully!');
            } else {
                return back()
                    ->withInput()
                    ->with('error', 'No changes were made to the client record.');
            }
        } catch (\Exception $e) {
            \Log::error('Update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error updating client: ' . $e->getMessage());
        }
    }

    public function searchSuggestions(Request $request)
    {
        $searchTerm = $request->input('query');

        \Log::info('Search suggestions requested with term: ' . $searchTerm);

        $query = Client::with('case') // Eager load the case relationship
                       ->select('id', 'clientFirstName', 'clientMiddleName', 'clientLastName', 'case_id');

        if ($searchTerm && strlen($searchTerm) > 1) { // Apply filter only if search term is more than 1 character
            $query->where(function ($q) use ($searchTerm) {
                $q->where('clientFirstName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('clientMiddleName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('clientLastName', 'like', '%' . $searchTerm . '%');
            });
        }

        $clients = $query->get();

        \Log::info('Search suggestions found: ' . $clients->count());
        \Log::info('Search suggestions data: ' . $clients->toJson());

        return response()->json($clients);
    }
}




