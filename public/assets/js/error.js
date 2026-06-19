/**
 * ZIEXAM AI - Error Pages Logic
 * Handles interactive elements on error pages (e.g., Reload).
 */
document.addEventListener('DOMContentLoaded', function () {
    
    // Handle Maintenance Mode "Check Status" button
    const reloadBtn = document.querySelector('.reload-page');
    
    if (reloadBtn) {
        reloadBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.reload();
        });
    }

});