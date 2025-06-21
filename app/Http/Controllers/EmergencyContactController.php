<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmergencyContact;
use App\Models\Client;
use App\Traits\CreatesNotifications;

class EmergencyContactController extends Controller
{
    use CreatesNotifications;

    public function index(Client $client)
    {
        $emergencyContacts = $client->emergencyContacts()->orderBy('priority')->get();
        return view('emergency-contacts.index', compact('client', 'emergencyContacts'));
    }

    public function create(Client $client)
    {
        return view('emergency-contacts.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'priority' => 'required|in:primary,secondary,tertiary',
            'is_available_24_7' => 'boolean',
            'special_instructions' => 'nullable|string'
        ]);

        $validated['client_id'] = $client->id;
        $validated['is_available_24_7'] = $request->has('is_available_24_7');

        EmergencyContact::create($validated);

        $this->notifyAdmins(
            'Emergency Contact Added',
            "Emergency contact {$validated['contact_name']} has been added for client {$client->clientFirstName} {$client->clientLastName}.",
            route('clients.show', $client->id)
        );

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Emergency contact added successfully!');
    }

    public function edit(Client $client, EmergencyContact $emergencyContact)
    {
        return view('emergency-contacts.edit', compact('client', 'emergencyContact'));
    }

    public function update(Request $request, Client $client, EmergencyContact $emergencyContact)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'priority' => 'required|in:primary,secondary,tertiary',
            'is_available_24_7' => 'boolean',
            'special_instructions' => 'nullable|string'
        ]);

        $validated['is_available_24_7'] = $request->has('is_available_24_7');

        $emergencyContact->update($validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Emergency contact updated successfully!');
    }

    public function destroy(Client $client, EmergencyContact $emergencyContact)
    {
        $emergencyContact->delete();

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Emergency contact deleted successfully!');
    }
} 