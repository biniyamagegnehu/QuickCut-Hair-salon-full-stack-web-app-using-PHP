document.addEventListener('DOMContentLoaded', function() {
    // Load all settings
    loadAdminProfile();
    loadSalonSettings();
    loadAboutContent();

    // Tab Event Listeners (Optional: reload data when switching tabs)
    const tabs = document.querySelectorAll('#settings-tabs button');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (event) {
            const targetId = event.target.getAttribute('data-bs-target');
            if (targetId === '#content-account') loadAdminProfile();
            if (targetId === '#content-salon') loadSalonSettings();
            if (targetId === '#content-about') loadAboutContent();
        });
    });

    // --- Account Settings ---
    const adminProfileForm = document.getElementById('admin-profile-form');
    const profileImageInput = document.getElementById('admin-profile-image');
    const profilePreview = document.getElementById('admin-profile-preview');

    profileImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    adminProfileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('api/settings/update_admin_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Update sidebar info
                document.getElementById('sidebar-admin-name').textContent = formData.get('name');
                document.getElementById('admin-display-name').textContent = formData.get('name');
            } else {
                showToast(data.message, 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('An error occurred while updating profile', 'danger');
        });
    });

    // --- Change Password ---
    const adminPasswordForm = document.getElementById('admin-password-form');
    const newPasswordInput = document.getElementById('new-password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    newPasswordInput.addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        if (val.length >= 6) strength += 25;
        if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength += 25;
        if (val.match(/[0-9]/)) strength += 25;
        if (val.match(/[^a-zA-Z0-9]/)) strength += 25;

        strengthBar.style.width = strength + '%';
        if (strength < 50) {
            strengthBar.className = 'progress-bar bg-danger';
            strengthText.textContent = 'Weak';
        } else if (strength < 100) {
            strengthBar.className = 'progress-bar bg-warning';
            strengthText.textContent = 'Medium';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            strengthText.textContent = 'Strong';
        }
    });

    adminPasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        if (data.new_password !== data.confirm_password) {
            showToast('New passwords do not match', 'danger');
            return;
        }

        fetch('api/settings/change_admin_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                adminPasswordForm.reset();
                strengthBar.style.width = '0%';
                strengthText.textContent = '';
            } else {
                showToast(data.message, 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('An error occurred', 'danger');
        });
    });

    // --- Salon Settings ---
    const salonSettingsForm = document.getElementById('salon-settings-form');
    const salonLogoInput = document.getElementById('salon-logo-input');
    const salonLogoPreview = document.getElementById('salon-logo-preview');

    salonLogoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                salonLogoPreview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    salonSettingsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('api/settings/update_salon_settings.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'danger');
            }
        });
    });

    // --- About Content ---
    const aboutContentForm = document.getElementById('about-content-form');
    const aboutImageInput = document.getElementById('about-image-input');
    const aboutImagePreview = document.getElementById('about-image-preview');

    aboutImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                aboutImagePreview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    aboutContentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('api/settings/update_about_content.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'danger');
            }
        });
    });



    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // --- Helper Functions ---

    function loadAdminProfile() {
        fetch('api/settings/get_admin_profile.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const admin = data.data;
                document.getElementById('admin-name').value = admin.name;
                document.getElementById('admin-email').value = admin.email;
                document.getElementById('admin-phone').value = admin.phone || '';
                document.getElementById('admin-display-name').textContent = admin.name;
                if (admin.profile_image) {
                    document.getElementById('admin-profile-preview').src = '../' + admin.profile_image;
                }
            }
        });
    }

    function loadSalonSettings() {
        fetch('api/settings/get_salon_settings.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const s = data.data;
                document.getElementById('salon-name').value = s.salon_name;
                document.getElementById('salon-tagline').value = s.tagline || '';
                document.getElementById('salon-address').value = s.address || '';
                document.getElementById('salon-phone').value = s.phone || '';
                document.getElementById('salon-email').value = s.email || '';
                document.getElementById('salon-facebook').value = s.facebook_link || '';
                document.getElementById('salon-instagram').value = s.instagram_link || '';
                document.getElementById('salon-telegram').value = s.telegram_link || '';
                document.getElementById('salon-map').value = s.map_link || '';
                if (s.logo) {
                    document.getElementById('salon-logo-preview').src = '../' + s.logo;
                }
            }
        });
    }

    function loadAboutContent() {
        fetch('api/settings/get_about_content.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const a = data.data;
                document.getElementById('about-title').value = a.about_title;
                document.getElementById('about-description').value = a.about_description || '';
                document.getElementById('about-mission').value = a.mission || '';
                document.getElementById('about-vision').value = a.vision || '';
                document.getElementById('about-story').value = a.story || '';
                if (a.about_image) {
                    document.getElementById('about-image-preview').src = '../' + a.about_image;
                }
            }
        });
    }

    // Theme initialization (removed from settings UI but kept for global support)
    const savedTheme = localStorage.getItem('admin-theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }
});

function showToast(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container');
    const toastHtml = `
        <div class="toast show align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    const toastElement = document.createElement('div');
    toastElement.innerHTML = toastHtml;
    toastContainer.appendChild(toastElement);
    
    setTimeout(() => {
        toastElement.remove();
    }, 4000);
}