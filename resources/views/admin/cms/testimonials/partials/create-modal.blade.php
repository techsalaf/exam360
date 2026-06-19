<div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('cms.add_testimonial') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.cms.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label-premium">{{ __('cms.user_name') }}</label>
                            <input type="text" name="name" class="form-control-premium" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label-premium">{{ __('cms.rating') }}</label>
                            <select name="rating" class="form-control-premium">
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
                        <input type="text" name="role" class="form-control-premium" placeholder="e.g. CEO, Marketing Manager">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.review') }}</label>
                        <textarea name="review" class="form-control-premium" rows="3" required></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-premium">{{ __('cms.user_photo') }}</label>
                        
                        <div class="image-upload-container">
                            {{-- STRUCTURE MATCHING HOMEPAGE SNIPPET --}}
                            <label for="create_avatar" id="create_preview_label" class="image-upload-label image-upload-lg">
                                
                                <input type="file" 
                                       name="avatar" 
                                       id="create_avatar" 
                                       class="file-input testimonial-avatar-input" 
                                       data-preview-img="create_preview_img" 
                                       data-preview-label="create_preview_label" 
                                       accept="image/*">

                                <div class="upload-content text-center">
                                    <div class="upload-icon-wrapper upload-icon-md"><i class="fa-regular fa-image"></i></div>
                                    <div class="upload-text small">{{ __('cms.upload_photo') }}</div>
                                </div>
                                
                                <img id="create_preview_img" class="image-preview" src="">
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">{{ __('cms.cancel') }}</button>
                    <button type="submit" class="btn btn-premium">{{ __('cms.save_testimonial') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>