/**
 * ZIEXAM AI - Currency Settings Logic
 * Handles the toggling of custom currency fields by toggling the Bootstrap 'd-none' class.
 */
document.addEventListener('DOMContentLoaded', function () {
    const currencySelect = document.getElementById('currency_code_select');
    const customFieldsContainer = document.querySelector('.custom-currency-fields-container');

    if (!currencySelect || !customFieldsContainer) return;

    function toggleCustomFields() {

        if (currencySelect.value === 'CUSTM') {
            customFieldsContainer.classList.remove('d-none'); 

            customFieldsContainer.querySelectorAll('input').forEach(input => input.setAttribute('required', true));
        } else {
            customFieldsContainer.classList.add('d-none');
            customFieldsContainer.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
        }
    }

    currencySelect.addEventListener('change', toggleCustomFields);

    // Initial check on load
    toggleCustomFields();
});