document.addEventListener('DOMContentLoaded', function () {
    
    // Retrieve configuration from data attributes (Envato Safe)
    const config = document.getElementById('couponConfig')?.dataset || {};
    const confirmTitle = config.confirmTitle || 'Are you sure?';
    const confirmText = config.confirmText || 'This action cannot be undone.';
    const confirmYes = config.confirmYes || 'Yes, delete it!';
    const confirmCancel = config.confirmCancel || 'Cancel';
    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';

    // 1. Handle Usage Progress Bars (Replaces inline style width)
    document.querySelectorAll('.usage-bar[data-progress]').forEach(function (bar) {
        const value = bar.dataset.progress;
        if (value !== undefined) {
            bar.style.width = value + '%';
        }
    });

    // 2. Handle Delete Confirmation
    document.querySelectorAll('.delete-form-sa').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', 
                cancelButtonColor: '#64748b', 
                confirmButtonText: confirmYes,
                cancelButtonText: confirmCancel,
                background: isDarkMode ? '#1e293b' : '#fff',
                color: isDarkMode ? '#f8fafc' : '#0f172a'
            }).then((result) => { 
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});