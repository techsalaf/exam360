/**
 * ZIEXAM AI - Login Page Logic
 * Handles captcha refreshing without inline handlers.
 */
document.addEventListener('DOMContentLoaded', function () {
    const captchaImage = document.getElementById('captchaImage');

    if (captchaImage) {
        captchaImage.addEventListener('click', function () {
            // Append random string to prevent browser caching
            const baseUrl = this.getAttribute('data-refresh-url');
            this.src = baseUrl + Math.random();
        });
    }
});