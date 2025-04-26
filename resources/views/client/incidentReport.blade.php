@extends('layout')
@section('title', 'Incident Report')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/incidentReport.css') }}">

@section('content')
    
    <div class="container">
        <h1>INCIDENT REPORTS</h1>
        <form action="/submit-event" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="date" name="event_date" class="input-box" placeholder="Event Date" required>
            
            <label for="upload" class="upload-box">
                <span>📎 Attach Picture</span>
                <input type="file" id="upload" name="event_image" style="display: none;">
            </label>
            
            <textarea name="incident_name" class="input-box" placeholder="INCIDENT NAME" required></textarea>
            <textarea name="incident_description" class="input-box" placeholder="DESCRIPTION OF THE INCIDENT" required></textarea>
            
            <div class="buttons">
                <button type="submit" class="button">Save</button>
                <button type="reset" class="button">Clear Entities</button>
            </div>
        </form>
    </div>
@endsection

