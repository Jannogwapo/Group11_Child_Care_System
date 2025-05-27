@extends('layout')
@section('title', 'Client Report')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Clients Currently In-House</h2>
    <!-- Download dropdown menu -->
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" id="downloadDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-download"></i> Download Report
        </button>
        <ul class="dropdown-menu" aria-labelledby="downloadDropdown">
          
            <li>
                <a class="dropdown-item" href="{{ route('admin.report.download', ['format' => 'excel']) }}">
                    Excel
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('admin.report.download', ['format' => 'pdf']) }}">
                    PDF
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('admin.report.download', ['format' => 'word']) }}">
                    Word
                </a>
            </li>
        </ul>
    </div>
</div>
    <p class="mb-4">This report lists all clients who are currently in-house.</p>

    @if($inHouseClients->isEmpty())
        <div class="alert alert-info">No clients are currently in-house.</div>
    @else
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
                @foreach($inHouseClients as $client)
                <tr>
                    <td>{{ $client->clientLastName }}, {{ $client->clientFirstName }}</td>
                    <td>{{ $client->gender->gender_name ?? 'Not specified' }}</td>
                    <td>{{ $client->clientAge }}</td>
                    <td>{{ $client->case->case_name ?? 'No Case' }}</td>
                    <td>{{ $client->isAStudent == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $client->isAPwd == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->clientdateofadmission)->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection