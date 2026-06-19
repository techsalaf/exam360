document.addEventListener('DOMContentLoaded', function () {
    
    const config = document.body.dataset;
    const confirmTitle = config.confirmTitle || 'Are you sure?';
    const confirmText = config.confirmText || 'This action cannot be undone.';
    const confirmYes = config.confirmYes || 'Yes, proceed!';

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    document.querySelectorAll('.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const title = button.dataset.title || confirmTitle;
            const text = button.dataset.text || confirmText;

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#ef4444',
                confirmButtonText: confirmYes
            }).then((result) => { 
                if (result.isConfirmed) {
                    this.submit(); 
                }
            });
        });
    });

    if (typeof TomSelect !== 'undefined') {
        document.querySelectorAll('.user-search-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                placeholder: 'Choose a user...',
                searchField: ['text'],
                maxOptions: 50,
                dropdownParent: 'body'
            });
        });
    }

});