"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const scriptTag = document.querySelector('script[src*="admin-questions.js"]');
    const config = {
        storeUrl: scriptTag ? scriptTag.dataset.storeUrl : null,
        generateUrl: scriptTag ? scriptTag.dataset.generateUrl : null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content
    };

    const examId = document.getElementById('exam_id_hidden')?.value;
    
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    function notify(type, message) {
        if (typeof ZiNotify !== 'undefined') {
            type === 'success' ? ZiNotify.success(message) : ZiNotify.error(message);
        } else {
            alert(message);
        }
    }

    function updateQuestionTypeUI(prefix, type) {
        const containerMcq = document.getElementById(`${prefix}_container_options`);
        const containerCoding = document.getElementById(`${prefix}_container_coding`);
        const containerCorrect = document.getElementById(`${prefix}_container_correct_answer`);
        
        const inputMcq = document.getElementById(`${prefix}_input_correct_mcq`);
        const inputTf = document.getElementById(`${prefix}_input_correct_tf`);
        const inputSa = document.getElementById(`${prefix}_input_correct_sa`);

        if (inputMcq) { inputMcq.classList.add('d-none'); inputMcq.disabled = true; }
        if (inputTf) { inputTf.classList.add('d-none'); inputTf.disabled = true; }
        if (inputSa) { inputSa.classList.add('d-none'); inputSa.disabled = true; }

        if (containerMcq) containerMcq.style.display = 'none';
        if (containerCoding) containerCoding.style.display = 'none';
        if (containerCorrect) containerCorrect.style.display = 'block';

        if (type === 'mcq') {
            if (containerMcq) containerMcq.style.display = 'block';
            if (inputMcq) { inputMcq.classList.remove('d-none'); inputMcq.disabled = false; }
        } 
        else if (type === 'true_false') {
            if (inputTf) { inputTf.classList.remove('d-none'); inputTf.disabled = false; }
        } 
        else if (type === 'short_answer') {
            if (inputSa) { inputSa.classList.remove('d-none'); inputSa.disabled = false; }
        }
        else if (type === 'coding') {
            if (containerCoding) containerCoding.style.display = 'block';
            if (containerCorrect) containerCorrect.style.display = 'none';
        }
    }

    function handleCodingImport(prefix) {
        const importSelect = document.getElementById(`${prefix}_import_coding_assessment`);
        if (!importSelect) return;

        importSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (!opt.value) return;

            try {
                const data = JSON.parse(opt.dataset.full);
                const textInp = document.getElementById(`${prefix}_text`);
                if (textInp) textInp.value = data.description || '';
                
                const langs = data.allowed_languages || [];
                document.querySelectorAll(`#${prefix}_container_coding input[name="allowed_languages[]"]`).forEach(chk => {
                    chk.checked = langs.includes(chk.value);
                });

                const containerId = `${prefix}-cases-container`;
                const container = document.getElementById(containerId);
                if (container) {
                    container.innerHTML = '';
                    (data.test_cases || []).forEach(tc => {
                        addTestCase(containerId, tc);
                    });
                }
                notify('success', 'Data pulled successfully.');
            } catch (e) {
                console.error(e);
                notify('error', 'Failed to pull data.');
            }
        });
    }

    handleCodingImport('create');
    handleCodingImport('edit');

    function handleFormSubmit(e, modalId) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        const formData = new FormData(form);
        const isEdit = form.dataset.method === 'PUT';
        if (isEdit) { formData.append('_method', 'PUT'); }

        fetch(form.action, {
            method: 'POST', 
            headers: {
                'X-CSRF-TOKEN': config.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async res => {
            const contentType = res.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return await res.json();
            } else {
                throw new Error("Server Error - Invalid Response");
            }
        })
        .then(data => {
            if (data.status === 'success') {
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById(modalId));
                if (modalInstance) modalInstance.hide();
                notify('success', data.message);
                setTimeout(() => window.location.reload(), 500);
            } else if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const name = key.includes('.') ? `options[${key.split('.')[1]}]` : key;
                    const field = form.querySelector(`[name="${name}"]`);
                    if (field) field.classList.add('is-invalid');
                });
                notify('error', 'Please check the form for errors.');
            } else {
                notify('error', data.message || 'An error occurred.');
            }
        })
        .catch(err => {
            console.error(err);
            notify('error', 'An unexpected error occurred: ' + err.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    const createTypeSelect = document.getElementById('create_type');
    if (createTypeSelect) {
        createTypeSelect.addEventListener('change', function() {
            updateQuestionTypeUI('create', this.value);
        });
        updateQuestionTypeUI('create', createTypeSelect.value);
    }

    const createForm = document.querySelector('#createQuestionModal form');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            handleFormSubmit(e, 'createQuestionModal');
        });
    }

    const editModalEl = document.getElementById('editQuestionModal');
    if (editModalEl) {
        const editModal = new bootstrap.Modal(editModalEl);
        const editForm = editModalEl.querySelector('form');
        const editTypeSelect = document.getElementById('edit_type');

        if (editTypeSelect) {
            editTypeSelect.addEventListener('change', function() {
                updateQuestionTypeUI('edit', this.value);
            });
        }

        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit-question');
            if (!btn) return;

            const data = btn.dataset;
            let options = {};
            editForm.dataset.method = 'PUT';

            try { options = JSON.parse(data.options || '{}'); } catch (error) { options = {}; }

            const txtInp = document.getElementById('edit_text'); if (txtInp) txtInp.value = data.text || '';
            const expInp = document.getElementById('edit_explanation'); if (expInp) expInp.value = data.explanation || '';

            if (editTypeSelect) {
                editTypeSelect.value = data.type;
                updateQuestionTypeUI('edit', data.type);
            }

            if (data.type === 'coding') {
                const codingLangs = options.allowed_languages || [];
                editForm.querySelectorAll('input[name="allowed_languages[]"]').forEach(chk => {
                    chk.checked = codingLangs.includes(chk.value);
                });

                const container = document.getElementById('edit-cases-container');
                if (container) {
                    container.innerHTML = '';
                    (options.test_cases || []).forEach((tc, i) => {
                        addTestCase('edit-cases-container', tc);
                    });
                }
            } else {
                ['A', 'B', 'C', 'D'].forEach(opt => {
                    const el = document.getElementById(`edit_option_${opt}`);
                    if (el) el.value = options[opt] || '';
                });

                const mcqCorrect = document.getElementById('edit_input_correct_mcq');
                const tfCorrect = document.getElementById('edit_input_correct_tf');
                const saCorrect = document.getElementById('edit_input_correct_sa');

                if (data.type === 'mcq' && mcqCorrect) mcqCorrect.value = data.correct;
                else if (data.type === 'true_false' && tfCorrect) tfCorrect.value = data.correct;
                else if (data.type === 'short_answer' && saCorrect) saCorrect.value = data.correct;
            }

            const currentPath = window.location.pathname.replace(/\/$/, '');
            editForm.action = `${currentPath}/${data.id}`;
            editModal.show();
        });

        editForm.addEventListener('submit', function(e) {
            handleFormSubmit(e, 'editQuestionModal');
        });
    }

    const aiModalEl = document.getElementById('aiQuestionModal');
    if (aiModalEl) {
        const aiModal = new bootstrap.Modal(aiModalEl);
        const btnGenerate = document.getElementById('btn_generate_questions');
        const btnSave = document.getElementById('btn_save_generated_questions');
        const previewArea = document.getElementById('aiQuestionPreview');
        const topicInput = document.getElementById('ai_topic');
        const providerSelect = document.getElementById('ai_provider_qs');

        if (btnGenerate) {
            btnGenerate.addEventListener('click', function () {
                const topic = topicInput.value.trim();
                const count = document.getElementById('ai_count').value;
                const difficulty = document.getElementById('ai_difficulty').value;
                const type = document.getElementById('ai_type').value;
                const provider = providerSelect ? providerSelect.value : 'custom';

                if (!topic) { notify('error', 'Please enter a topic.'); return; }

                btnGenerate.disabled = true;
                const originalText = btnGenerate.innerHTML;
                btnGenerate.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating...';
                previewArea.innerHTML = '<div class="text-center py-3 text-muted">Contacting AI provider...</div>';
                btnSave.classList.add('d-none');

                fetch(config.generateUrl, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": config.csrfToken,
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({ exam_id: examId, topic, count, difficulty, type, provider })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.questions.length > 0) {
                        let html = '';
                        data.questions.forEach((q, i) => {
                            let optionsHtml = '';
                            if (q.options) {
                                optionsHtml = '<div class="mt-2">';
                                for (const [key, val] of Object.entries(q.options)) {
                                    const isCorrect = key === q.correct_answer;
                                    optionsHtml += `<span class="badge ${isCorrect ? 'bg-success' : 'bg-secondary'} me-1 mb-1">${key}: ${val}</span>`;
                                }
                                optionsHtml += '</div>';
                            }
                            html += `<div class="card mb-2 border-light shadow-sm"><div class="card-body p-2"><h6 class="card-title text-dark fw-bold mb-1 small">#${i+1} ${q.question_text}</h6>${optionsHtml}</div></div>`;
                        });
                        previewArea.innerHTML = html;
                        btnSave.dataset.questions = JSON.stringify(data.questions);
                        btnSave.classList.remove('d-none');
                        notify('success', 'Questions generated successfully.');
                    } else {
                        previewArea.innerHTML = '<div class="text-center text-danger py-3">AI failed.</div>';
                        notify('error', data.message || 'Generation failed.');
                    }
                })
                .catch(err => { console.error(err); previewArea.innerHTML = '<div class="text-center text-danger py-3">System error.</div>'; })
                .finally(() => { btnGenerate.disabled = false; btnGenerate.innerHTML = originalText; });
            });
        }

        if (btnSave) {
            btnSave.addEventListener('click', function () {
                const questions = JSON.parse(this.dataset.questions || '[]');
                if (!questions.length) return;
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';
                fetch(config.storeUrl, {
                    method: 'POST',
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": config.csrfToken, "X-Requested-With": "XMLHttpRequest" },
                    body: JSON.stringify({ exam_id: examId, questions: questions })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') { aiModal.hide(); notify('success', data.message); setTimeout(() => window.location.reload(), 500); }
                    else { notify('error', data.message); this.disabled = false; this.innerHTML = 'Save Questions'; }
                })
                .catch(err => { console.error(err); notify('error', 'Save failed.'); this.disabled = false; this.innerHTML = 'Save Questions'; });
            });
        }
    }
});