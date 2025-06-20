@extends('layout')
@section('title', 'System Logs')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">System Logs</h1>

        <form method="GET" action="{{ route('admin.logs') }}" class="flex space-x-2">
    <button type="submit" name="filter" value="all" class="px-4 py-2 rounded {{ $filter === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
        All
    </button>
    <button type="submit" name="filter" value="clients" class="px-4 py-2 rounded {{ $filter === 'clients' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
        Clients
    </button>
    <button type="submit" name="filter" value="hearings" class="px-4 py-2 rounded {{ $filter === 'hearings' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
        Hearings
    </button>
    <button type="submit" name="filter" value="events" class="px-4 py-2 rounded {{ $filter === 'events' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
        Events
    </button>
    <button type="submit" name="filter" value="incidents" class="px-4 py-2 rounded {{ $filter === 'incidents' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
        Incidents
    </button>
</form>
    </div>

    @if($filter === 'all' || $filter === 'clients')
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        @if($filter==='clients')
        <h2 class="text-xl font-semibold mb-4">Client Activity</h2>
        @endif
        @if($recentClients->isEmpty())
            <p class="text-gray-500">No recent client activity found.</p>
        @else
        <table class="min-w-full bg-white">
            <tbody>
                @foreach($recentClients as $client)
                <tr>
                    <th>
                        <i class="bi bi-people text-6xl text-gray-700"></i>
                    </th>
                    <td class="p-6">
                        <p class="user-name font-bold"><strong>{{ $client->user->name ?? 'Unknown' }}</strong></p>
                        <p class="">
                            @if($client->created_at->eq($client->updated_at))
                                Added a new Client name <strong> {{ $client->clientFirstName ?? 'N/A' }} {{ $client->clientLastName ?? '' }}</strong>
                            @else
                                Updated Client <strong> {{ $client->clientFirstName ?? 'N/A' }} {{ $client->clientLastName ?? '' }}</strong>
                            @endif
                        </p>
                    </td>
                    <td class="py-6 px-4 border-b text-end">
                        <span style="color: #bbb; font-size: 0.8em;">
                            @if($client->created_at->eq($client->updated_at))
                                {{ $client->created_at }}
                            @else
                                {{ $client->updated_at }}
                            @endif
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endif

@if($filter === 'all' || $filter === 'hearings')
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        @if($filter === 'hearings')
        <h2 class="text-xl font-semibold mb-4">Recent Hearings</h2>
        @endif
        @if($recentHearings->isEmpty() && $filter ==='hearings')
            <p class="text-gray-500">No recent hearings found.</p>
        @else
        <table class="min-w-full bg-white">
            <tbody>
                @foreach($recentHearings as $hearing)
                <tr>
                    <th>
                        <i class="bi bi-calendar-check"></i>
                    </th>
                    <td class="py-2 px-4 border-b">
    {{ $hearing->hearing_date->format('Y-m-d') }}
    {{ \Carbon\Carbon::parse($hearing->time)->format('h:i A') }}
