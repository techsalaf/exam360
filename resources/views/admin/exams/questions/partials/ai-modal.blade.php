<x-modal 
    id="aiQuestionModal" 
    title="{{ __('questions.ai_modal_title') }}"
>
    <input type="hidden" id="exam_id_hidden" value="{{ $exam->id }}">
    
    <div class="text-center mb-4">
        <div class="ai-intro-icon mb-3 d-inline-flex align-items-center justify-content-center bg-dark text-warning rounded-circle">
            <i class="fa-solid fa-brain"></i>
        </div>
        <h5 class="fw-bold text-dark">{{ __('questions.ai_intro_title', ['exam' => $exam->title]) }}</h5>
        <p class="text-muted small mx-auto ai-intro-desc">
            {{ __('questions.ai_intro_desc') }}
        </p>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="ai_topic" class="form-label-premium mb-0">{{ __('questions.ai_label_topic') }} <span class="text-danger">*</span></label>
                <button type="button" id="btn_use_example_qs" class="btn btn-sm btn-light border text-muted py-0 px-2 fs-075">
                    <i class="fa-regular fa-lightbulb text-warning me-1"></i> {{ __('exams.btn_use_example') }}
                </button>
            </div>
            <input 
                id="ai_topic" 
                class="form-control-premium" 
                placeholder="{{ __('questions.ai_placeholder_topic') }}"
            >
        </div>
        <div class="col-md-4">
            <label for="ai_count" class="form-label-premium">{{ __('questions.ai_label_count') }} <span class="text-danger">*</span></label>
            <input type="number" id="ai_count" class="form-control-premium" value="5" min="1" max="50">
        </div>
        <div class="col-md-4">
            <label for="ai_difficulty" class="form-label-premium">{{ __('questions.ai_label_difficulty') }}</label>
            <select id="ai_difficulty" class="form-control-premium form-select">
                <option value="easy">{{ __('questions.diff_easy') }}</option>
                <option value="normal" selected>{{ __('questions.diff_normal') }}</option>
                <option value="hard">{{ __('questions.diff_hard') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="ai_type" class="form-label-premium">{{ __('questions.ai_label_type') }}</label>
            <select id="ai_type" class="form-control-premium form-select">
                <option value="mcq">{{ __('questions.type_mcq') }}</option>
                <option value="true_false">{{ __('questions.type_tf') }}</option>
            </select>
        </div>
    </div>
    
    <div class="mb-4">
        <label for="ai_provider_qs" class="form-label-premium">{{ __('exams.ai_label_provider') }}</label>
        <select id="ai_provider_qs" class="form-control-premium form-select">
            <option value="custom">{{ __('exams.ai_provider_custom') }}</option>
            <option value="gemini">{{ __('exams.ai_provider_gemini') }}</option>
            <option value="openai">{{ __('exams.ai_provider_openai') }}</option>
        </select>
        <div class="form-text small text-muted">
            <i class="fa-solid fa-circle-info me-1"></i> {{ __('questions.ai_provider_hint') }}
        </div>
    </div>

    <div id="aiQsConfigHint" class="mb-4 p-3 bg-warning-subtle border border-warning-subtle rounded-3 d-none">
        <div class="d-flex gap-3 align-items-start">
            <div class="bg-white p-2 rounded text-warning shadow-sm">
                <i class="fa-solid fa-bolt fs-6"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1 fs-09">{{ __('questions.ai_hint_title') }}</h6>
                <p class="mb-2 small text-muted lh-14">
                    {{ __('questions.ai_hint_desc') }}
                </p>
                <a href="{{ route('admin.settings.index') }}#ai-content" class="text-dark fw-bold small text-decoration-none">
                    {{ __('exams.ai_hint_btn') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div id="aiQuestionsContainer" class="border rounded-3 p-3 bg-light mb-4 preview-scroll-container">
        <h6 class="text-uppercase text-muted small fw-bold mb-2">{{ __('questions.preview_area') }}</h6>
        <div id="aiQuestionPreview" class="text-muted small">
            {{ __('questions.preview_placeholder') }}
        </div>
    </div>

    <div class="d-grid">
        <button type="button" id="btn_generate_questions" class="btn btn-dark btn-lg rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <span class="btn-text">{{ __('questions.ai_btn_generate') }}</span>
            <i class="fa-solid fa-brain btn-icon"></i>
        </button>
    </div>
    
    <div class="d-grid mt-2">
        <button type="button" id="btn_save_generated_questions" class="btn btn-success btn-lg fw-bold d-none shadow-sm">
            <i class="fa-solid fa-save me-2"></i> {{ __('questions.ai_btn_save') }}
        </button>
    </div>
</x-modal>