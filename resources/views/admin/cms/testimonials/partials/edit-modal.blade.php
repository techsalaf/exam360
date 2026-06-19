<div class="modal fade" id="editTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('cms.edit_testimonial') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTestimonialForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label-premium">{{ __('cms.user_name') }}</label>
                            <input type="text" name="name" id="edit_name" class="form-control-premium" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label-premium">{{ __('cms.rating') }}</label>
                            <select name="rating" id="edit_rating" class="form-control-premium">
                                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4">⭐⭐⭐⭐ (4)</option>
                                <option value="3">⭐⭐⭐ (3)</option>
                                <option value="2">⭐⭐ (2)</option>
                                <option value="1">⭐ (1)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.role_title') }}</label>
                        <input type="text" name="role" id="edit_role" class="form-control-premium">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.review') }}</label>
                        <textarea name="review" id="edit_review" class="form-control-premium" rows="3" required></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-premium">{{ __('cms.update_photo') }}</label>
                        <div class="image-upload-container">
                            
                            {{-- STRUCTURE MATCHING HOMEPAGE SNIPPET --}}
                            {{-- The classes 'has-image' and 'show' are toggled via JS in index.blade.php --}}
                            <label for="edit_avatar" id="edit_preview_label" class="image-upload-label image-upload-lg">
                                
                                <input type="file" 
                                       name="avatar" 
                                       id="edit_avatar" 
                                       class="file-input testimonial-avatar-input" 
                                       data-preview-img="edit_preview_img" 
                                       data-preview-label="edit_preview_label" 
                                       accept="image/*">
                                
                                <div class="upload-content text-center">
                                    <div class="upload-icon-wrapper upload-icon-md"><i class="fa-regular fa-image"></i></div>
                                    <div class="upload-text small">{{ __('cms.change_photo') }}</div>
                                </div>
                                
                                {{-- Preview Image --}}
                                <img id="edit_preview_img" class="image-preview" src="">
                                
                                {{-- Remove Button --}}
                                <button type="button" class="btn-remove-image" id="btn_remove_edit_avatar">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </label>

                            <input type="hidden" name="delete_avatar" id="edit_delete_avatar" value="0">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">{{ __('cms.cancel') }}</button>
                    <button type="submit" class="btn btn-premium">{{ __('cms.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>