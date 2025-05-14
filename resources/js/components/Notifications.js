import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

class Notifications {
    constructor() {
        this.notifications = [];
        this.setupEcho();
        this.setupNotificationSound();
    }

    setupEcho() {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            forceTLS: true
        });

        // Listen for notifications
        window.Echo.private(`App.Models.User.${window.userId}`)
            .notification((notification) => {
                this.handleNotification(notification);
            });
    }

    setupNotificationSound() {
        this.notificationSound = new Audio('/sounds/notification.mp3');
    }

    handleNotification(notification) {
        // Play sound
        this.notificationSound.play();

        // Add to notifications array
        this.notifications.unshift(notification);

        // Show toast notification
        this.showToast(notification);

        // Update notification count
        this.updateNotificationCount();
    }

    showToast(notification) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 max-w-sm transform transition-transform duration-300 ease-in-out';
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                    <p class="mt-1 text-sm text-gray-500">${notification.message}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    updateNotificationCount() {
        const count = this.notifications.length;
        const badge = document.querySelector('#notification-badge');
        if (badge) {
            badge.setAttribute('data-count', count);
            badge.classList.toggle('has-notifications', count > 0);
        }
    }
}

export default new Notifications();

document.addEventListener('DOMContentLoaded', function() {
    const bell = document.getElementById('notification-badge');
    const dropdown = document.getElementById('notification-dropdown');
    const list = document.getElementById('notification-list');
    let dropdownVisible = false;

    if (bell && dropdown && list) {
        bell.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownVisible = !dropdownVisible;
            dropdown.style.display = dropdownVisible ? 'block' : 'none';
            if (dropdownVisible) {
                fetch('/notifications')
                    .then(res => res.json())
                    .then(data => {
                        list.innerHTML = '';
                        if (data.length === 0) {
                            list.innerHTML = '<li class="text-center text-muted">No notifications</li>';
                        } else {
                            data.forEach(n => {
                                list.innerHTML += `<li><strong>${n.data.title || 'Notification'}</strong><br><span>${n.data.message || ''}</span><br><small class='text-muted'>${new Date(n.created_at).toLocaleString()}</small></li>`;
                            });
                        }
                    });
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
                dropdownVisible = false;
            }
        });
    }

    if (bell) {
        bell.addEventListener('click', function() {
            alert('Bell clicked!');
        });
    }
});

document.getElementById('notification-badge')
document.getElementById('notification-dropdown')
document.getElementById('notification-list')

typeof fetch
