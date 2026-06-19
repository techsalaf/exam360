/**
 * Exam Instructions Logic
 * Handles the enabling/disabling of the Start Exam button based on the agreement checkbox.
 */
document.addEventListener('DOMContentLoaded', () => {
    const checkbox = document.getElementById('instructionsAgree');
    const startBtn = document.getElementById('start-exam-button');

    // Check if the elements exist (they won't if $isUpcoming is true)
    if (!checkbox || !startBtn) return;

    const toggleButton = () => {
        // Sets disabled property based on whether the checkbox is checked.
        startBtn.disabled = !checkbox.checked;
    };

    // Bind the change event
    checkbox.addEventListener('change', toggleButton);
    
    // Initial check when the page loads
    toggleButton();
});