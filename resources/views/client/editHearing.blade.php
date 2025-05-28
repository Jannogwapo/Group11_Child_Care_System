@extends('layout')
@section('title', 'Edit Hearing')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/addHearing.css') }}">
@endsection
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Edit Hearing</h2>
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <form action="{{ route('hearings.update', $hearing) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">Client: </label>
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <input type="text" value="{{ $client->clientLastName }}, {{ $client->clientFirstName }}" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100" readonly tabindex="-1">
                </div>
                <div>
                    <label for="branch_id" class="block text-sm font-semibold text-gray-700 mb-2">Branch: </label>
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <input type="text" value="{{ $branch->branchName }}" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100" readonly tabindex="-1">
                </div>
                <div>
                    <label for="hearing_date" class="block text-sm font-semibold text-gray-700 mb-2">Hearing Date: </label>
                    <input type="date" name="hearing_date" id="hearing_date" 
                        value="{{ $hearing->hearing_date instanceof \Carbon\Carbon ? $hearing->hearing_date->format('Y-m-d') : $hearing->hearing_date }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label for="time" class="block text-sm font-semibold text-gray-700 mb-2">Hearing Time</label>
                    <input type="time" name="time" id="time" 
                        value="{{ \Carbon\Carbon::parse($hearing->time)->format('H:i') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="completed" {{ $hearing->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="postponed" {{ $hearing->status == 'postponed' ? 'selected' : '' }}>Postponed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('calendar.index') }}" 
                        class="px-5 py-2 rounded-lg text-sm font-medium text-white bg-sky-400 hover:bg-sky-500">Cancel</a>
                    <button type="submit" 
                        class="px-5 py-2 rounded-lg shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700">Update Hearing</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection