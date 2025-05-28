<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Providers\AuthServiceProvider;

class LogInController extends Controller
{
    
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
                ->with('success', 'Welcome back, ' . $user->name . '!');
            }
            return redirect()->route('dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
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
