@extends('layout')
@section('title', 'System Logs')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">System Logs</h1>

        <!-- Filter Dropdown -->
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

    <!-- Display Logs Based on Filter -->
    @if($filter === 'all' || $filter === 'clients')

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        @if($filter==='clients')
        <h2 class="text-xl font-semibold mb-4">Recent Clients</h2>
        @endif
        @if($recentClients->isEmpty())
            <p class="text-gray-500">No recent clients found.</p>
        @else
        <table class="min-w-full bg-white">

            <tbody>
                @foreach($recentClients as $client)
                <tr>
                    <th>
                        <i class="bi bi-people text-6xl text-gray-700"></i>
                    </th>
                    <td class="p-6">
                        <p class="text-meduim font-semibold">{{ $client->user->name ?? 'Unknown' }}</p>
                        <p class="">Added a new Client name {{ $client->clientFirstName ?? 'N/A' }} {{ $client->clientLastName ?? '' }}</p>
                    </td>
                    <td class="py-6 px-4 border-b">{{ $client->created_at->format('Y-m-d H:i:s') }}</td>
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
                    <th>
                    <i class="bi bi-calendar-check">
                    </th>
                    <td>
                        <td class="py-2 px-4 border-b">{{ $hearing->hearing_date->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 border-b">{{ $hearing->client->clientFirstName ?? 'N/A' }} {{ $hearing->client->clientLastName ?? '' }}</td>
                        <td class="py-2 px-4 border-b">{{ $hearing->created_at->format('Y-m-d H:i:s') }}</td>
                    </td>
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
                        <p>{{ $event->title }}</p>
                        <p>{{ $event->start_date->format('Y-m-d H:i:s') }}</p>
                        
                        </td>
                        <td class="py-2 px-4 border-b">{{ $event->created_at->format('Y-m-d H:i:s') }}</td>
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
                        <p>{{ $incident->incident_type }}</p>
                        <p>{{ $incident->created_at->format('Y-m-d H:i:s') }}</p>
                        
                        </td>
                        <td class="py-2 px-4 border-b">Client Involve: {{ $incident->client->clientFirstName ?? 'None' }} {{ $incident->client->clientLastName ?? '' }}</td>
                        <td class="py-2 px-4 border-b">{{ $incident->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endif

@endsection