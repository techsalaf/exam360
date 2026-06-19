document.addEventListener('DOMContentLoaded', function () {
    
    // Retrieve configuration from data attributes (Envato Safe)
    const configEl = document.getElementById('notifConfig');
    if (!configEl) return;

    const config = {
        deleteTitle: configEl.dataset.deleteTitle,
        deleteText: configEl.dataset.deleteText,
        confirmBtn: configEl.dataset.confirmBtn,
        cancelBtn: configEl.dataset.cancelBtn,
        markAllTitle: configEl.dataset.markTitle,
        markAllText: configEl.dataset.markText,
        confirmRead: configEl.dataset.confirmRead
    };

    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const swalColors = {
        confirm: '#059669',
        cancel: '#64748b',
        danger: '#ef4444',
        bg: isDarkMode ? '#1e293b' : '#fff',
        text: isDarkMode ? '#f8fafc' : '#0f172a'
    };

    // 1. Delete Single Notification
    document.querySelectorAll('.form-confirm-delete .btn-trigger-delete, .form-confirm-delete .delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: config.deleteTitle,
                text: config.deleteText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: swalColors.danger,
                cancelButtonColor: swalColors.cancel,
                confirmButtonText: config.confirmBtn,
                cancelButtonText: config.cancelBtn,
                background: swalColors.bg,
                color: swalColors.text
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 2. Mark All as Read Action
    document.querySelectorAll('.btn-confirm-read-all').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            Swal.fire({
                title: config.markAllTitle,
                text: config.markAllText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: swalColors.confirm,
                cancelButtonColor: swalColors.cancel,
                confirmButtonText: config.confirmRead,
                cancelButtonText: config.cancelBtn,
                background: swalColors.bg,
                color: swalColors.text
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

});