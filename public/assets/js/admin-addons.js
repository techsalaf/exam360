(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initToggleHandlers();
        initUploadPreview();
    });

    function initToggleHandlers() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('.js-addon-toggle').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const footer = this.closest('.addon-footer');
                const statusBadge = footer.querySelector('.status-badge');
                
                this.disabled = true;

                fetch('/admin/extra/addons/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    this.disabled = false;
                    if(data.status === 'success') {
                        statusBadge.textContent = data.is_active ? 'ENABLED' : 'DISABLED';
                        
                        if (data.is_active) {
                            statusBadge.classList.remove('status-inactive');
                            statusBadge.classList.add('status-active');
                        } else {
                            statusBadge.classList.remove('status-active');
                            statusBadge.classList.add('status-inactive');
                        }
                    } else {
                        this.checked = !this.checked; 
                    }
                })
                .catch(error => {
                    this.disabled = false;
                    this.checked = !this.checked;
                });
            });
        });
    }

    function initUploadPreview() {
        const input = document.getElementById('addon_zip');

        if(input) {
            input.addEventListener('change', function() {
                const uploadContent = document.querySelector('.addon-upload-area .upload-content');
                
                if(!uploadContent) return;

                const defaultTitle = uploadContent.querySelector('h6');
                const defaultSubtitle = uploadContent.querySelector('p');
                
                if(this.files && this.files[0]) {
                    defaultTitle.textContent = this.files[0].name;
                    defaultSubtitle.textContent = 'File attached. Click Install to finish.';
                } else {
                    defaultTitle.textContent = 'Click to Upload';
                    defaultSubtitle.textContent = 'Select .zip file (Max 10MB)';
                }
            });
        }
    }

})();