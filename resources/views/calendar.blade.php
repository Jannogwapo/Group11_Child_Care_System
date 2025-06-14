@extends('layout')

@section('title', 'Calendar')

@section('content')

@php
    $previousMonth = $previousMonth ?? '';
    $nextMonth = $nextMonth ?? '';
    $currentMonth = $currentMonth ?? \Carbon\Carbon::now()->format('Y-m');
    $currentDate = $currentDate ?? \Carbon\Carbon::now();
    $activeFilter = request('filter', 'upcoming');
@endphp

<div class="container mx-auto px-4 py-8">
    <!-- Notification Display -->
    @if(session('notification'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p>{{ session('notification') }}</p>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        @cannot('isAdmin')
            <h1 class="text-2xl font-bold text-gray-800 text-center">Calendar Hearing</h1>
            <a href="{{ route('hearings.create') }}" 
               class="px-6 py-2.5 rounded-full bg-gradient-to-r from-pink-500 to-pink-600 text-white hover:from-pink-600 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="bi bi-calendar-check"></i>
                <i class="bi bi-plus-lg"></i>
                <span class="font-medium">Add Hearing</span>
            </a>
        @else
            <h1 class="text-2xl font-bold text-gray-800 text-center">Overall Calendar Hearing</h1>
        @endcannot
    </div>

    <!-- Filter Buttons -->
    <div class="mb-6">
        <form method="GET" action="{{ route('calendar.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="month" value="{{ $currentMonth }}">
            
            @php
                $filterButtons = [
                    'upcoming' => ['icon' => 'bi-calendar-event', 'text' => 'Upcoming'],
                    'finished' => ['icon' => 'bi-check-circle', 'text' => 'Completed'],
                    'postponed' => ['icon' => 'bi-clock-history', 'text' => 'Postponed'],
                    'all' => ['icon' => 'bi-grid', 'text' => 'All'],
                    'editable' => ['icon' => 'bi-pencil-square', 'text' => '']
                ];
            @endphp

            @foreach($filterButtons as $value => $button)
                <button type="submit" 
                        name="filter" 
                        value="{{ $value }}"
                        class="px-4 py-2.5 rounded-lg flex items-center gap-2 transition-all duration-200
                               {{ request('filter', 'upcoming') == $value 
                                  ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md' 
                                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="bi {{ $button['icon'] }}"></i>
                    @if($button['text'])
                        <span class="font-medium">{{ $button['text'] }}</span>
                    @endif
                </button>
            @endforeach
        </form>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-3 {{ isset($allHearings) && count($allHearings) > 0 ? 'mb-8' : '' }}">
        <table id="cal-table" class="w-full">
            <tr>
                <td colspan="7" class="text-center py-3">
                    <a href="{{ route('calendar.index', ['month' => \Carbon\Carbon::now()->format('Y-m')]) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Today</a>
                </td>
            </tr>
            <tr class="border-b border-gray-200">
                <td class="py-3 text-center">
                    <a href="{{ route('calendar.index', ['month' => $previousMonth]) }}" 
                       class="text-blue-600 hover:text-blue-800 transition-colors flex items-center justify-center gap-1">
                        <i class="bi bi-chevron-double-left"></i>
                        <span>Prev</span>
                    </a>
                </td>
                <th colspan="5" class="text-center text-xl font-semibold text-gray-800">
                    {{ $currentDate->format('F Y') }}
                </th>
                <td class="text-center">
                    <a href="{{ route('calendar.index', ['month' => $nextMonth]) }}" 
                       class="text-blue-600 hover:text-blue-800 transition-colors flex items-center justify-center gap-1">
                        <span>Next</span>
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </td>
            </tr>
            <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                <th class="day-headings p-3 text-gray-600">Sun</th>
                <th class="day-headings p-3 text-gray-600">Mon</th>
                <th class="day-headings p-3 text-gray-600">Tue</th>
                <th class="day-headings p-3 text-gray-600">Wed</th>
                <th class="day-headings p-3 text-gray-600">Thu</th>
                <th class="day-headings p-3 text-gray-600">Fri</th>
                <th class="day-headings p-3 text-gray-600">Sat</th>
            </tr>

            @php
                $daysInWeek = 7;
                $currentDay = 1;
                $firstDayOfMonth = \Carbon\Carbon::parse($currentMonth)->startOfMonth()->dayOfWeek;
                $daysInMonth = \Carbon\Carbon::parse($currentMonth)->daysInMonth;
                $today = \Carbon\Carbon::now();
            @endphp

            @for($week = 0; $week < 6; $week++)
                <tr>
                    @for($dayOfWeek = 0; $dayOfWeek < $daysInWeek; $dayOfWeek++)
                        @php
                            $isCurrentMonth = ($week == 0 && $dayOfWeek >= $firstDayOfMonth) || 
                                            ($week > 0 && $currentDay <= $daysInMonth);
                            $isToday = $isCurrentMonth && 
                                     $currentDay == $today->day && 
                                     \Carbon\Carbon::parse($currentMonth)->month == $today->month;
                            $date = $isCurrentMonth ? \Carbon\Carbon::parse($currentMonth)->day($currentDay) : null;
                        @endphp
                        <td class="border border-gray-100 p-2 min-h-[120px] transition-colors duration-200
                                 {{ $isToday ? 'bg-blue-50' : '' }} 
                                 {{ !$isCurrentMonth ? 'bg-gray-50' : '' }}
                                 {{ $isCurrentMonth ? 'hover:bg-gray-50' : '' }}"
                            style="position:relative; {{ $isCurrentMonth ? 'cursor:pointer;' : '' }}"
                            @if($isCurrentMonth)
                                onclick="showCalendarPopup(event, '{{ $date->format('Y-m-d') }}')"
                            @endif
                        >
                            @if($isCurrentMonth)
                                <div class="font-semibold mb-2 text-center cursor-pointer" @if($isCurrentMonth) onclick="showCalendarPopup(event, '{{ $date->format('Y-m-d') }}')" @endif>
                                    {{ $currentDay }}
                                </div>
                                @php
                                    $dateStr = $date->format('Y-m-d');
                                    $filteredHearings = [];
                                    if (isset($hearings[$dateStr])) {
                                        foreach ($hearings[$dateStr] as $hearing) {
                                            if ($activeFilter === 'upcoming'
                                                && $hearing->status === 'scheduled'
                                                && (
                                                    $dateStr > $today->format('Y-m-d')
                                                    || ($dateStr === $today->format('Y-m-d') && $hearing->time >= $today->format('H:i:s'))
                                                )) {
                                                $filteredHearings[] = $hearing;
                                            } elseif ($activeFilter === 'editable'
                                                && $hearing->status === 'scheduled'
                                                && (
                                                    $dateStr < $today->format('Y-m-d')
                                                    || ($dateStr === $today->format('Y-m-d') && $hearing->time <= $today->format('H:i:s'))
                                                )) {
                                                $filteredHearings[] = $hearing;
                                            } elseif ($activeFilter === 'finished' && $hearing->status === 'completed') {
                                                $filteredHearings[] = $hearing;
                                            } elseif ($activeFilter === 'postponed' && $hearing->status === 'postponed') {
                                                $filteredHearings[] = $hearing;
                                            } elseif ($activeFilter === 'all') {
                                                $filteredHearings[] = $hearing;
                                            }
                                        }
                                    }
                                @endphp
                                @if(count($filteredHearings) > 0)
                                    <span style="display:inline-block;
                                        width:10px;
                                        height:10px;
                                        background:{{ 
                                            $activeFilter === 'finished' ? '#4CAF50' :
                                            ($activeFilter === 'upcoming' ? '#2196F3' :
                                            ($activeFilter === 'postponed' ? '#F44336' :
                                            ($activeFilter === 'editable' ? '#FFC107' : '#607D8B'))) 
                                        }};
                                        border-radius:50%;
                                        position:absolute;
                                        top:8px;
                                        right:8px;
                                        cursor:pointer;"
                                        onclick="showCalendarPopup(event, '{{ $date->format('Y-m-d') }}')"
                                        title="{{ 
                                            $activeFilter === 'upcoming' ? 'Upcoming hearing' :
                                            ($activeFilter === 'finished' ? 'Completed hearing' :
                                            ($activeFilter === 'postponed' ? 'Postponed hearing' :
                                            ($activeFilter === 'editable' ? 'Editable hearing' : 'All hearings')))
                                        }}"></span>
                                @endif
                                @php $currentDay++; @endphp
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>
</div>

<!-- Calendar Popup -->
<div id="calendarPopup" class="calendar-popup-modal" tabindex="-1">
    <div id="calendarPopupContent"></div>
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
        font-weight: 600;
        text-align: center;
    }
    .calendar-popup-modal {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 1000;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
    }
    #calendarPopupContent {
        background: #fff;
        border-radius: 16px;
        padding: 32px 24px;
        min-width: 500px;
        max-width: 98vw;
        min-height: 120px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 4px 32px rgba(0,0,0,0.1);
        animation: modalFade 0.2s ease-out;
    }
    @keyframes modalFade {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>

<script>
    const hearingsData = @json($hearings);

    function showCalendarPopup(e, date) {
        const popup = document.getElementById('calendarPopup');
        const content = document.getElementById('calendarPopupContent');
        let html = '';

        const filteredHearings = hearingsData[date] ? hearingsData[date] : [];
        const isEditableFilter = @json(request('filter')) === 'editable';

        if (filteredHearings.length > 0) {
            html += `<table class="w-full mb-4"><tbody>`;
            filteredHearings.forEach(hearing => {
                html += `
                <tr>
                    <td class="p-4 align-top w-3/4">
                        <div><strong>Client:</strong> ${hearing.client.clientLastName}, ${hearing.client.clientFirstName}</div>
                    </td>
                    <td class="p-4 w-1/4 text-right">
                        <div class="flex flex-col space-y-2 items-end">
                            ${isEditableFilter ? 
                                `<a href="/hearings/${hearing.id}/edit" class="px-3 py-1 bg-blue-500 text-black rounded text-xs w-32 text-center">
                                    <i class="bi bi-pencil-square"></i>
                                </a>` : ''
                            }
                            <a href="/hearings/${hearing.id}" class="px-3 py-1 bg-green-500 text-black rounded text-xs w-32 text-center">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                `;
            });
            html += `</tbody></table>`;
        } else {
            html = '<div>No hearings for this date.</div>';
        }

        html += `
            <div class="flex justify-end space-x-2 mt-4">
                <button onclick="hideCalendarPopup()" class="px-4 py-2 bg-gray-400 text-white rounded">Close</button>
            </div>
        `;

        content.innerHTML = html;
        popup.style.display = 'flex';
    }

    function hideCalendarPopup() {
        document.getElementById('calendarPopup').style.display = 'none';
    }

    document.addEventListener('click', function(event) {
        const popup = document.getElementById('calendarPopup');
        const content = document.getElementById('calendarPopupContent');
        if (popup.style.display === 'flex' && !content.contains(event.target) && !event.target.classList.contains('font-semibold')) {
            popup.style.display = 'none';
        }
    });
</script>

<!-- Hearings List Section -->
<div class="mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Hearing Schedule</h2>

    @if($isAdmin)
        <!-- All Hearings for Admin -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">All Hearings</h3>
            @if($allHearings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client Name</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judge</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allHearings as $hearing)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($hearing->time)->format('h:i A') }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->branch->branchName ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->branch->judgeName ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">
                                        @if($hearing->client->case && $hearing->client->case->case_name === 'CICL')
                                            <a href="{{ route('clients.show', $hearing->client->id) }}?case=CICL" class="text-blue-600 hover:underline">View Details</a>
                                        @else
                                            <a href="{{ route('clients.show', $hearing->client->id) }}" class="text-blue-600 hover:underline">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">No hearings found for this filter.</p>
            @endif
        </div>
    @else
        <!-- Male Hearings for Social Worker -->
        @if($maleHearings->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">For Male Clients</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Client Name</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Date</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Time</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Branch</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Judge</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maleHearings as $hearing)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($hearing->time)->format('h:i A') }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->branch->branchName ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->branch->judgeName ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">
                                        @if($hearing->client->case && $hearing->client->case->case_name === 'CICL')
                                            <a href="{{ route('clients.show', $hearing->client->id) }}?case=CICL" class="text-blue-600 hover:underline">View Details</a>
                                        @else
                                            <a href="{{ route('clients.show', $hearing->client->id) }}" class="text-blue-600 hover:underline">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Female Hearings for Social Worker -->
        @if($femaleHearings->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">For Female Clients</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Client Name</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Date</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Time</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Branch</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Judge</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($femaleHearings as $hearing)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ \Carbon\Carbon::parse($hearing->time)->format('h:i A') }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->branch->branchName ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $hearing->branch->judgeName ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">
                                        @if($hearing->client->case && $hearing->client->case->case_name === 'CICL')
                                            <a href="{{ route('clients.show', $hearing->client->id) }}?case=CICL" class="text-blue-600 hover:underline">View Details</a>
                                        @else
                                            <a href="{{ route('clients.show', $hearing->client->id) }}" class="text-blue-600 hover:underline">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>

@endsection