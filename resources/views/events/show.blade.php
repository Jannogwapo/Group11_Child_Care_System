@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<div class="container">
    <div class="event-detail-card">
        <div class="event-header">
            <a href="{{ route('events.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
            <div class="header-actions">
                <h1>{{ $event->title }}</h1>
                @cannot('isAdmin')
                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this event report?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">Delete Event</button>
                    </form>
                    <a href="{{ route('events.edit', $event) }}" class="edit-event-btn">Edit Event</a>
                @endcannot
            </div>
            <div class="date-badge">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</div>
        </div>

        <div class="event-content">
            @if($event->images && $event->images->count())
                <div class="event-image">
                    @foreach($event->images as $img)
                        <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Event Image" style="max-width: 200px; margin: 5px;">
                        </a>
                    @endforeach
                </div>
            @endif
            @if($event->picture)
                <div class="event-image">
                    <a href="{{ asset('storage/' . $event->picture) }}" target="_blank">
                        <img src="{{ asset('storage/' . $event->picture) }}" alt="Event Image">
                    </a>
                </div>
            @endif

            <div class="event-details">
                <div class="detail-section">
                    <h3>Description</h3>
                    <p>{{ $event->description }}</p>
                </div>

                <div class="detail-section">
                    <h3>Event Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Date:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }}</span>
                        </div>
                        @if($event->location)
                            <div class="info-item">
                                <span class="label">Location:</span>
                                <span class="value">{{ $event->location }}</span>
                            </div>
                        @endif
                        @if($event->type)
                            <div class="info-item">
                                <span class="label">Type:</span>
                                <span class="value">{{ $event->type }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Report Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Reported by:</span>
                            <span class="value">{{ $event->createdBy ? $event->createdBy->name : 'Unknown User' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Created:</span>
                            <span class="value">{{ $event->created_at->format('F d, Y h:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Last Updated:</span>
                            <span class="value">{{ $event->updated_at->format('F d, Y h:i A') }}</span>
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

.edit-event-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.2s;
    margin-left: 8px;
}

.edit-event-btn:hover {
    background: #0056b3;
}
</style>
@endsection

