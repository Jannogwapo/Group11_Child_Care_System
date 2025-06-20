<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Providers\AuthServiceProvider;

class LogInController extends Controller
{
    protected function redirectTo()
{
    if (auth()->user()->can('It')) {
        return route('admin.report');
    }
    return '/'; // Or wherever you want non-IT users to go
}
    public function showLogInForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->access_id === 3) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been disabled. Please contact the administrator.',
                ])->withInput($request->only('email'));
            } elseif ($user->role_id === 3) {
                return redirect()->route('admin.report')
                ->with('_just_logged_in', true);
            }
            return redirect()->route('dashboard')
                ->with('_just_logged_in', true);
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }
    public function logout(Request $request)
    {
        Auth::logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
