(function() {
    const configEl = document.getElementById('proctor-config');
    if (!configEl) return;

    const proctorConfig = JSON.parse(configEl.value);
    const modal = document.getElementById('proctoring-fixed-layer');
    const handle = document.getElementById('proctoring-drag-handle');
    const examUI = document.getElementById('exam-ui-container');

    function initDraggable() {
        let isDragging = false;
        let offset = { x: 0, y: 0 };

        handle.addEventListener('mousedown', (e) => {
            isDragging = true;
            offset.x = e.clientX - modal.getBoundingClientRect().left;
            offset.y = e.clientY - modal.getBoundingClientRect().top;
            modal.style.transition = 'none';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            modal.style.right = 'auto';
            modal.style.left = (e.clientX - offset.x) + 'px';
            modal.style.top = (e.clientY - offset.y) + 'px';
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
        });
    }

    function initSecurityRestrictions() {
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (typeof Proctoring !== 'undefined' && Proctoring.logViolation) {
                    Proctoring.logViolation('tab_switch', 'User switched browser tab or minimized window');
                }
                
                Swal.fire({
                    title: 'Violation Warning!',
                    text: 'Switching tabs or applications is strictly prohibited. This incident has been logged.',
                    icon: 'warning',
                    confirmButtonColor: '#ef4444'
                });
            }
        });

        window.addEventListener('blur', function() {
            if (typeof Proctoring !== 'undefined' && Proctoring.logViolation) {
                Proctoring.logViolation('window_blur', 'Browser window lost focus');
            }
        });
    }

    async function startProctoringProcess() {
        if (examUI) {
            examUI.style.opacity = '0.1';
            examUI.style.pointerEvents = 'none';
        }

        const result = await Swal.fire({
            title: 'Proctoring Activation',
            text: 'This exam is proctored. You must allow camera and microphone access to begin. Tab switching and refreshing are disabled.',
            icon: 'info',
            confirmButtonText: 'Allow Access',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonColor: '#059669'
        });

        if (result.isConfirmed) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                const videoEl = document.getElementById('proctoring-video');
                if (videoEl) videoEl.srcObject = stream;

                if (typeof Proctoring !== 'undefined') {
                    Proctoring.init({
                        sessionId: proctorConfig.sessionId,
                        violationLogUrl: proctorConfig.logUrl,
                        csrfToken: proctorConfig.csrf,
                        modelsUrl: proctorConfig.models,
                        lang: proctorConfig.lang
                    });
                }

                initSecurityRestrictions();

                if (examUI) {
                    examUI.style.opacity = '1';
                    examUI.style.pointerEvents = 'auto';
                }
            } catch (err) {
                await Swal.fire({
                    title: 'Permission Denied',
                    text: 'You cannot participate in this exam without camera access.',
                    icon: 'error',
                    confirmButtonText: 'Retry Access'
                });
                window.location.reload();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initDraggable();
        startProctoringProcess();
    });

    document.addEventListener('ProctoringViolation', function() {
        const dot = document.getElementById('status-dot');
        if(dot) dot.style.background = '#ef4444';
    });
    document.addEventListener('ProctoringClear', function() {
        const dot = document.getElementById('status-dot');
        if(dot) dot.style.background = '#10b981';
    });
})();