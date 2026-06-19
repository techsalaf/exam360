class CertificateStudio {
    constructor() {
        this.dom = {
            form: document.getElementById('certificateForm'),
            inputs: document.querySelectorAll('[data-model]'),
            aligners: document.querySelectorAll('[data-align]'),
            segments: document.querySelectorAll('.zi-seg-opt'),
            paper: document.getElementById('certificatePaper'),
            zoomDisplay: document.getElementById('zoomVal'),
            markupInput: document.getElementById('hiddenMarkup'),
            orientationInput: document.getElementById('orientationInput'),
            fontSelect: document.getElementById('fontSelector'),
            bgInput: document.getElementById('bgImageInput'),
            bgRemoveBtn: document.getElementById('removeBgBtn'),
            bgRemoveHidden: document.getElementById('removeBgHidden'),
            sigTypeInputs: document.querySelectorAll('input[name="sig_type_ui"]'),
            sigTextInput: document.getElementById('sigTextInput'),
            sigImgInput: document.getElementById('sigImgInput'),
            sigRemoveBtn: document.getElementById('removeSigBtn'),
            sigRemoveHidden: document.getElementById('removeSigHidden'),
            publishBtn: document.getElementById('btnPublish'),
            resetBtn: document.getElementById('btnReset'),
            toast: document.getElementById('ziToast'),
            lastFocused: null
        };

        this.state = {
            zoom: 0.75,
            width: 1123,
            height: 794
        };

        // NOTE: These defaults should ideally be passed via a configuration object from PHP if they need to be dynamic, 
        // but since they are hardcoded defaults, we rely on the translations injected into the PHP environment.
        // Assuming global helper function __() is available or using the hardcoded strings that match the translated PHP defaults:
        this.defaults = {
            title: 'Certificate of Completion',
            body: "This certifies that\n<strong>@{{full_name}}</strong>\n\nhas successfully completed the requirements for\n<strong>@{{exam_title}}</strong>",
            date: 'Date: @{{completed_at}}',
            sign: 'Director of Education'
        };

        this.init();
    }

    init() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(el => new bootstrap.Tooltip(el));
        }

        if(this.dom.publishBtn) {
            this.dom.publishBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.saveData();
            });
        }

        if(this.dom.resetBtn) {
            this.dom.resetBtn.addEventListener('click', () => this.confirmReset());
        }

        this.dom.inputs.forEach(el => {
            el.addEventListener('input', () => this.render());
            el.addEventListener('focus', (e) => { this.dom.lastFocused = e.target; });
        });

        this.dom.aligners.forEach(btn => {
            btn.addEventListener('click', (e) => this.setAlignment(e));
        });

        this.dom.segments.forEach(seg => {
            seg.addEventListener('click', (e) => this.handleSegment(e));
        });

        document.querySelectorAll('.zi-accordion-header').forEach(hdr => {
            hdr.addEventListener('click', () => {
                const targetId = hdr.dataset.target;
                const body = document.getElementById(targetId);
                const isOpen = body.classList.contains('show');
                
                document.querySelectorAll('.zi-accordion-body').forEach(b => b.classList.remove('show'));
                document.querySelectorAll('.zi-accordion-header').forEach(h => h.classList.remove('active'));
                
                if(!isOpen) {
                    body.classList.add('show');
                    hdr.classList.add('active');
                }
            });
        });

        document.querySelectorAll('[data-zoom-act]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const step = parseFloat(e.currentTarget.dataset.zoomAct);
                this.setZoom(this.state.zoom + step);
            });
        });

        if(this.dom.fontSelect) this.dom.fontSelect.addEventListener('change', () => this.handleFontChange());
        if(this.dom.bgInput) this.dom.bgInput.addEventListener('change', (e) => this.handleBgUpload(e));
        if(this.dom.bgRemoveBtn) this.dom.bgRemoveBtn.addEventListener('click', () => this.removeBackground());
        if(this.dom.sigImgInput) this.dom.sigImgInput.addEventListener('change', (e) => this.handleSigUpload(e));
        if(this.dom.sigRemoveBtn) this.dom.sigRemoveBtn.addEventListener('click', () => this.removeSignature());
        
        this.dom.sigTypeInputs.forEach(radio => {
            radio.addEventListener('change', () => this.renderSignature());
        });

        this.loadSavedState();
        this.setZoom(0.75);
        this.handleFontChange();
        this.render();
    }

    loadSavedState() {
        const savedBg = this.dom.paper.getAttribute('data-bg');
        if(savedBg && savedBg.length > 5 && savedBg !== 'null') {
            if(this.dom.bgRemoveBtn) this.dom.bgRemoveBtn.classList.remove('d-none');
        } else {
            if(this.dom.bgRemoveBtn) this.dom.bgRemoveBtn.classList.add('d-none');
        }

        const savedSig = document.getElementById('previewSignContainer').dataset.imgSrc;
        if(savedSig && savedSig.length > 5 && savedSig !== 'null') {
            const radioImg = document.querySelector('input[name="sig_type_ui"][value="image"]');
            if(radioImg) {
                radioImg.checked = true;
                const parent = radioImg.closest('.zi-segmented');
                parent.querySelectorAll('.zi-seg-opt').forEach(s => s.classList.remove('active'));
                radioImg.parentElement.classList.add('active');
                this.renderSignature();
            }
        }
    }

    saveData() {
        const btn = this.dom.publishBtn;
        const originalText = btn.innerHTML;
        this.saveState(); 

        const bgFile = this.dom.bgInput.files[0];
        if (bgFile && bgFile.size > 10 * 1024 * 1024) {
            this.showToast('Background image too large (Max 10MB)', true);
            return;
        }

        const formData = new FormData(this.dom.form);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Saving...';

        fetch(this.dom.form.action, {
            method: 'POST',
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken 
            }
        })
        .then(async response => {
            if(response.ok) {
                this.showToast('Certificate Saved Successfully!');
                window.location.hash = '#pane-branding-certificate';
                setTimeout(() => window.location.reload(), 500);
            } else {
                const resText = await response.text();
                let errorMsg = 'Server Error (500)';
                try {
                    const resJson = JSON.parse(resText);
                    if(resJson.message) errorMsg = resJson.message;
                    if(resJson.errors) {
                        const firstKey = Object.keys(resJson.errors)[0];
                        errorMsg = resJson.errors[firstKey][0];
                    }
                } catch(e) {}
                this.showToast(errorMsg, true);
            }
        })
        .catch(err => {
            this.showToast('Network Error', true);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    confirmReset() {
        if(typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Reset Design?',
                text: "This will revert your changes to the default template.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00b894',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.applyReset();
                }
            });
        } else {
            if(confirm('Reset certificate design to defaults?')) {
                this.applyReset();
            }
        }
    }

    applyReset() {
        document.querySelector('[data-model="previewTitle"]').value = this.defaults.title;
        document.querySelector('[data-model="previewBody"]').value = this.defaults.body;
        document.querySelector('[data-model="previewDate"]').value = this.defaults.date;
        document.querySelector('[data-model="previewSign"]').value = this.defaults.sign;
        
        this.removeBackground();
        this.removeSignature();
        
        const landscapeBtn = document.querySelector('.zi-seg-opt[data-val="landscape"]');
        if(landscapeBtn) landscapeBtn.click();

        this.render();
        this.showToast('Design Reset');
    }

    showToast(msg, isError = false) {
        this.dom.toast.innerText = msg;
        this.dom.toast.style.background = isError ? '#dc3545' : '#00b894';
        this.dom.toast.classList.add('show');
        setTimeout(() => this.dom.toast.classList.remove('show'), 3000);
    }

    render() {
        this.dom.inputs.forEach(input => {
            const target = document.getElementById(input.dataset.model);
            if(target) {
                target.innerHTML = input.value
                    .replace(/\n/g, '<br>')
                    .replace(/@{{(.*?)}}/g, '<span class="zi-var-pill">@{{$1}}</span>');
            }
        });
        this.renderSignature();
        this.saveState();
    }

    renderSignature() {
        const mode = document.querySelector('input[name="sig_type_ui"]:checked').value;
        const container = document.getElementById('previewSignContainer');
        const textVal = this.dom.sigTextInput.value;
        const removeBtn = this.dom.sigRemoveBtn;
        
        if (mode === 'text') {
            container.innerHTML = `<div class="cert-sig-line"></div><p class="fw-bold mb-0">${textVal}</p>`;
            document.getElementById('sigTextOptions').style.display = 'block';
            if(removeBtn) removeBtn.classList.add('d-none');
        } else {
            document.getElementById('sigTextOptions').style.display = 'none';
            const imgData = container.dataset.imgSrc;
            if(imgData && imgData.length > 5 && imgData !== 'null') {
                container.innerHTML = `<img src="${imgData}" class="cert-sig-img"><div class="cert-sig-line" style="border:0; border-top:1px solid #ccc; width:200px; margin-top:5px;"></div>`;
                if(removeBtn) removeBtn.classList.remove('d-none');
            } else {
                container.innerHTML = `<div class="cert-sig-line"></div><p class="text-muted small">[Signature Image]</p>`;
                if(removeBtn) removeBtn.classList.add('d-none');
            }
        }
    }

    setAlignment(e) {
        const btn = e.currentTarget;
        const group = btn.closest('.btn-group');
        const targetId = group.dataset.target;

        group.querySelectorAll('.btn').forEach(b => b.classList.remove('align-active', 'btn-secondary'));
        group.querySelectorAll('.btn').forEach(b => b.classList.add('btn-light'));
        
        btn.classList.add('align-active');
        btn.classList.remove('btn-light');

        const el = document.getElementById(targetId);
        if(el) {
            el.className = el.className.replace(/text-\w+/g, '');
            el.classList.add(`text-${btn.dataset.align}`);
        }
        this.saveState();
    }

    handleSegment(e) {
        const el = e.currentTarget;
        const parent = el.closest('.zi-segmented');
        parent.querySelectorAll('.zi-seg-opt').forEach(s => s.classList.remove('active'));
        el.classList.add('active');

        if(el.dataset.val === 'portrait') {
            this.state.width = 794; this.state.height = 1123;
        } else if (el.dataset.val === 'landscape') {
            this.state.width = 1123; this.state.height = 794;
        }

        if(parent.id === 'orientationSeg') {
            this.dom.orientationInput.value = el.dataset.val;
            this.resizePaper();
        }
    }

    handleFontChange() {
        this.dom.paper.className = `zi-paper ${this.dom.fontSelect.value}`;
    }

    handleBgUpload(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.dom.paper.style.backgroundImage = `url('${ev.target.result}')`;
                if(this.dom.bgRemoveBtn) this.dom.bgRemoveBtn.classList.remove('d-none');
                if(this.dom.bgRemoveHidden) this.dom.bgRemoveHidden.value = "0";
            };
            reader.readAsDataURL(file);
        }
    }

    removeBackground() {
        this.dom.paper.style.backgroundImage = 'none';
        this.dom.bgInput.value = ''; 
        if(this.dom.bgRemoveBtn) this.dom.bgRemoveBtn.classList.add('d-none');
        if(this.dom.bgRemoveHidden) this.dom.bgRemoveHidden.value = "1";
    }

    handleSigUpload(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                document.getElementById('previewSignContainer').dataset.imgSrc = ev.target.result;
                const radioImg = document.querySelector('input[name="sig_type_ui"][value="image"]');
                radioImg.checked = true;
                if(this.dom.sigRemoveHidden) this.dom.sigRemoveHidden.value = "0";
                const parent = radioImg.closest('.zi-segmented');
                parent.querySelectorAll('.zi-seg-opt').forEach(s => s.classList.remove('active'));
                radioImg.parentElement.classList.add('active');
                this.renderSignature();
            };
            reader.readAsDataURL(file);
        }
    }

    removeSignature() {
        const container = document.getElementById('previewSignContainer');
        container.removeAttribute('data-img-src');
        container.dataset.imgSrc = '';
        this.dom.sigImgInput.value = '';
        if(this.dom.sigRemoveHidden) this.dom.sigRemoveHidden.value = "1";
        const radioText = document.querySelector('input[name="sig_type_ui"][value="text"]');
        radioText.checked = true;
        const segParent = radioText.closest('.zi-segmented');
        segParent.querySelectorAll('.zi-seg-opt').forEach(s => s.classList.remove('active'));
        radioText.parentElement.classList.add('active');
        this.renderSignature();
    }

    resizePaper() {
        this.dom.paper.style.width = this.state.width + 'px';
        this.dom.paper.style.height = this.state.height + 'px';
        this.setZoom(this.state.zoom);
    }

    setZoom(val) {
        this.state.zoom = Math.max(0.4, Math.min(val, 1.5));
        this.dom.paper.style.transform = `scale(${this.state.zoom})`;
        const vMargin = (this.state.height * this.state.zoom) / 2;
        this.dom.paper.style.marginBottom = `${vMargin}px`;
        this.dom.paper.style.marginTop = '40px'; 
        this.dom.zoomDisplay.innerText = Math.round(this.state.zoom * 100) + '%';
    }

    insertVariable(tag) {
        const input = this.dom.lastFocused || document.querySelector('[data-model="previewBody"]');
        if(!input) return;
        const start = input.selectionStart;
        const end = input.selectionEnd;
        const text = input.value;
        input.value = text.substring(0, start) + tag + text.substring(end);
        input.focus();
        input.selectionStart = input.selectionEnd = start + tag.length;
        input.dispatchEvent(new Event('input'));
    }

    saveState() {
        const clone = this.dom.paper.cloneNode(true);
        clone.style.transform = '';
        clone.style.margin = '';
        this.dom.markupInput.value = clone.innerHTML;
    }
}

// FIX 1: Externalizing all event listeners from Blade (Rule 2 Compliance)
document.addEventListener('DOMContentLoaded', () => {
    window.ZiStudio = new CertificateStudio();

    const cancelBtn = document.getElementById('btnCancel');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            window.history.back();
        });
    }

    document.querySelectorAll('.insert-var-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const variable = btn.dataset.variable;
            // ZiStudio instance is guaranteed to be available here
            if (window.ZiStudio && typeof window.ZiStudio.insertVariable === 'function') {
                window.ZiStudio.insertVariable(variable);
            }
        });
    });
});