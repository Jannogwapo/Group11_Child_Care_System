@extends('layout')

@section('title', 'Social Worker Dashboard')

@section('content')
<div class="greeting-section">
    <div class="greeting-card">
        <div class="greeting-content">
            <div class="greeting-icon">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="greeting-text">
                <h2>Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-muted">{{ auth()->user()->role_id }}</p>
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

<br>
<br>

    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Clients</h5>
                    <h2 class="card-text">{{ $totalClients ?? 0 }}</h2>
                    <a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary">View Clients</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Hearings</h5>
                    <h2 class="card-text">{{ $upcomingHearings ?? 0 }}</h2>
                    <a href="" class="btn btn-sm btn-primary">View Calendar</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Events</h5>
                    <h2 class="card-text">{{ $activeEvents ?? 0 }}</h2>
                    <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">View Events</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">System Users</h5>
                    <h2 class="card-text">{{ $totalUsers ?? 0 }}</h2>
                    <a href="{{ route('admin.access') }}" class="btn btn-sm btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
    </div>

<br>
<br>

    <!-- Quick Actions -->
    @if(auth()->user()->role_id == 2)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('clients.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus"></i> Add New Client
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hearings.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-plus"></i> Schedule Hearing
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('events.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-event"></i> Create Event
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.logs') }}" class="btn btn-primary w-100">
                                <i class="bi bi-journal-text"></i> View System Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
<br>
<br>

    <!-- Statistics Charts (New Section) -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Overall Client</h5>
                </div>
                <div class="card-body">
                    <canvas id="overallClientChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Weekly Hearing</h5>
                </div>
                <div class="card-body">
                    <div class="calendar">
                        <div class="weekdays">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div class="calendar-grid">
                            <div class="calendar-day other-month">28</div>
                            <div class="calendar-day other-month">29</div>
                            <div class="calendar-day other-month">30</div>
                            <div class="calendar-day other-month">31</div>
                            <div class="calendar-day">1</div>
                            <div class="calendar-day">2</div>
                            <div class="calendar-day">3</div>
                            <div class="calendar-day">4</div>
                            <div class="calendar-day">5</div>
                            <div class="calendar-day active has-event">6</div>
                            <div class="calendar-day today">7</div>
                            <div class="calendar-day has-event">8</div>
                            <div class="calendar-day">9</div>
                            <div class="calendar-day">10</div>
                        </div>
                        
                        <div class="days-grid" id="calendarDays">
                            <!-- Days will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
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
    box-shadow: 0 4px 10px rgba(0, 119, 204, 0.3);
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
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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