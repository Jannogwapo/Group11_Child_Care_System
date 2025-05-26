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
                    <input type="date" name="hearing_date" id="hearing_date"
                           class="form-control"
                           min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                           required>
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
                            <option value="{{ $branch->id }}" data-judge-name="{{ $branch->judgeName }}">
                                {{ $branch->branchName }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="judge_name" class="block text-base font-semibold mb-1">Judge</label>
                    <input type="text" id="judge_name" class="w-full border border-gray-300 rounded px-4 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label for="notes" class="block text-base font-semibold mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full border border-gray-300 rounded px-4 py-2"
                        placeholder="Enter any notes (optional)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end mt-8">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded hover:bg-primary-dark">Add Hearing</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch_id');
            const judgeNameInput = document.getElementById('judge_name');

            function updateJudge() {
                const selectedOption = branchSelect.options[branchSelect.selectedIndex];
                const judgeName = selectedOption.getAttribute('data-judge-name') || '';
                judgeNameInput.value = judgeName;
            }

            branchSelect.addEventListener('change', updateJudge);
            updateJudge();
        });
    </script>
@endsection


</body>
</html>