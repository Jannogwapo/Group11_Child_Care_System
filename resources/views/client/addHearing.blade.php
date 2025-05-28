@extends('layout')
@section('title', 'Add Hearing')
@section('css')
<style>
    body {
        background: #e7fdf9;
        font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
    }
    .centered-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }
    .hearing-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(51, 65, 85, 0.13), 0 1.5px 10px rgba(30,41,59,0.07);
        padding: 2.5rem 2rem 2rem 2rem;
        margin: 2rem auto;
        animation: fadeIn 0.6s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .hearing-title {
        font-size: 2.1rem;
        font-weight: bold;
        margin-bottom: 2rem;
        text-align: center;
        color: #28bcb8;
        letter-spacing: -0.01em;
        text-shadow: 0 2px 10px #b2dfdb44;
    }
    .form-group {
        margin-bottom: 1.1rem;
    }
    label {
        display: block;
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: .3rem;
        color: #1a2424;
        letter-spacing: 0.01em;
    }
    select, input[type="date"], input[type="time"], input[type="text"], textarea {
        width: 100%;
        border: 1.5px solid #b2dfdb;
        border-radius: 7px;
        padding: 0.6rem 1rem;
        font-size: 1.07rem;
        background: #f8fbfa;
        color: #2b3a3a;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        margin-top: 2.5px;
    }
    select:focus, input:focus, textarea:focus {
        outline: none;
        border-color: #28bcb8;
        box-shadow: 0 0 0 2px #b2dfdb99;
        background: #fff;
    }
    select:disabled, input:disabled, textarea:disabled {
        background: #f3f4f6;
        color: #a0aec0;
        cursor: not-allowed;
    }
    input[readonly] {
        background: #e0f2f1;
        color: #757575;
        cursor: not-allowed;
    }
    textarea {
        min-height: 65px;
        resize: vertical;
        font-family: inherit;
    }
    .error-message {
        color: #ef4444;
        font-size: 0.91rem;
        margin-top: 0.18rem;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
    .submit-btn {
        padding: 0.5rem 2.1rem;
        background: linear-gradient(90deg, #28bcb8 0%, #3fd0c9 100%);
        color: #fff;
        border: none;
        border-radius: 20px;
        font-size: 1.08rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.18s, box-shadow 0.18s, transform 0.18s;
        box-shadow: 0 2px 12px 0 rgba(40,188,184,0.10);
        letter-spacing: 0.05em;
    }
    .submit-btn:hover, .submit-btn:focus {
        background: linear-gradient(90deg, #159c97 0%, #2be0d4 100%);
        box-shadow: 0 4px 18px 0 rgba(40,188,184,0.15);
        transform: translateY(-2px) scale(1.02);
    }

    /* Add some subtle input icon for dropdowns and date/time */
    select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,<svg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M1 1L6 6L11 1' stroke='%2328bcb8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg>");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 10px;
        padding-right: 2.1rem;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .hearing-card {
            padding: 1rem;
            max-width: 99vw;
        }
        .hearing-title {
            font-size: 1.25rem;
        }
        .submit-btn {
            width: 100%;
            padding: 0.7rem 0;
        }
        .form-actions {
            justify-content: center;
        }
    }
</style>
@endsection
@section('content')
    <div class="centered-container">
        <div class="hearing-card">
            <h1 class="hearing-title">Hearing</h1>
            <form action="{{ route('hearings.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label for="client_id">Client Name</label>
                    <select name="client_id" id="client_id" required>
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->clientLastName }}, {{ $client->clientFirstName }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="hearing_date">Hearing Date</label>
                    <input type="date" name="hearing_date" id="hearing_date"
                           min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                           required>
                    @error('hearing_date')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" name="time" id="time" required
                    min="{{ \Carbon\Carbon::now()->format('H--m-s') }}">
                    @error('time')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="branch_id">Branch</label>
                    <select name="branch_id" id="branch_id" required>
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" data-judge-name="{{ $branch->judgeName }}">
                                {{ $branch->branchName }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="judge_name">Judge</label>
                    <input type="text" id="judge_name" placeholder="Auto-filled by Branch" readonly>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        placeholder="Enter any notes (optional)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Add Hearing</button>
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