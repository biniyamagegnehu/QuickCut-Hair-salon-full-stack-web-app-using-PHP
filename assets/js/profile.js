/**
 * QuickCut - Profile Page Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initial fetch of profile data
    fetchProfileData();
    fetchBookingHistory();

    // Profile Form Submission
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(profileForm);
            
            try {
                const response = await fetch('update_profile.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast('Profile updated successfully!', 'success');
                    // Update header if name changed
                    const headerName = document.querySelector('#userDropdown span');
                    if (headerName) {
                        headerName.textContent = formData.get('name').split(' ')[0];
                    }
                } else {
                    showToast(data.message || 'Error updating profile', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong', 'error');
            }
        });
    }

    // Password Form Submission
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(passwordForm);
            
            if (formData.get('new_password') !== formData.get('confirm_password')) {
                showToast('New passwords do not match!', 'error');
                return;
            }

            try {
                const response = await fetch('change_password.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast('Password changed successfully!', 'success');
                    passwordForm.reset();
                } else {
                    showToast(data.message || 'Error changing password', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong', 'error');
            }
        });
    }

    // Profile Image Upload
    const avatarInput = document.getElementById('avatar-input');
    if (avatarInput) {
        avatarInput.addEventListener('change', async (e) => {
            if (e.target.files && e.target.files[0]) {
                const formData = new FormData();
                formData.append('profile_image', e.target.files[0]);
                
                try {
                    const response = await fetch('upload_profile_image.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        document.getElementById('profile-avatar').src = data.image_url;
                        showToast('Profile image updated!', 'success');
                    } else {
                        showToast(data.message || 'Error uploading image', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Something went wrong', 'error');
                }
            }
        });
    }
});

async function fetchProfileData() {
    try {
        const response = await fetch('get_profile.php');
        const data = await response.json();
        
        if (data.success) {
            const user = data.user;
            // Fill fields
            document.getElementById('profile-name').value = user.name;
            document.getElementById('profile-phone').value = user.phone || '';
            document.getElementById('profile-email').value = user.email;
            
            if (user.profile_image) {
                document.getElementById('profile-avatar').src = BASE_URL + user.profile_image;
            }

            // UI Adjustments for Google users
            if (user.auth_provider === 'google') {
                const passwordTab = document.getElementById('password-tab');
                if (passwordTab) passwordTab.style.display = 'none';
                document.getElementById('profile-email').disabled = true;
            }
        }
    } catch (error) {
        console.error('Error fetching profile:', error);
    }
}

async function fetchBookingHistory() {
    const historyContainer = document.getElementById('booking-history-list');
    if (!historyContainer) return;

    try {
        const response = await fetch('get_booking_history.php');
        const data = await response.json();
        
        if (data.success && data.bookings.length > 0) {
            historyContainer.innerHTML = data.bookings.map(booking => `
                <div class="history-card p-3 mb-3 bg-theme-surface-2 border-0 shadow-sm fade-in-up">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-sm bg-accent text-dark p-2 rounded-3 me-3">
                                <i class="fas fa-cut"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">${booking.service_name}</h6>
                                <p class="small text-muted mb-0"><i class="far fa-calendar-alt me-1"></i> ${booking.appointment_date} at ${booking.appointment_time}</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="status-badge status-${booking.status.toLowerCase()} mb-2 d-inline-block">${booking.status}</span>
                            <p class="small fw-bold mb-0">${booking.amount} ETB</p>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            historyContainer.innerHTML = `
                <div class="text-center py-5">
                    <img src="${BASE_URL}assets/images/no-bookings.svg" alt="No bookings" style="width: 150px; opacity: 0.5;">
                    <p class="text-muted mt-3">You haven't made any bookings yet.</p>
                    <a href="${BASE_URL}booking/bookappointment.php" class="btn btn-primary btn-sm rounded-pill px-4">Book Now</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error fetching bookings:', error);
        historyContainer.innerHTML = '<p class="text-center text-danger">Error loading booking history.</p>';
    }
}

function showToast(message, type = 'success') {
    // Check if toast container exists
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 show`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
