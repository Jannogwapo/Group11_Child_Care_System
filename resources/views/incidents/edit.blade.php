@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
<div class="container">
    <h1 class="form-title">Edit Incident Report</h1>

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

    <form method="POST" action="{{ route('incidents.update', $incident) }}" enctype="multipart/form-data" style="max-width: 800px; margin: 0 auto;">
        @csrf
        @method('PATCH')
        <div class="form-group" style="margin-bottom: 24px;">
            <input type="date" name="incident_date" class="input-date" placeholder="Incident Date" value="{{ old('incident_date', $incident->incident_date) }}" required style="width: 100%; max-width: 300px;">
        </div>
        <div style="display: flex; gap: 32px; flex-wrap: wrap; align-items: flex-start;">
            <div style="flex: 1; min-width: 220px;">
                <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
                    @if($incident->images && $incident->images->count())
                        @foreach($incident->images as $img)
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="Incident Image" style="max-width: 110px; max-height: 80px; border-radius: 6px; border: 1px solid #ccc; margin-bottom: 4px;">
                                <label style="font-size: 0.9em; color: #555;">
                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> Delete
                                </label>
                            </div>
                        @endforeach
                    @endif
                    @if($incident->incident_image)
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <img src="{{ asset('storage/' . $incident->incident_image) }}" alt="Incident Image" style="max-width: 110px; max-height: 80px; border-radius: 6px; border: 1px solid #ccc; margin-bottom: 4px;">
                            <label style="font-size: 0.9em; color: #555;">
                                <input type="checkbox" name="delete_picture" value="1"> Delete
                            </label>
                        </div>
                    @endif
                </div>
                <label class="image-upload" style="width: 100%;">
                    <input type="file" name="incident_images[]" class="input-file" accept="image/*" multiple onchange="previewImage(this)">
                    <div class="image-placeholder" id="imagePreview">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="18" height="18" rx="2" fill="#e0e0e0"/>
                            <circle cx="8.5" cy="9" r="2" fill="#bdbdbd"/>
                            <path d="M21 17L16 12L5 21" stroke="#bdbdbd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Attach Picture</span>
                    </div>
                </label>
                <div style="font-size: 0.9em; color: #888; margin-top: 8px;">Uploading new images will replace all current images.</div>
            </div>
            <div style="flex: 2; min-width: 260px; display: flex; flex-direction: column; gap: 16px;">
                <input type="text" name="incident_type" class="input-text" placeholder="INCIDENT NAME" value="{{ old('incident_type', $incident->incident_type) }}" required style="margin-bottom: 8px;">
                <textarea name="incident_description" class="input-textarea" rows="5" placeholder="DESCRIPTION OF THE INCIDENT" required style="min-height: 90px;">{{ old('incident_description', $incident->incident_description) }}</textarea>
            </div>
        </div>
        <div style="display: flex; justify-content: center; margin-top: 32px;">
            <button type="submit" class="update-btn">Update Report</button>
        </div>
    </form>
</div>

<style>
    .update-btn {
        background: #21807a;
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
        background: #1a6b66;
    }
</style>
@endsection 