/**
 * ZIEXAM AI - Notification Template Editor Script
 * Handles variable insertion into textareas without inline handlers.
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Use event delegation to handle clicks on variable badges
    document.body.addEventListener('click', function(e) {
        
        // check if clicked element is a variable badge
        const trigger = e.target.closest('[data-insert]');
        if (!trigger) return;

        // Get the text to insert (e.g., {{name}})
        const textToInsert = trigger.getAttribute('data-insert');
        
        // Find the parent card to scope the search for the textarea
        const card = trigger.closest('.sn-card');
        if (!card) return;

        // Find the textarea within this specific card
        const textarea = card.querySelector('textarea');
        if (!textarea) return;

        // Insert text at cursor position
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after  = text.substring(end, text.length);

        textarea.value = before + textToInsert + after;
        
        // Move cursor to end of inserted text and focus
        textarea.selectionStart = textarea.selectionEnd = start + textToInsert.length;
        textarea.focus();
    });

});