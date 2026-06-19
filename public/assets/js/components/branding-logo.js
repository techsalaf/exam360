// public/assets/js/components/branding-logo.js

document.addEventListener('DOMContentLoaded', () => {
    // Check if the required config and Swal are available
    if (typeof window.Swal === 'undefined' || typeof window.BrandingLogoMessages === 'undefined') {
        console.error("SweetAlert or BrandingLogoMessages configuration missing.");
        return;
    }

    /**
     * Global function called by data attribute handlers to trigger the deletion confirmation.
     * @param {string} fieldName - The hidden input name to send for deletion (e.g., 'delete_app_logo_light').
     */
    window.confirmDelete = function (fieldName) {
        Swal.fire({
            title: window.BrandingLogoMessages.confirmTitle,
            text: window.BrandingLogoMessages.confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: window.BrandingLogoMessages.yesRemove,
            cancelButtonText: window.BrandingLogoMessages.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('brandingForm');
                
                // Create a hidden input to signal deletion to the backend
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = fieldName;
                input.value = '1';
                
                form.appendChild(input);
                form.submit();
            }
        });
    };

    // Attach event listeners to all delete buttons using their data attribute (FIX 2)
    document.querySelectorAll('.logo-delete-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            const deleteField = btn.getAttribute('data-delete');
            if (deleteField) {
                window.confirmDelete(deleteField);
            }
        });
    });
});