<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check() && Auth::user()->role_id == 1) { // Admin role is 1
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }
} 
