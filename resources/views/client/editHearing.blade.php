@extends('layout')
@section('title', 'Edit Hearing')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/addHearing.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .page-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            padding: 2rem 1rem;
        }

        .form-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .form-header {
            background: linear-gradient(135deg, #1a73e8 0%, #4285f4 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            transform: skewY(-4deg);
        }

        .form-header h2 {
            color: white;
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-body {
            padding: 2.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            color: #1a73e8;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e8eaed;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 1.75rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #202124;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .input-group i {
            color: #5f6368;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e8eaed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.1);
            outline: none;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            border-color: #e8eaed;
            color: #5f6368;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%235f6368' viewBox='0 0 16 16'%3E%3Cpath d='M8 10.5l4.5-4.5H3.5l4.5 4.5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .next-hearing-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 2px solid #e8eaed;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .next-hearing-section h3 {
            color: #202124;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e8eaed;
        }

        .btn {
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: #1a73e8;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #1557b0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #5f6368;
            border: 2px solid #e8eaed;
        }

        .btn-secondary:hover {
            background: #e8eaed;
            color: #202124;
        }

        .error-message {
            color: #d93025;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .readonly-field {
            position: relative;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            border: 2px solid #e8eaed;
            color: #5f6368;
            font-size: 1rem;
        }

        .field-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #5f6368;
        }

        .status-select {
            position: relative;
        }

        .status-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 16px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-completed { background: #e6f4ea; color: #137333; }
        .status-postponed { background: #fef7e0; color: #b06000; }
        .status-ongoing { background: #e8f0fe; color: #1a73e8; }
    </style>
@endsection

@section('content')
<div style="display: flex; justify-content: center; align-items: flex-start; min-height: 80vh; background: #f3fcfa;">
    <div style="width: 600px; background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; margin-top: 40px;">
        <div style="background: #5fd1b3; color: #fff; font-size: 1.3rem; font-weight: 600; padding: 18px 32px; border-top-left-radius: 16px; border-top-right-radius: 16px;">
            Edit Hearing
        </div>
        <div style="padding: 32px;">
            @if (count($errors) > 0)
                <div class="alert alert-danger" style="margin-bottom: 18px;">
                    <ul style="margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('hearings.update', $hearing) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="reminder_code" value="{{ $hearing->reminder_code }}">
                <div style="display: flex; flex-direction: column; gap: 18px;">
                    <!-- Client Information -->
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label style="width: 160px; font-weight: 500;">Client Name</label>
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <input type="text" class="form-control"
                               value="{{ $client->clientLastName }}, {{ $client->clientFirstName }}"
                               readonly style="flex: 1; background: #f8f9fa;">
                    </div>

                    <!-- Branch Information -->
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label style="width: 160px; font-weight: 500;">Branch</label>
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <input type="text" class="form-control"
                               value="{{ $branch->branchName }}"
                               readonly style="flex: 1; background: #f8f9fa;">
                    </div>

                    <!-- Hearing Date -->
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label style="width: 160px; font-weight: 500;">Hearing Date</label>
                        <input type="hidden" name="hearing_date"
                               value="{{ $hearing->hearing_date ? ($hearing->hearing_date instanceof \Carbon\Carbon ? $hearing->hearing_date->format('Y-m-d') : $hearing->hearing_date) : '' }}">
                        <input type="text" class="form-control"
                               value="{{ $hearing->hearing_date ? ($hearing->hearing_date instanceof \Carbon\Carbon ? $hearing->hearing_date->format('F d, Y') : \Carbon\Carbon::parse($hearing->hearing_date)->format('F d, Y')) : 'N/A' }}"
                               readonly style="flex: 1; background: #f8f9fa;">
                    </div>

                    <!-- Hearing Time -->
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label style="width: 160px; font-weight: 500;">Time</label>
                        <input type="hidden" name="time" value="{{ $hearing->time ? \Carbon\Carbon::parse($hearing->time)->format('H:i') : '' }}">
                        <input type="text" class="form-control"
                               value="{{ $hearing->time ? \Carbon\Carbon::parse($hearing->time)->format('g:i A') : 'N/A' }}"
                               readonly style="flex: 1; background: #f8f9fa;">
                    </div>

                    <!-- Status -->
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="status" style="width: 160px; font-weight: 500;">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required style="flex: 1;">
                            <option value="ongoing" {{ $hearing->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="postponed" {{ $hearing->status == 'postponed' ? 'selected' : '' }}>Postponed</option>
                            <option value="completed" {{ $hearing->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Notes (Current/Past Hearing) -->
                    <div style="display: flex; align-items: flex-start; gap: 18px;">
                        <label for="notes" style="width: 160px; font-weight: 500;">Notes (Current Hearing)</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" style="flex: 1; resize: vertical;">{{ old('notes', $hearing->notes) }}</textarea>
                    </div>

                    <!-- Next Hearing Fields -->
                    <div id="nextHearingFields" style="display: {{ ($hearing->status === 'ongoing' || $hearing->status === 'postponed') ? 'block' : 'none' }};">
                        <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                                <span class="material-icons" style="color: #21807a;">event_repeat</span>
                                <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Next Hearing Details</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 18px;">
                                <div style="display: flex; align-items: center; gap: 18px;">
                                    <label for="next_hearing_date" style="width: 160px; font-weight: 500;">Next Date</label>
                                    <input type="date" name="next_hearing_date" id="next_hearing_date"
                                           class="form-control @error('next_hearing_date') is-invalid @enderror"
                                           value="{{ $hearing->next_hearing_date ?? '' }}"
                                           min="{{ date('Y-m-d') }}"
                                           style="flex: 1;">
                                </div>
                                <div style="display: flex; align-items: center; gap: 18px;">
                                    <label for="next_hearing_time" style="width: 160px; font-weight: 500;">Next Time</label>
                                    <input type="time" name="next_hearing_time" id="next_hearing_time"
                                           class="form-control @error('next_hearing_time') is-invalid @enderror"
                                           value="{{ $hearing->next_hearing_time ?? '' }}"
                                           style="flex: 1;">
                                </div>
                                <!-- Next Hearing Notes -->
                                <div style="display: flex; align-items: flex-start; gap: 18px;">
                                    <label for="next_hearing_notes" style="width: 160px; font-weight: 500;">Notes (Next Hearing)</label>
                                    <textarea name="next_hearing_notes" id="next_hearing_notes" class="form-control @error('next_hearing_notes') is-invalid @enderror" rows="3" style="flex: 1; resize: vertical;">{{ old('next_hearing_notes', $hearing->next_hearing_notes) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                        <a href="{{ route('calendar.index') }}"
                           style="background: #f3f4f6; color: #374151; font-weight: 600; border-radius: 8px; padding: 10px 24px; text-decoration: none;">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary"
                                style="background: #21807a; color: #fff; font-weight: 600; border-radius: 8px; padding: 10px 32px;">
                            Update Hearing
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const nextFields = document.getElementById('nextHearingFields');
    function toggleNextFields() {
        if (statusSelect.value === 'ongoing' || statusSelect.value === 'postponed') {
            nextFields.style.display = 'block';
        } else {
            nextFields.style.display = 'none';
        }
    }
    statusSelect.addEventListener('change', toggleNextFields);
    toggleNextFields();
});
</script>

@endsection
