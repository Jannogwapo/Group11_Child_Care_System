@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <h2 style="font-size: 2rem; font-weight: bold; color: #21807a; display: flex; align-items: center; gap: 10px;">
                <span class="material-icons" style="font-size: 2rem; vertical-align: middle;">person</span>
                Client Details
            </h2>
            <div style="display: flex; gap: 12px;">
                @cannot('isAdmin')
                    <a href="{{ route('clients.edit', $client->id) }}" style="background: #21807a; color: #fff; font-weight: 600; border-radius: 8px; padding: 10px 24px; text-decoration: none;">Edit Client</a>
                @endcannot
                <a href="{{ route('clients.view') }}" style="background: #5fd1b3; color: #fff; font-weight: 600; border-radius: 8px; padding: 10px 24px; text-decoration: none;">Back to List</a>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Personal Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">person</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Personal Information</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 220px;">
                        <div style="color: #888;">Full Name</div>
                        <div style="font-weight: 500;">{{ $client->clientFirstName }} {{ $client->clientMiddleName }} {{ $client->clientLastName }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Gender</div>
                        <div style="font-weight: 500;">{{ $client->gender->gender_name ?? 'Not specified' }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Birthdate</div>
                        <div style="font-weight: 500;">{{ $client->clientBirthdate }}</div>
                    </div>
                    <div style="flex: 1; min-width: 80px;">
                        <div style="color: #888;">Age</div>
                        <div style="font-weight: 500;">{{ $client->clientAge }}</div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">home</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Contact Information</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 220px;">
                        <div style="color: #888;">Address</div>
                        <div style="font-weight: 500;">{{ $client->clientaddress }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Guardian</div>
                        <div style="font-weight: 500;">{{ $client->clientguardian }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Guardian Relationship</div>
                        <div style="font-weight: 500;">{{ $client->clientguardianrelationship }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Contact Number</div>
                        <div style="font-weight: 500;">{{ $client->guardianphonenumber }}</div>
                    </div>
                </div>
            </div>

            <!-- Case Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">gavel</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Case Information</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Case Type</div>
                        <div style="font-weight: 500;">{{ $client->case->case_name ?? 'Not specified' }}</div>
                    </div>
                    @if($client->case->case_name === 'CICL' && $client->cicl_case_details)
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">CICL Case Details</div>
                        <div style="font-weight: 500;">{{ $client->cicl_case_details }}</div>
                    </div>
                    @endif
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Status</div>
                        <div style="font-weight: 500;">{{ $client->status->status_name ?? 'Not specified' }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Admission Date</div>
                        <div style="font-weight: 500;">{{ $client->clientdateofadmission }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Location</div>
                        <div style="font-weight: 500;">{{ $client->location->location ?? 'Not specified' }}</div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div style="background: #f8fdfa; border-radius: 12px; padding: 24px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <span class="material-icons" style="color: #21807a;">info</span>
                    <span style="font-size: 1.2rem; font-weight: 600; color: #21807a;">Additional Information</span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">Student</div>
                        <div style="font-weight: 500;">{{ $client->isAStudent ? 'Yes' : 'No' }}</div>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <div style="color: #888;">PWD</div>
                        <div style="font-weight: 500;">{{ $client->isAPwd ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection 