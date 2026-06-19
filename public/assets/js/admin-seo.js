document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('seoBannerImage');
    const previewDiv = document.getElementById('newBannerPreview');
    const emptyState = document.getElementById('emptyBannerState');
    const existingBanner = document.getElementById('existingBanner');
    const deleteFlag = document.getElementById('seoBannerDeleteFlag');
    const deleteBtn = document.getElementById('deleteBannerBtn');

    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="New Banner Preview" class="img-fluid rounded shadow-sm seo-banner-preview">`;
                    previewDiv.classList.remove('d-none');
                    if (emptyState) emptyState.classList.add('d-none');
                    if (existingBanner) existingBanner.classList.add('d-none');
                    deleteFlag.value = 0; 
                }
                reader.readAsDataURL(file);
            } else {
                previewDiv.classList.add('d-none');
            }
        });
    }

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            Swal.fire({
                title: window.seoAlerts.title,
                text: window.seoAlerts.text,
                icon: window.seoAlerts.icon,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: window.seoAlerts.yes_remove,
                cancelButtonText: window.seoAlerts.cancel
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteFlag.value = 1;
                    fileInput.value = ''; 
                    if (existingBanner) existingBanner.classList.add('d-none');
                    if (emptyState) emptyState.classList.remove('d-none');
                    previewDiv.innerHTML = '';
                    previewDiv.classList.add('d-none');
                }
            });
        });
    }
});