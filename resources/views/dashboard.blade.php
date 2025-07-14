@php
    use Carbon\Carbon;
@endphp

@extends('layout')

@section('title')
    @can ('isAdmin')
        Admin Dashboard
    @else
        User Dashboard
    @endcan
@endsection

@section('content')
<div class="greeting-section">
    <div class="greeting-card">
        <div class="greeting-content">
            <div class="greeting-icon">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="greeting-text">
                <h2>
                    @php
                        \Log::info('Dashboard - _just_logged_in session state:', ['value' => session('_just_logged_in')]);
                    @endphp
                    @if(session('_just_logged_in'))
                        Welcome back, {{ auth()->user()->name }}!
                    @else
                        Welcome, {{ auth()->user()->name }}!
                    @endif
                </h2>
                @can('isAdmin')
                <p class="text-muted">Admin</p>
                @else
                <p class="text-muted">Social Worker</p>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <h2 class="card-text">{{ $totalClients ?? 0 }}</h2>
                <a href="{{ route('clients.view') }}" class="btn btn-sm btn-primary">View Clients</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upcoming Hearings</h5>
                <h2 class="card-text">{{ $upcomingHearings ?? 0 }}</h2>
                <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-primary">View Calendar</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Events</h5>
                <h2 class="card-text">{{ $activeEvents }}</h2>
                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">View Events</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
@cannot('isAdmin')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body py-2">
                <div class="row justify-content-between">
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('clients.create') }}" class="btn btn-primary w-100 py-1">
                            <i class="bi bi-person-plus fs-5"></i>
                            <div class="mt-1 small">Add New Client</div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('hearings.create') }}" class="btn btn-primary w-100 py-1">
                            <i class="bi bi-calendar-plus fs-5"></i>
                            <div class="mt-1 small">Schedule Hearing</div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('events.create') }}" class="btn btn-primary w-100 py-1">
                            <i class="bi bi-calendar-event fs-5"></i>
                            <div class="mt-1 small">Create Event</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcannot

<!-- Statistics Charts -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                @can('isAdmin')
                    <h5 class="mb-0">Overall Clients by Gender</h5>
                @else
                    <h5 class="mb-0">Client Statistics</h5>
                @endcan
            </div>
            <div class="card-body">
                <canvas id="overallClientChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card inhouse-case-card">
            <div class="card-header inhouse-case-header">
                <h5 class="mb-0">In-House Clients by Case Type</h5>
            </div>
            <div class="card-body inhouse-case-body">
                <canvas id="inHouseByCaseChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Weekly Hearing</h5>
            </div>
            <div class="card-body">
                <div align="center" class="mb-2">
                    <strong>
                        {{ $startOfWeek->format('F') }}{{ $startOfWeek->month != $days[6]->month ? ' – ' . $days[6]->format('F') : '' }}
                        {{ $startOfWeek->format('Y') }}
                    </strong>
                </div>
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                            <th>Sun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($days as $day)
<td style="position:relative; cursor:pointer;" onclick="showWeeklyHearingClients('{{ $day->toDateString() }}')">
    {{ $day->format('j') }}
    @php
        $hasHearing = false;
        $now = \Carbon\Carbon::now();
        foreach($weeklyHearings as $hearing) {
            if($hearing->hearing_date->format('Y-m-d') === $day->format('Y-m-d')) {
                if($day->format('Y-m-d') > $now->format('Y-m-d') ||
                   ($day->format('Y-m-d') === $now->format('Y-m-d') &&
                    Carbon::parse($hearing->time)->format('H:i:s') > $now->format('H:i:s'))) {
                    $hasHearing = true;
                    break;
                }
            }
        }
    @endphp
    @if($hasHearing)
        <span style="display:inline-block;
                     width:10px;
                     height:10px;
                     background:#dc3545;
                     border-radius:50%;
                     position:absolute;
                     top:8px;
                     right:8px;"></span>
    @endif
</td>
@endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="max-width: 480px; width: 100%;">
            <div class="card-header">
                <h5 class="mb-0">Discharged Client</h5>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="padding: 1.5rem 0;">
                <canvas id="dischargeClientChart" style="max-width: 320px; max-height: 220px; width: 100%; height: 220px; display: block; margin: 0 auto;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="weeklyHearingModal" tabindex="-1" aria-labelledby="weeklyHearingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="weeklyHearingModalLabel">Hearings for <span id="modalDate"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalClients">
        <!-- Client links will be injected here -->
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
const isAdmin = {!! json_encode(Gate::allows('isAdmin')) !!};
const userGenderId = {!! json_encode(auth()->user()->gender_id) !!};
const inHouseByCaseLabels = {!! json_encode($inHouseByCaseLabels) !!};
const inHouseByCaseBoys = {!! json_encode($inHouseByCaseBoys) !!};
const inHouseByCaseGirls = {!! json_encode($inHouseByCaseGirls) !!};
const inHouseByCaseCanvas = document.getElementById('inHouseByCaseChart');
if (inHouseByCaseCanvas) {
    const inHouseByCaseCtx = inHouseByCaseCanvas.getContext('2d');
    new Chart(inHouseByCaseCtx, {
        type: 'line',
        data: {
            labels: inHouseByCaseLabels,
            datasets: isAdmin ? [
                {
                    label: 'Boys',
                    data: inHouseByCaseBoys,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: '#36A2EB',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Girls',
                    data: inHouseByCaseGirls,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: '#FF69B4',
                    borderWidth: 2,
                    fill: false
                }
            ] : [
                {
                    label: userGenderId == 1 ? 'Boys' : 'Girls',
                    data: userGenderId == 1 ? inHouseByCaseBoys : inHouseByCaseGirls,
                    backgroundColor: userGenderId == 1 ? '#36A2EB' : '#FF69B4',
                    borderColor: userGenderId == 1 ? '#36A2EB' : '#FF69B4',
                    borderWidth: 2,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Case Type' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Clients' }
                }
            }
        }
    });
}
</script>
@endpush

