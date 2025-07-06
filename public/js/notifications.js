document.addEventListener('DOMContentLoaded', function() {
    // Mark notifications as read when clicked
    const notificationItems = document.querySelectorAll('.notification-item');
    
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const notificationId = this.getAttribute('data-notification-id');
            const isUnread = this.classList.contains('unread');
            
            // Only send AJAX request if notification is unread
            if (isUnread) {
                // Send AJAX request to mark as read
                fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove unread class
                        this.classList.remove('unread');
                        
                        // Add read time in red
                        const contentDiv = this.querySelector('.notification-content');
                        const now = new Date();
                        const readTimeElement = document.createElement('small');
                        readTimeElement.className = 'read-time';
                        readTimeElement.innerHTML = 'Read: <span style="color: #dc3545;">just now</span>';
                        contentDiv.appendChild(readTimeElement);
                        
                        // Update unread count
                        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                        
                        // Hide notification dot if no unread notifications
                        if (unreadCount === 0) {
                            const dot = document.querySelector('.notification-dot');
                            if (dot) dot.remove();
                        }
                    }
                });
            }
        });
    });
});

