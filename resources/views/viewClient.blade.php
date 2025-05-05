@extends('layout')
@section('title')
    @can('isAdmin')
        Admin Client List
    @else
        Social Worker Client List
@endcan
@endsection

@section('content')
<style>
    .case-filter-btn {
        display: inline-block;
        margin: 0 5px 10px 0;
        padding: 6px 18px;
        border-radius: 20px;
        border: none;
        background: #e0e7ef;
        color: #333;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .case-filter-btn.active, .case-filter-btn:hover {
        background: #a7c7e7;
        color: #222;
    }
    .case-section {
        margin-bottom: 32px;
    }
    .case-title {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 12px;
        color: #2b3a4a;
        letter-spacing: 1px;
    }
    .client-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 18px 20px;
        margin-bottom: 16px;
        transition: box-shadow 0.2s;
    }
    .client-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.13);
    }
    .client-name {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .client-info {
        color: #555;
        font-size: 0.97rem;
        margin-bottom: 2px;
    }
    .client-actions a {
        margin-right: 10px;
        color: #1976d2;
        font-weight: 500;
        text-decoration: none;
    }
    .client-actions a:hover {
        text-decoration: underline;
    }
</style>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Client List</h1>
        @cannot('isAdmin')
            <a href="{{ route('clients.create') }}" class="px-4 py-2 rounded-full bg-pink-500 text-black hover:bg-pink-600 ml-auto">
                Add Client
            </a>
        @endcannot
    </div>

    <!-- Filter Buttons -->
    <div id="case-filters" class="mb-6">
        <button class="case-filter-btn active" data-case="all">ALL</button>
        @foreach($cases as $case)
            <button class="case-filter-btn" data-case="case-{{ $case->id }}">{{ strtoupper($case->case_name) }}</button>
        @endforeach
    </div>

    <!-- Grouped Client List -->
    <div id="client-list">
        @foreach($cases as $case)
            <div class="case-section" data-case-group="case-{{ $case->id }}">
                <div class="case-title">{{ strtoupper($case->case_name) }}</div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php $hasClient = false; @endphp
                    @foreach($clients as $client)
                        @if($client->case && $client->case->id == $case->id)
                            @php $hasClient = true; @endphp
                            <div class="client-card">
                                <div class="client-name">{{ $client->clientFirstName }} {{ $client->clientLastName }}</div>
                                <div class="client-info">Gender: {{ $client->gender->gender_name ?? 'Not specified' }}</div>
                                <div class="client-info">Address: {{ $client->clientaddress }}</div>
                                <div class="client-info">Contact: {{ $client->guardianphonenumber }}</div>
                                <div class="client-info">Status: {{ $client->status->status_name ?? 'New' }}</div>
                                <div class="client-info">Admission Date: {{ $client->clientdateofadmission }}</div>
                                <div class="client-actions mt-2">
                                    @cannot('isAdmin')
                                    <a href="{{ route('clients.edit', $client->id) }}">Edit</a>
                                    @endcannot
                                    <a href="{{ route('clients.show', $client->id) }}">View Details</a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if(!$hasClient)
                        <div class="text-gray-400 italic col-span-full">No clients in this case.</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    // Filtering logic
    document.querySelectorAll('.case-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.case-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const caseId = this.getAttribute('data-case');
            document.querySelectorAll('[data-case-group]').forEach(section => {
                if(caseId === 'all') {
                    section.style.display = '';
                } else {
                    section.style.display = section.getAttribute('data-case-group') === caseId ? '' : 'none';
                }
            });
        });
    });
</script>
@endsection 