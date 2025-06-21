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
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">Calendar Hearing</h1>
      @cannot('isAdmin')
        <a href="{{ route('hearings.create') }}" class="case-filter-btn flex items-center gap-2">
            <i class="bi bi-calendar-check"></i>
            <i class="bi bi-plus-lg"></i>
            <span class="font-medium">Add Hearing</span>
        </a>
        @endcannot

    <div class="flex items-center gap-4 mb-6 justify-end">
        <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-lg">
            <button id="calendarViewBtn" class="case-filter-btn{{ $activeFilter == 'calendar' ? ' active' : '' }}" type="button">
                <i class="bi bi-calendar3 text-xl"></i>
            </button>
            <button id="listViewBtn" class="case-filter-btn{{ $activeFilter == 'list' ? ' active' : '' }}" type="button">
                <i class="bi bi-list-ul text-xl"></i>
            </button>
        </div>

    </div>

    <!-- Filter Buttons -->
    <div class="mb-6">
        <form method="GET" action="{{ route('calendar.index') }}" class="case-filter-bar flex flex-wrap items-center gap-3" style="margin-bottom: 32px;">
            <input type="hidden" name="month" value="{{ $currentMonth }}">
            @php
                $filterButtons = [
                    'upcoming' => ['icon' => 'bi-calendar-event', 'text' => 'Upcoming'],
                    'ongoing' => ['icon' => 'bi-play-circle', 'text' => 'Ongoing Hearing'],
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
                        class="case-filter-btn{{ request('filter', 'upcoming') == $value ? ' active' : '' }} flex items-center gap-2">
                    <i class="bi {{ $button['icon'] }}"></i>
                    @if($button['text'])
                        <span class="font-medium">{{ $button['text'] }}</span>
                    @endif
                </button>
            @endforeach
        </form>
    </div>

    <!-- Calendar Section -->
    <div class="calendar-section bg-white rounded-xl shadow-lg p-6 mt-3 {{ isset($allHearings) && count($allHearings) > 0 ? 'mb-8' : '' }}">
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
                                            } elseif ($activeFilter === 'ongoing' && in_array($hearing->status, ['ongoing', 'ongoing-upcoming'])) {
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
                                            ($activeFilter === 'editable' ? '#FFC107' :
                                            ($activeFilter === 'ongoing' ? '#FF9800' : '#607D8B')))
                                        )}};
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
                                            ($activeFilter === 'editable' ? 'Editable hearing' :
                                            ($activeFilter === 'ongoing' ? 'Ongoing hearing' : 'All hearings'))))
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
    .calendar-section {
        display: block;
    }
    .list-section {
        display: none;
    }
    .view-active {
        background-color: white !important;
        color: #2563eb !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .case-filter-bar {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        margin-bottom: 32px;
        flex-wrap: wrap;
        text-decoration: none;
    }
    .case-filter-btn {
        display: inline-block;
        margin: 0;
        padding: 6px 18px;
        border-radius: 20px;
        border: none;
        background: #e0e7ef;
        color: #222;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        text-decoration: none;
    }
    .case-filter-btn.active, .case-filter-btn:hover {
        background: var(--primary-color, #2563eb);
        color: var(--text-color, #fff);
    }
</style>

<script>
    const hearingsData = @json($hearings);

    // View toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const calendarViewBtn = document.getElementById('calendarViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const calendarSection = document.querySelector('.calendar-section');
        const listSection = document.querySelector('.list-section');

        function setActiveView(view) {
            if (view === 'calendar') {
                calendarSection.style.display = 'block';
                listSection.style.display = 'none';
                calendarViewBtn.classList.add('view-active');
                listViewBtn.classList.remove('view-active');
            } else {
                calendarSection.style.display = 'none';
                listSection.style.display = 'block';
                listViewBtn.classList.add('view-active');
                calendarViewBtn.classList.remove('view-active');
            }
        }

        calendarViewBtn.addEventListener('click', () => setActiveView('calendar'));
        listViewBtn.addEventListener('click', () => setActiveView('list'));

        // Set initial view
        setActiveView('calendar');
    });

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
                            
                                <a href="/hearings/${hearing.id}/edit" class="px-3 py-1 bg-blue-500 text-black rounded text-xs w-32 text-center">
                                    <i class="bi bi-pencil-square"></i>
                                </a>  
                            
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
<div class="list-section mt-8">
    @if($isAdmin)
        <!-- All Hearings for Admin -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-700">All Hearings</h3>
                <div class="flex items-center gap-2">
                    @php
                        $inHouseCount = $allHearings->where('client.status', 'in-house')->count();
                        $ongoingCount = $allHearings->whereIn('status', ['ongoing', 'ongoing-upcoming'])->count();
                    @endphp
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        In-House: {{ $inHouseCount }}
                    </span>
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                        Ongoing: {{ $ongoingCount }}
                    </span>
                </div>
            </div>
            @php
                $filteredHearings = $allHearings->filter(function($hearing) {
                    $activeFilter = request('filter', 'upcoming');
                    $isInHouse = $hearing->client->status === 'in-house';
                    $isOngoing = in_array($hearing->status, ['ongoing', 'ongoing-upcoming']);
                    
                    // Show hearings based on filter and status
                    if ($activeFilter === 'ongoing') {
                        return $isOngoing;
                    } elseif ($activeFilter === 'upcoming') {
                        return $isInHouse && $hearing->status === 'scheduled';
                    } elseif ($activeFilter === 'finished') {
                        return $isInHouse && $hearing->status === 'completed';
                    } elseif ($activeFilter === 'postponed') {
                        return $isInHouse && $hearing->status === 'postponed';
                    } elseif ($activeFilter === 'editable') {
                        return $isInHouse && $hearing->status === 'scheduled';
                    } elseif ($activeFilter === 'all') {
                        return $isInHouse || $isOngoing;
                    }
                    
                    return $isInHouse;
                });
            @endphp
            @if($filteredHearings->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Information</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hearing Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Court Information</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($filteredHearings as $hearing)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-person text-blue-600 text-lg"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Case: {{ $hearing->client->case->case_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    {{ $hearing->client->status === 'in-house' ? 'In-House' : 'External' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-calendar-date text-gray-400"></i>
                                                {{ $hearing->hearing_date ? \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') : 'N/A' }}
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <i class="bi bi-clock text-gray-400"></i>
                                                {{ $hearing->time ? \Carbon\Carbon::parse($hearing->time)->format('h:i A') : 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-building text-gray-400"></i>
                                                {{ $hearing->branch->branchName ?? 'N/A' }}
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <i class="bi bi-person-badge text-gray-400"></i>
                                                {{ $hearing->branch->judgeName ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-800',
                                                'ongoing' => 'bg-orange-100 text-orange-800',
                                                'ongoing-upcoming' => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'postponed' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusColor = $statusColors[$hearing->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                            {{ ucfirst(str_replace('-', ' ', $hearing->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            @if($hearing->client->case && $hearing->client->case->case_name === 'CICL')
                                                <a href="{{ route('clients.show', $hearing->client->id) }}?case=CICL"
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    View
                                                </a>
                                            @else
                                                <a href="{{ route('clients.show', $hearing->client->id) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    View
                                                </a>
                                            @endif
                                            @if(request('filter') === 'editable' || in_array($hearing->status, ['ongoing', 'ongoing-upcoming']))
                                                <a href="{{ route('hearings.edit', $hearing->id) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors duration-200">
                                                    <i class="bi bi-pencil-square mr-1"></i>
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="bi bi-calendar-x text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No Hearings Found</h3>
                    <p class="text-gray-500">There are no hearings matching your current filter.</p>
                </div>
            @endif
        </div>
    @else
        @php
            $userGender = auth()->user()->gender_id;
            $hearings = $userGender == 1 ? $maleHearings : $femaleHearings;
            $genderText = $userGender == 1 ? 'Male' : 'Female';
            
            $filteredHearings = $hearings->filter(function($hearing) {
                $activeFilter = request('filter', 'upcoming');
                $isInHouse = $hearing->client->status === 'in-house';
                $isOngoing = in_array($hearing->status, ['ongoing', 'ongoing-upcoming']);
                
                // Show hearings based on filter and status
                if ($activeFilter === 'ongoing') {
                    return $isOngoing;
                } elseif ($activeFilter === 'upcoming') {
                    return $isInHouse && $hearing->status === 'scheduled';
                } elseif ($activeFilter === 'finished') {
                    return $isInHouse && $hearing->status === 'completed';
                } elseif ($activeFilter === 'postponed') {
                    return $isInHouse && $hearing->status === 'postponed';
                } elseif ($activeFilter === 'editable') {
                    return $isInHouse && $hearing->status === 'scheduled';
                } elseif ($activeFilter === 'all') {
                    return $isInHouse || $isOngoing;
                }
                
                return $isInHouse;
            });
            
            $inHouseCount = $hearings->where('client.status', 'in-house')->count();
            $ongoingCount = $hearings->whereIn('status', ['ongoing', 'ongoing-upcoming'])->count();
        @endphp
        <!-- Hearings for Social Worker based on gender -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-700">For {{ $genderText }} Clients</h3>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        In-House: {{ $inHouseCount }}
                    </span>
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                        Ongoing: {{ $ongoingCount }}
                    </span>
                </div>
            </div>
            @if($filteredHearings->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Information</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hearing Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Court Information</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($filteredHearings as $hearing)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-person text-blue-600 text-lg"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Case: {{ $hearing->client->case->case_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    {{ $hearing->client->status === 'in-house' ? 'In-House' : 'External' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-calendar-date text-gray-400"></i>
                                                {{ $hearing->hearing_date ? \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') : 'N/A' }}
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <i class="bi bi-clock text-gray-400"></i>
                                                {{ $hearing->time ? \Carbon\Carbon::parse($hearing->time)->format('h:i A') : 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-building text-gray-400"></i>
                                                {{ $hearing->branch->branchName ?? 'N/A' }}
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <i class="bi bi-person-badge text-gray-400"></i>
                                                {{ $hearing->branch->judgeName ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-800',
                                                'ongoing' => 'bg-orange-100 text-orange-800',
                                                'ongoing-upcoming' => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'postponed' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusColor = $statusColors[$hearing->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                            {{ ucfirst(str_replace('-', ' ', $hearing->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            @if($hearing->client->case && $hearing->client->case->case_name === 'CICL')
                                                <a href="{{ route('clients.show', $hearing->client->id) }}?case=CICL"
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    View
                                                </a>
                                            @else
                                                <a href="{{ route('clients.show', $hearing->client->id) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    View
                                                </a>
                                            @endif
                                            @if(request('filter') === 'editable' || in_array($hearing->status, ['ongoing', 'ongoing-upcoming']))
                                                <a href="{{ route('hearings.edit', $hearing->id) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors duration-200">
                                                    <i class="bi bi-pencil-square mr-1"></i>
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="bi bi-calendar-x text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No {{ strtolower($genderText) }} Client Hearings</h3>
                    <p class="text-gray-500">There are no {{ strtolower($genderText) }} client hearings matching your current filter.</p>
                </div>
            @endif
        </div>
    @endif
</div>

@endsection
