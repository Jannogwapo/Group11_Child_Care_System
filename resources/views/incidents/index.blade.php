@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<div class="container">
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
            <p class="no-data">No incident reports available.</p>
        @endforelse
    </div>
</div>

<style>
.no-data {
    text-align: center;
    color: #666;
    padding: 20px;
    font-style: italic;
}

.event-link {
    text-decoration: none;
    color: inherit;
    display: block;
    margin-bottom: 16px;
}

.event-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 16px;
    transition: transform 0.2s;
}

.event-card:hover {
    transform: translateY(-2px);
}

.date-badge {
    display: inline-block;
    background: #f0f0f0;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 12px;
}

.content {
    display: flex;
    gap: 16px;
}

.image-placeholder {
    width: 120px;
    height: 120px;
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
}

.image-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.text-content {
    flex: 1;
}

.text-content h3 {
    margin: 0 0 8px 0;
    color: #333;
}

.text-content p {
    margin: 0 0 8px 0;
    color: #666;
    font-size: 0.9rem;
}

.uploaded-by {
    color: #888;
    font-size: 0.8rem;
}

.add-btn {
    background: #00b300;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background-color 0.2s;
}

.add-btn:hover {
    background: #009900;
}
</style>
@endsection 