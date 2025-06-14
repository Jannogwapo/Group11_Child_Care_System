@extends('layout')

@section('title', 'Hearing Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <h2 style="font-size: 2rem; font-weight: bold; color: #21807a; display: flex; align-items: center; gap: 10px;">
                <span class="material-icons" style="font-size: 2rem; vertical-align: middle;">gavel</span>
                Hearing Details
            </h2>
            <div style="display: flex; gap: 12px;">
                @if(request('filter') === 'editable')
                    <a href="{{ route('hearings.edit', $hearing->id) }}" 
                       style="background: #21807a; color: #fff; font-weight: 600; border-radius: 8px; padding: 10px 24px; text-decoration: none;">
                        <span class="material-icons" style="font-size: 1.2rem; vertical-align: middle; margin-right: 4px;">edit</span>
                        Edit Hearing
                    </a>
                @endif
                <a href="{{ route('calendar.index') }}" 
                   style="background: #5fd1b3; color: #fff; font-weight: 600; border-radius: 8px; padding: 10px 24px; text-decoration: none;">
                    <span class="material-icons" style="font-size: 1.2rem; vertical-align: middle; margin-right: 4px;">arrow_back</span>
                    Back to Calendar
                </a>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Client Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">person</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Client Information</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 220px;">
                        <div style="color: #888;">Full Name</div>
                        <div style="font-weight: 500;">{{ $hearing->client->clientLastName }}, {{ $hearing->client->clientFirstName }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Gender</div>
                        <div style="font-weight: 500;">{{ $hearing->client->clientgender == 1 ? 'Male' : 'Female' }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Case</div>
                        <div style="font-weight: 500;">{{ $hearing->client->case->case_name }}</div>
                    </div>
                </div>
            </div>

            <!-- Hearing Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">event</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Hearing Schedule</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Date</div>
                        <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('F j, Y') }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Time</div>
                        <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($hearing->time)->format('g:i A') }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Status</div>
                        <div style="font-weight: 500;">{{ ucfirst($hearing->status) }}</div>
                    </div>
                </div>
            </div>

            <!-- Branch Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">business</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Branch Information</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Branch Name</div>
                        <div style="font-weight: 500;">{{ $hearing->branch->branchName }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Judge</div>
                        <div style="font-weight: 500;">{{ $hearing->branch->judgeName }}</div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
          
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">notes</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Remarks</span>
                </div>
                <div style="color: #333; line-height: 1.6;">
                    {{ $hearing->notes ?? 'No Remarks' }}
                </div>
            </div>
           
        </div>
    </div>
</div>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
