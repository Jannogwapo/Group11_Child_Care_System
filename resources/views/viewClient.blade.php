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
    .case-filter-bar {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }
    .case-filter-btn {
        display: inline-block;
        margin: 0;
        padding: 6px 18px;
        border-radius: 20px;
        border: none;
        background: #e0e7ef;
        color: #222;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .case-filter-btn.active, .case-filter-btn:hover {
        background: var(--primary-color);
        color: var(--text-color);
    }
    .case-section {
        margin-bottom: 32px;
    }
    .client-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        padding: 0 12px;
    }
    .client-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 24px;
        transition: box-shadow 0.2s;
    }
    .client-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.13);
    }
    .client-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 12px;
        color: #2d3748;
    }
    .client-info {
        display: flex;
        justify-content: space-between;
        color: #4a5568;
        font-size: 0.95rem;
        margin-bottom: 8px;
        padding: 4px 0;
        border-bottom: 1px solid #edf2f7;
    }
    .client-info:last-of-type {
        border-bottom: none;
    }
    .client-info-label {
        font-weight: 500;
        color: #718096;
    }
    .client-info-value {
        color: #2d3748;
    }
    .add-client-btn {
        background: var(--primary-color);
        color: var(--text-color) !important;
        border-radius: 20px;
        padding: 7px 20px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        margin-left: 18px;
        display: inline-block;
    }
    .add-client-btn:hover {
        background: var(--secondary-color);
        color: var(--text-color) !important;
        text-decoration: none;
    }
    .client-actions {
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #edf2f7;
        display: flex;
        gap: 12px;
    }
    .client-actions a {
        background: var(--primary-color);
        color: var(--text-color) !important;
        font-weight: 500;
        text-decoration: none;
        border-radius: 12px;
        padding: 6px 16px;
        transition: background 0.2s, color 0.2s;
        flex: 1;
        text-align: center;
    }
    .client-actions a:hover {
        background: var(--secondary-color);
        color: var(--text-color) !important;
        text-decoration: none;
    }
</style>
<div class="container mx-auto px-4 py-8">
    <div class="case-filter-bar">
        <button class="case-filter-btn active" data-case="all">ALL</button>
        @foreach($cases as $case)
            <button class="case-filter-btn" data-case="case-{{ $case->id }}">{{ strtoupper($case->case_name) }}</button>
        @endforeach
        @cannot('isAdmin')
            <a href="{{ route('clients.create') }}" class="add-client-btn">ADD CLIENT</a>
        @endcannot
    </div>

    <!-- Grouped Client List -->
    <div id="client-list">
        @foreach($cases as $case)
            <div class="case-section" data-case-group="case-{{ $case->id }}">
                <div class="client-grid">
                    @php $hasClient = false; @endphp
                    @foreach($clients as $client)
                        @if($client->case && $client->case->id == $case->id)
                            @php $hasClient = true; @endphp
                            <div class="client-card">
                                <div class="client-name">{{ $client->clientFirstName }} {{ $client->clientLastName }}</div>
                                <div class="client-info">
                                    <span class="client-info-label">Gender:</span>
                                    <span class="client-info-value">{{ $client->gender->gender_name ?? 'Not specified' }}</span>
                                </div>
                                <div class="client-info">
                                    <span class="client-info-label">Address:</span>
                                    <span class="client-info-value">{{ $client->clientaddress }}</span>
                                </div>
                                <div class="client-info">
                                    <span class="client-info-label">Contact:</span>
                                    <span class="client-info-value">{{ $client->guardianphonenumber }}</span>
                                </div>
                                <div class="client-info">
                                    <span class="client-info-label">Status:</span>
                                    <span class="client-info-value">{{ $client->status->status_name ?? 'New' }}</span>
                                </div>
                                <div class="client-info">
                                    <span class="client-info-label">Admission Date:</span>
                                    <span class="client-info-value">{{ $client->clientdateofadmission }}</span>
                                </div>
                                <div class="client-actions mt-4">
                                    @cannot('isAdmin')
                                    <a href="{{ route('clients.edit', $client->id) }}">Edit</a>
                                    @endcannot
                                    <a href="{{ route('clients.show', $client->id) }}">View Details</a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if(!$hasClient)
                        <div class="text-gray-400 italic col-span-2 text-center">No clients in this case.</div>
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