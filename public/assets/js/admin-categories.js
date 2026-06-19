"use strict";

document.addEventListener("DOMContentLoaded", function() {

    // 1. FLOATING BULK DELETE
    const selectAll = document.getElementById('selectAll');
    const bulkItems = document.querySelectorAll('.bulk-item');
    const floatingBar = document.getElementById('floatingBulkBar');
    const selectedCount = document.getElementById('selectedCount');

    function toggleFloatingBar() {
        const count = document.querySelectorAll('.bulk-item:checked').length;
        if(count > 0) {
            floatingBar.classList.add('show');
            selectedCount.innerText = count;
        } else {
            floatingBar.classList.remove('show');
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            bulkItems.forEach(item => item.checked = this.checked);
            toggleFloatingBar();
        });
    }

    bulkItems.forEach(item => {
        item.addEventListener('change', toggleFloatingBar);
    });

    // 2. EDIT MODAL LOGIC (UPDATED)
    const editModalEl = document.getElementById('editCategoryModal');
    
    if(editModalEl) {
        const editModal = new bootstrap.Modal(editModalEl);
        const editForm = document.querySelector('#editCategoryModal form');
        
        // Input Fields
        const nameInput = document.getElementById('edit_name');
        const descInput = document.getElementById('edit_description');
        const meta1Input = document.getElementById('edit_meta_1');
        const meta2Input = document.getElementById('edit_meta_2');
        
        // Image Elements
        const previewImg = document.getElementById('edit_preview_img');
        const previewLabel = document.getElementById('edit_preview_label');
        const deleteFlag = document.getElementById('edit_delete_image_flag');
        const fileInput = document.getElementById('edit_image_input');
        const removeBtn = document.getElementById('btn_remove_edit_image');

        document.querySelectorAll('.btn-edit-category').forEach(btn => {
            btn.addEventListener('click', function() {
                // Get Data
                const { id, name, image, description, meta1, meta2, action } = this.dataset;

                // Populate Fields
                if(editForm) editForm.action = action;
                if(nameInput) nameInput.value = name;
                if(descInput) descInput.value = description || '';
                if(meta1Input) meta1Input.value = meta1 || '';
                if(meta2Input) meta2Input.value = meta2 || '';

                // Reset Image State
                if(deleteFlag) deleteFlag.value = "0";
                if(fileInput) fileInput.value = "";

                // Image Handling
                if(image && image !== '') {
                    previewImg.src = image;
                    previewImg.classList.add('show');
                    previewLabel.classList.add('has-image');
                } else {
                    previewImg.src = '';
                    previewImg.classList.remove('show');
                    previewLabel.classList.remove('has-image');
                }
                
                editModal.show();
            });
        });

        if(removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation(); 
                previewImg.src = ''; previewImg.classList.remove('show');
                previewLabel.classList.remove('has-image');
                fileInput.value = ''; deleteFlag.value = '1';
            });
        }
    }

    // 3. SWEETALERT: TOGGLE STATUS
    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const isActive = this.getAttribute('data-active') === '1'; 
            
            Swal.fire({
                title: isActive ? 'Disable Category?' : 'Enable Category?',
                text: isActive ? 'This will hide the category.' : 'This will make the category visible.',
                icon: isActive ? 'warning' : 'info',
                showCancelButton: true,
                confirmButtonText: isActive ? 'Yes, Disable' : 'Yes, Enable',
                buttonsStyling: false,
                customClass: {
                    popup: 'zi-swal-popup',
                    confirmButton: isActive ? 'btn btn-danger px-4' : 'btn btn-success px-4',
                    cancelButton: 'btn btn-light border px-4',
                    actions: 'gap-3'
                }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // 4. SWEETALERT: DELETE ACTIONS
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form') || document.getElementById(this.getAttribute('form'));
            
            Swal.fire({
                title: 'Delete Category?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                buttonsStyling: false,
                customClass: {
                    popup: 'zi-swal-popup',
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-light border px-4',
                    actions: 'gap-3'
                }
            }).then((result) => {
                if (result.isConfirmed) if(form) form.submit();
            });
        });
    });

});

// GLOBAL HELPER
function previewImage(input, imgId, labelId, deleteFlagId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(imgId).src = e.target.result;
            document.getElementById(imgId).classList.add('show');
            document.getElementById(labelId).classList.add('has-image');
            if(deleteFlagId) document.getElementById(deleteFlagId).value = "0";
        }
        reader.readAsDataURL(file);
    }
}