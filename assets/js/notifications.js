document.addEventListener('DOMContentLoaded', function() {
    const notificationCount = document.getElementById('notification-count');
    const notificationList = document.getElementById('notification-list');
    
    if (!notificationCount || !notificationList) return;

    function fetchNotifications() {
        fetch(BASE_URL + 'notifications/get_notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications) {
                    updateNotificationUI(data.notifications);
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function updateNotificationUI(notifications) {
        let unreadCount = 0;
        let html = '<li><h6 class="dropdown-header">Notifications</h6></li>';

        if (notifications.length === 0) {
            html += '<li id="no-notifications"><a class="dropdown-item text-muted text-center" href="#">No notifications</a></li>';
        } else {
            notifications.forEach(notification => {
                if (notification.is_read == 0) unreadCount++;
                
                let icon = 'fa-bell';
                let colorClass = 'text-primary';
                
                if (notification.type === 'booking') {
                    icon = 'fa-calendar-check';
                    colorClass = 'text-success';
                } else if (notification.type === 'queue') {
                    icon = 'fa-list-ol';
                    colorClass = 'text-warning';
                } else if (notification.type === 'status') {
                    icon = 'fa-info-circle';
                    colorClass = 'text-info';
                }

                const readClass = notification.is_read == 0 ? 'fw-bold bg-light' : '';
                
                html += `
                    <li>
                        <a class="dropdown-item ${readClass} border-bottom py-2" href="#" onclick="markAsRead(${notification.id}, event)">
                            <div class="d-flex align-items-center">
                                <div class="me-3 ${colorClass}"><i class="fas ${icon}"></i></div>
                                <div>
                                    <div class="small">${notification.message}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">${new Date(notification.created_at).toLocaleString()}</div>
                                </div>
                            </div>
                        </a>
                    </li>
                `;
            });
        }

        notificationList.innerHTML = html;

        if (unreadCount > 0) {
            notificationCount.innerText = unreadCount;
            notificationCount.style.display = 'inline-block';
        } else {
            notificationCount.style.display = 'none';
        }
    }

    window.markAsRead = function(id, event) {
        if (event) event.preventDefault();
        
        fetch(BASE_URL + 'notifications/mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notification_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotifications(); // Refresh list
            }
        })
        .catch(error => console.error('Error marking as read:', error));
    };

    // Initial fetch
    fetchNotifications();

    // Poll every 10 seconds
    setInterval(fetchNotifications, 10000);
});
