@extends('layout')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg mx-auto">
        <h2 class="text-2xl font-bold mb-4">Notification Details</h2>
        <div class="mb-4">
            <strong>Title:</strong>
            <p class="text-lg">{{ $notification->data['title'] ?? 'No Title' }}</p>
        </div>
        <div class="mb-4">
            <strong>Message:</strong>
            <p>{{ $notification->data['message'] ?? 'No Message' }}</p>
        </div>
        <div class="mb-4">
            <strong>Date:</strong>
            <p>{{ $notification->created_at->format('F d, Y h:i A') }}</p>
        </div>
        @if(!empty($notification->data['link']))
        <div class="mb-4">
            <strong>Related Link:</strong>
            <a href="{{ $notification->data['link'] }}" class="text-blue-600 underline">View Related</a>
        </div>
        @endif
        <a href="{{ url()->previous() }}" class="inline-block mt-6 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Back</a>
    </div>
</div>
@endsection
