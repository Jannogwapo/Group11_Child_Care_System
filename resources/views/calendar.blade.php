@extends('layout')

@section('title', 'Calendar')

@section('content')
@php
    $previousMonth = $previousMonth ?? '';
    $nextMonth = $nextMonth ?? '';
    $currentMonth = $currentMonth ?? \Carbon\Carbon::now()->format('Y-m');
    $currentDate = $currentDate ?? \Carbon\Carbon::now();
@endphp
<div class="container mx-auto px-4 py-8">
    <!-- Notification Display -->
    @if(session('notification'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p>{{ session('notification') }}</p>
        </div>
    @endif
@if(auth()->user()->role_id == 2)
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Calendar Hearing</h1>
        
            <a href="{{ route('hearings.create') }}" class="px-4 py-2 rounded-full bg-pink-500 text-black hover:bg-pink-600 ml-auto">
                Add Hearing
            </a>
            </div>
        @endif


    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 {{ isset($allHearings) && count($allHearings) > 0 ? 'mb-8' : '' }}">
        <table id="cal-table" class="w-full">
            <tr>
                <td colspan="7" class="text-center py-2">
                    <a href="{{ route('calendar.index', ['month' => Carbon\Carbon::now()->format('Y-m')]) }}" class="text-blue-500 hover:text-blue-700">Today</a>
                </td>
            </tr>
            <tr class="border-b">
                <td class="py-2">
                    <a href="{{ route('calendar.index', ['month' => $previousMonth]) }}" class="text-blue-500 hover:text-blue-700">&lt;&lt; Prev</a>
                </td>
                <th colspan="5" class="text-center text-xl font-semibold">{{ $currentDate->format('F Y') }}</th>
                <td class="text-right">
                    <a href="{{ route('calendar.index', ['month' => $nextMonth]) }}" class="text-blue-500 hover:text-blue-700">Next &gt;&gt;</a>
                </td>
            </tr>
            <tr class="bg-gray-100">
                <th class="day-headings p-2">Sun</th>
                <th class="day-headings p-2">Mon</th>
                <th class="day-headings p-2">Tue</th>
                <th class="day-headings p-2">Wed</th>
                <th class="day-headings p-2">Thu</th>
                <th class="day-headings p-2">Fri</th>
                <th class="day-headings p-2">Sat</th>
            </tr>

            @php
                $daysInWeek = 7;
                $currentDay = 1;
                $firstDayOfMonth = Carbon\Carbon::parse($currentMonth)->startOfMonth()->dayOfWeek;
                $daysInMonth = Carbon\Carbon::parse($currentMonth)->daysInMonth;
                $today = Carbon\Carbon::now();
            @endphp

            @for($week = 0; $week < 6; $week++)
                <tr>
                    @for($dayOfWeek = 0; $dayOfWeek < $daysInWeek; $dayOfWeek++)
                        @php
                            $isCurrentMonth = ($week == 0 && $dayOfWeek >= $firstDayOfMonth) || 
                                            ($week > 0 && $currentDay <= $daysInMonth);
                            $isToday = $isCurrentMonth && 
                                     $currentDay == $today->day && 
                                     Carbon\Carbon::parse($currentMonth)->month == $today->month;
                            $date = $isCurrentMonth ? Carbon\Carbon::parse($currentMonth)->day($currentDay) : null;
                        @endphp
                        
                        <td class="border p-2 min-h-[120px] {{ $isToday ? 'bg-blue-50' : '' }} {{ !$isCurrentMonth ? 'bg-gray-50' : '' }}">
                            @if($isCurrentMonth)
                                <div class="font-semibold mb-2">{{ $currentDay }}</div>
                                @if(isset($hearings[$date->format('Y-m-d')]))
                                    <div class="space-y-1 mt-2">
                                        @foreach($hearings[$date->format('Y-m-d')] as $hearing)
                                            <div class="p-1 rounded bg-blue-50 text-xs text-gray-800">
                                                {{ $hearing->client->clientLastName }}, {{ $hearing->client->clientFirstName }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @php $currentDay++; @endphp
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>

    <!-- Hearings List Section - Only show if there are hearings -->
    @if(isset($allHearings) && count($allHearings) > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">All Hearings</h2>
                <span class="text-sm text-gray-600">{{ count($allHearings) }} Total Hearings</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judge</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allHearings as $hearing)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $hearing->hearing_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($hearing->time)->format('g:i A') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $hearing->client->clientLastName }}, {{ $hearing->client->clientFirstName }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $hearing->judge->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $hearing->status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                        ($hearing->status === 'completed' ? 'bg-green-100 text-green-800' :
                                        ($hearing->status === 'postponed' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800'))
                                    }}">
                                        {{ ucfirst($hearing->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('hearings.edit', $hearing) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('hearings.destroy', $hearing) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this hearing?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    #cal-table {
        border-collapse: collapse;
        width: 100%;
    }
    #cal-table td, #cal-table th {
        border: 1px solid #e2e8f0;
    }
    .day-headings {
        background-color: #f7fafc;
        font-weight: 600;
        text-align: center;
    }
</style>
@endsection