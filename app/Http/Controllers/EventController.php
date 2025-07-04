<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\Incident;
use Illuminate\Support\Facades\Storage;
use App\Traits\CreatesNotifications;
use App\Models\EventImage;

class EventController extends Controller
{
    use CreatesNotifications;

    /**
     * Display a listing of events and incidents.
     */
    public function index()
    {
        // Fetch events
        $events = Event::orderBy('start_date', 'desc')->get();

        // Fetch incidents
        $incidents = Incident::orderBy('incident_date', 'desc')->get();

        // Pass both to the view
        return view('events.index', compact('events', 'incidents'));
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
                'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Create the event
            $event = new Event();
            $event->title = $validated['event_name'];
            $event->description = $validated['description'];
            $event->start_date = $validated['event_date'];
            $event->end_date = $validated['event_date'];
            $event->created_by = auth()->id();
            $event->status = 'pending';

            // Keep old single-image logic for backward compatibility
            if ($request->hasFile('picture')) {
                try {
                    $path = $request->file('picture')->store('images', 'public');
                    $event->picture = $path;
                } catch (\Exception $e) {
                    return back()->with('error', 'Error uploading image: ' . $e->getMessage())->withInput();
                }
            }

            $event->save();

            // Handle multiple image uploads
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    $path = $file->store('images', 'public');
                    $event->images()->create(['image_path' => $path]);
                }
            }

            // Create notification for admins
            $this->notifyAdmins(
                'New Event Created',
                "A new event titled '{$event->title}' has been created by {$request->user()->name}.",
                route('events.show', $event->id),
                'event'
            );

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
            // Save event details for notification before deletion
            $eventTitle = $event->title;
            $userName = auth()->user()->name ?? 'Unknown User';

            // Delete the associated image if it exists
            if ($event->picture) {
                Storage::disk('public')->delete($event->picture);
            }

            $event->delete();

            // Notify admins about the event deletion
            $this->notifyAdmins(
                'Event Deleted',
                "Event '{$eventTitle}' has been deleted by {$userName}.",
                route('admin.logs', ['filter' => 'events']),
                'event'
            );

            return redirect()->route('events.index')
                ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting event: ' . $e->getMessage());
        }
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'event_title' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date',
            'event_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the event fields
        $event->title = $validated['event_title'];
        $event->description = $validated['event_description'];
        $event->start_date = $validated['event_date'];
        $event->end_date = $validated['event_date'];
        $event->save();

        // Delete selected images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imgId) {
                $img = EventImage::find($imgId);
                if ($img) {
                    \Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }
        }
        // Delete single picture if requested
        if ($request->has('delete_picture') && $event->picture) {
            \Storage::disk('public')->delete($event->picture);
            $event->picture = null;
            $event->save();
        }

        // If new images are uploaded, delete all old images and replace
        if ($request->hasFile('event_images')) {
            // Delete all EventImage images
            foreach ($event->images as $img) {
                \Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
            // Delete single picture if exists
            if ($event->picture) {
                \Storage::disk('public')->delete($event->picture);
                $event->picture = null;
                $event->save();
            }
            // Save new images
            foreach ($request->file('event_images') as $file) {
                $path = $file->store('events', 'public');
                $event->images()->create(['image_path' => $path]);
            }
        }

        // Notify admins about the update if there were changes
        $this->notifyAdmins(
            'Event Updated',
            "Event '{$event->title}' has been updated by {$request->user()->name}.",
            route('events.show', $event->id),
            'event'
        );

        return redirect()->route('events.show', $event)
            ->with('success', 'Event report updated successfully!');
    }
}



