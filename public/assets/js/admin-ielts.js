'use strict';

document.addEventListener('DOMContentLoaded', function() {
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const confirmForms = document.querySelectorAll('.confirm-action');
    const config = document.getElementById('ieltsConfig');
    
    if (config && confirmForms.length > 0) {
        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const title = config.dataset.confirmTitle || 'Are you sure?';
                const text = config.dataset.confirmText || 'This action cannot be undone.';
                const confirmBtn = config.dataset.confirmYes || 'Yes, delete it';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: confirmBtn
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    }
});