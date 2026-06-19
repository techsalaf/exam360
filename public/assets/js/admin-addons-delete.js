/**
 * CMS Addons Delete Confirmation Script
 * Uses SweetAlert2 for delete confirmation on the Addons page.
 */
(function () {
    'use strict';

    function initAddonDeleteSweetAlert() {
        const config = document.getElementById('addonConfig');
        if (!config || typeof Swal === 'undefined') {
            return;
        }

        const deleteButtons = document.querySelectorAll('.delete-addon-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: config.getAttribute('data-confirm-title'),
                    text: config.getAttribute('data-confirm-text'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#059669',
                    confirmButtonText: config.getAttribute('data-confirm-yes'),
                    cancelButtonText: config.getAttribute('data-confirm-cancel')
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initAddonDeleteSweetAlert);
})();