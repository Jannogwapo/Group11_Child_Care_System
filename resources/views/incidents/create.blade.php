@extends('layout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
<div class="container">
    <h1 class="form-title">INCIDENT REPORT</h1>

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

    <form method="POST" action="{{ route('incidents.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="date" name="incident_date" class="input-date" placeholder="Incident Date" required>
        </div>
        <div class="form-row">
            <label class="image-upload">
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
            <div class="event-details">
                <input type="text" name="incident_type" class="input-text" placeholder="INCIDENT NAME" required>
                <textarea name="incident_description" class="input-textarea" rows="4" placeholder="DESCRIPTION OF THE INCIDENT" required></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="modal-btn">Save</button>
            <button type="reset" class="modal-btn" onclick="resetImagePreview()">Clear Entities</button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetImagePreview() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = `
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="3" width="18" height="18" rx="2" fill="#e0e0e0"/>
            <circle cx="8.5" cy="9" r="2" fill="#bdbdbd"/>
            <path d="M21 17L16 12L5 21" stroke="#bdbdbd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Attach Picture</span>
    `;
}
</script>
@endsection
