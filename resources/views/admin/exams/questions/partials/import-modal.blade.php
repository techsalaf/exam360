<div class="modal fade" id="importQuestionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <div class="bg-success-subtle text-success p-2 rounded-3">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                    {{ __('questions.import_modal_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formBulkImport" action="{{ route('admin.exams.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm mb-4 py-3" style="background-color: #e3f2fd; color: #0d47a1;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2 fw-bold">
                                <i class="fa-solid fa-circle-info"></i>
                                {{ __('questions.import_instructions_title') }}
                            </div>
                            <a href="{{ route('admin.exams.questions.download_template', $exam->id) }}" class="btn btn-white btn-sm border shadow-sm rounded-pill px-3">
                                <i class="fa-solid fa-download me-1 text-primary"></i> {{ __('questions.btn_download_template') }}
                            </a>
                        </div>
                        <div class="small">
                            <p class="mb-1"><strong>{{ __('questions.import_instructions_columns') }}:</strong> <code>question_text, type, correct_answer, options, explanation</code></p>
                            <p class="mb-1"><strong>{{ __('questions.import_instructions_types') }}:</strong> <code>mcq, true_false, short_answer</code></p>
                            <p class="mb-0"><strong>{{ __('questions.import_instructions_options') }}:</strong> {{ __('questions.import_instructions_options_desc') }}</p>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark">{{ __('questions.import_file_label') }}</label>
                        <div class="input-group">
                            <input type="file" name="file" class="form-control rounded-3" accept=".csv" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" id="btnSubmitImport">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> {{ __('questions.btn_import_now') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('formBulkImport').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitImport');
    const originalBtnHtml = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> {{ __("questions.btn_importing") }}';

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json' 
        }
    })
    .then(async res => {
        let data = null;
        try {
            data = await res.json();
        } catch (err) {
            throw new Error("Server Error");
        }
        
        if (res.ok && data && data.status === 'success') {
            location.reload();
        } else {
            const errorMsg = data && data.message ? data.message : '{{ __("questions.import_failed") }}';
            alert(errorMsg);
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }
    })
    .catch(err => {
        alert(err.message || 'An unexpected error occurred during import.');
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;
    });
});
</script>