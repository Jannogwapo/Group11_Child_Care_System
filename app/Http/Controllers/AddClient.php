<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cases;
use App\Models\Gender;
use App\Models\Client;
use App\Models\Status;
use App\Models\IsAStudent;
use App\Models\IsAPwd;
use App\Models\User;
use App\Models\Location;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Traits\CreatesNotifications;

class AddClient extends Controller
{
    use CreatesNotifications;

    public function showAddClientForm()
    {
        // Check if user is a social worker
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Unauthorized access.');
        }

        $genders = Gender::all();
        $cases = Cases::all();
        $status = Status::all();
        $isAStudent = IsAStudent::all();
        $isAPwd = IsAPwd::all();
        $locations = Location::all();
        $userGender = auth()->User->gender_id;
        $branches = Branch::all();
        return view('client/addClient', compact('genders', 'cases', 'status', 'isAStudent', 'isAPwd', 'locations', 'userGender', 'branches'));
    }

    public function index()
    {
        // Check if user is a social worker
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Unauthorized access.');
        }

        $genders = Gender::all();
        $cases = Cases::all();
        $status = Status::all();
        $isAStudent = IsAStudent::all();
        $isAPwd = IsAPwd::all();
        $locations = Location::all();
        $userGender = auth()->User->gender_id;
        $branches = Branch::all();

        return view('client/addClient', compact('genders', 'cases', 'status', 'isAStudent', 'isAPwd', 'locations', 'userGender', 'branches'));
    }

    public function create()
    {
        // Check if user is a social worker
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Unauthorized access.');
        }

        $cases = Cases::all();
        $genders = Gender::all();
        $status = Status::all();
        $isAStudent = IsAStudent::all();
        $isAPwd = IsAPwd::all();
        $locations = Location::all();
        $userGender = auth()->user()->gender_id;
        $branches = Branch::all();

        return view('client/addClient', compact('cases', 'genders', 'status', 'isAStudent', 'isAPwd', 'locations', 'userGender', 'branches'));
    }

    public function store(Request $request)
    {
        // Check if user is a social worker
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Unauthorized access.');
        }

        // Get the user's gender
        $userGender = auth()->user()->gender_id;

        // Validate the form data
        $validated = $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'guardian' => 'required|string|max:255',
            'guardianRelationship' => 'required|string|max:255',
            'parentContact' => 'nullable|string|max:11',
            'case_id' => 'required|integer|exists:case,id',
            'cicl_case_details' => 'nullable|string|max:255',
            'admissionDate' => 'required|date',
            'status_id' => 'required|integer|exists:status,id',
            'isAStudent' => 'required|integer|exists:isAStudent,id',
            'isAPwd' => 'required|integer|exists:isAPwd,id',
            'location_id' => 'integer|exists:location,id',
        ]);

        try {
            // Create a new client with the user's gender
            $client = Client::create([
                'clientLastName' => $validated['lname'],
                'clientFirstName' => $validated['fname'],
                'clientMiddleName' => $validated['mname'] ?? null,
                'clientBirthdate' => $validated['birthdate'],
                'clientAge' => $validated['age'],
                'clientgender' => $userGender, // Use the user's gender
                'clientaddress' => $validated['address'],
                'clientguardian' => $validated['guardian'],
                'clientguardianrelationship' => $validated['guardianRelationship'],
                'guardianphonenumber' => $validated['parentContact'] ?? 'N/A', // Set default value if null
                'case_id' => $validated['case_id'],
                'cicl_case_details' => $validated['cicl_case_details'] ?? null,
                'clientdateofadmission' => $validated['admissionDate'],
                'status_id' => $validated['status_id'],
                'isAStudent' => $validated['isAStudent'],
                'isAPwd' => $validated['isAPwd'],
                'user_id' => Auth::id(),
                'location_id' => 1,
            ]);

            // Create notification for admins
            $this->notifyAdmins(
                'New Client Added',
                "A new client {$client->clientFirstName} {$client->clientLastName} has been added by {$request->user()->name}.",
                route('clients.show', $client->id),
                'client'
            );

            return redirect()->route('clients.view')->with('success', 'Client added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error adding client: ' . $e->getMessage());
        }
    }
}
