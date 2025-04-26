@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Client List</h1>
        <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Client
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($clients as $client)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-2">{{ $client->clientFirstName }} {{ $client->clientLastName }}</h2>
                <p class="text-gray-600">Gender: {{ $client->gender->gender_name ?? 'Not specified' }}</p>
                <p class="text-gray-600">Address: {{ $client->clientaddress }}</p>
                <p class="text-gray-600">Contact: {{ $client->guardianphonenumber }}</p>
                <p class="text-gray-600">Status: {{ $client->status->status_name ?? 'New' }}</p>
                <p class="text-gray-600">Admission Date: {{ $client->clientdateofadmission }}</p>
                <div class="mt-4">
                    <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">Edit</a>
                    <a href="{{ route('clients.show', $client->id) }}" class="text-green-500 hover:text-green-700">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 