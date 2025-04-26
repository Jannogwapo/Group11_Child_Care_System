@extends('layout')
@section('title', 'Add Hearing')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/addHearing.css') }}">
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Add Hearing</h1>

        <form action="{{ route('hearings.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Selection -->
                <div class="space-y-2">
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                    <select name="client_id" id="client_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->clientLastName }}, {{ $client->clientFirstName }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hearing Date -->
                <div class="space-y-2">
                    <label for="hearing_date" class="block text-sm font-medium text-gray-700">Hearing Date</label>
                    <input type="date" name="hearing_date" id="hearing_date" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('hearing_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time -->
                <div class="space-y-2">
                    <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                    <input type="time" name="time" id="time" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch Selection -->
                <div class="space-y-2">
                    <label for="branch_id" class="block text-sm font-medium text-gray-700">Branch</label>
                    <select name="branch_id" id="branch_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branchName }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judge Selection -->
                <div class="space-y-2">
                    <label for="judge_id" class="block text-sm font-medium text-gray-700">Judge</label>
                    <select name="judge_id" id="judge_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Judge</option>
                        @foreach($judges as $judge)
                            <option value="{{ $judge->id }}">{{ $judge->judgeName }}</option>
                        @endforeach
                    </select>
                    @error('judge_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Selection -->
                <div class="space-y-2">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="postponed">Postponed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('calendar.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save Hearing
                </button>
            </div>
        </form>
    </div>
@endsection


</body>
</html>