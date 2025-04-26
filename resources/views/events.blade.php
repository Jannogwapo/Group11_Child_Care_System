@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Events</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Total Events</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Upcoming Events</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['upcoming'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Completed Events</h3>
            <p class="text-3xl font-bold text-gray-600">{{ $stats['completed'] }}</p>
        </div>
    </div>

    <!-- Events Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($upcomingEvents as $event)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $event->event_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $event->event_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $event->event_time }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $event->event_location }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Upcoming
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $upcomingEvents->links() }}
    </div>
</div>
@endsection 