/**
 * ZIEXAM AI - SMS Notification Template Logic
 * Handles character counting and variable insertion without inline scripts.
 */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Initialize character counts on page load
    document.querySelectorAll('[data-countable]').forEach(updateCount);

    // 2. Character Counter Event Listener (Input)
    document.addEventListener('input', function (e) {
        if (e.target.matches('[data-countable]')) {
            updateCount(e.target);
        }
    });

    /**
     * Updates the character count display for a specific textarea.
     * @param {HTMLTextAreaElement} textarea 
     */
    function updateCount(textarea) {
        const wrapper = textarea.closest('.sn-card');
        const counter = wrapper ? wrapper.querySelector('.sn-char-count') : null;
        
        if (!counter) return;

        const len = textarea.value.length;
        const limit = 160;

        // Logic for SMS segments (Standard GSM 03.38 is 160 chars)
        if (len > limit) {
            const segments = Math.ceil(len / limit);
            counter.textContent = `${len} chars (${segments} segments)`;
            counter.classList.add('text-danger');
            counter.classList.remove('text-muted');
        } else {
            counter.textContent = `${len}/${limit}`;
            counter.classList.remove('text-danger');
            counter.classList.add('text-muted');
        }
    }

    // 3. Variable Insertion Logic (Click)
    document.addEventListener('click', function (e) {
        const badge = e.target.closest('[data-insert]');
        if (!badge) return;

        const textToInsert = badge.getAttribute('data-insert');
        
        // Find the specific card context
        const wrapper = badge.closest('.sn-card');
        if (!wrapper) return;

        const textarea = wrapper.querySelector('textarea');
        if (!textarea) return;

        // Standard cursor insertion
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const val = textarea.value;

        textarea.value = val.substring(0, start) + textToInsert + val.substring(end);

        // Move cursor and focus
        textarea.selectionStart = textarea.selectionEnd = start + textToInsert.length;
        textarea.focus();

        // Update the character count immediately after insertion
        updateCount(textarea);
    });

});