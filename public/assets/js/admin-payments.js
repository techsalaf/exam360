document.addEventListener('DOMContentLoaded', function () {
    
    // Retrieve localized config from data attributes (Envato Safe)
    const config = document.getElementById('paymentConfig')?.dataset || {};
    const confirmTitle = config.confirmTitle || 'Are you sure?';
    const confirmText = config.confirmText || 'This action cannot be undone.';
    const confirmYes = config.confirmYes || 'Yes, proceed';
    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';

    // Initialize Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle Confirmations (Approve/Reject)
    document.querySelectorAll('.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Allow overriding defaults via specific button data attributes
            const btn = this.querySelector('button[type="submit"]');
            const title = btn.dataset.title || confirmTitle;
            const text = btn.dataset.text || confirmText;
            const type = btn.dataset.type || 'warning';

            Swal.fire({
                title: title,
                text: text,
                icon: type,
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#ef4444',
                confirmButtonText: confirmYes,
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