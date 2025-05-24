@extends('layout')
@section('title', 'Admin Reports')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Admin Reports</h1>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Client Statistics Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Client Distribution by Case Type</h2>
            <canvas id="clientChart"></canvas>
        </div>

        <!-- Discharge Statistics Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Monthly Discharge Statistics</h2>
            <canvas id="dischargeChart"></canvas>
        </div>

        <!-- Case Status Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Client Distribution by Status</h2>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Client Statistics Chart
    const clientCtx = document.getElementById('clientChart').getContext('2d');
    new Chart(clientCtx, {
        type: 'doughnut',
        data: {
            labels: @json($clientStats['labels']),
            datasets: [{
                data: @json($clientStats['data']),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
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

    // Discharge Statistics Chart
    const dischargeCtx = document.getElementById('dischargeChart').getContext('2d');
    new Chart(dischargeCtx, {
        type: 'bar',
        data: {
            labels: @json($dischargeStats['labels']),
            datasets: [{
                label: 'Discharged Clients',
                data: @json($dischargeStats['data']),
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Case Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: @json($caseStatusStats['labels']),
            datasets: [{
                data: @json($caseStatusStats['data']),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
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
@endsection 