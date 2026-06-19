// assets/js/admin-maintenance.js

document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.querySelector('[data-image-input]');
    const imagePreview = document.querySelector('[data-image-preview]');
    const deleteButton = document.querySelector('[data-delete-image-btn]');
    const deleteField = document.querySelector('[data-delete-field]');
    const existingContainer = document.querySelector('[data-existing-image-container]');
    const uploadContainer = document.querySelector('[data-upload-container]');
    
    // Ensure Swal is available and gather translation texts from data attributes
    if (deleteButton && typeof Swal !== 'undefined') {
        const title = deleteButton.getAttribute('data-swal-title');
        const text = deleteButton.getAttribute('data-swal-text');
        const confirmText = deleteButton.getAttribute('data-swal-confirm');
        const cancelText = deleteButton.getAttribute('data-swal-cancel');

        deleteButton.addEventListener('click', function() {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            }).then((result) => {
                if (result.isConfirmed) {
                    if (deleteField) {
                        deleteField.value = 1;
                    }
                    if (existingContainer) existingContainer.classList.add('d-none');
                    if (uploadContainer) uploadContainer.classList.remove('d-none');
                    // Clear the file input element
                    if (imageInput) {
                        imageInput.value = '';
                    }
                }
            });
        });
    }

    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            imagePreview.innerHTML = ''; 
            if (event.target.files.length > 0) {
                const file = event.target.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-fluid', 'rounded', 'shadow-sm', 'maintenance-preview-img');
                    imagePreview.appendChild(img);
                }
                reader.readAsDataURL(file);
                
                // When uploading a new image, ensure delete flag is reset
                if (deleteField) {
                     deleteField.value = 0;
                }
                if (existingContainer) existingContainer.classList.add('d-none');
                if (uploadContainer) uploadContainer.classList.remove('d-none');
            }
        });
    }
});