"use strict";

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Tooltips
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Handle Radial Progress Charts
    document.querySelectorAll('.radial-progress[data-progress]').forEach(function (el) {
        const value = el.dataset.progress;
        if (value !== undefined) {
            el.style.setProperty('--progress', value + '%');
        }
    });

    // Certificate Issue Logic
    const issueBtn = document.querySelector('.btn-issue-action');
    if (issueBtn) {
        issueBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: this.dataset.title,
                text: this.dataset.text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                confirmButtonText: this.dataset.confirm
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('issueCertForm').submit();
                }
            });
        });
    }

    // AI Analysis Logic
    const analyzeBtn = document.getElementById('btnAnalyzeAI');
    if(analyzeBtn) {
        const modalEl = document.getElementById('aiAnalysisModal');
        const modal = new bootstrap.Modal(modalEl);
        
        const ui = {
            loading: document.getElementById('aiLoadingState'),
            content: document.getElementById('aiContentState'),
            summary: document.getElementById('aiSummaryText'),
            strengths: document.getElementById('aiStrengthsList'),
            weaknesses: document.getElementById('aiWeaknessesList'),
            recommendation: document.getElementById('aiRecommendationText'),
            source: document.getElementById('aiSourceLabel'),
            hint: document.getElementById('aiConfigHint')
        };

        let hasAnalyzed = false;

        analyzeBtn.addEventListener('click', function() {
            modal.show();
            
            if (hasAnalyzed) {
                ui.loading.classList.add('d-none');
                ui.content.classList.remove('d-none');
                return;
            }

            ui.loading.classList.remove('d-none');
            ui.content.classList.add('d-none');
            ui.hint.classList.add('d-none');

            const url = this.dataset.url;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    ui.summary.textContent = res.data.summary;
                    ui.recommendation.textContent = res.data.recommendation;
                    ui.source.textContent = 'Analysis provided by ' + res.source;
                    
                    ui.strengths.innerHTML = '';
                    res.data.strengths.forEach(item => {
                        ui.strengths.innerHTML += `<li><i class="fa-solid fa-check text-success me-2"></i> ${item}</li>`;
                    });

                    ui.weaknesses.innerHTML = '';
                    res.data.weaknesses.forEach(item => {
                        ui.weaknesses.innerHTML += `<li><i class="fa-solid fa-xmark text-danger me-2"></i> ${item}</li>`;
                    });

                    if (res.source.includes('Local')) {
                        ui.hint.classList.remove('d-none');
                    } else {
                        ui.hint.classList.add('d-none');
                    }

                    ui.loading.classList.add('d-none');
                    ui.content.classList.remove('d-none');
                    hasAnalyzed = true;
                } else {
                    throw new Error(res.message || 'Unknown error');
                }
            })
            .catch(error => {
                ui.loading.innerHTML = `
                    <div class="text-danger py-4">
                        <i class="fa-solid fa-circle-exclamation fs-1 mb-3"></i>
                        <h6>Analysis Failed</h6>
                        <p class="small text-muted">${error.message}</p>
                        <button class="btn btn-sm btn-outline-danger mt-2" onclick="location.reload()">Reload Page</button>
                    </div>
                `;
            });
        });
    }
});