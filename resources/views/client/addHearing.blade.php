@extends('layout')
@section('title', 'Add Hearing')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/addHearing.css') }}">
@endsection
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-transparent">
          <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-10" style="margin: 0 auto;">
            <h1 class="text-4xl font-bold mb-10 text-center">Hearing</h1>
            <form action="{{ route('hearings.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="client_id" class="block text-base font-semibold mb-1">Client Name</label>
                    <select name="client_id" id="client_id" required class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->clientLastName }}, {{ $client->clientFirstName }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="hearing_date" class="block text-base font-semibold mb-1">Hearing Date</label>
                    <input type="date" name="hearing_date" id="hearing_date" required class="w-full border border-gray-300 rounded px-4 py-2">
                    @error('hearing_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="time" class="block text-base font-semibold mb-1">Time</label>
                    <input type="time" name="time" id="time" required class="w-full border border-gray-300 rounded px-4 py-2">
                    @error('time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="branch_id" class="block text-base font-semibold mb-1">Branch</label>
                    <select name="branch_id" id="branch_id" required class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" data-judge-id="{{ $branch->judge_id }}">{{ $branch->branchName }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="judge_id" class="block text-base font-semibold mb-1">Judge</label>
                    <select name="judge_id" id="judge_id" required class="w-full border border-gray-300 rounded px-4 py-2" readonly>
                        <option value="">Select Judge</option>
                        @foreach($judges as $judge)
                            <option value="{{ $judge->id }}">{{ $judge->judgeName }}</option>
                        @endforeach
                    </select>
                    @error('judge_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-base font-semibold mb-1">Status</label>
                    <select name="status" id="status" required class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">Select Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="postponed">Postponed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="rescheduled">Rescheduled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded hover:bg-primary-dark">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch_id');
            const judgeSelect = document.getElementById('judge_id');

            branchSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const judgeId = selectedOption.getAttribute('data-judge-id');
                
                // Reset judge selection
                judgeSelect.value = '';
                
                if (judgeId) {
                    // Find and select the corresponding judge
                    for (let option of judgeSelect.options) {
                        if (option.value === judgeId) {
                            option.selected = true;
                            break;
                        }
                    }
                }
            });
        });
    </script>
@endsection


</body>
</html>