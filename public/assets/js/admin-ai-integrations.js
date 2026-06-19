/**
 * ZIEXAM AI - AI Integration Settings Logic
 * Handles driver switching and password visibility.
 */
document.addEventListener('DOMContentLoaded', function () {
    const driverSelect = document.getElementById('aiDriverSelect');
    const geminiBox = document.getElementById('geminiSettings');
    const openaiBox = document.getElementById('openaiSettings');

    // Toggle Driver Configuration Boxes
    function toggleSettings() {
        // Reset active class
        geminiBox.classList.remove('active');
        openaiBox.classList.remove('active');

        // Apply active class based on selection
        if (driverSelect.value === 'gemini') {
            geminiBox.classList.add('active');
        } else if (driverSelect.value === 'openai') {
            openaiBox.classList.add('active');
        }
    }

    if (driverSelect) {
        toggleSettings(); // Initialize on load
        driverSelect.addEventListener('change', toggleSettings);
    }

    // Password Visibility Toggle (Event Delegation)
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('.ai-pass-toggle');
        if (!toggleBtn) return;

        const container = toggleBtn.parentElement;
        const input = container.querySelector('input');
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
    });
});