<x-modal 
    id="createPlanModal" 
    title="{{ __('plans.create_modal_title') }}" 
    action="{{ route('admin.plans.store') }}" 
    submit-text="{{ __('plans.create_submit_btn') }}">

    <div class="mb-3">
        <label class="form-label fw-bold text-dark">{{ __('plans.label_name') }}</label>
        <p class="text-muted small mt-0 mb-2">{{ __('plans.help_name') }}</p>
        <input type="text" name="name" class="form-control" placeholder="{{ __('plans.placeholder_name') }}" required>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_price_monthly') }}</label>
            <input type="number" step="0.01" name="price_monthly" class="form-control" placeholder="0.00" required>
        </div>
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_price_yearly') }}</label>
            <input type="number" step="0.01" name="price_yearly" class="form-control" placeholder="0.00" required>
        </div>
    </div>

    <div class="mb-4 p-3 bg-light rounded border">
        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-link me-2"></i>{{ __('plans.label_included_categories') }}</label>
        <p class="text-muted small mt-0 mb-2">{{ __('plans.help_included_categories') }}</p>
        <select name="category_ids[]" class="form-select select2-categories" multiple data-placeholder="{{ __('plans.placeholder_categories') }}">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_limit_monthly') }}</label>
            <p class="text-muted small mt-0 mb-2">{{ __('plans.help_limit_monthly') }}</p>
            <input type="number" name="limit_monthly" class="form-control" value="0" required>
        </div>
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_limit_yearly') }}</label>
            <p class="text-muted small mt-0 mb-2">{{ __('plans.help_limit_yearly') }}</p>
            <input type="number" name="limit_yearly" class="form-control" value="0" required>
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-bold text-dark">{{ __('plans.label_desc') }}</label>
        <textarea name="short_description" class="form-control" rows="2" placeholder="{{ __('plans.placeholder_desc') }}"></textarea>
    </div>
</x-modal>