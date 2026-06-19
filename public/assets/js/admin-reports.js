document.addEventListener('DOMContentLoaded', function () {
    
    // Initialize Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirmation Logic
    document.querySelectorAll('.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            
            Swal.fire({
                title: btn.dataset.title || 'Are you sure?',
                text: btn.dataset.text || 'Action cannot be undone.',
                icon: btn.dataset.type || 'warning',
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

    // Select2 Logic
    if (typeof $ !== 'undefined' && $.fn.select2) {
        
        function formatUserOption(state) {
            if (!state.id) return state.text;
            return $('<span><i class="fa-regular fa-user me-2 opacity-50"></i> ' + state.text + '</span>');
        }

        function formatStatusOption(option) {
            if (!option.id) return option.text;
            let colorClass = '';
            
            if (option.element.value === 'success') colorClass = 'status-dot-success';
            else if (option.element.value === 'failed') colorClass = 'status-dot-failed';
            else if (option.element.value === 'suspicious') colorClass = 'status-dot-suspicious';
            
            if (colorClass) {
                return $('<span><span class="status-option-dot ' + colorClass + '"></span>' + option.text + '</span>');
            }
            return option.text;
        }

        // Initialize User Select2
        $('.select2-users').select2({
            placeholder: "Select User", // Explicit JS placeholder
            allowClear: true,
            minimumInputLength: 0,
            templateResult: formatUserOption,
            templateSelection: formatUserOption
        });
        
        // Initialize Status Select2
        $('.select2-status').select2({
            minimumResultsForSearch: Infinity,
            templateResult: formatStatusOption,
            templateSelection: formatStatusOption
        });
        
        // Mobile Offcanvas Select2
        const offcanvas = document.getElementById('reportFilterOffcanvas');
        if (offcanvas) {
            offcanvas.addEventListener('shown.bs.offcanvas', function () {
                 $('.select2-users-mobile').select2({
                    placeholder: "Select User",
                    allowClear: true,
                    minimumInputLength: 0,
                    dropdownParent: $('#reportFilterOffcanvas')
                });
            });
        }
    }
});