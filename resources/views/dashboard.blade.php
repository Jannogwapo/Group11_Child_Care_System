@can('Access')
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
                <h2>Welcome, {{ auth()->user()->name }}!</h2>
                @can('isAdmin')
                <p class="text-muted">Admin</p>
                @else
                <p class="text-muted">Social Worker</p>
                @endcan
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
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
                <h5 class="card-title">Events</h5>
                <h2 class="card-text">{{ $activeEvents ?? 0 }}</h2>
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


    <!-- Statistics Charts (New Section) -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    @can('isAdmin')
                        <h5 class="mb-0">Overall Client</h5>
                    @else
                        <h5 class="mb-0">Your Client Statistics</h5>
                    @endcan
                </div>
                <div class="card-body">
                    <canvas id="overallClientChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 >Weekly Hearing</h5>
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
        $hearingDateTime = \Carbon\Carbon::parse($hearing->hearing_date->toDateString() . ' ' . $hearing->time);
        if(
            $hearing->hearing_date->isSameDay($day) &&
            in_array($hearing->status, ['pending', 'scheduled']) &&
            $hearingDateTime->greaterThanOrEqualTo($now)
        ) {
            $hasHearing = true;
            break;
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
            <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Discharge Client</h5>
                </div>
                <div class="card-body">
                    <canvas id="dischargeClientChart"></canvas>
                </div>
            </div>
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
@endcan
    
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
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare the data for quick lookup
    const weeklyHearingsData = @json($weeklyHearings->groupBy(function($h) { return $h->hearing_date->toDateString(); }));

    function showWeeklyHearingClients(date) {
    const now = new Date();
    const hearings = weeklyHearingsData[date] || [];
    let clients = [];

    hearings.forEach(h => {
        // Eloquent may serialize dates as objects, so parse accordingly
        let hearingTime = h.time ? h.time : "00:00:00";
        let hearingDateStr = typeof h.hearing_date === 'string' ? h.hearing_date : h.hearing_date.date.substr(0, 10);
        const hearingDateTime = new Date(`${hearingDateStr}T${hearingTime}`);

        if (['pending', 'scheduled'].includes(h.status) && hearingDateTime >= now) {
            clients.push(h.client);
        }
    });

    let html = '';
    if (clients.length > 0) {
        clients.forEach(client => {
            html += `<div>
                <a href="/clients/${client.id}" target="_blank">
                    ${client.clientLastName}, ${client.clientFirstName}
                </a>
            </div>`;
        });
    } else {
        html = '<div>No hearings for this date.</div>';
    }
    document.getElementById('modalDate').innerText = date;
    document.getElementById('modalClients').innerHTML = html;
    // Show the modal (Bootstrap 5)
    var myModal = new bootstrap.Modal(document.getElementById('weeklyHearingModal'));
    myModal.show();
}
</script>
<script>
    const clientStats = @json($clientStats ?? ['labels' => [], 'data' => []]);
    const dischargeStats = @json($dischargeStats ?? ['labels' => [], 'data' => []]);

    // Overall Client Donut Chart
    const donutChart = new Chart(document.getElementById('overallClientChart'), {
        type: 'doughnut',
        data: {
            labels: clientStats.labels,
            datasets: [{
                data: clientStats.data,
                backgroundColor: [
                    '#FFB6C1',
                    '#FFE4B5',
                    '#98FB98',
                    '#DDA0DD',
                    '#87CEEB'
                ]
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Bar Chart for Discharge Client
    const barChart = new Chart(document.getElementById('dischargeClientChart'), {
        type: 'bar',
        data: {
            labels: dischargeStats.labels,
            datasets: [{
                label: 'Discharged Clients',
                data: dischargeStats.data,
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

<!-- Modal for showing clients on a specific date -->
