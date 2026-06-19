/**
 * ZIEXAM AI - Social Settings Logic
 * Handles password visibility and clipboard actions via event delegation.
 */
document.addEventListener('click', function (e) {

    // 1. Password toggle logic
    const passBtn = e.target.closest('[data-toggle="password"]');
    if (passBtn) {
        const input = passBtn.previousElementSibling;
        const icon = passBtn.querySelector('i');

        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    }

    // 2. Copy to clipboard logic
    const copyBtn = e.target.closest('[data-copy]');
    if (copyBtn) {
        const input = copyBtn.previousElementSibling;
        
        if (input && navigator.clipboard) {
            navigator.clipboard.writeText(input.value).then(() => {
                const icon = copyBtn.querySelector('i');
                const originalClass = icon.className;

                // Visual feedback
                icon.className = 'fa-solid fa-check text-success';

                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    }

});