</td>
                    <td class="py-2 px-4 border-b">
                        <strong>{{ $hearing->user->name }}</strong> added a hearing to Client <strong> {{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}</strong>
                    </td>
                    <td>
                        <span style="color: #bbb; font-size: 0.8em;">{{ $hearing->created_at }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endif

@if($filter === 'all' || $filter === 'events')
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        @if($filter ==='events')
        <h2 class="text-xl font-semibold mb-4">Recent Events</h2>
        @endif
        @if($recentEvents->isEmpty())
            <p class="text-gray-500">No recent events found.</p>
        @else
        <table class="min-w-full bg-white">
            <tbody>
                @foreach($recentEvents as $event)
                    <tr>
                        <th>
                            <i class="bi bi-calendar-event text-6xl text-gray-700"></i>
                        </th>

                        <td>
                            <strong>{{ $event->createdBy->name ?? 'Unknown User' }}</strong> created an event:
                            <span class="event-title">{{ $event->title }}</span>

                        </td>
                        <td>
                        <span style="color: #bbb; font-size: 0.8em;">{{ $event->created_at->format('Y-m-d H:i:s') }} </span>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endif

@if($filter === 'all' || $filter === 'incidents')
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    @if($filter ==='incidents')
    <h2 class="text-xl font-semibold mb-4">Recent Incidents</h2>
    @endif
    @if($recentIncidents->isEmpty())
        <p class="text-gray-500">No recent incidents found.</p>
    @else
        <table class="min-w-full bg-white">
            <tbody>
                @foreach($recentIncidents as $incident)
                    <tr>
                        <th>
                        <i class="bi bi-calendar-event"></i>
                        </th>
                        <td>
                            <strong>{{ $incident->user->name ?? 'Unknown User' }}</strong> created an incident:
                            <br>
                            <br>
                            <span class="incident-title">{{ $incident->title }}</span>
                        <p>{{ $incident->incident_type }}</p>                        </td>
                        <td class="py-2 px-4 border-b">Client Involve: None</td>

                        <td>
                        <span style="color: #bbb; font-size: 0.8em;">{{ $incident->created_at->format('Y-m-d H:i:s') }} </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endif

@endsection

<style>
    /* Ensure header elements retain their styling on the Logs page */
    .notification-badge {
        color: var(--text-color) !important;
    }

    .btn-logout {
        background-color: transparent !important;
        border: 1px solid var(--accent-color) !important;
        color: var(--text-color) !important;
    }

    .btn-logout i {
        color: var(--text-color) !important;
    }

    /* Container styling */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Header and filter buttons */
    .flex.justify-between {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2.5rem;
    }

    /* Filter buttons styling */
    button[type="submit"] {
        transition: all 0.3s ease;
        border: 1px solid transparent;
        font-weight: 500;
        letter-spacing: 0.5px;
        background-color: #f3f4f6;
        color: #4b5563;
    }

    button[type="submit"]:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: #e5e7eb;
    }

    /* Active filter button */
    button[type="submit"].bg-blue-500 {
        background-color: #7AE2CF !important;
        color: #1A3A34 !important;
        box-shadow: 0 2px 4px rgba(122, 226, 207, 0.3);
    }

    button[type="submit"].bg-blue-500:hover {
        background-color: #6ACEC0 !important;
        box-shadow: 0 4px 6px rgba(122, 226, 207, 0.4);
    }

    /* Card styling */
    .bg-white.rounded-lg {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        margin-bottom: 2rem;
        border-radius: 15px;
        overflow: hidden;
    }

    .bg-white.rounded-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    /* Section headers */
    h2.text-xl {
        padding: 1.5rem;
        margin: 0;
        border-bottom: 2px solid #f3f4f6;
        background: #f8fafc;
        font-size: 1.4rem;
        color: #1e293b;
    }

    /* Table styling */
    table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    tr {
        transition: background-color 0.2s ease;
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    td, th {
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Icon styling */
    .bi {
        transition: transform 0.2s ease;
        font-size: 2rem;
    }

    tr:hover .bi {
        transform: scale(1.1);
    }

    /* Text styling */
    .text-gray-800 {
        color: #1f2937;
        font-weight: 600;
    }

    .text-gray-500 {
        color: #6b7280;
    }

    /* Empty state styling */
    p.text-gray-500 {
        text-align: center;
        padding: 3rem;
        font-style: italic;
        color: #94a3b8;
        font-size: 1.1rem;
    }

    /* User name styling */
    .user-name {
        color: #000000; /* Make the name black */
        margin-bottom: 0.25rem; /* Add a small margin below the name */
    }

    /* Custom scrollbar */
    .min-w-full::-webkit-scrollbar {
        height: 6px;
    }

    .min-w-full::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .min-w-full::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .min-w-full::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Page title */
    h1.text-2xl {
        font-size: 2rem;
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
</style>
