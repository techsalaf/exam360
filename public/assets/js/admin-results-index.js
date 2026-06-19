"use strict";

document.addEventListener('DOMContentLoaded', function () {
    
    // Initialize Tooltips
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Handle Dynamic Progress Bars
    document.querySelectorAll('.progress-bar[data-progress]').forEach(function (bar) {
        const value = bar.dataset.progress;
        if (value !== undefined) {
            bar.style.width = value + '%';
        }
    });

    // Confirmation Logic
    document.querySelectorAll('.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            
            Swal.fire({
                title: btn.dataset.title || 'Are you sure?',
                text: btn.dataset.text || 'Action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => { 
                if (result.isConfirmed) {
                    this.submit(); 
                }
            });
        });
    });
});