<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class AdminGateMiddleware
{
    public function handle($request, Closure $next)
{
    if (Gate::define('admin')) {
        return $next($request); // Allow access if the user is an admin
    } else {
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
}
