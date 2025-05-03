<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\Incident;
use Illuminate\Support\Facades\Storage;

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

        $events = Event::orderBy('start_date', 'desc')->get();
        $incidents = Incident::orderBy('incident_date', 'desc')->get();

        return view('events.index', compact('upcomingEvents', 'stats', 'events', 'incidents'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'event_name' => 'required|string|max:255',
                'description' => 'required|string',
                'event_date' => 'required|date',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Create the event
            $event = new Event();
            $event->title = $validated['event_name'];
            $event->description = $validated['description'];
            $event->start_date = $validated['event_date'];
            $event->end_date = $validated['event_date'];
            $event->created_by = auth()->id();
            $event->status = 'pending';

            // Handle file upload if present
            if ($request->hasFile('picture')) {
                try {
                    $path = $request->file('picture')->store('images', 'public');
                    $event->picture = $path;
                } catch (\Exception $e) {
                    return back()->with('error', 'Error uploading image: ' . $e->getMessage())->withInput();
                }
            }

            $event->save();

            return redirect()->route('events.index')
                ->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating event: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        try {
            // Delete the associated image if it exists
            if ($event->picture) {
                Storage::disk('public')->delete($event->picture);
            }

            $event->delete();

            return redirect()->route('events.index')
                ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting event: ' . $e->getMessage());
        }
    }
}
