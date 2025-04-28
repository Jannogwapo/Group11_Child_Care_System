@extends('layout')

@section('title', 'Calendar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Calendar Hearing</h1>
        @if(auth()->user()->role_id == 2)
            <a href="{{ route('hearings.create') }}" class="px-4 py-2 rounded-full bg-pink-500 text-black hover:bg-pink-600 ml-auto">
                Add Hearing
            </a>
        @endif
    </div>

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
                                    <div class="space-y-2">
                                        @foreach($hearings[$date->format('Y-m-d')] as $hearing)
                                            <div class="p-2 rounded {{ $hearing->status === 'cancelled' ? 'bg-red-50' : 'bg-blue-50' }}">
                                                <div class="text-sm font-medium {{ $hearing->status === 'cancelled' ? 'text-red-600' : 'text-blue-600' }}">
                                                    {{ $hearing->client->clientLastName }}, {{ $hearing->client->clientFirstName }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $hearing->time }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $hearing->judge->name }}
                                                </div>
                                                <div class="text-xs mt-1">
                                                    <span class="px-2 py-1 rounded-full text-xs {{ 
                                                        $hearing->status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                                        ($hearing->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                        ($hearing->status === 'postponed' ? 'bg-yellow-100 text-yellow-800' :
                                                        'bg-red-100 text-red-800'))
                                                    }}">
                                                        {{ ucfirst($hearing->status) }}
                                                    </span>
                                                </div>
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
            <h2 class="text-xl font-semibold mb-4">All Hearings</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judge</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allHearings as $hearing)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $hearing->hearing_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $hearing->time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $hearing->client->clientLastName }}, {{ $hearing->client->clientFirstName }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $hearing->judge->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs {{ 
                                        $hearing->status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                        ($hearing->status === 'completed' ? 'bg-green-100 text-green-800' :
                                        ($hearing->status === 'postponed' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800'))
                                    }}">
                                        {{ ucfirst($hearing->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('hearings.edit', $hearing) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('hearings.destroy', $hearing) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this hearing?')">Delete</button>
                                    </form>
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