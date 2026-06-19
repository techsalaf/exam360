document.addEventListener('DOMContentLoaded', function () {
    const btnCode = document.getElementById('btn-code-mode');
    const btnVisual = document.getElementById('btn-visual-mode');
    const editor = document.getElementById('instruction-editor');
    const preview = document.getElementById('instruction-preview');

    // Return early if elements do not exist to prevent console errors
    if (!btnCode || !btnVisual || !editor || !preview) {
        return;
    }

    function toggleDisplay(mode) {
        if (mode === 'visual') {
            btnVisual.classList.add('active');
            btnCode.classList.remove('active');

            let content = editor.value.trim();
            
            if (content.length === 0) {
                content = '<p class="text-muted text-center py-5"><em>No instructions content to display.</em></p>';
            }

            preview.innerHTML = content;
            editor.classList.add('d-none');
            preview.classList.remove('d-none');
        } else {
            btnCode.classList.add('active');
            btnVisual.classList.remove('active');

            editor.classList.remove('d-none');
            preview.classList.add('d-none');
        }
    }

    btnCode.addEventListener('click', function () {
        toggleDisplay('code');
    });

    btnVisual.addEventListener('click', function () {
        toggleDisplay('visual');
    });
});