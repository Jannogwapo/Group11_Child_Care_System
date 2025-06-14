<div class="client-grid">
    @foreach($clients as $client)
        <div class="client-card">
            <div class="client-name">{{ $client->clientFirstName }} {{ $client->clientLastName }}</div>
            <div class="client-info"><span class="client-info-label">Gender:</span> <span class="client-info-value">{{ $client->gender->gender_name ?? 'Not specified' }}</span></div>
            <div class="client-info"><span class="client-info-label">Address:</span> <span class="client-info-value">{{ $client->clientaddress }}</span></div>
            <div class="client-info"><span class="client-info-label">Contact:</span> <span class="client-info-value">{{ $client->guardianphonenumber }}</span></div>
            <div class="client-info"><span class="client-info-label">Status:</span> <span class="client-info-value">{{ $client->status->status_name ?? 'New' }}</span></div>
            <div class="client-info"><span class="client-info-label">Admission Date:</span> <span class="client-info-value">{{ $client->clientdateofadmission }}</span></div>
            <div class="client-actions mt-4">
                @cannot('isAdmin')
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">Edit</a>
                @endcannot
                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-secondary">View Details</a>
            </div>
        </div>
    @endforeach
</div> 