@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div class="greeting-section">
    <div class="greeting-card">
        <div class="greeting-content">
            <div class="greeting-icon">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="greeting-text">
                <h2>Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-muted">Social Worker Dashboard</p>
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
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Clients</h5>
                    <h2 class="card-text">{{ $myClients ?? 0 }}</h2>
                    <a href="{{ route('clients.view') }}" class="btn btn-sm btn-primary">View Clients</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Hearings</h5>
                    <h2 class="card-text">{{ $myHearings ?? 0 }}</h2>
                    <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-primary">View Calendar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Events</h5>
                    <h2 class="card-text">{{ $myEvents ?? 0 }}</h2>
                    <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">View Events</a>
                </div>
            </div>
        </div>
    </div>

<br>
<br>
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('clients.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus"></i> Add New Client
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('hearings.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-plus"></i> Schedule Hearing
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('events.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-event"></i> Create Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<br>

<!-- Statistics Charts (New Section) -->
<div class="row mt-4">
    <!-- Case Status Chart -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Case Status Distribution</h5>
            </div>
            <div class="card-body">
                <div class="h-64">
                    <canvas id="caseStatusChart"></canvas>
                </div>
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
@endsection

@section('styles')
<style>
    /* Greeting Section Styles */
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

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #444;
        transition: all 0.2s;
        cursor: pointer;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.active {
        background-color: #0077CC;
        color: white;
        border-color: #0077CC;
        font-weight: 500;
    }

    .calendar-day.today {
        border: 2px solid #0077CC;
        font-weight: bold;
    }

    .calendar-day.has-event {
        position: relative;
    }

    .calendar-day.has-event::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 6px;
        height: 6px;
        background-color: #ff4444;
        border-radius: 50%;
    }

    .calendar-day.other-month {
        color: #ccc;
        background-color: #f9f9f9;
    }

    canvas {
        max-height: 300px;
        width: 100% !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 1rem;
        }
        
        .calendar-day {
            font-size: 0.8rem;
        }
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Case Status Chart
    const caseStatusData = @json($caseStatusStats);
    const caseStatusChart = new Chart(document.getElementById('caseStatusChart'), {
        type: 'doughnut',
        data: {
            labels: caseStatusData.labels,
            datasets: [{
                data: caseStatusData.data,
                backgroundColor: [
                    '#FFB6C1', // Pink
                    '#FFE4B5', // Peach
                    '#98FB98', // Light Green
                    '#DDA0DD', // Plum
                    '#87CEEB', // Sky Blue
                    '#FFA07A', // Light Salmon
                    '#D3D3D3', // Light Gray
                    '#90EE90', // Light Green
                    '#FFD700', // Gold
                    '#B0C4DE'  // Light Steel Blue
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Function to update the case status chart
    function updateCaseStatusChart() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newData = JSON.parse(doc.querySelector('script').textContent);
                
                caseStatusChart.data.labels = newData.labels;
                caseStatusChart.data.datasets[0].data = newData.data;
                caseStatusChart.update();
            });
    }

    // Update the chart every 30 seconds
    setInterval(updateCaseStatusChart, 30000);

    // Overall Client Chart
    const clientStats = @json($clientStats);
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
    const dischargeStats = @json($dischargeStats);
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