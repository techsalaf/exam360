document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById('password-field');
    const strengthText = document.getElementById('password-strength-text');
    const segments = [
        document.getElementById('seg1'),
        document.getElementById('seg2'),
        document.getElementById('seg3'),
        document.getElementById('seg4')
    ];

    function checkPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) score++;
        if (password.match(/[0-9]/)) score++;
        if (password.match(/[^a-zA-Z0-9\s]/)) score++;
        return score;
    }

    if(passwordField) {
        passwordField.addEventListener('input', function() {
            const password = passwordField.value;
            const score = checkPasswordStrength(password);

            segments.forEach(seg => {
                seg.className = 'strength-segment';
            });

            if (password.length === 0) {
                strengthText.textContent = "Minimum 8 characters with letters and numbers.";
                strengthText.className = 'input-hint';
                return;
            }

            if (score === 1) {
                segments[0].classList.add('weak');
                strengthText.textContent = "Weak: Too simple.";
                strengthText.className = 'strength-text weak';
            } else if (score === 2) {
                segments[0].classList.add('medium');
                segments[1].classList.add('medium');
                strengthText.textContent = "Medium: Try adding symbols or numbers.";
                strengthText.className = 'strength-text medium';
            } else if (score === 3) {
                segments[0].classList.add('strong');
                segments[1].classList.add('strong');
                segments[2].classList.add('strong');
                strengthText.textContent = "Strong: Good combination.";
                strengthText.className = 'strength-text strong';
            } else if (score === 4) {
                segments[0].classList.add('strong');
                segments[1].classList.add('strong');
                segments[2].classList.add('strong');
                segments[3].classList.add('strong');
                strengthText.textContent = "Excellent: Highly secure.";
                strengthText.className = 'strength-text strong';
            }
        });
    }
});