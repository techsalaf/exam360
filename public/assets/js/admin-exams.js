"use strict";

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : null;
}

window.fillExamplePrompt = function() {
    const el = document.getElementById('aiLocalizationData');
    if (!el) return;
    
    const examples = JSON.parse(el.textContent);
    const randomExample = examples[Math.floor(Math.random() * examples.length)];
    
    const textarea = document.getElementById('ai_prompt');
    if (textarea) {
        textarea.value = randomExample;
        textarea.classList.add('bg-light');
        setTimeout(() => textarea.classList.remove('bg-light'), 300);
    }
};

window.toggleConfigHint = function() {
    const providerSelect = document.getElementById('ai_provider');
    const hintBox = document.getElementById('aiConfigHint');
    
    if (providerSelect && hintBox) {
        if (providerSelect.value === 'custom') {
            hintBox.classList.remove('d-none');
            if (typeof $ !== 'undefined') $(hintBox).slideDown();
        } else {
            hintBox.classList.add('d-none');
            if (typeof $ !== 'undefined') $(hintBox).slideUp();
        }
    }
};

document.addEventListener("DOMContentLoaded", function() {
    
    window.toggleConfigHint();

    // ==========================================
    // MODAL TRIGGERS (DESKTOP & MOBILE)
    // ==========================================

    // Desktop AI Button
    const btnAi = document.getElementById('triggerAiModal');
    if (btnAi) {
        btnAi.addEventListener('click', function() {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('aiExamModal'));
            modal.show();
        });
    }

    // Desktop Create Button
    const btnCreate = document.getElementById('triggerCreateModal');
    if (btnCreate) {
        btnCreate.addEventListener('click', function() {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createExamModal'));
            modal.show();
        });
    }

    // Mobile AI Button (Inside Offcanvas)
    const btnAiMobile = document.getElementById('triggerAiModalMobile');
    if (btnAiMobile) {
        btnAiMobile.addEventListener('click', function() {
            // Close the offcanvas filter first
            const offcanvasEl = document.getElementById('examFilterOffcanvas');
            if(offcanvasEl) {
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if(offcanvas) offcanvas.hide();
            }
            
            // Open Modal
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('aiExamModal'));
            modal.show();
        });
    }

    // Mobile Create Button (Inside Offcanvas)
    const btnCreateMobile = document.getElementById('triggerCreateModalMobile');
    if (btnCreateMobile) {
        btnCreateMobile.addEventListener('click', function() {
            // Close the offcanvas filter first
            const offcanvasEl = document.getElementById('examFilterOffcanvas');
            if(offcanvasEl) {
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if(offcanvas) offcanvas.hide();
            }

            // Open Modal
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createExamModal'));
            modal.show();
        });
    }

    // ==========================================
    // HELPERS & FORM LOGIC
    // ==========================================

    // Helper: Toggle Negative Marking Inputs
    function bindNegativeMarking(switchId, inputId) {
        const toggle = document.getElementById(switchId);
        const input = document.getElementById(inputId);
        
        if(toggle && input) {
            const container = input.closest('.negative-value-container');
            const handler = () => {
                if(toggle.checked) {
                    input.removeAttribute('disabled');
                    input.setAttribute('required', 'required');
                    if(container) container.classList.remove('opacity-50');
                } else {
                    input.setAttribute('disabled', 'disabled');
                    input.removeAttribute('required');
                    if(container) container.classList.add('opacity-50');
                }
            };
            toggle.addEventListener('change', handler);
            handler(); 
        }
    }

    bindNegativeMarking('create_has_negative_marking', 'create_negative_mark_value');
    bindNegativeMarking('edit_has_negative_marking', 'edit_negative_mark_value');

    // Helper: Update Plan Info Box
    function updatePlanInfo(selectElementId, infoBoxId) {
        const select = document.getElementById(selectElementId);
        const infoBox = document.getElementById(infoBoxId);
        
        if(!select || !infoBox) return;

        const handlePlanChange = () => {
            const selectedOption = select.options[select.selectedIndex];
            
            if (select.value && select.value !== "") {
                const name = selectedOption.text;
                const monthly = selectedOption.getAttribute('data-monthly');
                const yearly = selectedOption.getAttribute('data-yearly');
                const currency = selectedOption.getAttribute('data-currency') || '$';

                const nameEl = infoBox.querySelector('.plan-name');
                const priceEl = infoBox.querySelector('.plan-price');
                if(nameEl) nameEl.textContent = name;
                if(priceEl) priceEl.textContent = `${currency}${monthly}/mo or ${currency}${yearly}/yr`;
                
                infoBox.classList.remove('d-none');
            } else {
                infoBox.classList.add('d-none');
            }
        };

        select.addEventListener('change', handlePlanChange);
        handlePlanChange(); 
    }

    // Pricing Toggles
    const createPriceContainer = document.getElementById('create_price_container');
    const createRadios = document.querySelectorAll('.create-pricing-toggle');
    if(createRadios && createPriceContainer) {
        createRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'paid') {
                    createPriceContainer.classList.remove('d-none');
                } else {
                    createPriceContainer.classList.add('d-none');
                    const input = createPriceContainer.querySelector('input');
                    if(input) input.value = '';
                }
            });
        });
    }

    const editPriceContainer = document.getElementById('edit_price_container');
    const editRadios = document.querySelectorAll('.edit-pricing-toggle');
    if(editRadios && editPriceContainer) {
        editRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'paid') {
                    editPriceContainer.classList.remove('d-none');
                } else {
                    editPriceContainer.classList.add('d-none');
                    const input = editPriceContainer.querySelector('input');
                    if(input) input.value = '';
                }
            });
        });
    }

    updatePlanInfo('create_plan', 'create_plan_info');
    updatePlanInfo('edit_plan', 'edit_plan_info');

    // Helper: Response Parser
    const parseJsonResponse = async (response) => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Server Error:', text);
            throw new Error('Server returned an invalid response. Check console for details.');
        }
    };

    // Helper: Sanitize Form Data
    const sanitizeFormData = (formData) => {
        // Handle Pricing: Force '0' if free
        const pricingType = formData.get('pricing_type');
        if (pricingType === 'free') {
            formData.set('price', '0'); 
        } else {
            if (formData.has('price') && formData.get('price') === "") {
                formData.delete('price');
            }
        }

        // Clean optional fields
        const optionalFields = [
            'plan_id', 'start_date', 'end_date', 
            'result_date', 'negative_mark_value', 'total_marks'
        ];
        
        optionalFields.forEach(field => {
            if (formData.has(field) && formData.get(field) === "") {
                formData.delete(field);
            }
        });

        return formData;
    };

    // ==========================================
    // AI GENERATION LOGIC
    // ==========================================
    const btnGenerate = document.getElementById('btn_generate_exam');
    const promptInput = document.getElementById('ai_prompt');
    const providerSelect = document.getElementById('ai_provider');
    const aiModalEl = document.getElementById('aiExamModal');
    const createModalEl = document.getElementById('createExamModal');
    
    // Matched to HTML IDs
    const inpTitle = document.getElementById('create_title');
    const inpDuration = document.getElementById('create_duration');
    const inpPass = document.getElementById('create_pass_percentage');
    const inpMarks = document.getElementById('create_total_marks');
    const inpDesc = document.getElementById('create_description');

    if (btnGenerate && aiModalEl && createModalEl) {
        const aiModal = new bootstrap.Modal(aiModalEl);
        const createModal = new bootstrap.Modal(createModalEl);

        btnGenerate.addEventListener('click', function() {
            const token = getCsrfToken();
            if(!token) return ZiNotify.error('CSRF Token missing.');

            const prompt = promptInput.value.trim();
            const provider = providerSelect.value;
            
            const spinner = this.querySelector('.spinner-border');
            const btnText = this.querySelector('.btn-text');
            const btnIcon = this.querySelector('.btn-icon');

            this.disabled = true;
            if(spinner) spinner.classList.remove('d-none');
            if(btnText) btnText.textContent = "Generating..."; 
            if(btnIcon) btnIcon.classList.add('d-none');
            
            fetch("/admin/exams/generate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ prompt, provider })
            })
            .then(parseJsonResponse)
            .then(res => {
                if (res.status === 'success' || (res.status === 'error' && res.data)) {
                    const data = res.data;
                    aiModal.hide();
                    createModal.show();

                    if(data.title && inpTitle) inpTitle.value = data.title;
                    if(data.duration && inpDuration) inpDuration.value = data.duration;
                    if(data.pass_percentage && inpPass) inpPass.value = data.pass_percentage;
                    if(data.total_marks && inpMarks) inpMarks.value = data.total_marks;
                    if(data.description && inpDesc) inpDesc.value = data.description;

                    if(inpTitle) {
                        inpTitle.classList.add('bg-success-subtle');
                        setTimeout(() => inpTitle.classList.remove('bg-success-subtle'), 1000);
                    }

                    if(res.status === 'error') ZiNotify.warning(res.message); 
                    else ZiNotify.success('Exam details generated successfully.');
                } else {
                    ZiNotify.error(res.message || 'Generation failed.');
                }
            })
            .catch(error => {
                console.error(error);
                ZiNotify.error(error.message || 'AI Generation failed. Please try again.');
            })
            .finally(() => {
                this.disabled = false;
                if(spinner) spinner.classList.add('d-none');
                if(btnText) btnText.textContent = 'Generate Exam Details';
                if(btnIcon) btnIcon.classList.remove('d-none');
            });
        });
    }

    // ==========================================
    // EDIT MODAL LOGIC
    // ==========================================
    const editModalEl = document.getElementById('editExamModal');
    
    if(editModalEl) {
        const editModal = new bootstrap.Modal(editModalEl);
        const editForm = document.getElementById('editExamModalForm') || editModalEl.querySelector('form');
        
        if(editForm && !editForm.getAttribute('enctype')) {
            editForm.setAttribute('enctype', 'multipart/form-data');
        }

        const titleInput = document.getElementById('edit_title');
        const catInput = document.getElementById('edit_category');
        const durationInput = document.getElementById('edit_duration');
        const passInput = document.getElementById('edit_pass_percentage');
        const marksInput = document.getElementById('edit_total_marks');
        
        const priceInput = document.getElementById('edit_price');
        const planInput = document.getElementById('edit_plan');
        const freeRadio = document.getElementById('edit_price_free');
        const paidRadio = document.getElementById('edit_price_paid');
        
        const startInput = document.getElementById('edit_start_date');
        const endInput = document.getElementById('edit_end_date');
        const resultInput = document.getElementById('edit_result_date');

        const bannerPreview = document.getElementById('edit_banner_preview');
        const bannerImg = bannerPreview ? bannerPreview.querySelector('img') : null;

        const negToggle = document.getElementById('edit_has_negative_marking');
        const negInput = document.getElementById('edit_negative_mark_value');

        const formatDateTime = (dateStr) => {
            if (!dateStr || dateStr === 'null' || dateStr.trim() === '') return '';
            return dateStr.replace(' ', 'T').substring(0, 16);
        };

        document.querySelectorAll('.btn-edit-exam').forEach(btn => {
            btn.addEventListener('click', function() {
                const data = this.dataset;

                if(editForm) editForm.action = data.action;

                if(titleInput) titleInput.value = data.title;
                if(catInput) catInput.value = data.category;
                if(durationInput) durationInput.value = data.duration;
                if(passInput) passInput.value = data.pass;
                if(marksInput) marksInput.value = data.marks || '';
                
                const isPaid = data.paid === '1';
                if(isPaid) {
                    if(paidRadio) paidRadio.checked = true;
                    if(editPriceContainer) editPriceContainer.classList.remove('d-none');
                    if(priceInput) priceInput.value = data.price;
                } else {
                    if(freeRadio) freeRadio.checked = true;
                    if(editPriceContainer) editPriceContainer.classList.add('d-none');
                    if(priceInput) priceInput.value = '';
                }

                if(planInput) {
                    planInput.value = data.plan || '';
                    planInput.dispatchEvent(new Event('change'));
                }

                if(startInput) startInput.value = formatDateTime(data.start);
                if(endInput) endInput.value = formatDateTime(data.end);
                if(resultInput) resultInput.value = formatDateTime(data.result);

                if(bannerPreview && bannerImg) {
                    if(data.banner && data.banner.trim() !== '') {
                        bannerImg.src = data.banner;
                        bannerPreview.classList.remove('d-none');
                    } else {
                        bannerPreview.classList.add('d-none');
                        bannerImg.src = '';
                    }
                }

                if(negToggle && negInput) {
                    const hasNegative = data.hasNegativeMarking === '1';
                    negToggle.checked = hasNegative;
                    negInput.value = data.negativeMarkValue || '';
                    negToggle.dispatchEvent(new Event('change'));
                }

                editModal.show();
            });
        });

        editForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const token = getCsrfToken();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            let formData = new FormData(this);
            formData = sanitizeFormData(formData);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.status === 'success') {
                    ZiNotify.success(data.message || 'Exam updated successfully.');
                    editModal.hide();
                    window.location.reload();
                } else if (data.status === 'error') {
                    let errorMsg = 'Validation Failed: <br>';
                    if (data.errors) {
                        for (const [key, value] of Object.entries(data.errors)) {
                            errorMsg += value[0] + '<br>';
                            const input = editForm.querySelector(`[name="${key}"]`);
                            if(input) input.classList.add('is-invalid');
                        }
                    } else {
                        errorMsg += data.message || 'Unknown error occurred.';
                    }
                    ZiNotify.error(errorMsg);
                }
            })
            .catch(error => {
                console.error(error);
                ZiNotify.error(error.message || 'Something went wrong. Please check console.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // ==========================================
    // CREATE MODAL SUBMIT
    // ==========================================
    const createModalNode = document.getElementById('createExamModal');
    const createForm = createModalNode ? createModalNode.querySelector('form') : null;
    
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const token = getCsrfToken();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            let formData = new FormData(this);
            formData = sanitizeFormData(formData);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.status === 'success') {
                    const createModalInstance = bootstrap.Modal.getInstance(createModalNode);
                    createModalInstance.hide();
                    createForm.reset();
                    
                    const createNegToggle = document.getElementById('create_has_negative_marking');
                    const createNegInput = document.getElementById('create_negative_mark_value');
                    
                    if(createNegToggle) {
                        createNegToggle.checked = false;
                        createNegToggle.dispatchEvent(new Event('change'));
                    }
                    if(createNegInput) createNegInput.value = '';

                    if(document.getElementById('create_price_free')) document.getElementById('create_price_free').checked = true;
                    if(createPriceContainer) createPriceContainer.classList.add('d-none');
                    if(document.getElementById('create_plan_info')) document.getElementById('create_plan_info').classList.add('d-none');

                    const successModalEl = document.getElementById('examSuccessModal');
                    if(successModalEl) {
                        document.getElementById('success_exam_id').textContent = '#' + data.exam_id;
                        document.getElementById('success_exam_title').textContent = data.exam_title;
                        document.getElementById('btn_go_questions').href = data.redirect_url;
                        
                        const successModal = new bootstrap.Modal(successModalEl);
                        successModal.show();
                        
                        successModalEl.addEventListener('hidden.bs.modal', function () {
                            window.location.reload();
                        });
                    } else {
                        ZiNotify.success('Exam created successfully');
                        setTimeout(() => window.location.reload(), 1000);
                    }

                } else if (data.status === 'error') {
                    let errorMsg = 'Validation Failed: <br>';
                    if (data.errors) {
                        for (const [key, value] of Object.entries(data.errors)) {
                            errorMsg += value[0] + '<br>';
                            const input = createForm.querySelector(`[name="${key}"]`);
                            if(input) input.classList.add('is-invalid');
                        }
                    } else {
                        errorMsg += data.message || 'Unknown error occurred.';
                    }
                    ZiNotify.error(errorMsg);
                }
            })
            .catch(error => {
                console.error(error);
                ZiNotify.error(error.message || 'Something went wrong. Please check console.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // ==========================================
    // BULK ACTIONS & OTHER HELPERS
    // ==========================================
    const selectAll = document.getElementById('selectAll');
    const bulkItems = document.querySelectorAll('.bulk-item');
    const floatingBar = document.getElementById('floatingBulkBar');
    const selectedCount = document.getElementById('selectedCount');

    function toggleBar() {
        const count = document.querySelectorAll('.bulk-item:checked').length;
        if(floatingBar && selectedCount) {
            if(count > 0) {
                floatingBar.classList.add('show');
                selectedCount.innerText = count;
            } else {
                floatingBar.classList.remove('show');
            }
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            bulkItems.forEach(item => item.checked = this.checked);
            toggleBar();
        });
    }
    bulkItems.forEach(item => item.addEventListener('change', toggleBar));

    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const isActive = this.getAttribute('data-active') === '1'; 
            
            Swal.fire({
                title: isActive ? 'Disable Exam?' : 'Enable Exam?',
                text: isActive ? 'Hidden from students.' : 'Visible to students.',
                icon: isActive ? 'warning' : 'info',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#ef4444' : '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: isActive ? 'Yes, Disable' : 'Yes, Enable'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });

    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form') || document.getElementById(this.getAttribute('form'));
            
            Swal.fire({
                title: this.dataset.title || 'Delete Exam?',
                text: this.dataset.text || "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete'
            }).then((result) => { if (result.isConfirmed && form) form.submit(); });
        });
    });

});