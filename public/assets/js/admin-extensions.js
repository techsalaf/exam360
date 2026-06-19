/**
 * ZIEXAM AI - Extension Settings Logic
 * Handles password visibility and clipboard copying via event delegation.
 */
document.addEventListener('click', function (e) {
    
    // Toggle Secret Visibility
    const toggleBtn = e.target.closest('.toggle-secret');
    if (toggleBtn) {
        const input = toggleBtn.previousElementSibling;
        const icon = toggleBtn.querySelector('i');

        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    // Copy to Clipboard
    const copyBtn = e.target.closest('.copy-btn');
    if (copyBtn) {
        const input = copyBtn.previousElementSibling;
        
        if (input && navigator.clipboard) {
            navigator.clipboard.writeText(input.value).then(() => {
                const icon = copyBtn.querySelector('i');
                const originalClass = icon.className;
                
                icon.className = 'fa-solid fa-check text-success';
                
                setTimeout(() => {
                    icon.className = originalClass;
                }, 1500);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    }
});