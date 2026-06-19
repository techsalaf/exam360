window.ExamLogic = {};

document.addEventListener('DOMContentLoaded', function() {
    
    const dataEl = document.getElementById('exam-data');
    let data = {};

    if (dataEl && dataEl.dataset.exam) {
        try {
            data = JSON.parse(dataEl.dataset.exam);
        } catch (e) {
            console.error("Failed to parse Exam Data:", e);
            showError("Initialization error: Invalid exam data format.");
            return;
        }
    }
    
    const questions = data.questions || [];
    const existingAnswers = data.existingAnswers || {};
    const config = {
        saveUrl: data.saveUrl,
        csrfToken: data.csrfToken,
        secondsRemaining: data.secondsRemaining,
        isCodingActive: data.isCodingActive || false
    };
    const strings = data.strings || {};

    if (questions.length === 0) {
        showError(strings.noQuestions || "No questions found. Please contact support.");
        return;
    }

    const totalQuestions = questions.length;
    let currentIndex = 0;
    let answeredCount = Object.keys(existingAnswers).length;
    let markedQuestions = new Set();
    let timeLeft = config.secondsRemaining;
    let isSubmitting = false;

    function forceSubmitExam(reason) {
        if (isSubmitting) return;
        isSubmitting = true;

        if (window.currentQuestion) {
            window.saveAnswer(window.currentQuestion);
        }

        Swal.fire({
            icon: 'error',
            title: 'Exam Terminated',
            text: reason + '. Your exam is being submitted automatically.',
            showConfirmButton: false,
            allowOutsideClick: false,
            timer: 2000
        }).then(() => {
            const form = document.getElementById('submit-exam-form');
            if (form) form.submit();
        });
    }

    function initSecurityRestrictions() {
        window.addEventListener('beforeunload', function (e) {
            if (isSubmitting) return;
            e.preventDefault();
            e.returnValue = 'Reloading will terminate your exam.';
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden && !isSubmitting) {
                forceSubmitExam('Tab switching or minimizing detected');
            }
        });

        window.addEventListener('blur', function() {
            if (!isSubmitting) {
                forceSubmitExam('Focus lost');
            }
        });

        document.addEventListener('contextmenu', event => event.preventDefault());
        
        document.onkeydown = function(e) {
            if (e.keyCode == 123) return false; 
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false;
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) return false;
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false;
            if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false;
            if (e.keyCode == 116 || (e.ctrlKey && e.keyCode == 82)) {
                forceSubmitExam('Page reload attempt');
                return false;
            }
        };
    }

    try {
        initSecurityRestrictions();
        renderQuestion(currentIndex);
        renderNavigator();
        updateStats();
        startTimer();
        bindEventHandlers();
    } catch (e) {
        console.error("Init Error:", e);
        showError(e.message);
    }

    function showError(msg) {
        const errEl = document.getElementById('js-error-display');
        const txtEl = document.getElementById('q-text');
        if(errEl) {
            if(txtEl) txtEl.style.display = 'none';
            errEl.style.display = 'block';
            errEl.textContent = msg;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: msg
        });
    }

    function renderQuestion(index) {
        if (!questions[index]) return;

        const q = questions[index];
        window.currentQuestion = q;
        const currentAnswerId = existingAnswers[q.id] ? String(existingAnswers[q.id]) : null;

        const qNumEl = document.getElementById('current-q-num');
        if(qNumEl) qNumEl.textContent = index + 1;
        
        let mediaHtml = '';
        if (q.media_type && q.media_type !== 'text') {
            const mediaUrl = q.media_url ? `/storage/${q.media_url}` : '';
            if (q.media_type === 'image' && mediaUrl) {
                mediaHtml = `<div class="question-media-box mb-3"><img src="${mediaUrl}" alt="Question Media" class="img-fluid rounded border"></div>`;
            } else if (q.media_type === 'video' && mediaUrl) {
                mediaHtml = `<div class="question-media-box mb-3"><video src="${mediaUrl}" controls class="img-fluid rounded border"></video></div>`;
            } else if (q.media_type === 'math' && q.math_content) {
                mediaHtml = `<div class="question-media-box math-display-area mb-3">$$${q.math_content}$$</div>`;
            }
        }

        const qTextEl = document.getElementById('q-text');
        if(qTextEl) qTextEl.innerHTML = mediaHtml + (q.question_text || (strings.questionMissingText || "Question missing text."));

        if (q.media_type === 'math' && window.MathJax) {
             window.MathJax.typesetPromise([qTextEl]);
        }
        
        const optionsContainer = document.getElementById('answer-options-list');
        const codingContainer = document.getElementById('coding-arena-container');
        const testCaseContainer = document.getElementById('exam-test-cases-container');

        if(optionsContainer) optionsContainer.innerHTML = '';
        if(codingContainer) codingContainer.style.display = 'none';
        if(testCaseContainer) { testCaseContainer.innerHTML = ''; testCaseContainer.classList.add('d-none'); }

        if (q.type === 'coding' && codingContainer && config.isCodingActive) {
            if(optionsContainer) optionsContainer.style.display = 'none';
            codingContainer.style.display = 'block';

            const codingConfig = q.coding_config || {};
            const langs = codingConfig.allowed_languages || ['javascript'];
            const testCases = codingConfig.test_cases || [];
            const langSelect = document.getElementById('language-select');
            
            if (langSelect) {
                langSelect.innerHTML = '';
                langs.forEach(l => {
                    const opt = document.createElement('option');
                    opt.value = l;
                    opt.textContent = l.toUpperCase();
                    langSelect.appendChild(opt);
                });
            }

            if (testCases.length > 0 && testCaseContainer) {
                testCaseContainer.classList.remove('d-none');
                let tcHtml = '<div class="tc-wrapper"><h6 class="tc-section-title">Example Test Cases</h6>';
                testCases.forEach((tc, idx) => {
                    let rawIn = tc.input || tc.input_text || '';
                    let rawOut = tc.output || tc.output_text || '';
                    const cleanIn = rawIn.toString().replace(/^(Input:?\s*)/i, '').trim();
                    const cleanOut = rawOut.toString().replace(/^(Expected Output:?\s*|Output:?\s*)/i, '').trim();
                    tcHtml += `
                        <div class="tc-item mb-3">
                            <div class="tc-case-badge">Case ${idx + 1}</div>
                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="tc-small-label">Input</div>
                                        <pre class="tc-code-block">${cleanIn || 'N/A'}</pre>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tc-small-label">Expected Output</div>
                                        <pre class="tc-code-block highlight">${cleanOut || 'N/A'}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
                tcHtml += '</div>';
                testCaseContainer.innerHTML = tcHtml;
            }

            if (window.codingEditor) {
                window.codingEditor.setValue(currentAnswerId || "// Write your code here...");
                
                let mode = (langs[0] || 'javascript');
                if(mode === 'javascript') mode = 'text/javascript';
                if(mode === 'python') mode = 'text/x-python';
                if(mode === 'php' || mode === 'c' || mode === 'cpp' || mode === 'java') mode = 'text/x-c++src';
                
                window.codingEditor.setOption("mode", mode);
                
                setTimeout(() => window.codingEditor.refresh(), 50);
                
                const terminal = document.getElementById('terminal');
                if(terminal) terminal.innerHTML = `<div class="terminal-msg text-success">> Environment ready for ${(langs[0] || 'code').toUpperCase()}</div>`;
            }
        } else {
            if(optionsContainer) {
                optionsContainer.style.display = 'block';
                let displayOptions = [];

                if (q.type === 'true_false') {
                    displayOptions = [{ id: 'True', option_text: 'True' }, { id: 'False', option_text: 'False' }];
                }
                else if (q.type === 'mcq') {
                    if (q.options && typeof q.options === 'object') {
                        if (Array.isArray(q.options)) {
                            displayOptions = q.options;
                        } else {
                            displayOptions = Object.keys(q.options).map(key => {
                                return { 
                                    id: key, 
                                    option_text: q.options[key],
                                    option_media_url: q.option_media ? q.option_media[key] : null
                                };
                            });
                        }
                    }
                }
                
                if (displayOptions.length > 0) {
                    displayOptions.forEach(opt => {
                        const isSelected = (currentAnswerId === String(opt.id)) ? 'selected' : '';
                        let labelContent = opt.id;
                        let mediaContent = '';
                        if (opt.id === 'True') labelContent = '<i class="fa-solid fa-check"></i>'; 
                        else if (opt.id === 'False') labelContent = '<i class="fa-solid fa-xmark"></i>'; 
                        else if (opt.option_media_url) mediaContent = `<img src="/storage/${opt.option_media_url}" class="option-image-preview">`;

                        const html = `
                            <div class="option-card ${isSelected}" onclick="window.ExamLogic.selectOption(${q.id}, '${opt.id}', this)">
                                <div class="d-flex align-items-center">
                                    <div class="option-label">${labelContent}</div>
                                    ${mediaContent}
                                    <div class="option-text">${opt.option_text}</div>
                                </div>
                            </div>
                        `;
                        optionsContainer.insertAdjacentHTML('beforeend', html);
                    });
                } else {
                    if (q.type === 'short_answer') {
                        const val = currentAnswerId || '';
                        optionsContainer.innerHTML = `
                            <div class="mt-2">
                                <input type="text" class="form-control" placeholder="Type your answer..." 
                                    value="${val}"
                                    onblur="window.ExamLogic.selectOption(${q.id}, this.value, this)" 
                                    style="padding: 15px; border-radius: 8px; border: 2px solid #e2e8f0; width: 100%;">
                            </div>
                        `;
                    } else {
                        optionsContainer.innerHTML = `<div class="p-3 text-muted">${strings.noOptions || "No options available."}</div>`;
                    }
                }
            }
        }

        const markBtn = document.getElementById('mark-review-toggle');
        if (markBtn) markBtn.classList.toggle('marked', markedQuestions.has(q.id));

        const prevBtn = document.getElementById('btn-prev');
        const nextBtn = document.getElementById('btn-next');
        const finishBtn = document.getElementById('btn-finish');

        if(prevBtn) prevBtn.disabled = (index === 0);
        if (index === totalQuestions - 1) {
            if(nextBtn) nextBtn.classList.add('d-none');
            if(finishBtn) finishBtn.classList.remove('d-none');
        } else {
            if(nextBtn) nextBtn.classList.remove('d-none');
            if(finishBtn) finishBtn.classList.add('d-none');
        }

        renderNavigator(); 
    }

    window.ExamLogic.selectOption = function(questionId, optionId, element) {
        if (element && element.classList.contains('option-card')) {
            document.querySelectorAll('.option-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }
        if (optionId === undefined || optionId === null) return;
        existingAnswers[questionId] = optionId;
        answeredCount = Object.keys(existingAnswers).filter(k => existingAnswers[k] && existingAnswers[k].toString().trim() !== '').length;
        updateStats();
        renderNavigator();
        
        const q = questions.find(item => item.id === questionId);
        window.saveAnswer(q);
    };

    window.saveAnswer = function(question) {
        if(!question) return;

        const statusEl = document.getElementById('save-status');
        if(statusEl) statusEl.textContent = strings.saving || 'Saving...';

        let payload = {
            question_id: question.id,
            _token: config.csrfToken,
            type: question.type
        };

        if (question.type === 'coding') {
            payload.assessment_id = question.assessment_id;
            payload.code = window.codingEditor ? window.codingEditor.getValue() : '';
            payload.language = document.getElementById('language-select')?.value || 'javascript';
            existingAnswers[question.id] = payload.code;
        } else {
            payload.option_id = existingAnswers[question.id] || '';
        }

        fetch(config.saveUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": config.csrfToken },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if(statusEl) statusEl.textContent = strings.saved || 'All answers automatically saved.';
            answeredCount = Object.keys(existingAnswers).filter(k => existingAnswers[k] && existingAnswers[k].toString().trim() !== '').length;
            updateStats();
        })
        .catch(err => {
            if(statusEl) statusEl.textContent = strings.saveError || 'Connection lost. Trying to reconnect...';
        });
    }

    function renderNavigator() {
        const grid = document.getElementById('nav-grid');
        if(!grid) return;
        grid.innerHTML = '';
        questions.forEach((q, idx) => {
            let statusClass = currentIndex === idx ? 'current' : (existingAnswers[q.id] ? 'answered' : 'unvisited');
            if (markedQuestions.has(q.id)) statusClass += ' marked-dot';
            const btn = document.createElement('button');
            btn.className = `nav-button ${statusClass}`;
            btn.textContent = idx + 1;
            btn.addEventListener('click', () => {
                window.saveAnswer(questions[currentIndex]);
                currentIndex = idx;
                renderQuestion(currentIndex);
            });
            grid.appendChild(btn);
        });
    }

    function updateStats() {
        const sAns = document.getElementById('stat-answered');
        const sBar = document.getElementById('progress-bar-fill');
        if(sAns) sAns.textContent = answeredCount;
        if(sBar) sBar.style.width = `${(answeredCount / totalQuestions) * 100}%`;
    }

    function startTimer() {
        const timerEl = document.getElementById('timer-text') || document.getElementById('exam-timer');
        if(!timerEl) return;
        const timerInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                isSubmitting = true;
                if(window.currentQuestion) window.saveAnswer(window.currentQuestion);
                Swal.fire({ icon: 'warning', title: strings.timeUpTitle || 'Time is up!', timer: 3000, showConfirmButton: false })
                .then(() => { const form = document.getElementById('submit-exam-form'); if (form) form.submit(); });
                return;
            }
            const m = Math.floor(timeLeft / 60);
            const s = Math.floor(timeLeft % 60);
            timerEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            if (timeLeft < 300) timerEl.classList.add('danger');
            timeLeft--;
        }, 1000);
    }

    function validateCurrentQuestion() {
        const currentQ = questions[currentIndex];
        const isAnswered = (existingAnswers.hasOwnProperty(currentQ.id) && String(existingAnswers[currentQ.id]).trim() !== '') || (currentQ.type === 'coding' && window.codingEditor && window.codingEditor.getValue().trim() !== '');
        if (!isAnswered && !markedQuestions.has(currentQ.id)) {
            Swal.fire({ icon: 'warning', title: strings.validationTitle || 'Action Required', text: strings.validationText || 'Please answer or mark for review.' });
            return false;
        }
        return true;
    }

    function confirmSubmission() {
        const title = markedQuestions.size > 0 ? (strings.reviewTitle || 'Review Marked') : (strings.finishTitle || 'Submit Exam');
        Swal.fire({ title: title, icon: 'question', showCancelButton: true, confirmButtonText: 'Submit' })
        .then((result) => { if (result.isConfirmed) { 
            isSubmitting = true; 
            if(window.currentQuestion) window.saveAnswer(window.currentQuestion);
            const form = document.getElementById('submit-exam-form'); 
            if (form) form.submit(); 
        }});
    }

    function bindEventHandlers() {
        const btnPrev = document.getElementById('btn-prev');
        if(btnPrev) {
            btnPrev.addEventListener('click', () => {
                window.saveAnswer(questions[currentIndex]);
                if (currentIndex > 0) { currentIndex--; renderQuestion(currentIndex); }
            });
        }

        const btnNext = document.getElementById('btn-next');
        if(btnNext) {
            btnNext.addEventListener('click', () => {
                window.saveAnswer(questions[currentIndex]);
                if (validateCurrentQuestion() && currentIndex < totalQuestions - 1) { currentIndex++; renderQuestion(currentIndex); }
            });
        }

        const btnMark = document.getElementById('mark-review-toggle');
        if(btnMark) {
            btnMark.addEventListener('click', function() {
                const q = questions[currentIndex];
                if (markedQuestions.has(q.id)) markedQuestions.delete(q.id); else markedQuestions.add(q.id);
                updateStats(); renderNavigator(); this.classList.toggle('marked', markedQuestions.has(q.id));
            });
        }

        const btnRun = document.getElementById('run-btn');
        if(btnRun) {
            btnRun.addEventListener('click', () => {
                const term = document.getElementById('terminal');
                if(!term) return;
                const code = window.codingEditor ? window.codingEditor.getValue() : '';
                term.innerHTML = 'Executing...';
                try {
                    let logs = [];
                    const oldLog = console.log;
                    console.log = (...a) => logs.push(a.map(x => typeof x === 'object' ? JSON.stringify(x) : x).join(' '));
                    eval(code);
                    console.log = oldLog;
                    term.innerHTML = logs.length ? logs.join('<br>') : 'Executed (No output)';
                } catch(e) { term.innerHTML = 'Error: ' + e.message; }
            });
        }

        const btnEnd = document.getElementById('btn-end-exam');
        if(btnEnd) {
            btnEnd.addEventListener('click', () => {
                window.saveAnswer(questions[currentIndex]);
                confirmSubmission();
            });
        }

        const btnFinish = document.getElementById('btn-finish');
        if(btnFinish) {
            btnFinish.addEventListener('click', () => {
                window.saveAnswer(questions[currentIndex]);
                confirmSubmission();
            });
        }

        const langSelect = document.getElementById('language-select');
        if(langSelect) {
            langSelect.addEventListener('change', function(e) {
                if (window.codingEditor) {
                    let mode = e.target.value;
                    if(mode === 'javascript') mode = 'text/javascript';
                    if(mode === 'python') mode = 'text/x-python';
                    if(mode === 'php' || mode === 'c' || mode === 'cpp' || mode === 'java') mode = 'text/x-c++src';
                    window.codingEditor.setOption("mode", mode);
                }
            });
        }
    }
});