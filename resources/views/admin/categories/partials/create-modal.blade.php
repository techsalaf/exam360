<x-modal 
    id="addCategoryModal" 
    title="{{ __('categories.create_modal_title') }}" 
    action="{{ route('admin.categories.store') }}" 
    hasFile="true"
    submitText="{{ __('categories.create_submit_btn') }}"
>
    <!-- Name Input -->
    <div class="mb-4">
        <label for="create_name" class="form-label-premium">{{ __('categories.label_name') }}</label>
        <input type="text" 
               class="form-control-premium @error('name') is-invalid @enderror" 
               id="create_name" 
               name="name" 
               value="{{ old('name') }}"
               placeholder="{{ __('categories.placeholder_name') }}" 
               required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Description Input -->
    <div class="mb-4">
        <label for="create_description" class="form-label-premium">{{ __('categories.label_desc') }}</label>
        <textarea name="description" 
                  id="create_description" 
                  class="form-control-premium" 
                  rows="2" 
                  placeholder="{{ __('categories.placeholder_desc') }}">{{ old('description') }}</textarea>
    </div>

    <!-- Meta Labels -->
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="create_meta_1" class="form-label-premium">{{ __('categories.label_meta1') }}</label>
            <input type="text" 
                   name="meta_text_1" 
                   id="create_meta_1" 
                   class="form-control-premium" 
                   value="{{ old('meta_text_1') }}" 
                   placeholder="{{ __('categories.placeholder_meta1') }}">
        </div>
        <div class="col-md-6">
            <label for="create_meta_2" class="form-label-premium">{{ __('categories.label_meta2') }}</label>
            <input type="text" 
                   name="meta_text_2" 
                   id="create_meta_2" 
                   class="form-control-premium" 
                   value="{{ old('meta_text_2') }}" 
                   placeholder="{{ __('categories.placeholder_meta2') }}">
        </div>
    </div>

    <!-- Image Upload -->
    <div class="mb-2">
        <label class="form-label-premium">{{ __('categories.label_icon') }}</label>
        
        <div class="image-upload-container">
            <label for="create_image_input" id="create_preview_label" class="image-upload-label">
                <div class="upload-content">
                    <div class="upload-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </div>
                    <div class="upload-text">
                        <span>{{ __('categories.upload_text_click') }}</span> {{ __('categories.upload_text_image') }}
                    </div>
                </div>
                <img id="create_preview_img" class="image-preview" src="" alt="Preview">
            </label>

            <input type="file" 
                   name="image" 
                   id="create_image_input" 
                   class="file-input" 
                   accept="image/png, image/jpeg, image/jpg, image/svg+xml"
                   onchange="previewImage(this, 'create_preview_img', 'create_preview_label')">
        </div>

        <div class="upload-help-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>{!! __('categories.upload_help') !!}</span>
        </div>
        
        @error('image')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</x-modal>