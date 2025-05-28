@extends('layout')
@section('title', 'Client Report')
@section('content')


<div class="mb-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
        <!-- Header and description: top left -->
        <div>
            <h2 class="mb-1">Clients Currently In-House</h2>
            <p class="mb-0">This report lists all clients who are currently in-house.</p>
        </div>
        <!-- Download and Filter: top right -->
        <div class="d-flex align-items-end gap-3 mt-3 mt-md-0">
            <!-- Download Dropdown: right, before filter -->
            <!-- FIXED: changed admin.report.index to admin.report -->
            <form method="GET" action="{{ route('admin.report') }}" class="d-flex align-items-end gap-2">
                <div>
                    <label for="as_of" class="form-label mb-0">As of Month:</label>
                    <input type="month" name="as_of" id="as_of" class="form-control" value="{{ request('as_of', now()->format('Y-m')) }}">
                </div>
                <button type="submit" class="btn btn-primary ms-2">Filter</button>
            </form>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="downloadDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download"></i> Download Report
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="downloadDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.report.download', ['format' => 'excel', 'as_of' => request('as_of', now()->format('Y-m'))]) }}">
                            Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.report.download', ['format' => 'pdf', 'as_of' => request('as_of', now()->format('Y-m'))]) }}">
                            PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.report.download', ['format' => 'word', 'as_of' => request('as_of', now()->format('Y-m'))]) }}">
                            Word
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Filter Form: rightmost -->
        </div>

    </div>
</div>

@if($inHouseClients->isEmpty())
    <div class="alert alert-info">No clients are currently in-house.</div>
@else
    @php
        // Sort clients by last name, then first name (alphabetically)
        $sortedClients = $inHouseClients->sortBy([
            ['clientLastName', 'asc'],
            ['clientFirstName', 'asc'],
        ])->values(); // reset keys for correct foreach iteration
    @endphp
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Case</th>
                <th>Student</th>
                <th>Pwd</th>
                <th>Admission Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sortedClients as $client)
            <tr>
                <td>{{ $client->clientLastName }}, {{ $client->clientFirstName }}</td>
                <td>{{ $client->gender->gender_name ?? 'Not specified' }}</td>
                <td>
                    @if($client->clientBirthdate)
                        {{ \Carbon\Carbon::parse($client->clientBirthdate)->age }}
                    @else
                        Unknown
                    @endif
                </td>
                <td>{{ $client->case->case_name ?? 'No Case' }}</td>
                <td>{{ ($client->isAStudent == 1 || $client->isAStudent === true || $client->isAStudent === '1') ? 'Yes' : 'No' }}</td>
                <td>{{ ($client->isAPwd == 1 || $client->isAPwd === true || $client->isAPwd === '1') ? 'Yes' : 'No' }}</td>
                <td>{{ \Carbon\Carbon::parse($client->clientdateofadmission)->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection