@extends('layout')

@section('title', 'Incident Report Details')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
            @cannot('isAdmin')
            <div class="d-flex">
                <a href="{{ route('incidents.edit', $incident) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('incidents.destroy', $incident) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this incident report?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
            @endcannot
        </div>
        <div class="card-body">
            <h1 class="card-title">{{ $incident->incident_type }}</h1>
            <p class="text-muted">
                <i class="bi bi-calendar-event"></i>
                {{ \Carbon\Carbon::parse($incident->incident_date)->format('F d, Y') }}
            </p>

            @if($incident->images->isNotEmpty())
                <div class="mb-4">
                    <h5>Images</h5>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach($incident->images as $image)
                            <div class="col">
                                <div class="card h-100">
                                    <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="incident-images">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="Incident Image" style="height: 200px; object-fit: cover;">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($incident->incident_image)
                <div class="mb-4">
                     <h5>Image</h5>
                    <a href="{{ asset('storage/' . $incident->incident_image) }}" data-lightbox="incident-images">
                        <img src="{{ asset('storage/' . $incident->incident_image) }}" class="img-fluid rounded" alt="Incident Image">
                    </a>
                </div>
            @endif

            <div class="mt-4">
                <h5>Description</h5>
                <p>{{ $incident->incident_description }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .card-title {
        font-weight: 300;
        font-size: 2.5rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush
