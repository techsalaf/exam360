@props(['plan', 'categories'])
<x-modal 
    id="editPlanModal{{ $plan->id }}" 
    title="{{ __('plans.edit_modal_title', ['name' => $plan->name]) }}" 
    action="{{ route('admin.plans.update', $plan->id) }}" 
    is-edit 
    submit-text="{{ __('plans.edit_submit_btn') }}">

    <div class="mb-3">
        <label class="form-label fw-bold text-dark">{{ __('plans.label_name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_price_monthly') }}</label>
            <input type="number" step="0.01" name="price_monthly" class="form-control" value="{{ $plan->price_monthly }}" required>
        </div>
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_price_yearly') }}</label>
            <input type="number" step="0.01" name="price_yearly" class="form-control" value="{{ $plan->price_yearly }}" required>
        </div>
    </div>

    <div class="mb-4 p-3 bg-light rounded border">
        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-link me-2"></i>{{ __('plans.label_included_categories') }}</label>
        @php $selectedIds = $plan->categories->pluck('id')->toArray(); @endphp
        <select name="category_ids[]" class="form-select select2-categories" multiple>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(in_array($category->id, $selectedIds))>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_limit_monthly') }}</label>
            <input type="number" name="limit_monthly" class="form-control" value="{{ $plan->limit_monthly }}" required>
        </div>
        <div class="col-6">
            <label class="form-label fw-bold text-dark">{{ __('plans.label_limit_yearly') }}</label>
            <input type="number" name="limit_yearly" class="form-control" value="{{ $plan->limit_yearly }}" required>
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-bold text-dark">{{ __('plans.label_desc') }}</label>
        <textarea name="short_description" class="form-control" rows="2">{{ $plan->short_description }}</textarea>
    </div>
</x-modal>