// customer_profile.js - Logic for viewing customer profile in admin dashboard

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const customerId = urlParams.get('id');

    if (!customerId) {
        window.location.href = 'customers.php';
        return;
    }

    loadCustomerProfile(customerId);
    loadCustomerStats(customerId);
    loadBookingHistory(customerId);
    loadPaymentHistory(customerId);
    loadQueueStatus(customerId);
});

async function loadCustomerProfile(id) {
    try {
        const response = await fetch(`get_customer_profile.php?id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            const customer = data.customer;
            document.getElementById('customer-name-heading').textContent = customer.name;
            document.getElementById('customer-name').textContent = customer.name;
            document.getElementById('customer-email').textContent = customer.email || 'Not provided';
            document.getElementById('customer-phone').textContent = customer.phone || 'Not provided';
            document.getElementById('customer-created').textContent = new Date(customer.created_at).toLocaleDateString();
            document.getElementById('customer-auth').textContent = customer.auth_provider.toUpperCase();
            
            if (customer.profile_image) {
                document.getElementById('customer-avatar').src = `../${customer.profile_image}`;
            }
        }
    } catch (err) {
        console.error('Error loading profile:', err);
    }
}

async function loadCustomerStats(id) {
    // This will be populated from both bookings and payments endpoints
}

async function loadBookingHistory(id) {
    const tbody = document.getElementById('bookings-list');
    try {
        const response = await fetch(`get_customer_bookings.php?id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            // Update stats
            document.getElementById('total-bookings').textContent = data.stats.total;
            document.getElementById('completed-bookings').textContent = data.stats.completed;
            document.getElementById('cancelled-bookings').textContent = data.stats.cancelled;
            
            if (data.bookings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">No booking history found</td></tr>';
                return;
            }

            tbody.innerHTML = data.bookings.map(booking => `
                <tr>
                    <td><strong>${booking.service_name}</strong></td>
                    <td>${booking.appointment_date}</td>
                    <td>${booking.appointment_time}</td>
                    <td><span class="badge bg-${getStatusColor(booking.status)}">${booking.status.toUpperCase()}</span></td>
                    <td>${booking.price} ETB</td>
                </tr>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading bookings:', err);
    }
}

async function loadPaymentHistory(id) {
    const tbody = document.getElementById('payments-list');
    try {
        const response = await fetch(`get_customer_payments.php?id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            // Update stats
            document.getElementById('total-spent').textContent = `${data.stats.total_paid || 0} ETB`;
            
            if (data.payments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">No payment history found</td></tr>';
                return;
            }

            tbody.innerHTML = data.payments.map(payment => `
                <tr>
                    <td><code>${payment.transaction_ref}</code></td>
                    <td><strong>${payment.amount} ETB</strong></td>
                    <td><span class="badge bg-${payment.status === 'success' ? 'success' : 'warning'}">${payment.status.toUpperCase()}</span></td>
                    <td>${new Date(payment.created_at).toLocaleDateString()}</td>
                </tr>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading payments:', err);
    }
}

async function loadQueueStatus(id) {
    const container = document.getElementById('queue-status-container');
    try {
        const response = await fetch(`get_customer_queue_status.php?id=${id}`);
        const data = await response.json();
        
        if (data.success && data.active) {
            const appt = data.appointment;
            container.innerHTML = `
                <div class="queue-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-white">Live Queue Status</h5>
                        <span class="badge bg-primary">TODAY</span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-white-50 small mb-1">POSITION</p>
                            <div class="queue-pos">#${appt.queue_position}</div>
                        </div>
                        <div class="col-6 border-start border-white border-opacity-10">
                            <p class="text-white-50 small mb-1">EST. WAIT</p>
                            <div class="h3 fw-bold mb-0 text-white">${appt.est_wait_time} min</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top border-white border-opacity-10">
                        <p class="mb-0 text-white-50 small">Current Appointment:</p>
                        <p class="mb-0 fw-bold">${appt.service_name} at ${appt.appointment_time}</p>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="card bg-light border-0">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-clock-rotate-left fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No active queue participation for today</p>
                    </div>
                </div>
            `;
        }
    } catch (err) {
        console.error('Error loading queue status:', err);
    }
}

function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'completed': return 'success';
        case 'waiting': return 'warning';
        case 'confirmed': return 'primary';
        case 'cancelled': return 'danger';
        case 'in-progress': return 'info';
        default: return 'secondary';
    }
}
