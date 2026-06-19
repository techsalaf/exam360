"use strict";

document.addEventListener('DOMContentLoaded', function () {
    
    // Initialize Tooltips
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Publish Confirmation
    document.querySelectorAll('.publish-form-sa').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const button = this.querySelector('button');
            
            Swal.fire({
                title: button.dataset.title || 'Are you sure?',
                text: button.dataset.text || 'Action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669', 
                cancelButtonColor: '#64748b', 
                confirmButtonText: 'Yes, Publish!'
            }).then((result) => { 
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});