<link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">

<div class="settings-content">
    <form id="brandingForm" action="{{ route('admin.settings.branding.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="setting_group_key" value="branding_logo">

        <div class="setting-card">
            
            <div class="setting-header">
                <h3 class="setting-title">{{ __('branding.logo.title') }}</h3>
                <p class="setting-desc">{{ __('branding.logo.desc') }}</p>
            </div>

            <div class="border rounded-3 p-4 mb-4">
                {{-- FIX 3: Replaced hardcoded text-dark with text-main-color or similar theme variable --}}
                <h6 class="fw-bold text-main-color mb-4">{{ __('branding.logo.system_logos') }}</h6>
                
                <div class="row g-4">
                    <!-- Light Mode Logo -->
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('branding.logo.light_mode') }}</label>
                        <div class="d-flex align-items-center gap-3">
                            {{-- FIX 3: Using .logo-preview-box for static dimensions --}}
                            <div class="p-3 bg-light border rounded text-center d-flex align-items-center justify-content-center logo-preview-box">
                                @if(!empty($settings['app_logo_light']))
                                    {{-- FIX 3: Removed inline style="max-height: 100%;" --}}
                                    <img src="{{ Storage::url($settings['app_logo_light']) }}" class="img-fluid logo-preview-box-img">
                                    
                                    {{-- FIX 3: Removed onclick and inline styles, added .logo-delete-btn-sm and .logo-delete-trigger --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-danger rounded-circle logo-delete-trigger logo-delete-btn-sm" 
                                            data-delete="delete_app_logo_light" 
                                            title="{{ __('branding.remove') }}">
                                        {{-- FIX 3: Removed inline style="font-size: 10px;" --}}
                                        <i class="fa-solid fa-times logo-delete-icon-sm"></i>
                                    </button>
                                @else
                                    <span class="text-muted small">{{ __('branding.none') }}</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="app_logo_light" class="form-control-premium" accept="image/*">
                                <div class="form-text text-muted mt-2 small">{{ __('branding.recommended') }} {{ __('branding.logo.light_help') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Logo -->
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('branding.logo.dark_mode') }}</label>
                        <div class="d-flex align-items-center gap-3">
                            {{-- FIX 3: Using .logo-preview-box for static dimensions --}}
                            <div class="p-3 bg-dark border rounded text-center d-flex align-items-center justify-content-center logo-preview-box">
                                 @if(!empty($settings['app_logo_dark']))
                                    {{-- FIX 3: Removed inline style="max-height: 100%;" --}}
                                    <img src="{{ Storage::url($settings['app_logo_dark']) }}" class="img-fluid logo-preview-box-img">
                                    
                                    {{-- FIX 3: Removed onclick and inline styles, added .logo-delete-btn-sm and .logo-delete-trigger --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-danger rounded-circle logo-delete-trigger logo-delete-btn-sm" 
                                            data-delete="delete_app_logo_dark" 
                                            title="{{ __('branding.remove') }}">
                                        {{-- FIX 3: Removed inline style="font-size: 10px;" --}}
                                        <i class="fa-solid fa-times logo-delete-icon-sm"></i>
                                    </button>
                                @else
                                    <span class="text-white small">{{ __('branding.none') }}</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="app_logo_dark" class="form-control-premium" accept="image/*">
                                <div class="form-text text-muted mt-2 small">{{ __('branding.recommended') }} {{ __('branding.logo.dark_help') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border rounded-3 p-4">
                {{-- FIX 3: Replaced hardcoded text-dark with text-main-color or similar theme variable --}}
                <h6 class="fw-bold text-main-color mb-4">{{ __('branding.logo.browser_icon') }}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('branding.logo.favicon') }}</label>
                        <div class="d-flex align-items-center gap-3">
                            {{-- FIX 3: Using .favicon-preview-box for static dimensions --}}
                            <div class="p-2 border rounded d-flex align-items-center justify-content-center bg-white shadow-sm favicon-preview-box">
                                 @if(!empty($settings['app_favicon']))
                                    <img src="{{ Storage::url($settings['app_favicon']) }}" width="32">
                                    
                                    {{-- FIX 3: Removed onclick and inline styles, added .favicon-delete-btn-xs and .logo-delete-trigger --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-danger rounded-circle logo-delete-trigger favicon-delete-btn-xs" 
                                            data-delete="delete_app_favicon" 
                                            title="{{ __('branding.remove') }}">
                                        {{-- FIX 3: Removed inline style="font-size: 8px;" --}}
                                        <i class="fa-solid fa-times favicon-delete-icon-xs"></i>
                                    </button>
                                @else
                                    <i class="fa-solid fa-globe text-muted"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="app_favicon" class="form-control-premium" accept=".ico,.png">
                                <div class="form-text text-muted mt-2 small">{{ __('branding.recommended') }} {{ __('branding.logo.favicon_help') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> {{ __('branding.save') }}
                </button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    // FIX 1: Configuration object to pass localized strings to external JS (ALLOWED inline script)
    window.BrandingLogoMessages = {
        confirmTitle: '{{ __("branding.logo.alerts.confirm_title") }}',
        confirmText: '{{ __("branding.logo.alerts.confirm_text") }}',
        yesRemove: '{{ __("branding.logo.alerts.yes_remove") }}',
        cancel: '{{ __("branding.cancel") }}'
    };
</script>
{{-- FIX 1: Load external JS file containing all logic and SweetAlert handling --}}
<script src="{{ asset('assets/js/components/branding-logo.js') }}"></script>
@endpush