</div>

@endsection

@section('styles')
<style>
    .greeting-section {
    margin-bottom: 2rem;
}

.greeting-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.8);
    position: relative;
    overflow: hidden;
}

.greeting-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #0077CC 0%, #00a8ff 100%);
    opacity: 0.1;
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.greeting-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.greeting-icon {
    background: linear-gradient(135deg, #0077CC 0%, #00a8ff 100%);
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(0,119,204,0.3);
}

.greeting-icon i {
    font-size: 2rem;
    color: white;
}

.greeting-text h2 {
    margin: 0;
    font-size: 1.8rem;
    color: #2c3e50;
    font-weight: 600;
}

.greeting-text p {
    margin: 0.5rem 0 0 0;
    font-size: 1rem;
    color: #6c757d;
}

    .charts-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .card-hearing {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .calendar {
        width: 100%;
    }

    .weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-weight: bold;
    }

    .days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-size: 14px;
    }

    .calendar-day.active {
        background-color: #0d6efd;
        color: white;
    }

    .calendar-day.has-event {
        position: relative;
    }

    .calendar-day.has-event::after {
        content: '';
        position: absolute;
        bottom: 2px;
        width: 4px;
        height: 4px;
        background-color: #dc3545;
        border-radius: 50%;
    }

    .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
            border-radius: 5px;
            font-size: 14px;
            color: #666;
            position: relative;
            background: white;
        }

        .calendar-day.active {
            background-color: #0077CC;
            color: white;
            border-color: #0077CC;
        }

        .calendar-day.has-event::after {
            content: '';
            position: absolute;
            bottom: 4px;
            width: 4px;
            height: 4px;
            background-color: #ff4444;
            border-radius: 50%;
        }

        .calendar-day.today {
            border-color: #0077CC;
            font-weight: bold;
        }

        .calendar-day.other-month {
            color: #ccc;
            background-color: #f9f9f9;
        }

    .table thead th {
        font-size: 15px !important;
    }

    .btn-skyblue {
        background: linear-gradient(135deg, #0077CC 0%, #00a8ff 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-skyblue:hover {
        background: linear-gradient(135deg, #0066b3 0%, #0099e6 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 119, 204, 0.2);
    }
/* Improved In-House Clients by Case Type Chart Card */
#inHouseByCaseChart {
    background: #f0f7ff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(54,162,235,0.08);
    padding: 1rem;
    margin-top: 1rem;
    max-width: 420px;
    width: 100%;
    height: 260px !important;
    display: block;
}
.inhouse-case-card {
    background: #f8fafc;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(54,162,235,0.08);
    border: none;
    margin-bottom: 2rem;
}
.inhouse-case-header {
    background: linear-gradient(90deg, #7AE2CF 0%, #36A2EB 100%);
    border-radius: 14px 14px 0 0;
    color: #1A3A34;
    font-weight: bold;
    font-size: 1.15rem;
    padding: 1rem 1.2rem;
    border-bottom: none;
    letter-spacing: 0.5px;
}
.inhouse-case-body {
    padding: 1.5rem 1.2rem 1.2rem 1.2rem;
    background: #fff;
    border-radius: 0 0 14px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare the data for quick lookup
    const weeklyHearingsData = {!! json_encode($weeklyHearings->groupBy('hearing_date_formatted')) !!};
    const currentDateTime = new Date();

    console.log('Weekly Hearings Data:', weeklyHearingsData); // Debug log

    function showWeeklyHearingClients(date) {
        console.log('Showing hearings for date:', date); // Debug log
        console.log('Available hearings:', weeklyHearingsData[date]); // Debug log

        const hearings = weeklyHearingsData[date] || [];
        let html = '';

        if (hearings.length > 0) {
            // Filter hearings to only show upcoming ones
            const upcomingHearings = hearings.filter(hearing => {
                const hearingDateTime = new Date(hearing.hearing_date_formatted + 'T' + hearing.time_formatted);
                return hearingDateTime > currentDateTime;
            });

            if (upcomingHearings.length > 0) {
                upcomingHearings
                    .sort((a, b) => a.time_formatted.localeCompare(b.time_formatted))
                    .forEach(hearing => {
                        const client = hearing.client;
                        if (!client) {
                            console.log('Warning: Null client found for hearing:', hearing); // Debug log
                            return;
                        }

                        const genderClass = client.clientgender === 1 ? 'text-primary' : 'text-danger';
                        const genderIcon = client.clientgender === 1 ? '👦' : '👧';
                        const formattedTime = new Date('1970-01-01T' + hearing.time_formatted).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        html += `<div class="mb-3 p-2 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="/clients/${client.id}" target="_blank" class="${isAdmin ? genderClass : ''}">
                                    ${isAdmin ? genderIcon : ''} ${client.clientLastName}, ${client.clientFirstName}
                                </a>
                                ${isAdmin ? `<span class="ms-2 badge ${client.clientgender === 1 ? 'bg-primary' : 'bg-danger'}">${client.gender?.gender_name || (client.clientgender === 1 ? 'Male' : 'Female')}</span>` : ''}
                            </div>
                            <div class="small text-muted mt-1">
                                Time: ${formattedTime}
                            </div>
                        </div>`;
                    });
            } else {
                html = '<div>No upcoming hearings for this date.</div>';
            }
        } else {
            html = '<div>No hearings for this date.</div>';
        }

        document.getElementById('modalDate').innerText = new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('modalClients').innerHTML = html;
        var myModal = new bootstrap.Modal(document.getElementById('weeklyHearingModal'));
        myModal.show();
    }
</script>
<script>
    const clientStats = {!! json_encode($clientStats ?? ['labels' => [], 'boys' => [], 'girls' => []]) !!};
    const dischargeStats = {!! json_encode($dischargeStats ?? ['labels' => [], 'boys' => [], 'girls' => []]) !!};

    // Grouped Bar Chart for Boys and Girls per Case Type
    const overallClientCtx = document.getElementById('overallClientChart').getContext('2d');
    new Chart(overallClientCtx, {
        type: 'bar',
        data: {
            labels: clientStats.labels,
            datasets: isAdmin ? [
                {
                    label: 'Male',
                    data: clientStats.boys,
                    backgroundColor: '#36A2EB'
                },
                {
                    label: 'Female',
                    data: clientStats.girls,
                    backgroundColor: '#FF69B4'
                }
            ] : [
                {
                    label: userGenderId == 1 ? 'Male' : 'Female',
                    data: userGenderId == 1 ? clientStats.boys : clientStats.girls,
                    backgroundColor: userGenderId == 1 ? '#36A2EB' : '#FF69B4'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: isAdmin,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: false,
                        text: 'Case Type'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Clients'
                    },
                    stacked: false
                }
            }
        }
    });

    // Bar Chart for Discharge Client
    const barChart = new Chart(document.getElementById('dischargeClientChart'), {
        type: 'bar',
        data: {
            labels: dischargeStats.labels,
            datasets: isAdmin ? [
                {
                    label: 'Male',
                    data: dischargeStats.boys,
                    backgroundColor: '#36A2EB'
                },
                {
                    label: 'Female',
                    data: dischargeStats.girls,
                    backgroundColor: '#FF69B4'
                }
            ] : [
                {
                    label: userGenderId == 1 ? 'Male' : 'Female',
                    data: userGenderId == 1 ? dischargeStats.boys : dischargeStats.girls,
                    backgroundColor: userGenderId == 1 ? '#36A2EB' : '#FF69B4'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: isAdmin,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Discharged Clients'
                    },
                    ticks: {
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                }
            }
        }
    });

    // Location-based client chart
    const locationCtx = document.getElementById('locationClientChart').getContext('2d');
    let locationLabels = {!! json_encode($locationStats['labels']) !!};
    let locationData = {!! json_encode($locationStats['data']) !!};
    let locationColors = [
        'rgba(54, 162, 235, 0.8)', // Boys
        'rgba(255, 99, 132, 0.8)'  // Girls
    ];
    let locationBorderColors = [
        'rgba(54, 162, 235, 1)',
        'rgba(255, 99, 132, 1)'
    ];

    if (!isAdmin) {
        if (userGenderId == 1) { // Male social worker
            locationLabels = [locationLabels[0]];
            locationData = [locationData[0]];
            locationColors = [locationColors[0]];
            locationBorderColors = [locationBorderColors[0]];
        } else if (userGenderId == 2) { // Female social worker
            locationLabels = [locationLabels[1]];
            locationData = [locationData[1]];
            locationColors = [locationColors[1]];
            locationBorderColors = [locationBorderColors[1]];
        }
    }

    new Chart(locationCtx, {
        type: 'line',
        data: {
            labels: locationLabels,
            datasets: [{
                data: locationData,
                backgroundColor: locationColors,
                borderColor: locationBorderColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
