@extends('layout')
@section('title', 'System Logs')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Logs</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Recent User Activities</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $user)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                        <td class="py-2 px-4 border-b">{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Clients -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Recent Client Activities</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentClients as $client)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $client->clientFirstName }} {{ $client->clientLastName }}</td>
                        <td class="py-2 px-4 border-b">{{ $client->status->status_name ?? 'New' }}</td>
                        <td class="py-2 px-4 border-b">{{ $client->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Events -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Recent Events</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Start Date</th>
                    <th class="py-2 px-4 border-b">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEvents as $event)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $event->title }}</td>
                        <td class="py-2 px-4 border-b">{{ $event->start_date->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 border-b">{{ $event->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Hearings -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Hearings</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Hearing Date</th>
                    <th class="py-2 px-4 border-b">Client</th>
                    <th class="py-2 px-4 border-b">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentHearings as $hearing)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $hearing->hearing_date->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 border-b">{{ $hearing->client->clientFirstName ?? 'N/A' }} {{ $hearing->client->clientLastName ?? '' }}</td>
                        <td class="py-2 px-4 border-b">{{ $hearing->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 