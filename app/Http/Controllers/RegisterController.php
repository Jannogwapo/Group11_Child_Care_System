<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gender;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Providers\AuthServiceProvider;

class RegisterController extends Controller
{
    /**
     * Handle the registration form submission.
     */ 
    public function store(Request $request)
    {
        // Validate the form data
         dd(request()->all());
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|integer|exists:user_role,id',
            'gender' => 'required|integer|exists:genders,id',
            'access_id'=>'required|integer|exists:access_logs,id',
        ]);
       
        

        try {
            // Create a new user
            User::create([
                'name' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['position'],
                'gender_id' => $validated['gender'],
                'remember_token' => Str::random(10),
                'email_verified_at' => now(),
                'access_id' => 1,
                        ]);
                  
            return redirect('login')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            // Log the error for debugging


            return back()->with('error', 'An unexpected error occurred. Please try again later.')->withInput();
        }
    }

    /**
     * Show the registration form.
     */
    public function create()
    {
        $roles = UserRole::all(); // Fetch all roles
        $genders = Gender::all(); // Fetch all genders
    
        return view('register', compact('roles', 'genders'));
  
    }
}