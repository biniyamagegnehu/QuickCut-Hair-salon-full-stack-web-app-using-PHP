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
            historyContainer.innerHTML = data.bookings.map(booking => {
                const isCompleted = booking.status.toLowerCase() === 'completed';
                const isRated = booking.rating_id !== null;
                const barberInfo = booking.barber_name ? `<p class="small text-muted mb-0">Barber: <strong>${booking.barber_name}</strong></p>` : '';
                
                let actionBtn = '';
                if (isCompleted && !isRated && booking.barber_id) {
                    actionBtn = `
                        <button class="btn btn-warning btn-sm rounded-pill px-3 mt-2" 
                                onclick="openRatingModal(${booking.id}, ${booking.barber_id}, '${booking.barber_name}', '${booking.service_name} - ${booking.appointment_date}')">
                            <i class="fas fa-star me-1"></i> Rate Barber
                        </button>
                    `;
                } else if (isRated) {
                    actionBtn = `<span class="badge bg-light text-warning border mt-2"><i class="fas fa-check-circle me-1"></i> Rated</span>`;
                }

                return `
                    <div class="history-card p-3 mb-3 bg-theme-surface-2 border-0 shadow-sm fade-in-up">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <div class="service-icon-sm bg-accent text-dark p-2 rounded-3 me-3">
                                    <i class="fas fa-cut"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">${booking.service_name}</h6>
                                    <p class="small text-muted mb-0"><i class="far fa-calendar-alt me-1"></i> ${booking.appointment_date} at ${booking.appointment_time}</p>
                                    ${barberInfo}
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="status-badge status-${booking.status.toLowerCase()} mb-2 d-inline-block">${booking.status}</span>
                                <p class="small fw-bold mb-0">${booking.amount} ETB</p>
                                ${actionBtn}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
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

function openRatingModal(appId, barberId, barberName, appInfo) {
    document.getElementById('rating-appointment-id').value = appId;
    document.getElementById('rating-barber-id').value = barberId;
    document.getElementById('rating-barber-name').textContent = barberName;
    document.getElementById('rating-appointment-info').textContent = appInfo;
    
    // Reset stars
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach(s => {
        s.classList.remove('fas');
        s.classList.add('far');
    });
    document.getElementById('rating-value').value = 0;
    document.getElementById('rating-desc').textContent = 'Select a rating';
    document.getElementById('submit-rating-btn').disabled = true;
    document.getElementById('rating-review').value = '';

    const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
    modal.show();
}

// Global scope for onclick
window.openRatingModal = openRatingModal;

// Rating star logic
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star-btn');
    const ratingValue = document.getElementById('rating-value');
    const ratingDesc = document.getElementById('rating-desc');
    const submitBtn = document.getElementById('submit-rating-btn');
    
    const descMap = {
        1: 'Poor',
        2: 'Fair',
        3: 'Good',
        4: 'Very Good',
        5: 'Excellent'
    };

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const val = parseInt(star.dataset.value);
            ratingValue.value = val;
            ratingDesc.textContent = descMap[val];
            submitBtn.disabled = false;
            
            stars.forEach((s, index) => {
                if (index < val) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });

        star.addEventListener('mouseover', () => {
            const val = parseInt(star.dataset.value);
            stars.forEach((s, index) => {
                if (index < val) s.classList.add('text-primary');
            });
        });

        star.addEventListener('mouseout', () => {
            stars.forEach(s => s.classList.remove('text-primary'));
        });
    });

    submitBtn?.addEventListener('click', async () => {
        const payload = {
            appointment_id: document.getElementById('rating-appointment-id').value,
            barber_id: document.getElementById('rating-barber-id').value,
            rating: document.getElementById('rating-value').value,
            review: document.getElementById('rating-review').value
        };

        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
            
            const response = await fetch('../barber_ratings/add_barber_rating.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('ratingModal')).hide();
                fetchBookingHistory(); // Refresh list
            } else {
                showToast(data.message, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Failed to submit rating', 'danger');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Rating';
        }
    });
});

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
