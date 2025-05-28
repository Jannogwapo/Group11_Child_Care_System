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
    <div class="flex justify-between items-center mb-6">
        @cannot('isAdmin')
            <h1 class="text-2xl font-bold text-gray-800 text-center"> Calendar Hearing</h1>
            <a href="{{ route('hearings.create') }}" class="px-4 py-2 rounded-full bg-pink-500 text-black hover:bg-pink-600 ml-auto">
                <i class="bi bi-calendar-check"></i><i class="bi bi-plus-lg">Add Hearing</i>
            </a>
        @else
            <h1 class="text-2xl font-bold text-gray-800 text-center">Overall Calendar Hearing</h1>
        @endcannot
    </div>

    <form method="GET" action="{{ route('calendar.index') }}" class="mb-6 flex items-center gap-4">
        <input type="hidden" name="month" value="{{ $currentMonth }}">
        <button type="submit" name="filter" value="upcoming" class="px-4 py-2 rounded {{ request('filter', 'upcoming') == 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
            Upcoming
        </button>
        <button type="submit" name="filter" value="finished" class="px-4 py-2 rounded {{ request('filter') == 'finished' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
            Finished
        </button>
        <button type="submit" name="filter" value="postponed" class="px-4 py-2 rounded {{ request('filter') == 'postponed' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
            Posponed
        </button>
        <button type="submit" name="filter" value="all" class="px-4 py-2 rounded {{ request('filter') == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
            All
        </button>
        <button type="submit" name="filter" value="editable" class="px-4 py-2 rounded {{ request('filter') == 'editable' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
            <i class="bi bi-pencil-square"></i>
        </button>
    </form>

    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-3 {{ isset($allHearings) && count($allHearings) > 0 ? 'mb-8' : '' }}">
        <table id="cal-table" class="w-full">
            <tr>
                <td colspan="7" class="text-center py-2">
                    <a href="{{ route('calendar.index', ['month' => \Carbon\Carbon::now()->format('Y-m')]) }}" class="text-blue-500 hover:text-blue-700">Today</a>
                </td>
            </tr>
            <tr class="border-b">
                <td class="py-2 text-center">
                    <a href="{{ route('calendar.index', ['month' => $previousMonth]) }}" class="text-blue-500 hover:text-blue-700">&lt;&lt; Prev</a>
                </td>
                <th colspan="5" class="text-center text-xl font-semibold">{{ $currentDate->format('F Y') }}</th>
                <td class="text-center">
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
                        <td class="border p-2 min-h-[120px] {{ $isToday ? 'bg-blue-50' : '' }} {{ !$isCurrentMonth ? 'bg-gray-50' : '' }}"
                            style="position:relative; cursor:pointer;"
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
                                                    || ($dateStr === $today->format('Y-m-d') && $hearing->time > $today->format('H:i:s'))
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
                                        background:#dc3545;
                                        border-radius:50%;
                                        position:absolute;
                                        top:8px;
                                        right:8px;"
                                        title="{{ $activeFilter === 'editable' ? 'Editable hearing on this date' : 'Filtered hearing on this date' }}"></span>
                                @endif
                                @php $currentDay++; @endphp
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>
    </div>

    <!-- Calendar Popup -->
    <div id="calendarPopup" class="calendar-popup-modal" tabindex="-1">
        <div id="calendarPopupContent"></div>
    </div>
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
    /* Popup container */
    .calendar-popup-modal {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 1000;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
    }
    .calendar-popup-modal > #calendarPopupContent,
    .calendar-popup-modal > .flex {
        margin: 0 auto;
    }
    #calendarPopupContent {
        background: #fff;
        border-radius: 12px;
        padding: 32px 24px;
        min-width: 500px;
        max-width: 98vw;
        min-height: 120px;
        max-height: 60vh;
        overflow-y: auto;
        box-shadow: 0 2px 32px rgba(0,0,0,0.25);
    }
    .calendar-popup .popup-arrow {
        position: absolute;
        top: -10px;
        left: 30px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-bottom: 10px solid #fff;
        filter: drop-shadow(0 -2px 2px rgba(0,0,0,0.1));
    }
</style>

<script>
    const hearingsData = @json($hearings);

    function showCalendarPopup(e, date) {
        const popup = document.getElementById('calendarPopup');
        const content = document.getElementById('calendarPopupContent');
        let html = '';

        const filteredHearings = hearingsData[date] ? hearingsData[date] : [];

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
                            <a href="/hearings/${hearing.id}/edit" class="px-3 py-1 bg-blue-500 text-black rounded text-xs w-32 text-center"><i class="bi bi-pencil-square"></i></a>
                            <a href="/clients/${hearing.client.id}" class="px-3 py-1 bg-green-500 text-black rounded text-xs w-32 text-center"><i class="bi bi-ticket-detailed"></i></a>
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
@endsection