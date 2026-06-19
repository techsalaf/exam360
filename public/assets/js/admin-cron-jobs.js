/**
 * ZIEXAM AI - Cron Jobs Settings Logic
 * Handles copying the cron command to clipboard.
 */
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.querySelector('.cron-copy-btn');
    const commandElement = document.getElementById('cronCommand');

    if (copyBtn && commandElement) {
        copyBtn.addEventListener('click', function() {
            const command = commandElement.innerText;
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(command).then(() => {
                    toastr.success(window.cronTexts.copied);
                }).catch(() => {
                    toastr.error(window.cronTexts.failed);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = command;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    toastr.success(window.cronTexts.copied);
                } catch (err) {
                    toastr.error(window.cronTexts.failed);
                }
                document.body.removeChild(textArea);
            }
        });
    }
});