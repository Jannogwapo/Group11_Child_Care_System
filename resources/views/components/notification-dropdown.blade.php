@php
    $notifications = auth()->user()->notifications()->latest()->take(10)->get();
@endphp

<div class="notification-dropdown">
    <div class="notification-badge" id="notificationBell">
        <i class="bi bi-bell"></i>
    </div>
    
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
            <h3>Notifications</h3>
        </div>
        
        <div class="notification-list">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['link'] ?? '#' }}" 
                   class="notification-item">
                    <div class="notification-content">
                        <h4>{{ $notification->data['title'] }}</h4>
                        <p>{{ $notification->data['message'] }}</p>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </a>
            @empty
                <div class="no-notifications">
                    <p>No notifications</p>
                </div>
            @endforelse
        </div>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('notificationBell');
        const panel = document.getElementById('notificationPanel');

        bell.addEventListener('click', function(e) {
            e.stopPropagation();
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(e) {
            if (!panel.contains(e.target) && e.target !== bell) {
                panel.style.display = 'none';
            }
        });
    });
</script> 