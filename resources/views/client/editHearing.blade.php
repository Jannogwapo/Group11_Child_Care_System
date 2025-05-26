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

                    <!-- Client Selection -->
                    <div>
                        <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">Client</label>
                        <select name="client_id" id="client_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $hearing->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->clientLastName }}, {{ $client->clientFirstName }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Branch Selection -->
                    <div>
                        <label for="branch_id" class="block text-sm font-semibold text-gray-700 mb-2">Branch</label>
                        <select name="branch_id" id="branch_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Select a branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $hearing->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branchName }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hearing Date -->
                    <div>
                        <label for="hearing_date" class="block text-sm font-semibold text-gray-700 mb-2">Hearing Date</label>
                        <input type="date" name="hearing_date" id="hearing_date" 
                               value="{{ $hearing->hearing_date->format('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('hearing_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hearing Time -->
                    <div>
                        <label for="time" class="block text-sm font-semibold text-gray-700 mb-2">Hearing Time</label>
                        <input type="time" name="time" id="time" 
                               value="{{ \Carbon\Carbon::parse($hearing->time)->format('H:i') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="scheduled" {{ $hearing->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ $hearing->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="postponed" {{ $hearing->status == 'postponed' ? 'selected' : '' }}>Postponed</option>
                            <option value="cancelled" {{ $hearing->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rescheduled" {{ $hearing->status == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('calendar.index') }}" 
                           class="px-5 py-2 rounded-lg text-sm font-medium text-white bg-sky-400 hover:bg-sky-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-300 transition">Cancel</a>
                        <button type="submit" 
                                class="px-5 py-2 rounded-lg shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400 transition">Update Hearing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 