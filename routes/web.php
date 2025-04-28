<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\AddClient;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AddHearing;
use Illuminate\Support\Facades\Gate;

// Public Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LogInController::class, 'showLogInForm'])->name('login');
    Route::post('/login', [LogInController::class, 'login'])->name('login.post');

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'create'])->name('register'); // Updated route name
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store'); // Updated route name
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Client Management
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/view', [ClientController::class, 'showClient'])->name('clients.view');
        Route::get('/create', [AddClient::class, 'create'])->name('clients.create');
        Route::post('/', [AddClient::class, 'store'])->name('clients.store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('clients.show');
        Route::get('/{client}/view', [ClientController::class, 'show'])->name('viewClient');
        Route::get('/view/all', [ClientController::class, 'showClient'])->name('viewClient');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    });

    // Calendar Routes
    Route::prefix('calendar')->group(function () {
        Route::get('/', [HearingController::class, 'index'])->name('calendar.index');
        Route::get('/upcoming', [HearingController::class, 'upcoming'])->name('calendar.upcoming');
        Route::get('/completed', [HearingController::class, 'completed'])->name('calendar.completed');
    });

    // Hearing Management Routes
    Route::prefix('hearings')->group(function () {
        Route::get('/create', [HearingController::class, 'create'])->name('hearings.create');
        Route::post('/', [HearingController::class, 'store'])->name('hearings.store');
        Route::get('/{hearing}/edit', [HearingController::class, 'edit'])->name('hearings.edit');
        Route::put('/{hearing}', [HearingController::class, 'update'])->name('hearings.update');
        Route::delete('/{hearing}', [HearingController::class, 'destroy'])->name('hearings.destroy');
    });

    // Events
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('events.index');
        Route::get('/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/', [EventController::class, 'store'])->name('events.store');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/events', [EventController::class, 'report'])->name('reports.events');
        Route::get('/incidents', [EventController::class, 'incidents'])->name('reports.incidents');
        Route::get('/incidents/boy', [EventController::class, 'incidentsBoy'])->name('reports.incidents.boy');
        Route::get('/incidents/girl', [EventController::class, 'incidentsGirl'])->name('reports.incidents.girl');
    });

    // Admin Routes
    Route::get('/admin/chart', function () {
        if (Gate::allows('admin')) {
            return app(DashboardController::class)->chart();
        }
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    })->name('admin.chart');

    Route::get('/admin/access', function () {
        if (Gate::allows('admin')) {
            return app(DashboardController::class)->access();
        }
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    })->name('admin.access');

    Route::get('/admin/logs', function () {
        if (Gate::allows('admin')) {
            return app(DashboardController::class)->logs();
        }
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    })->name('admin.logs');

    // Test route for admin gate
    Route::get('/test-admin', function () {
        return Gate::allows('isAdmin');
        // if (Gate::allows('isAdmin', auth()->user())) {
            
        //     return 'You are an admin!';
        // }
        // return 'You are not an admin.';
    })->name('test.admin');

    // Logout
    Route::post('/logout', [LogInController::class, 'logout'])->name('logout');
    Route::get('/logout', [LogInController::class, 'logout'])->name('logout');
}); 