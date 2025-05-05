<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_type' => 'required|string|max:255',
            'incident_description' => 'required|string',
            'incident_date' => 'required|date',
            'incident_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if present
        if ($request->hasFile('incident_image')) {
            $path = $request->file('incident_image')->store('incidents', 'public');
            $validated['incident_image'] = $path;
        }

        // Add user_id to the validated data
        $validated['user_id'] = auth()->id();

        Incident::create($validated);

        return redirect()->route('events.index')->with('success', 'Incident reported successfully!');
    }

    public function show(Incident $incident)
    {
        return view('incidents.show', compact('incident'));
    }

    public function destroy(Incident $incident)
    {
        try {
            // Delete the associated image if it exists
            if ($incident->incident_image) {
                Storage::disk('public')->delete($incident->incident_image);
            }

            $incident->delete();

            return redirect()->route('events.index')
                ->with('success', 'Incident report deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting incident report: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the incidents.
     */
    public function index()
    {
        // Fetch all incidents, ordered by the incident date
        $incidents = Incident::orderBy('incident_date', 'desc')->get();

        // Return the view with the incidents data
        return view('incidents.index', compact('incidents'));
    }
}