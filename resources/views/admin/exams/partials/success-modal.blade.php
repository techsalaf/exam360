<x-modal 
    id="examSuccessModal" 
    title="" 
    action="#" 
    submitText="{{ __('exams.success_btn') }}"
>
    <div class="text-center py-4">
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle success-icon-circle">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <h4 class="fw-bold text-dark mb-2">{{ __('exams.success_title') }}</h4>
        <p class="text-muted mb-4">{{ __('exams.success_msg') }}</p>

        <div class="bg-light border rounded-3 p-3 mb-4 d-inline-block text-start success-details-box">
            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                <span class="text-muted small text-uppercase fw-bold">{{ __('exams.label_id') }}</span>
                <span class="fw-bold text-dark" id="success_exam_id">#---</span>
            </div>
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-muted small text-uppercase fw-bold">{{ __('exams.label_title') }}</span>
                <span class="fw-bold text-dark text-end success-title-text" id="success_exam_title">---</span>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">{{ __('exams.btn_close') }}</button>
            
            <a href="#" id="btn_go_questions" class="btn btn-success px-4 d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check"></i> {{ __('exams.btn_add_questions') }}
            </a>
        </div>
    </div>
</x-modal>