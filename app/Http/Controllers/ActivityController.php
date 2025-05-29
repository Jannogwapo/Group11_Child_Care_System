<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'activity_title' => 'required|string|max:255',
            'activity_description' => 'required|string',
            'activity_date' => 'required|date',
            'activity_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the activity
        $activity->update($validated);

        // Handle multiple image uploads
        if ($request->hasFile('activity_images')) {
            foreach ($request->file('activity_images') as $file) {
                $path = $file->store('activities', 'public');
                $activity->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity report updated successfully!');
    }
} 