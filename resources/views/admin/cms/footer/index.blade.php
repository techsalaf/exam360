@extends('layouts.admin')

@section('title', __('cms.footer_editor'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cms.css') }}">
@endpush

@section('content')

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('cms.footer_editor') }}</h1>
            <p class="text-muted small mb-0">{{ __('cms.footer_desc') }}</p>
        </div>
        <button type="submit" form="footerForm" class="btn btn-cms-primary shadow-sm px-4">
            <i class="fa-solid fa-save me-2"></i> {{ __('cms.save_changes') }}
        </button>
    </div>

    <form id="footerForm" action="{{ route('admin.cms.footer.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            {{-- Left Column: Content --}}
            <div class="col-lg-6">
                
                <!-- 1. Footer Logo (New) -->
                <div class="cms-card mb-4">
                    <div class="cms-header">
                        <h5 class="cms-title"><i class="fa-solid fa-image"></i> {{ __('cms.footer_logo') }}</h5>
                        <p class="cms-desc">{{ __('cms.footer_logo_desc') }}</p>
                    </div>
                    <div class="cms-body">
                        <div class="d-flex align-items-center gap-4">
                            
                            {{-- FIX (Rule 2): Replaced inline style with class footer-logo-preview --}}
                            <div class="footer-logo-preview p-3 bg-dark rounded border border-secondary">
                                @if(!empty($settings['footer_logo']))
                                    {{-- FIX (Rule 2): Replaced inline style with class footer-logo-img --}}
                                    <img src="{{ Storage::url($settings['footer_logo']) }}" alt="{{ __('cms.footer_logo') }}" class="footer-logo-img">
                                @else
                                    <span class="text-muted small">{{ __('cms.no_logo') }}</span>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <label class="form-label-premium">{{ __('cms.upload_logo_light') }}</label>
                                <input type="file" name="footer_logo" class="form-control-cms" accept="image/*">
                                <div class="form-text text-muted small">{{ __('cms.logo_recommendation') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. About & Copyright -->
                <div class="cms-card mb-4">
                    <div class="cms-header">
                        <h5 class="cms-title"><i class="fa-solid fa-circle-info"></i> {{ __('cms.about_column') }}</h5>
                        <p class="cms-desc">{{ __('cms.about_column_desc') }}</p>
                    </div>
                    <div class="cms-body">
                        <div class="mb-4">
                            <label class="form-label-premium">{{ __('cms.footer_about_text') }}</label>
                            {{-- Uses cms_admin_text helper --}}
                            <textarea name="footer_about_text" class="form-control-cms" rows="4" placeholder="{{ __('cms.about_placeholder') }}">{{ cms_admin_text($settings, 'footer_about_text') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label-premium">{{ __('cms.copyright_text') }}</label>
                            {{-- Uses cms_admin_text helper --}}
                            <input type="text" name="footer_copyright" class="form-control-cms" value="{{ cms_admin_text($settings, 'footer_copyright') }}">
                        </div>
                    </div>
                </div>

                <!-- 3. Contact Info -->
                <div class="cms-card">
                    <div class="cms-header">
                        <h5 class="cms-title"><i class="fa-solid fa-address-book"></i> {{ __('cms.contact_info') }}</h5>
                        <p class="cms-desc">{{ __('cms.contact_info_desc') }}</p>
                    </div>
                    <div class="cms-body">
                        <div class="mb-3">
                            <label class="form-label-premium">{{ __('cms.address') }}</label>
                            {{-- Uses cms_admin_text helper --}}
                            <input type="text" name="contact_address" class="form-control-cms" value="{{ cms_admin_text($settings, 'contact_address') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-premium">{{ __('cms.support_email') }}</label>
                            <input type="text" 
                                   name="contact_email" 
                                   class="form-control-cms" 
                                   value="{{ $settings['contact_email'] ?? '' }}"
                                   placeholder="{{ __('cms.support_email_placeholder') }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label-premium">{{ __('cms.phone_number') }}</label>
                            <input type="text" 
                                   name="contact_phone" 
                                   class="form-control-cms" 
                                   value="{{ $settings['contact_phone'] ?? '' }}"
                                   placeholder="{{ __('cms.phone_placeholder') }}">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Socials --}}
            <div class="col-lg-6">
                <div class="cms-card h-100">
                    <div class="cms-header">
                        <h5 class="cms-title"><i class="fa-solid fa-share-nodes"></i> {{ __('cms.social_links') }}</h5>
                        <p class="cms-desc">{{ __('cms.social_links_desc') }}</p>
                    </div>
                    <div class="cms-body">
                        <div class="mb-3">
                            <label class="form-label-premium"><i class="fa-brands fa-facebook text-primary me-1"></i> {{ __('cms.facebook_url') }}</label>
                            <input type="url" name="social_facebook" class="form-control-cms" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-premium"><i class="fa-brands fa-twitter text-info me-1"></i> {{ __('cms.twitter_url') }}</label>
                            <input type="url" name="social_twitter" class="form-control-cms" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-premium"><i class="fa-brands fa-instagram text-danger me-1"></i> {{ __('cms.instagram_url') }}</label>
                            <input type="url" name="social_instagram" class="form-control-cms" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-premium"><i class="fa-brands fa-linkedin text-primary me-1"></i> {{ __('cms.linkedin_url') }}</label>
                            <input type="url" name="social_linkedin" class="form-control-cms" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label-premium"><i class="fa-brands fa-youtube text-danger me-1"></i> {{ __('cms.youtube_url') }}</label>
                            <input type="url" name="social_youtube" class="form-control-cms" value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                        </div>
                        
                        <div class="alert alert-light border mt-4 mb-0 small text-muted">
                            <i class="fa-solid fa-info-circle me-1"></i> {{ __('cms.menu_manager_note') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection