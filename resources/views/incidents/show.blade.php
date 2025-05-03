@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<div class="container">
    <div class="event-detail-card">
        <div class="event-header">
            <a href="{{ route('incidents.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
            <div class="header-actions">
                <h1>{{ $incident->incident_type }}</h1>
                <form action="{{ route('incidents.destroy', $incident) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this incident report?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Delete Incident</button>
                </form>
            </div>
            <div class="date-badge">{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}</div>
        </div>

        <div class="event-content">
            @if($incident->incident_image)
                <div class="event-image">
                    <img src="{{ asset('storage/' . $incident->incident_image) }}" alt="Incident Image">
                </div>
            @endif

            <div class="event-details">
                <div class="detail-section">
                    <h3>Description</h3>
                    <p>{{ $incident->incident_description }}</p>
                </div>

                <div class="detail-section">
                    <h3>Incident Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Date:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($incident->incident_date)->format('F d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Location:</span>
                            <span class="value">{{ $incident->incident_location }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Type:</span>
                            <span class="value">{{ $incident->incident_type }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Severity:</span>
                            <span class="value status-badge {{ strtolower($incident->severity_level) }}">{{ $incident->severity_level }}</span>
                        </div>
                        @if($incident->client)
                            <div class="info-item">
                                <span class="label">Client:</span>
                                <span class="value">{{ $incident->client->first_name }} {{ $incident->client->last_name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Action Taken</h3>
                    <p>{{ $incident->action_taken }}</p>
                </div>

                @if($incident->follow_up_required)
                    <div class="detail-section">
                        <h3>Follow-up Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Status:</span>
                                <span class="value status-badge follow-up">Follow-up Required</span>
                            </div>
                            @if($incident->follow_up_notes)
                                <div class="info-item">
                                    <span class="label">Notes:</span>
                                    <span class="value">{{ $incident->follow_up_notes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="detail-section">
                    <h3>Report Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Reported by:</span>
                            <span class="value">{{ $incident->user ? $incident->user->name : 'Unknown User' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Created:</span>
                            <span class="value">{{ $incident->created_at->format('F d, Y h:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Last Updated:</span>
                            <span class="value">{{ $incident->updated_at->format('F d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.event-detail-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 24px;
    margin: 20px 0;
}

.event-header {
    margin-bottom: 24px;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #666;
    text-decoration: none;
    margin-bottom: 16px;
}

.back-btn:hover {
    color: #333;
}

.event-header h1 {
    margin: 0 0 16px 0;
    font-size: 1.8rem;
    color: #222;
}

.event-content {
    display: grid;
    gap: 24px;
}

.event-image {
    width: 100%;
    max-height: 400px;
    overflow: hidden;
    border-radius: 8px;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.event-details {
    display: grid;
    gap: 24px;
}

.detail-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.detail-section h3 {
    margin: 0 0 16px 0;
    font-size: 1.2rem;
    color: #333;
}

.info-grid {
    display: grid;
    gap: 16px;
}

.info-item {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 8px;
}

.label {
    color: #666;
    font-size: 0.9rem;
}

.value {
    color: #333;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-badge.low {
    background: #d4edda;
    color: #155724;
}

.status-badge.medium {
    background: #fff3cd;
    color: #856404;
}

.status-badge.high {
    background: #f8d7da;
    color: #721c24;
}

.status-badge.critical {
    background: #dc3545;
    color: #fff;
}

.status-badge.follow-up {
    background: #cce5ff;
    color: #004085;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.delete-form {
    margin: 0;
}

.delete-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.2s;
}

.delete-btn:hover {
    background: #c82333;
}
</style>
@endsection
