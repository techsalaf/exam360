/**
 * ZIEXAM AI - General Notifications Logic
 * Handles SMS driver switching and password visibility toggles.
 */

document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Config Parsing (Safe JSON)
    // We check if the config element exists first to avoid errors on other pages
    const configEl = document.getElementById('sms-config');
    if (!configEl) return;
    
    let smsConfig;
    try {
        smsConfig = JSON.parse(configEl.textContent);
    } catch (e) {
        console.error('Error parsing SMS config', e);
        return;
    }

    // Cache DOM elements for SMS Driver Logic
    const driversRadios = document.querySelectorAll('input[name="sms_driver"]');
    const driverContainer = document.querySelector('.zi-driver-grid');
    const lblKey = document.getElementById('lbl-sms-key');
    const lblSecret = document.getElementById('lbl-sms-secret');
    const lblFrom = document.getElementById('lbl-sms-from');

    /**
     * Updates the input labels based on the selected driver (Twilio vs Vonage)
     */
    function updateSmsLabels(driverKey) {
        if (!smsConfig.drivers || !smsConfig.drivers[driverKey]) return;
        const data = smsConfig.drivers[driverKey];

        if (lblKey) lblKey.textContent = data.key;
        if (lblSecret) lblSecret.textContent = data.secret;
        if (lblFrom) lblFrom.textContent = data.from;
    }

    // 2. Handle SMS Driver Switching
    if (driverContainer && driversRadios.length > 0) {
        driversRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                // Update visual active state on cards
                const cards = driverContainer.querySelectorAll('.zi-driver-card');
                cards.forEach(card => card.classList.remove('active'));
                
                const activeCard = this.closest('.zi-driver-card');
                if (activeCard) activeCard.classList.add('active');

                // Update text labels based on selection
                updateSmsLabels(this.value);
            });
        });
    }

    // Initialize labels on page load based on current selection
    if (smsConfig.initialDriver) {
        updateSmsLabels(smsConfig.initialDriver);
    }

    // 3. Handle Password Visibility Toggle (Event Delegation)
    // This allows the toggle to work on any input group with the correct data attribute
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('[data-toggle="password-visibility"]');
        if (!toggleBtn) return;

        const container = toggleBtn.closest('.zi-input-group');
        if (!container) return;

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