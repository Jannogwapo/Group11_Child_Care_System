@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Client Details</h1>
            <div class="space-x-2">
                <a href="{{ route('clients.edit', $client->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Client
                </a>
                <a href="{{ route('clients.view') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-700">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="font-medium">{{ $client->clientFirstName }} {{ $client->clientMiddleName }} {{ $client->clientLastName }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="font-medium">{{ $client->gender->gender_name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Birthdate</p>
                        <p class="font-medium">{{ $client->clientBirthdate }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Age</p>
                        <p class="font-medium">{{ $client->clientAge }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-700">Contact Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium">{{ $client->clientaddress }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Guardian</p>
                        <p class="font-medium">{{ $client->clientguardian }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Guardian Relationship</p>
                        <p class="font-medium">{{ $client->clientguardianrelationship }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Contact Number</p>
                        <p class="font-medium">{{ $client->guardianphonenumber }}</p>
                    </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-700">Case Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Case Type</p>
                        <p class="font-medium">{{ $client->case->case_name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="font-medium">{{ $client->status->status_name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Admission Date</p>
                        <p class="font-medium">{{ $client->clientdateofadmission }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Location</p>
                        <p class="font-medium">{{ $client->location->location ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-700">Additional Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Student</p>
                        <p class="font-medium">{{ $client->isAStudent ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">PWD</p>
                        <p class="font-medium">{{ $client->isAPwd ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Branch</p>
                        <p class="font-medium">{{ $client->branch->branchName ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 