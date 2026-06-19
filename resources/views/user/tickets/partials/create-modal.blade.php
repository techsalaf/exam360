<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="createTicketModalLabel">{{ __('frontend.modal_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('frontend.close_btn') }}"></button>
            </div>
            
            <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="ticketSubject" class="form-label fw-semibold">{{ __('frontend.subject_label') }}</label>
                        <input type="text" class="form-control" id="ticketSubject" name="subject" required placeholder="{{ __('frontend.subject_place') }}" value="{{ old('subject') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ticketCategory" class="form-label fw-semibold">{{ __('frontend.category_label') }}</label>
                            <select class="form-select" id="ticketCategory" name="category" required>
                                <option value="" selected disabled>{{ __('frontend.select_cat') }}</option>
                                <option value="Billing" {{ old('category') == 'Billing' ? 'selected' : '' }}>{{ __('frontend.cat_billing') }}</option>
                                <option value="Technical Issue" {{ old('category') == 'Technical Issue' ? 'selected' : '' }}>{{ __('frontend.cat_tech') }}</option>
                                <option value="Course Content" {{ old('category') == 'Course Content' ? 'selected' : '' }}>{{ __('frontend.cat_content') }}</option>
                                <option value="General Inquiry" {{ old('category') == 'General Inquiry' ? 'selected' : '' }}>{{ __('frontend.cat_general') }}</option>
                                <option value="Feature Request" {{ old('category') == 'Feature Request' ? 'selected' : '' }}>{{ __('frontend.cat_feature') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ticketPriority" class="form-label fw-semibold">{{ __('frontend.priority_label') }}</label>
                            <select class="form-select" id="ticketPriority" name="priority" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('frontend.p_low') }}</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>{{ __('frontend.p_medium') }}</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('frontend.p_high') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ticketMessage" class="form-label fw-semibold">{{ __('frontend.desc_label') }}</label>
                        <textarea class="form-control" id="ticketMessage" name="message" rows="5" required placeholder="{{ __('frontend.desc_place') }}">{{ old('message') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('frontend.attachments_optional') }}</label>
                        <input type="file" class="form-control" name="attachments[]" multiple>
                        <small class="text-muted">{{ __('frontend.supported_formats') }}</small>
                    </div>

                    <div class="alert alert-info small mt-3 mb-0">
                        <i class="fa-solid fa-circle-info me-2"></i> {{ __('frontend.support_notice') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('frontend.cancel_btn') }}</button>
                    <button type="submit" class="btn btn-primary fw-bold">{{ __('frontend.submit_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>