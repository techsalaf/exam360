<x-modal 
    id="aiExamModal" 
    title="{{ __('exams.ai_modal_title') }}"
>
    <!-- Hidden Data for JS -->
    <script type="application/json" id="aiLocalizationData">
        @json([
            __('exams.ai_example_1'),
            __('exams.ai_example_2'),
            __('exams.ai_example_3')
        ])
    </script>

    <div class="text-center mb-4">
        <div class="mb-3 d-inline-flex align-items-center justify-content-center bg-dark text-warning rounded-circle ai-icon-circle">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <h5 class="fw-bold text-dark">{{ __('exams.ai_intro_title') }}</h5>
        <p class="text-muted small mx-auto ai-desc-text">
            {{ __('exams.ai_intro_desc') }}
        </p>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label for="ai_prompt" class="form-label-premium mb-0">{{ __('exams.ai_label_prompt') }}</label>
            <button type="button" class="btn btn-sm btn-light border text-muted py-0 px-2 ai-btn-sm-text" onclick="fillExamplePrompt()">
                <i class="fa-regular fa-lightbulb text-warning me-1"></i> {{ __('exams.btn_use_example') }}
            </button>
        </div>

        <textarea 
            id="ai_prompt" 
            class="form-control-premium" 
            rows="4" 
            placeholder="{{ __('exams.ai_placeholder_prompt') }}"
        ></textarea>
    </div>

    <div class="mb-4">
        <label for="ai_provider" class="form-label-premium">{{ __('exams.ai_label_provider') }}</label>
        <select id="ai_provider" class="form-control-premium form-select" onchange="toggleConfigHint()">
            <option value="custom">{{ __('exams.ai_provider_custom') }}</option>
            <option value="gemini">{{ __('exams.ai_provider_gemini') }}</option>
            <option value="openai">{{ __('exams.ai_provider_openai') }}</option>
        </select>
        <div class="form-text small text-muted">
            <i class="fa-solid fa-circle-info me-1"></i> {{ __('exams.ai_provider_hint') }}
        </div>
    </div>
    
    <div id="aiConfigHint" class="mb-4 p-3 bg-warning-subtle border border-warning-subtle rounded-3 d-none">
        <div class="d-flex gap-3 align-items-start">
            <div class="bg-white p-2 rounded text-warning shadow-sm">
                <i class="fa-solid fa-bolt fs-6"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1 ai-hint-title">{{ __('exams.ai_hint_title') }}</h6>
                <p class="mb-2 small text-muted ai-hint-desc">
                    {{ __('exams.ai_hint_desc') }}
                </p>
                <a href="{{ route('admin.settings.index') }}#ai-content" class="text-dark fw-bold small text-decoration-none">
                    {{ __('exams.ai_hint_btn') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="d-grid">
        <button type="button" id="btn_generate_exam" class="btn btn-dark btn-lg rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <span class="btn-text">{{ __('exams.ai_btn_generate') }}</span>
            <i class="fa-solid fa-arrow-right btn-icon"></i>
        </button>
    </div>

    <div id="ai_feedback" class="mt-3 text-center text-danger small fw-bold d-none"></div>
</x-modal>