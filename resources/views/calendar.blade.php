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
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 text-center">Calendar Hearing</h1>
        @cannot('isAdmin')

            <a href="{{ route('hearings.create') }}" class="px-4 py-2 rounded-full bg-pink-500 text-black hover:bg-pink-600 ml-auto">
                Add Hearing
            </a>
            </div>
        @endcannot


    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 {{ isset($allHearings) && count($allHearings) > 0 ? 'mb-8' : '' }}">
        <table id="cal-table" class="w-full">
            <tr>
                <td colspan="7" class="text-center py-2">
                    <a href="{{ route('calendar.index', ['month' => Carbon\Carbon::now()->format('Y-m')]) }}" class="text-blue-500 hover:text-blue-700">Today</a>
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
                                @if(isset($hearings[$date->format('Y-m-d')]))
                                    @php
                                        $hasUpcoming = false;
                                        $now = \Carbon\Carbon::now();
                                        foreach($hearings[$date->format('Y-m-d')] as $hearing) {
                                            $datePart = $hearing['hearing_date'];
                                            $timePart = $hearing['time'];
                                            if (strlen($datePart) > 10) {
                                                $hearingDateTime = \Carbon\Carbon::parse($datePart);
                                            } else {
                                                $hearingDateTime = \Carbon\Carbon::parse($datePart.' '.$timePart);
                                            }
                                            // Show the dot if the hearing is today (any time) or in the future
                                            if ($hearingDateTime->isSameDay($now) || $hearingDateTime->greaterThan($now)) {
                                                $hasUpcoming = true;
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if($hasUpcoming)
                                        <span style="display:inline-block;
                                            width:10px;
                                            height:10px;
                                            background:#dc3545;
                                            border-radius:50%;
                                            position:absolute;
                                            top:8px;
                                            right:8px;"></span>
                                    @endif
                                    <div class="space-y-1 mt-2">
                                        @foreach($hearings[$date->format('Y-m-d')] as $hearing)
                                            @php
                                                $datePart = $hearing['hearing_date'];
                                                $timePart = $hearing['time'];
                                                if (strlen($datePart) > 10) {
                                                    $hearingDateTime = \Carbon\Carbon::parse($datePart);
                                                } else {
                                                    $hearingDateTime = \Carbon\Carbon::parse($datePart.' '.$timePart);
                                                }
                                            @endphp
                                            @if($hearingDateTime->greaterThanOrEqualTo(\Carbon\Carbon::now()))
                                                <div class="p-1 rounded bg-blue-50 text-xs text-gray-800">
                                                    <span style="display:inline-block;
                                                        width:10px;
                                                        height:10px;
                                                        background:#dc3545;
                                                        border-radius:50%;
                                                        position:absolute;
                                                        top:8px;
                                                        right:8px;"></span>
                                                </div>
                                            @endif
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

    <!-- Hearing Details Modal -->


<!-- Calendar Popup -->
<div id="calendarPopup" class="calendar-popup-modal">
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
  min-width: 500px;      /* Increased width */
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

    function showHearings(date) {
        const modal = document.getElementById('hearingModal');
        const modalDate = document.getElementById('modalDate');
        const modalContent = document.getElementById('modalContent');
        modalDate.textContent = date;

        let content = '';
        if (hearingsData[date]) {
            hearingsData[date].forEach(hearing => {
                content += `
                    <table class="w-full mb-4">
                    <tr>
                    <th class="border p-4 bg-gray-50">
                        <div><strong>Client:</strong> ${hearing.client.clientLastName}, ${hearing.client.clientFirstName}</div>
                        <div><strong>Branch:</strong> ${hearing.branch ? hearing.branch.branchName : 'N/A'}</div>
                        <div><strong>Judge:</strong> ${hearing.branch ? hearing.branch.judgeName : 'N/A'}</div>
                        <div><strong>Time:</strong> ${hearing.time}</div>
                        <div><strong>Status:</strong> ${hearing.status}</div>
                        <div><strong>Notes:</strong> ${hearing.notes ?? ''}</div>
                    </th>
                    <th class="border p-4 bg-gray-50 text-right">
                        <div class="flex space-x-2 mt-2">
                            <a href="/hearings/${hearing.id}/edit" class="px-3 py-1 bg-blue-500 text-white rounded text-xs">Edit Hearing</a>
                            <a href="/clients/${hearing.client.id}" class="px-3 py-1 bg-green-500 text-white rounded text-xs">Show Client Details</a>
                        </div>
                    </th>
                    </tr>
                    </table>
                `;
            });
        } else {
            content = '<div>No hearings for this date.</div>';
        }
        modalContent.innerHTML = content;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('hearingModal').classList.add('hidden');
    }

    function showCalendarPopup(e, date) {
    const popup = document.getElementById('calendarPopup');
    const content = document.getElementById('calendarPopupContent');
    let html = '';

    if (hearingsData[date]) {
        html += `<table class="w-full mb-4">
        <tbody>`;
        hearingsData[date].forEach(hearing => {
            html += `
            <tr>
                <td class="p-4  align-top w-3/4">
                    <div><strong>Client:</strong> ${hearing.client.clientLastName}, ${hearing.client.clientFirstName}</div>
                    <div><strong>Branch:</strong> ${hearing.branch ? hearing.branch.branchName : 'N/A'}</div>
                    <div><strong>Judge:</strong> ${hearing.branch ? hearing.branch.judgeName : 'N/A'}</div>
                    <div><strong>Time:</strong> ${hearing.time}</div>
                    <div><strong>Status:</strong> ${hearing.status}</div>
                    <div><strong>Notes:</strong> ${hearing.notes ?? ''}</div>
                </td>
                <td class="p-4  w-1/4 text-right">
                    <div class="flex flex-col space-y-2 items-end">
                        <a href="/hearings/${hearing.id}/edit" class="px-3 py-1 bg-blue-500 text-black rounded text-xs w-32 text-center">Edit Hearing</a>
                        <a href="/clients/${hearing.client.id}" class="px-3 py-1 bg-green-500 text-black rounded text-xs w-32 text-center">Show Client Details</a>
                    </div>
                </td>
            </tr>
        `;
        });
        html += `</tbody></table>`;
    } else {
        html = '<div>No hearings for this date.</div>';
    }

    // Add the Close button at the bottom
    html += `
        <div class="flex justify-end space-x-2 mt-4">
            <button onclick="hideCalendarPopup()" class="px-4 py-2 bg-sky-400 text-white rounded hover:bg-sky-500">Close</button>
        </div>
    `;

    content.innerHTML = html;
    popup.style.display = 'flex';
}

    function hideCalendarPopup() {
        document.getElementById('calendarPopup').style.display = 'none';
    }

    // Optional: Hide popup when clicking outside the content
    document.addEventListener('click', function(event) {
        const popup = document.getElementById('calendarPopup');
        const content = document.getElementById('calendarPopupContent');
        if (popup.style.display === 'flex' && !content.contains(event.target) && !event.target.classList.contains('font-semibold')) {
            popup.style.display = 'none';
        }
    });
</script>
@endsection