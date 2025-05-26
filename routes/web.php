<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\AddClient;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AddHearing;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\LogsController;

Route::get('/', function () {
    return view('welcome');
});
// Public Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LogInController::class, 'showLogInForm'])->name('login');
    Route::post('/login', [LogInController::class, 'login'])->name('login.post');

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::middleware(['can:Access'])->group(function () {
        
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
            Route::patch('/{client}', [ClientController::class, 'update'])->name('clients.update');
        });

        Route::prefix('hearings')->group(function () {
            Route::get('/create', [HearingController::class, 'create'])->name('hearings.create');
            Route::post('/', [HearingController::class, 'store'])->name('hearings.store');
            Route::get('/upcoming', [HearingController::class, 'getUpcomingHearings'])->name('hearings.upcoming');
            Route::get('/{hearing}/edit', [HearingController::class, 'edit'])->name('hearings.edit');
            Route::put('/{hearing}', [HearingController::class, 'update'])->name('hearings.update');
            Route::delete('/{hearing}', [HearingController::class, 'destroy'])->name('hearings.destroy');
        });

    // Calendar Routes
        Route::prefix('calendar')->group(function () {
            Route::get('/', [HearingController::class, 'index'])->name('calendar.index');
            Route::get('/upcoming', [HearingController::class, 'upcoming'])->name('calendar.upcoming');
            Route::get('/completed', [HearingController::class, 'completed'])->name('calendar.completed');
        });

    // Events
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('events.index');
            Route::get('/create', [EventController::class, 'create'])->name('events.create');
            Route::post('/', [EventController::class, 'store'])->name('events.store');
            Route::get('/{event}', [EventController::class, 'show'])->name('events.show');
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        });

    //Indident
        Route::prefix('incidents')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('incidents.index');
            Route::get('/create', [IncidentController::class, 'create'])->name('incidents.create');
            Route::post('/', [IncidentController::class, 'store'])->name('incidents.store');
            Route::get('/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
            Route::delete('/{incident}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
        });

    // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/events', [EventController::class, 'report'])->name('reports.events');
            Route::get('/incidents', [EventController::class, 'incidents'])->name('reports.incidents');
            Route::get('/incidents/boy', [EventController::class, 'incidentsBoy'])->name('reports.incidents.boy');
            Route::get('/incidents/girl', [EventController::class, 'incidentsGirl'])->name('reports.incidents.girl');
        });

        Route::middleware(['can:isAdmin'])->group(function () {
            Route::get('/admin/chart', [ReportController::class, 'report'])->name('admin.report');
            Route::get('/admin/access', [AccessController::class, 'access'])->name('admin.access');
            Route::delete('/admin/access/{user}', [AccessController::class, 'delete'])->name('admin.access.delete');
            Route::get('/admin/logs', [LogsController::class, 'logs'])->name('admin.logs');
            Route::put('/toggle-user/{user}', [AccessController::class, 'toggleUser'])->name('admin.toggle-user');
            Route::get('/admin/report/download', [ReportController::class, 'downloadInHouse'])->name('admin.report.download');
        });
    
    });
 
    Route::post('/logout', [LogInController::class, 'logout'])->name('logout');
    Route::get('/logout', [LogInController::class, 'logout'])->name('logout');
});

// Define middleware for admin routes











