document.addEventListener('DOMContentLoaded', function () {

    // --- File Attachment Handler ---
    const attachmentInput = document.getElementById('attachment');
    const fileNameContainer = document.getElementById('file-names-container');

    if (attachmentInput && fileNameContainer) {
        
        function truncateFilename(filename, maxLength = 25) {
            if (filename.length <= maxLength) return filename;
            const dotIndex = filename.lastIndexOf('.');
            const ext = dotIndex > -1 ? filename.substring(dotIndex) : '';
            const name = dotIndex > -1 ? filename.substring(0, dotIndex) : filename;
            const start = name.substring(0, 15);
            const end = name.substring(name.length - 5);
            return `${start}...${end}${ext}`;
        }

        attachmentInput.addEventListener('change', function () {
            fileNameContainer.innerHTML = '';

            Array.from(this.files).forEach(file => {
                const span = document.createElement('span');
                span.className = 'file-chip';
                span.title = file.name;

                const iconClass = file.type.includes('image') ? 'fa-file-image' : 'fa-file';
                span.innerHTML = `<i class="fa-solid ${iconClass} me-1"></i> ${truncateFilename(file.name)}`;

                fileNameContainer.appendChild(span);
            });
        });
    }

});