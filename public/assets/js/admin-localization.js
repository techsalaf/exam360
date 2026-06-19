document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.confirm-delete-lang').forEach(btn => {
        btn.addEventListener('click', function () {
            const formId = this.dataset.form;
            const langName = this.dataset.name;

            Swal.fire({
                title: window.localeAlerts.delete_title,
                text: window.localeAlerts.delete_text.replace(':name', langName),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: window.localeAlerts.yes_delete,
                cancelButtonText: window.localeAlerts.cancel
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });

    document.querySelectorAll('.confirm-default').forEach(btn => {
        btn.addEventListener('click', function () {
            const formId = this.dataset.form;
            const langName = this.dataset.name;

            Swal.fire({
                title: window.localeAlerts.default_title,
                text: window.localeAlerts.default_text.replace(':name', langName),
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: window.localeAlerts.yes_default,
                cancelButtonText: window.localeAlerts.cancel
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });

});