/**
 * Admin CMS Image Handler
 * Handles file preview and removal for generic image inputs.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Handle File Selection (Show Preview)
    const inputs = document.querySelectorAll('.generic-image-input');
    
    if (inputs.length > 0) {
        inputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = this.files[0];
                
                // Get target IDs from data attributes
                const previewId = this.getAttribute('data-preview-img');
                const labelId = this.getAttribute('data-preview-label');
                const deleteInputId = this.getAttribute('data-delete-input');
                
                const previewImg = document.getElementById(previewId);
                const label = document.getElementById(labelId);
                const deleteInput = document.getElementById(deleteInputId);

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        // Update Image Source
                        if(previewImg) {
                            previewImg.src = evt.target.result;
                            previewImg.classList.add('show');
                        }
                        // Update Label Styling
                        if(label) {
                            label.classList.add('has-image');
                        }
                        // Reset Delete Flag
                        if(deleteInput) {
                            deleteInput.value = '0';
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // 2. Handle Remove Button Click
    const removeBtns = document.querySelectorAll('.generic-image-remove');
    
    if (removeBtns.length > 0) {
        removeBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent file dialog from opening

                const previewId = this.getAttribute('data-preview-img');
                const labelId = this.getAttribute('data-preview-label');
                const deleteInputId = this.getAttribute('data-delete-input');

                const previewImg = document.getElementById(previewId);
                const label = document.getElementById(labelId);
                const deleteInput = document.getElementById(deleteInputId);

                // Clear File Input
                if (label) {
                    const fileInput = label.querySelector('input[type="file"]');
                    if (fileInput) fileInput.value = '';
                    label.classList.remove('has-image');
                }

                // Hide Preview
                if (previewImg) {
                    previewImg.src = '';
                    previewImg.classList.remove('show');
                }

                // Set Delete Flag to True
                if (deleteInput) {
                    deleteInput.value = '1';
                }
            });
        });
    }
});