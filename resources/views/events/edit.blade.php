@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
<div class="container">
    <h1 class="form-title">Edit Event Report</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <input type="date" name="event_date" class="input-date" placeholder="Event Date" value="{{ old('event_date', $event->event_date) }}" required>
        </div>
        <div class="form-row">
            <label class="image-upload">
                <input type="file" name="event_images[]" class="input-file" accept="image/*" multiple onchange="previewImage(this)">
                <div class="image-placeholder" id="imagePreview">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="18" height="18" rx="2" fill="#e0e0e0"/>
                        <circle cx="8.5" cy="9" r="2" fill="#bdbdbd"/>
                        <path d="M21 17L16 12L5 21" stroke="#bdbdbd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Attach Picture</span>
                </div>
            </label>
            <div class="event-details">
                <input type="text" name="event_title" class="input-text" placeholder="EVENT NAME" value="{{ old('event_title', $event->event_title) }}" required>
                <textarea name="event_description" class="input-textarea" rows="4" placeholder="DESCRIPTION OF THE EVENT" required>{{ old('event_description', $event->event_description) }}</textarea>
            </div>
        </div>
        <button type="submit" class="update-btn">Update Report</button>
    </form>
</div>

<style>
    .update-btn {
        background: #00b300;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s;
        margin-top: 20px;
    }

    .update-btn:hover {
        background: #009900;
    }
</style>
@endsection 