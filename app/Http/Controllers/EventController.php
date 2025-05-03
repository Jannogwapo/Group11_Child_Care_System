<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Providers\AuthServiceProvider;

class EventController extends Controller
{
    public function index()
    {
        // Get upcoming events
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->paginate(10);

        // Get statistics
        $stats = [
            'total' => Event::count(),
            'upcoming' => Event::where('start_date', '>=', now())->count(),
            'completed' => Event::where('end_date', '<', now())->count(),
        ];

        return view('events.index', compact('upcomingEvents', 'stats'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
        ]);

        // Add created_by field
        $validated['created_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'pending';

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }
} 
