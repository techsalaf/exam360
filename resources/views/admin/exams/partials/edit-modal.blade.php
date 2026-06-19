<x-modal 
    id="editExamModal" 
    title="{{ __('exams.edit_modal_title') }}" 
    action="#" 
    isEdit="true"
    submitText="{{ __('exams.edit_submit_btn') }}"
    enctype="multipart/form-data"
>
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="edit_title" class="form-label-premium">{{ __('exams.label_title') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control-premium" id="edit_title" name="title" required>
    </div>
    
    <div class="mb-4">
        <label for="edit_banner" class="form-label-premium">{{ __('exams.label_banner') }}</label>
        <div class="d-flex gap-3 align-items-center">
            <div class="flex-grow-1">
                <input type="file" class="form-control-premium" id="edit_banner" name="banner" accept="image/png, image/jpeg, image/webp">
                <div class="form-text small text-muted">{{ __('exams.banner_help_edit') }}</div>
            </div>
            <div id="edit_banner_preview" class="d-none position-relative border rounded p-1 bg-white exam-banner-preview">
                <img src="" alt="Banner Preview" class="w-100 h-100 object-fit-cover rounded">
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="edit_category" class="form-label-premium">{{ __('exams.label_category') }} <span class="text-danger">*</span></label>
            <select class="form-control-premium form-select" id="edit_category" name="category_id" required>
                <option value="" disabled>{{ __('exams.select_category') }}</option>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-6">
            <label for="edit_duration" class="form-label-premium">{{ __('exams.label_duration') }} <span class="text-danger">*</span></label>
            <input type="number" class="form-control-premium" id="edit_duration" name="duration" min="1" required>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="edit_pass_percentage" class="form-label-premium">{{ __('exams.label_pass_percentage') }}</label>
            <input type="number" class="form-control-premium" id="edit_pass_percentage" name="pass_percentage" min="1" max="100">
        </div>
        <div class="col-md-6">
            <label for="edit_total_marks" class="form-label-premium">{{ __('exams.label_total_marks') }}</label>
            <input type="number" class="form-control-premium" id="edit_total_marks" name="total_marks">
        </div>
    </div>
    
    <div class="mb-4 p-3 rounded-2 bg-light border border-dashed">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input negative-marking-toggle" type="checkbox" role="switch" id="edit_has_negative_marking" name="has_negative_marking" value="1">
            <label class="form-check-label fw-bold small" for="edit_has_negative_marking">{{ __('exams.enable_negative_marking') }}</label>
        </div>
        <div class="negative-value-container opacity-50">
            <label for="edit_negative_mark_value" class="form-label-premium text-xs text-muted">{{ __('exams.negative_mark_value') }}</label>
            <input type="number" step="0.01" min="0" class="form-control-premium form-control-sm" id="edit_negative_mark_value" name="negative_mark_value" disabled>
        </div>
    </div>

    <div class="p-3 bg-light rounded-3 border mb-4">
        <h6 class="text-dark fw-bold small text-uppercase mb-3"><i class="fa-solid fa-tag me-1"></i> {{ __('exams.pricing_access') }}</h6>
        
        <div class="mb-3">
            <label for="edit_plan" class="form-label-premium">{{ __('exams.label_plan') }}</label>
            <select class="form-control-premium form-select" id="edit_plan" name="plan_id">
                <option value="">{{ __('exams.no_plan') }}</option>
                @if(isset($plans))
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" 
                                data-monthly="{{ $plan->price_monthly }}" 
                                data-yearly="{{ $plan->price_yearly }}"
                                data-currency="{{ $currencySymbol ?? '$' }}">
                            {{ $plan->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <div id="edit_plan_info" class="mt-2 p-2 bg-info-subtle border border-info rounded-2 d-none">
                <div class="d-flex align-items-start gap-2">
                    <i class="fa-solid fa-circle-info text-info mt-1"></i>
                    <div>
                        <span class="d-block fw-bold text-dark small">{{ __('exams.plan_selected') }}: <span class="plan-name"></span></span>
                        <span class="d-block text-muted text-xs">{{ __('exams.plan_info') }} <span class="plan-price fw-bold text-dark"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-top my-3"></div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-premium">{{ __('exams.label_sales_model') }}</label>
                <div class="d-flex gap-3 mt-2">
                    <div class="form-check">
                        <input class="form-check-input edit-pricing-toggle" type="radio" name="pricing_type" id="edit_price_free" value="free">
                        <label class="form-check-label small fw-bold" for="edit_price_free" id="label_edit_price_free">Free</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input edit-pricing-toggle" type="radio" name="pricing_type" id="edit_price_paid" value="paid">
                        <label class="form-check-label small fw-bold" for="edit_price_paid">{{ __('exams.model_paid') }}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-none" id="edit_price_container">
                <label for="edit_price" class="form-label-premium">{{ __('exams.label_price') }}</label>
                <div class="input-icon-wrapper">
                    <span class="input-icon fw-bold exam-price-icon">{{ $currencySymbol ?? '$' }}</span>
                    <input type="number" class="form-control-premium" id="edit_price" name="price" step="0.01" min="0">
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label-premium">{{ __('exams.label_start_date') }}</label>
                <input type="datetime-local" class="form-control-premium" id="edit_start_date" name="start_date" max="2037-12-31T23:59">
            </div>
            <div class="col-md-4">
                <label class="form-label-premium">{{ __('exams.label_end_date') }}</label>
                <input type="datetime-local" class="form-control-premium" id="edit_end_date" name="end_date" max="2037-12-31T23:59">
            </div>
            <div class="col-md-4">
                <label class="form-label-premium">{{ __('exams.label_result_date') }}</label>
                <input type="datetime-local" class="form-control-premium" id="edit_result_date" name="result_date" max="2037-12-31T23:59">
            </div>
        </div>
    </div>
    
    <div class="mb-2">
        <label for="edit_description" class="form-label-premium">{{ __('exams.label_desc') }}</label>
        <textarea class="form-control-premium" id="edit_description" name="description" rows="3"></textarea>
    </div>
</x-modal>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editPlanSelect = document.getElementById('edit_plan');
    const editFreeLabel = document.getElementById('label_edit_price_free');

    function updateEditPricingLabels() {
        if (!editPlanSelect || !editFreeLabel) return;
        
        if (editPlanSelect.value !== '') {
            editFreeLabel.innerHTML = 'Plan Only';
        } else {
            editFreeLabel.innerHTML = 'Free';
        }
    }

    if (editPlanSelect) {
        editPlanSelect.addEventListener('change', updateEditPricingLabels);
        updateEditPricingLabels(); 
    }

    const editModal = document.getElementById('editExamModal');
    if (editModal) {
        editModal.addEventListener('shown.bs.modal', function () {
            updateEditPricingLabels();
        });
    }
});
</script>