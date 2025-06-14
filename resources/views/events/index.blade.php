@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<div class="container">
    <div class="reports-row">
        <!-- Activity Reports Section -->
        <div class="report-section">
            <div class="report-header">
                <h2>ACTIVITY REPORTS</h2>
                @cannot('isAdmin')
                    <a href="{{ route('events.create') }}" class="add-btn">ADD ACTIVITY</a>
                @endcannot
            </div>

            @forelse($events as $event)
                <a href="{{ route('events.show', $event) }}" class="event-link">
                    <div class="event-card">
                        <div class="date-badge">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</div>
                        <div class="content">
                            <div class="image-placeholder">
                                @if($event->images && $event->images->count())
                                    <img src="{{ asset('storage/' . $event->images->first()->image_path) }}" alt="Event Image">
                                @elseif($event->picture)
                                    <img src="{{ asset('storage/' . $event->picture) }}" alt="Event Image">
                                @endif
                            </div>
                            <div class="text-content">
                                <h3>{{ $event->title }}</h3>
                                <p>{{ $event->description }}</p>
                                <div class="uploaded-by">
                                    <small>Uploaded by: {{ $event->createdBy ? $event->createdBy->name : 'Unknown User' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                No activity reports available.
            @endforelse
        </div>

        <div class="divider"></div>

        <!-- Incident Reports Section -->
        <div class="report-section">
            <div class="report-header">
                <h2>INCIDENT REPORTS</h2>
                @cannot('isAdmin')
                    <a href="{{ route('incidents.create') }}" class="add-btn">ADD INCIDENT</a>
                @endcannot
            </div>

            @forelse($incidents as $incident)
                <a href="{{ route('incidents.show', $incident) }}" class="event-link">
                    <div class="event-card">
                        <div class="date-badge">{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}</div>
                        <div class="content">
                            <div class="image-placeholder">
                                @if($incident->images && $incident->images->count())
                                    <img src="{{ asset('storage/' . $incident->images->first()->image_path) }}" alt="Incident Image">
                                @elseif($incident->incident_image)
                                    <img src="{{ asset('storage/' . $incident->incident_image) }}" alt="Incident Image">
                                @endif
                            </div>
                            <div class="text-content">
                                <h3>{{ $incident->incident_type }}</h3>
                                <p>{{ $incident->incident_description }}</p>
                                <div class="uploaded-by">
                                    <small>Uploaded by: {{ $incident->user ? $incident->user->name : 'Unknown User' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                No incident reports available.
            @endforelse
        </div>
    </div>
</div>
@endsection
