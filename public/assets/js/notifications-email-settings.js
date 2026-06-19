/**
 * ZIEXAM AI - Email Settings Logic
 * Handles driver switching, password toggling, and test email dispatch.
 */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Mail Driver Switching Logic
    const driverRadios = document.querySelectorAll('input[name="mail_driver"]');
    
    if (driverRadios.length > 0) {
        driverRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                const driver = this.value;
                const smtp = document.getElementById('smtp-panel');
                const mailgun = document.getElementById('mailgun-panel');
                
                // Reset visibility using classes
                if(smtp) smtp.classList.add('d-none');
                if(mailgun) mailgun.classList.add('d-none');

                // Show selected panel
                if (driver === 'smtp' && smtp) smtp.classList.remove('d-none');
                if (driver === 'mailgun' && mailgun) mailgun.classList.remove('d-none');

                // Update Visual Active State on Cards
                document.querySelectorAll('.zi-driver-card').forEach(c => c.classList.remove('active'));
                
                const card = this.closest('.zi-driver-card');
                if(card) card.classList.add('active');
            });
        });
    }

    // 2. Password Visibility Toggle (Event Delegation)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-toggle="password"]');
        if (!btn) return;

        const input = btn.previousElementSibling;
        const icon = btn.querySelector('i');

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

    // 3. Test Mail Logic
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-test-mail]');
        if (!btn) return;

        // Retrieve configuration from JSON block
        const configEl = document.getElementById('mail-test-config');
        if (!configEl) return;

        let config;
        try {
            config = JSON.parse(configEl.textContent);
        } catch (err) {
            console.error('Invalid Mail Test Config', err);
            return;
        }

        // Confirmation
        if (!confirm(config.confirm)) return;

        // Set Loading State
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = config.loading;

        // Perform Request
        fetch(config.url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': config.csrf,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;

            if (data.status === 'success') {
                alert(data.message); 
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            console.error('Mail Test Error:', error);
            alert(config.error);
        });
    });

});