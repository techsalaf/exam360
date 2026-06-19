<link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">

<div class="settings-content">
    <form action="{{ route('admin.settings.branding.update') }}" method="POST">
        @csrf
        <input type="hidden" name="setting_group_key" value="branding_styling">

        <div class="setting-card">
            
            <div class="setting-header">
                <h3 class="setting-title">{{ __('branding.styling.title') }}</h3>
                <p class="setting-desc">{{ __('branding.styling.desc') }} <span class="text-warning fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> {{ __('branding.styling.warning') }}</span></p>
            </div>

            <!-- CSS Editor -->
            <div class="border rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    {{-- FIX 1: Using .branding-editor-icon-box for width/height --}}
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-primary branding-editor-icon-box">
                        {{-- FIX 1: Using .branding-editor-icon for font-size --}}
                        <i class="fa-brands fa-css3-alt branding-editor-icon"></i>
                    </div>
                    <div>
                        <label class="form-label-premium mb-0">{{ __('branding.styling.css_label') }}</label>
                        <span class="text-muted small d-block">{{ __('branding.styling.css_sub') }}</span>
                    </div>
                </div>
                
                {{-- FIX 1: Using .branding-code-textarea for font-size --}}
                <textarea name="custom_css" class="form-control-premium font-monospace bg-light branding-code-textarea" rows="10" placeholder="{{ __('branding.styling.css_placeholder') }}">{{ $settings['custom_css'] ?? '' }}</textarea>
            </div>

            <!-- JS Editor -->
            <div class="border rounded-3 p-4">
                <div class="d-flex align-items-center mb-3">
                    {{-- FIX 1: Using .branding-editor-icon-box for width/height --}}
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-warning branding-editor-icon-box">
                        {{-- FIX 1: Using .branding-editor-icon for font-size --}}
                        <i class="fa-brands fa-js branding-editor-icon"></i>
                    </div>
                    <div>
                        <label class="form-label-premium mb-0">{{ __('branding.styling.js_label') }}</label>
                        <span class="text-muted small d-block">{{ __('branding.styling.js_sub') }}</span>
                    </div>
                </div>

                {{-- FIX 1: Using .branding-code-textarea for font-size --}}
                <textarea name="custom_js" class="form-control-premium font-monospace bg-light branding-code-textarea" rows="10" placeholder="{{ __('branding.styling.js_placeholder') }}">{{ $settings['custom_js'] ?? '' }}</textarea>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-code me-2"></i> {{ __('branding.styling.save_code') }}
                </button>
            </div>

        </div>
    </form>
</div>