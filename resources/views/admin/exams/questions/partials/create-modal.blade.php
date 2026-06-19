<x-modal 
    id="createQuestionModal" 
    title="{{ __('questions.create_modal_title') }}" 
    action="{{ route('admin.exams.questions.store', ['examId' => $exam->id]) }}" 
    submitText="{{ __('questions.create_submit_btn') }}"
>
    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
    
    <div class="mb-3">
        <label for="create_type" class="form-label-premium text-uppercase fs-075 ls-wide">{{ __('questions.label_type') }} <span class="text-danger">*</span></label>
        <select class="form-control-premium form-select shadow-none" id="create_type" name="type" required>
            <option value="mcq">{{ __('questions.type_mcq') }}</option>
            <option value="true_false">{{ __('questions.type_tf') }}</option>
            <option value="short_answer">{{ __('questions.type_short') }}</option>
            @if(class_exists('\Addons\CodingAssessments\Models\CodingAssessment'))
            <option value="coding">{{ __('questions.type_coding') }}</option>
            @endif
        </select>
    </div>

    <div class="mb-3">
        <label for="create_text" class="form-label-premium text-uppercase fs-075 ls-wide">{{ __('questions.label_text') }} <span class="text-danger">*</span></label>
        <textarea class="form-control-premium shadow-none" id="create_text" name="question_text" rows="3" required></textarea>
    </div>

    <div id="create_container_options" class="border rounded-3 p-3 bg-light mb-3" style="display:none;">
        <h6 class="text-dark fw-bold mb-3 fs-085">{{ __('questions.mcq_options_title') }}</h6>
        <div class="row g-3">
            <div class="col-6"><input type="text" class="form-control-premium shadow-none" name="options[A]" placeholder="{{ __('questions.option_a') }}"></div>
            <div class="col-6"><input type="text" class="form-control-premium shadow-none" name="options[B]" placeholder="{{ __('questions.option_b') }}"></div>
            <div class="col-6"><input type="text" class="form-control-premium shadow-none" name="options[C]" placeholder="{{ __('questions.option_c') }}"></div>
            <div class="col-6"><input type="text" class="form-control-premium shadow-none" name="options[D]" placeholder="{{ __('questions.option_d') }}"></div>
        </div>
    </div>

    @if(class_exists('\Addons\CodingAssessments\Models\CodingAssessment'))
    <div id="create_container_coding" class="border rounded-3 p-3 bg-light mb-3" style="display:none;">
        <div class="mb-3">
            <label class="form-label fw-bold small text-dark mb-2">{{ __('questions.label_import_coding') }}</label>
            <select class="form-select form-select-sm shadow-none border py-2" id="create_import_coding_assessment">
                <option value="">{{ __('questions.import_coding_placeholder') }}</option>
                @foreach($codingAssessments as $ca)
                <option value="{{ $ca->id }}" data-full='@json($ca)'>{{ $ca->title }}</option>
                @endforeach
            </select>
            <div class="form-text fs-07 text-muted mt-2 lh-base">{{ __('questions.import_coding_hint') }}</div>
        </div>
        <hr class="my-3 opacity-10">
        <div class="mb-3">
            <label class="form-label fw-bold small text-dark d-block mb-2">{{ __('questions.label_allowed_langs') }}</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach(['javascript', 'python', 'php', 'cpp', 'java'] as $lang)
                <div class="form-check">
                    <input class="form-check-input shadow-none" type="checkbox" name="allowed_languages[]" value="{{ $lang }}" id="create_lang_{{ $lang }}">
                    <label class="form-check-label small text-uppercase fw-bold text-muted cursor-pointer" for="create_lang_{{ $lang }}">{{ strtoupper($lang) }}</label>
                </div>
                @endforeach
            </div>
        </div>
        <hr class="my-3 opacity-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-label fw-bold small text-dark mb-0">{{ __('questions.label_test_cases') }}</label>
            <button type="button" class="btn btn-xs btn-outline-success py-1 px-3 fs-075 border shadow-none d-flex align-items-center gap-1 bg-white" onclick="addTestCase('create-cases-container')">
                <i class="fa-solid fa-plus"></i> {{ __('questions.btn_add_case') }}
            </button>
        </div>
        <div id="create-cases-container"></div>
    </div>
    @endif

    <div class="mb-3" id="create_container_correct_answer">
        <label class="form-label-premium text-uppercase fs-075 ls-wide">{{ __('questions.label_correct_answer') }} <span class="text-danger">*</span></label>
        <select class="form-control-premium form-select shadow-none" id="create_input_correct_mcq" name="correct_answer">
            <option value="A">Option A</option>
            <option value="B">Option B</option>
            <option value="C">Option C</option>
            <option value="D">Option D</option>
        </select>
        <select class="form-control-premium form-select d-none shadow-none" id="create_input_correct_tf" name="correct_answer" disabled>
            <option value="True">True</option>
            <option value="False">False</option>
        </select>
        <input type="text" class="form-control-premium d-none shadow-none" id="create_input_correct_sa" name="correct_answer" placeholder="{{ __('questions.placeholder_answer') }}" disabled>
    </div>
    
    <div class="mb-3">
        <label for="create_explanation" class="form-label-premium text-uppercase fs-075 ls-wide">{{ __('questions.label_explanation') }}</label>
        <textarea class="form-control-premium shadow-none" id="create_explanation" name="explanation" rows="2"></textarea>
    </div>
</x-modal>