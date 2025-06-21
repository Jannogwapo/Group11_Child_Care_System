@php
    $user = auth()->user();
    $upcomingHearings = collect(); // Initialize as empty collection
    $notifications = collect();    // Default to empty for social workers

    // Check if the user is a Social Worker
    $isSocialWorker = ($user && !empty($user->role) && $user->role->role_name === 'Social Worker');

    if ($isSocialWorker) {
        // For Social Workers, ONLY fetch upcoming hearings based on their gender
        $userGenderId = $user->gender_id;
        $now = \Carbon\Carbon::now();

        $upcomingHearings = App\Models\Hearing::whereHas('client', function($query) use ($userGenderId) {
            $query->where('clientgender', $userGenderId);
        })
        ->where('status', 'scheduled')
        ->where(function($query) use ($now) {
            $query->where('hearing_date', '>', $now->format('Y-m-d'))
                  ->orWhere(function($q) use ($now) {
                      $q->where('hearing_date', $now->format('Y-m-d'))
                        ->where('time', '>', $now->format('H:i:s'));
                  });
        })
        ->with('client', 'branch') // Eager load relationships
        ->orderBy('hearing_date', 'asc')
        ->orderBy('time', 'asc')
        ->take(5) // Limit to 5 upcoming hearings
        ->get();

    } else {
        // For Admins or other roles, fetch general notifications
        $notifications = $user->notifications()->latest()->take(10)->get();
    }
@endphp

<div class="notification-dropdown">
    <div class="notification-badge" id="notificationBell">
        <i class="bi bi-bell"></i>
        @if($notifications->where('read_at', null)->count() > 0)
            <span class="notification-dot"></span>
        @endif
    </div>

    <div class="notification-panel" id="notificationPanel">
        {{-- General Notifications Section (only for non-social workers or if notifications exist) --}}
        @if($notifications->isNotEmpty())
            <div class="notification-header">
                <h3>Notifications</h3>
            </div>
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->data['link'] ?? '#' }}"
                       class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                       data-notification-id="{{ $notification->id }}">
                        <div class="notification-content">
                            <h4>{{ $notification->data['title'] }}</h4>
                            <p>{{ $notification->data['message'] }}</p>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>

                        </div>
                    </a>
                @empty
                    {{-- This empty block should ideally not be reached if $notifications->isNotEmpty() is true --}}
                @endforelse
            </div>
        @endif

        {{-- Section for Upcoming Hearings (for Social Workers, or if relevant for other roles) --}}
        @if($upcomingHearings->isNotEmpty())
            <div class="notification-header" @if($notifications->isEmpty()) style="border-top: none;" @else style="border-top: 1px solid #eee;" @endif>
                <h3>Upcoming Hearings</h3>
            </div>
            <div class="notification-list">
                @foreach($upcomingHearings as $hearing)
                    <a href="{{ route('hearings.show', $hearing->id) }}" class="notification-item">
                        <div class="notification-content">
                            <h4>Hearing with {{ $hearing->client->clientFirstName }} {{ $hearing->client->clientLastName }}</h4>
                            <p>Date: {{ $hearing->hearing_date ? \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') : 'N/A' }} at {{ $hearing->time ? \Carbon\Carbon::parse($hearing->time)->format('h:i A') : 'N/A' }}</p>
                            <small>Location: {{ $hearing->branch->branchName ?? 'N/A' }}</small>
                            <br>
                            <small>Judge: {{ $hearing->branch->judgeName ?? 'N/A' }}</small>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($notifications->isEmpty() && $upcomingHearings->isEmpty())
            <div class="no-notifications">
                <p>No new notifications or upcoming hearings.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .notification-dropdown {
        position: relative;
    }

    .notification-badge {
        position: relative;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .notification-badge:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .notification-panel {
        position: absolute;
        top: 100%;
        right: 0;
        width: 350px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        z-index: 1000;
        margin-top: 8px;
    }

    .notification-header {
        padding: 12px 16px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .notification-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .notification-item {
        display: block;
        padding: 12px 16px;
        border-bottom: 1px solid #eee;
        text-decoration: none;
        color: inherit;
        transition: background-color 0.2s;
    }

    .notification-item:hover {
        background-color: #f5f5f5;
    }

    .notification-content h4 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
    }

    .notification-content p {
        margin: 0 0 4px 0;
        font-size: 13px;
        color: #666;
    }

    .notification-content small {
        color: #999;
        font-size: 12px;
    }

    .no-notifications {
        padding: 24px;
        text-align: center;
        color: #666;
    }

    /* Conditional Red Dot */
    .notification-dot {
        position: absolute;
        top: 6px; /* Adjust as needed */
        right: 6px; /* Adjust as needed */
        width: 10px;
        height: 10px;
        background-color: #dc3545; /* Red color */
        border-radius: 50%;
        z-index: 2;
    }

    .notification-item.unread {
        background-color: #f0f7ff;
    }

    .notification-item.unread:hover {
        background-color: #e6f0fa;
    }

    .read-time {
        display: block;
        margin-top: 4px;
        font-style: italic;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('notificationBell');
        const panel = document.getElementById('notificationPanel');

        // Debugging: Log the elements to console
        console.log('Notification Bell Element:', bell);
        console.log('Notification Panel Element:', panel);

        if (bell && panel) {
            bell.addEventListener('click', function(e) {
                e.stopPropagation();
                panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!panel.contains(e.target) && e.target !== bell) {
                    panel.style.display = 'none';
                }
            });
        }
    });
</script>





