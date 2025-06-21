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
                        <div style="font-weight: 500;">{{ $hearing->hearing_date ? \Carbon\Carbon::parse($hearing->hearing_date)->format('F j, Y') : 'N/A' }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Time</div>
                        <div style="font-weight: 500;">{{ $hearing->time ? \Carbon\Carbon::parse($hearing->time)->format('g:i A') : 'N/A' }}</div>
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

        @if(isset($relatedHearings) && $relatedHearings->count() > 1)
        @php
            $now = \Carbon\Carbon::now();
            $nextHearing = $relatedHearings->filter(function($h) use ($now) {
                $date = $h->hearing_date instanceof \Carbon\Carbon ? $h->hearing_date : \Carbon\Carbon::parse($h->hearing_date);
                if ($h->time) {
                    $date->setTimeFromTimeString($h->time);
                }
                return $date->gt($now);
            })->sortBy(function($h) {
                $date = $h->hearing_date instanceof \Carbon\Carbon ? $h->hearing_date : \Carbon\Carbon::parse($h->hearing_date);
                if ($h->time) {
                    $date->setTimeFromTimeString($h->time);
                }
                return $date;
            })->first();
        @endphp
        @if($nextHearing)
        <!-- Next Scheduled Hearing Section -->
        <div style="background: #fffde7; border-radius: 12px; padding: 24px; margin-bottom: 24px; border: 1px solid #ffe082;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <span class="material-icons" style="color: #ffb300;">event_upcoming</span>
                <span style="font-size: 1.1rem; font-weight: 600; color: #ffb300;">Next Scheduled Hearing</span>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                <div style="flex: 1; min-width: 120px;">
                    <div style="color: #888;">Date</div>
                    <div style="font-weight: 500;">{{ $nextHearing->hearing_date ? \Carbon\Carbon::parse($nextHearing->hearing_date)->format('F j, Y') : 'N/A' }}</div>
                </div>
                <div style="flex: 1; min-width: 120px;">
                    <div style="color: #888;">Time</div>
                    <div style="font-weight: 500;">{{ $nextHearing->time ? \Carbon\Carbon::parse($nextHearing->time)->format('g:i A') : 'N/A' }}</div>
                </div>
                <div style="flex: 1; min-width: 120px;">
                    <div style="color: #888;">Status</div>
                    <div style="font-weight: 500;">{{ ucfirst($nextHearing->status) }}</div>
                </div>
            </div>
        </div>
        @endif
        <!-- Related Hearings Section -->
        <div style="background: #e3f2fd; border-radius: 12px; padding: 24px; margin-top: 32px; border: 1px solid #90caf9;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <span class="material-icons" style="color: #1976d2;">link</span>
                <span style="font-size: 1.1rem; font-weight: 600; color: #1976d2;">All Related Hearings (Reminder Code: {{ $hearing->reminder_code }})</span>
            </div>
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#bbdefb;">
                        <th style="padding:8px; text-align:left;">Date</th>
                        <th style="padding:8px; text-align:left;">Time</th>
                        <th style="padding:8px; text-align:left;">Status</th>
                        <th style="padding:8px; text-align:left;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relatedHearings as $rel)
                        <tr style="border-bottom:1px solid #e3f2fd;">
                            <td style="padding:8px;">{{ $rel->hearing_date ? \Carbon\Carbon::parse($rel->hearing_date)->format('F j, Y') : 'N/A' }}</td>
                            <td style="padding:8px;">{{ $rel->time ? \Carbon\Carbon::parse($rel->time)->format('g:i A') : 'N/A' }}</td>
                            <td style="padding:8px;">{{ ucfirst($rel->status) }}</td>
                            <td style="padding:8px;">{{ $rel->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
