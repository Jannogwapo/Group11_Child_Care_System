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
use App\Models\PhilippineProvince;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AddClient extends Controller
{
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
        $provinces = PhilippineProvince::all();
        return view('client/addClient', compact('genders', 'cases', 'status', 'isAStudent', 'isAPwd', 'locations', 'provinces'));
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
        $provinces = PhilippineProvince::all();
        
        return view('client/addClient', compact('genders', 'cases', 'status', 'isAStudent', 'isAPwd', 'locations', 'provinces'));
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
        $provinces = PhilippineProvince::all();
        
        return view('client/addClient', compact('cases', 'genders', 'status', 'isAStudent', 'isAPwd', 'locations', 'provinces'));
    }

    public function store(Request $request)
    {
        // Check if user is a social worker
        if (Gate::allows('isAdmin')) {
            return redirect()->route('clients.view')->with('error', 'Unauthorized access.');
        }
       

        // Validate the form data
        $validated = $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'age' => 'required|integer',
            'gender' => 'required|integer|exists:gender,id',
            'address' => 'required|string|max:255',
            'guardian' => 'required|string|max:255',
            'guardianRelationship' => 'required|string|max:255',
            'parentContact' => 'required|string|max:11',
            'case_id' => 'required|integer|exists:case,id',
            'admissionDate' => 'required|date',
            'status_id' => 'required|integer|exists:status,id',
            'isAStudent' => 'required|integer|exists:isAStudent,id',
            'isAPwd' => 'required|integer|exists:isAPwd,id',
            'location_id' => 'required|integer|exists:location,id',
        ]);

        try {
            // Create a new client
            Client::create([
                'clientLastName' => $validated['lname'],
                'clientFirstName' => $validated['fname'],
                'clientMiddleName' => $validated['mname'] ?? null,
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
                'user_id' => Auth::id(),
                'branch_id' => null,
                'location_id' => $validated['location_id'],
            ]);

            return redirect()->route('clients.view')->with('success', 'Client added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('clients.view')->with('error', 'Error adding client: ' . $e->getMessage());
        }
    }
}
