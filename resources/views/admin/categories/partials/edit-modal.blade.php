<x-modal 
    id="editCategoryModal" 
    title="{{ __('categories.edit_modal_title') }}" 
    action="#" 
    isEdit="true"
    hasFile="true"
    submitText="{{ __('categories.edit_submit_btn') }}"
>
    <!-- Name Input -->
    <div class="mb-4">
        <label for="edit_name" class="form-label-premium">{{ __('categories.label_name') }}</label>
        <input type="text" 
               class="form-control-premium @error('name') is-invalid @enderror" 
               id="edit_name" 
               name="name" 
               required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Description Input -->
    <div class="mb-4">
        <label for="edit_description" class="form-label-premium">{{ __('categories.label_desc') }}</label>
        <textarea name="description" 
                  id="edit_description" 
                  class="form-control-premium" 
                  rows="2"></textarea>
    </div>

    <!-- Meta Labels -->
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="edit_meta_1" class="form-label-premium">{{ __('categories.label_meta1') }}</label>
            <input type="text" 
                   name="meta_text_1" 
                   id="edit_meta_1" 
                   class="form-control-premium" 
                   placeholder="{{ __('categories.placeholder_meta1') }}">
        </div>
        <div class="col-md-6">
            <label for="edit_meta_2" class="form-label-premium">{{ __('categories.label_meta2') }}</label>
            <input type="text" 
                   name="meta_text_2" 
                   id="edit_meta_2" 
                   class="form-control-premium" 
                   placeholder="{{ __('categories.placeholder_meta2') }}">
        </div>
    </div>

    <!-- Image Upload with Delete Functionality -->
    <div class="mb-2">
        <label class="form-label-premium">{{ __('categories.label_update_image') }}</label>
        
        <div class="image-upload-container">
            <!-- Hidden Flag for Deletion -->
            <input type="hidden" name="delete_image" id="edit_delete_image_flag" value="0">

            <label for="edit_image_input" id="edit_preview_label" class="image-upload-label">
                
                <!-- Remove Image Button -->
                <button type="button" class="btn-remove-image" id="btn_remove_edit_image" title="{{ __('categories.remove_image_title') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>

                <!-- Default Placeholder -->
                <div class="upload-content">
                    <div class="upload-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path><line x1="16" y1="5" x2="22" y2="5"></line><line x1="19" y1="2" x2="19" y2="8"></line><circle cx="9" cy="9" r="2"></circle><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path></svg>
                    </div>
                    <div class="upload-text">
                        <span>{{ __('categories.upload_change_image') }}</span>
                    </div>
                </div>

                <!-- Preview Image -->
                <img id="edit_preview_img" class="image-preview" src="" alt="Category Icon">
            </label>

            <!-- File Input -->
            <input type="file" 
                   name="image" 
                   id="edit_image_input" 
                   class="file-input" 
                   accept="image/png, image/jpeg, image/jpg, image/svg+xml"
                   onchange="previewImage(this, 'edit_preview_img', 'edit_preview_label', 'edit_delete_image_flag')">
        </div>

        <div class="upload-help-text">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>{!! __('categories.upload_help') !!}</span>
        </div>
    </div>
</x-modal>