// services.js - Services specific JavaScript
let allServices = [];

document.addEventListener('DOMContentLoaded', function() {
    initServices();
});

// Initialize services
function initServices() {
    loadServices();
    setupServiceEventListeners();
    setupImagePreview();
}

// Setup image preview
function setupImagePreview() {
    const imageInput = document.getElementById('service-image');
    const previewContainer = document.getElementById('service-image-preview');

    if (imageInput && previewContainer) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Load services
function loadServices() {
    const tbody = document.getElementById('services-list');
    if (!tbody) return;
    
    fetch('get_services.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) return;
            allServices = data.services;
            
            if (allServices.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-cut"></i>
                                <h4>No Services Found</h4>
                                <p>Add your first service to get started</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = '';
            
            allServices.forEach(service => {
                const row = document.createElement('tr');
                const imageHtml = service.image_path 
                    ? `<img src="../${service.image_path}" class="service-img" alt="${service.name}">`
                    : `<div class="service-img-placeholder"><i class="fas fa-image"></i></div>`;
                
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            ${imageHtml}
                            <strong>${service.name}</strong>
                        </div>
                    </td>
                    <td class="service-description">
                        ${service.description || '-'}
                    </td>
                    <td>
                        <span class="service-duration">${service.duration} min</span>
                    </td>
                    <td>
                        <span class="currency">${service.price} ETB</span>
                    </td>
                    <td>
                        <span class="badge ${service.status === 'active' ? 'bg-success' : 'bg-secondary'}">${service.status}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" onclick="editServiceById(${service.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteService(${service.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(err => console.error(err));
}

// Setup service event listeners
function setupServiceEventListeners() {
    // Add service form
    const addServiceForm = document.getElementById('addServiceForm');
    if (addServiceForm) {
        addServiceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const serviceId = document.getElementById('edit-service-id').value;
            if (serviceId) {
                updateServiceDetails(serviceId);
            } else {
                addNewService();
            }
        });
    }

    // Reset modal when closed
    const modal = document.getElementById('addServiceModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('addServiceForm').reset();
            document.getElementById('edit-service-id').value = '';
            document.querySelector('#addServiceModal .modal-title').textContent = 'Add New Service';
            document.getElementById('service-image-preview').innerHTML = `
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Click to upload or drag & drop</p>
            `;
        });
    }
}

// Add new service
function addNewService() {
    const formData = new FormData();
    
    formData.append('name', document.getElementById('service-name').value);
    formData.append('description', document.getElementById('service-description').value);
    formData.append('duration', document.getElementById('service-duration').value);
    formData.append('price', document.getElementById('service-price').value);
    formData.append('status', document.getElementById('service-status').value);
    
    const imageFile = document.getElementById('service-image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const saveBtn = document.getElementById('save-service-btn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    fetch('add_service.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save Service';
        if (data.success) {
            const modalElement = document.getElementById('addServiceModal');
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();
            loadServices();
            showNotification('New service added successfully!', 'success');
        } else {
            showNotification(data.message, 'danger');
        }
    })
    .catch(err => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save Service';
        console.error(err);
    });
}

// Edit service by ID
function editServiceById(id) {
    const service = allServices.find(s => s.id == id);
    if (service) {
        editService(service);
    }
}

// Edit service
function editService(service) {
    document.getElementById('edit-service-id').value = service.id;
    document.getElementById('service-name').value = service.name;
    document.getElementById('service-description').value = service.description || '';
    document.getElementById('service-duration').value = service.duration;
    document.getElementById('service-price').value = service.price;
    document.getElementById('service-status').value = service.status || 'active';
    
    if (service.image_path) {
        document.getElementById('service-image-preview').innerHTML = `<img src="../${service.image_path}" alt="Preview">`;
    } else {
        document.getElementById('service-image-preview').innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to upload or drag & drop</p>
        `;
    }
    
    document.querySelector('#addServiceModal .modal-title').textContent = 'Edit Service';
    
    const modalElement = document.getElementById('addServiceModal');
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.show();
}

// Update service details
function updateServiceDetails(id) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('name', document.getElementById('service-name').value);
    formData.append('description', document.getElementById('service-description').value);
    formData.append('duration', document.getElementById('service-duration').value);
    formData.append('price', document.getElementById('service-price').value);
    formData.append('status', document.getElementById('service-status').value);
    
    const imageFile = document.getElementById('service-image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    const saveBtn = document.getElementById('save-service-btn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

    fetch('update_service.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save Service';
        if (data.success) {
            const modalElement = document.getElementById('addServiceModal');
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();
            loadServices();
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'danger');
        }
    })
    .catch(err => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save Service';
        console.error(err);
    });
}

// Delete service
function deleteService(id) {
    if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
        fetch('delete_service.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadServices();
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'danger');
            }
        })
        .catch(err => console.error(err));
    }
}
