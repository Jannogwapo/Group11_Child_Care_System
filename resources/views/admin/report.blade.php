@extends('layout')
@section('title', 'Client Report')
@section('content')


    
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Clients Currently In-House</h2>
    <a href="{{ route('admin.report.download') }}" class="btn btn-success">
        <i class="bi bi-download"></i> Download Report
    </a>
</div>
    <p class="mb-4">This report lists all clients who are currently in-house.</p>

    @if($inHouseClients->isEmpty())
        <div class="alert alert-info">No clients are currently in-house.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Admission Date</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($inHouseClients as $client)
                    <tr>
                        <td>{{ $client->clientLastName }}, {{ $client->clientFirstName }}</td>
                        <td>{{ $client->clientdateofadmission }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection