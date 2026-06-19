@extends('layouts.admin')

@section('title', __('cms.testimonials'))

@push('styles')
    <link href="{{ asset('assets/css/cms.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-categories.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="desktop-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.testimonial_manager') }}</h1>
            <p class="text-muted small mb-0">{{ __('cms.testimonial_desc') }}</p>
        </div>
        
        <div class="page-header-actions">
            <button class="btn btn-cms-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
                <i class="fa-solid fa-plus me-2"></i>
                {{ __('cms.add_testimonial') }}
            </button>
        </div>
    </div>

    <div class="cms-list-container">
        
        <div class="cms-list-header">
            <div class="cms-col-header cms-col-3">{{ __('cms.user_info') }}</div>
            <div class="cms-col-header cms-col-4">{{ __('cms.review') }}</div>
            <div class="cms-col-header cms-col-1 text-center">{{ __('cms.rating') }}</div>
            <div class="cms-col-header cms-col-1 text-center">{{ __('cms.status') }}</div>
            <div class="cms-col-header cms-col-1 text-end">{{ __('cms.actions') }}</div>
        </div>

        @forelse($testimonials as $t)
            <div class="cms-list-item">
                
                <div class="cms-col-3 d-flex align-items-center">
                    <div class="flex-shrink-0">
                        @if($t->avatar)
                            <img src="{{ Str::startsWith($t->avatar, 'http') ? $t->avatar : asset('storage/' . $t->avatar) }}" class="item-avatar" alt="{{ $t->name }}">
                        @else
                            <div class="item-avatar-placeholder">
                                {{ substr($t->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0 cms-text-dark fw-bold testimonial-name">{{ $t->name }}</h5>
                        <p class="small mb-0 cms-text-subtle">{{ cms_admin_text(['role' => $t->role], 'role') ?: __('cms.default_role') }}</p>
                    </div>
                </div>

                <div class="cms-col-4 small pe-4 cms-text-subtle">
                    <i class="fa-solid fa-quote-left text-primary-cms opacity-25 me-1"></i>
                    {{ Str::limit(cms_admin_text(['review' => $t->review], 'review'), 80) }}
                </div>

                <div class="cms-col-1 text-center">
                    <div class="rating-stars">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $t->rating)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star rating-star-empty"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                
                <div class="cms-col-1 text-center">
                    @if($t->is_active)
                        <span class="badge-cms-published">{{ __('cms.active') }}</span>
                    @else
                        <span class="badge-cms-draft">{{ __('cms.hidden') }}</span>
                    @endif
                </div>
                
                <div class="cms-col-1 text-end">
                    <div class="action-group justify-content-end gap-1">
                        <button type="button" class="btn-circle edit btn-edit-testi" 
                                title="{{ __('cms.edit') }}" 
                                data-id="{{ $t->id }}" 
                                data-name="{{ $t->name }}"
                                data-role="{{ cms_admin_text(['role' => $t->role], 'role') }}"
                                data-review="{{ cms_admin_text(['review' => $t->review], 'review') }}"
                                data-rating="{{ $t->rating }}"
                                data-avatar="{{ $t->avatar ? (Str::startsWith($t->avatar, 'http') ? $t->avatar : asset('storage/' . $t->avatar)) : '' }}"
                                data-action="{{ route('admin.cms.testimonials.update', $t->id) }}">
                            <i class="fa-solid fa-pen text-muted-cms"></i>
                        </button>

                        <form action="{{ route('admin.cms.testimonials.toggle', $t->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-circle toggle" title="{{ __('cms.toggle_status') }}">
                                <i class="fa-solid {{ $t->is_active ? 'fa-eye' : 'fa-eye-slash' }} text-muted-cms"></i>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.cms.testimonials.destroy', $t->id) }}" method="POST" class="d-inline delete-testimonial-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-circle danger btn-cms-danger-icon" title="{{ __('cms.delete') }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="cms-empty-icon-wrapper">
                    <i class="fa-regular fa-comment-dots fa-2x"></i>
                </div>
                <h6 class="fw-bold cms-text-dark">{{ __('cms.no_testimonials') }}</h6>
                <p class="small cms-text-subtle">{{ __('cms.add_reviews_hint') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $testimonials->links() }}
    </div>

    @include('admin.cms.testimonials.partials.create-modal')
    @include('admin.cms.testimonials.partials.edit-modal')

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        document.querySelectorAll('.testimonial-avatar-input').forEach(input => {
            input.addEventListener('change', function () {
                const imgId = this.getAttribute('data-preview-img');
                const labelId = this.getAttribute('data-preview-label');
                previewImage(this, imgId, labelId);
            });
        });

        document.querySelectorAll('.delete-testimonial-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm("{{ __('cms.confirm_delete') }}")) {
                    e.preventDefault();
                }
            });
        });
        
        const editModalEl = document.getElementById('editTestimonialModal');
        
        if(editModalEl) {
            const editModal = new bootstrap.Modal(editModalEl);
            const editForm = document.getElementById('editTestimonialForm');
            
            document.querySelectorAll('.btn-edit-testi').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = this.dataset;
                    
                    editForm.action = data.action;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_role').value = data.role;
                    document.getElementById('edit_review').value = data.review;
                    document.getElementById('edit_rating').value = data.rating;
                    
                    const previewImg = document.getElementById('edit_preview_img');
                    const previewLabel = document.getElementById('edit_preview_label');
                    const deleteFlag = document.getElementById('edit_delete_avatar');
                    const fileInput = document.getElementById('edit_avatar');
                    
                    deleteFlag.value = '0';
                    fileInput.value = '';

                    if(data.avatar && data.avatar.trim() !== '') {
                        previewImg.src = data.avatar;
                        previewLabel.classList.add('has-image');
                        previewImg.classList.add('show');
                    } else {
                        previewImg.src = '';
                        previewLabel.classList.remove('has-image');
                        previewImg.classList.remove('show');
                    }

                    editModal.show();
                });
            });

            const removeBtn = document.getElementById('btn_remove_edit_avatar');
            if(removeBtn){
                removeBtn.addEventListener('click', function(e){
                    e.preventDefault(); 
                    e.stopPropagation();
                    
                    document.getElementById('edit_preview_img').src = '';
                    document.getElementById('edit_preview_img').classList.remove('show');
                    document.getElementById('edit_preview_label').classList.remove('has-image');
                    
                    document.getElementById('edit_delete_avatar').value = '1';
                    document.getElementById('edit_avatar').value = '';
                });
            }
        }
    });

    function previewImage(input, imgId, labelId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(imgId);
                const label = document.getElementById(labelId);
                
                if(img) {
                    img.src = e.target.result;
                    img.classList.add('show');
                }
                if(label) label.classList.add('has-image');
                
                const form = input.closest('form');
                if(form) {
                    const delFlag = form.querySelector('input[name="delete_avatar"]');
                    if(delFlag) delFlag.value = '0';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush