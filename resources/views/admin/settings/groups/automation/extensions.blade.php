@php
    $captchaEnabled = ($extensionSettings['ext_custom_captcha_enable'] ?? '0') === '1';
    $captchaLength = $extensionSettings['ext_custom_captcha_length'] ?? '6';

    $recaptchaEnabled = ($extensionSettings['ext_recaptcha_enable'] ?? '0') === '1';
    $recaptchaSiteKey = $extensionSettings['ext_recaptcha_site_key'] ?? '';
    $recaptchaSecret = $extensionSettings['ext_recaptcha_secret'] ?? '';

    $tawkEnabled = ($extensionSettings['ext_tawk_enable'] ?? '0') === '1';
    $tawkLink = $extensionSettings['ext_tawk_link'] ?? '';

    $googleEnabled = ($extensionSettings['social_google_enable'] ?? '0') === '1';
    $googleClientId = $extensionSettings['social_google_client_id'] ?? '';
    $googleSecret = $extensionSettings['social_google_secret'] ?? '';

    $fbEnabled = ($extensionSettings['social_facebook_enable'] ?? '0') === '1';
    $fbClientId = $extensionSettings['social_facebook_client_id'] ?? '';
    $fbSecret = $extensionSettings['social_facebook_secret'] ?? '';
@endphp

<div class="tab-pane fade show active" id="extensions-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.automation.update') }}" method="POST">
            @csrf

            <input type="hidden" name="setting_group_key" value="extensions">

            <div class="setting-card">
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('automation.ext.title') }}</h3>
                    <p class="setting-desc">{{ __('automation.ext.desc') }}</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="ext-icon-box">
                                        <i class="fa-brands fa-google text-danger fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark m-0">{{ __('automation.ext.google.title') }}</h6>
                                        <span class="text-muted small">{{ __('automation.ext.google.desc') }}</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch ext-switch">
                                    <input type="hidden" name="social_google_enable" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="social_google_enable" 
                                           value="1" 
                                           {{ $googleEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.google.client_id') }}</label>
                                    <input type="text" name="social_google_client_id" class="form-control-premium" value="{{ $googleClientId }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.google.client_secret') }}</label>
                                    <div class="position-relative">
                                        <input type="password" name="social_google_secret" class="form-control-premium pe-5" value="{{ $googleSecret }}">
                                        <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 toggle-secret ext-toggle-btn">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.google.callback') }}</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control-premium pe-5 font-monospace" value="{{ url('auth/google/callback') }}" readonly>
                                        <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 copy-btn ext-toggle-btn" title="{{ __('automation.copy_url') }}">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-muted mt-1 small">{{ __('automation.ext.google.help') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="ext-icon-box">
                                        <i class="fa-brands fa-facebook text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark m-0">{{ __('automation.ext.facebook.title') }}</h6>
                                        <span class="text-muted small">{{ __('automation.ext.facebook.desc') }}</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch ext-switch">
                                    <input type="hidden" name="social_facebook_enable" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="social_facebook_enable" 
                                           value="1" 
                                           {{ $fbEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.facebook.app_id') }}</label>
                                    <input type="text" name="social_facebook_client_id" class="form-control-premium" value="{{ $fbClientId }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.facebook.app_secret') }}</label>
                                    <div class="position-relative">
                                        <input type="password" name="social_facebook_secret" class="form-control-premium pe-5" value="{{ $fbSecret }}">
                                        <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 toggle-secret ext-toggle-btn">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.facebook.callback') }}</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control-premium pe-5 font-monospace" value="{{ url('auth/facebook/callback') }}" readonly>
                                        <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 copy-btn ext-toggle-btn" title="{{ __('automation.copy_url') }}">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-muted mt-1 small">{{ __('automation.ext.facebook.help') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="ext-icon-box dark-bg">
                                        <i class="fa-solid fa-shield-cat text-success fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark m-0">{{ __('automation.ext.captcha.title') }}</h6>
                                        <span class="text-muted small">{{ __('automation.ext.captcha.desc') }}</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch ext-switch">
                                    <input type="hidden" name="ext_custom_captcha_enable" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="ext_custom_captcha_enable" 
                                           value="1" 
                                           {{ $captchaEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.captcha.length') }}</label>
                                    <select name="ext_custom_captcha_length" class="form-control-premium form-select">
                                        <option value="4" {{ $captchaLength == '4' ? 'selected' : '' }}>4 {{ __('automation.ext.captcha.chars') }}</option>
                                        <option value="6" {{ $captchaLength == '6' ? 'selected' : '' }}>6 {{ __('automation.ext.captcha.chars') }}</option>
                                        <option value="8" {{ $captchaLength == '8' ? 'selected' : '' }}>8 {{ __('automation.ext.captcha.chars') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="ext-icon-box">
                                        <i class="fa-solid fa-arrows-rotate text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark m-0">{{ __('automation.ext.recaptcha.title') }}</h6>
                                        <span class="text-muted small">{{ __('automation.ext.recaptcha.desc') }}</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch ext-switch">
                                    <input type="hidden" name="ext_recaptcha_enable" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="ext_recaptcha_enable" 
                                           value="1" 
                                           {{ $recaptchaEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.recaptcha.site_key') }}</label>
                                    <input type="text" name="ext_recaptcha_site_key" class="form-control-premium mb-2" value="{{ $recaptchaSiteKey }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-premium">{{ __('automation.ext.recaptcha.secret_key') }}</label>
                                    <div class="position-relative">
                                        <input type="password" name="ext_recaptcha_secret" class="form-control-premium pe-5" value="{{ $recaptchaSecret }}">
                                        <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 toggle-secret ext-toggle-btn">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="ext-icon-box">
                                        <i class="fa-solid fa-comments text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark m-0">{{ __('automation.ext.tawk.title') }}</h6>
                                        <span class="text-muted small">{{ __('automation.ext.tawk.desc') }}</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch ext-switch">
                                    <input type="hidden" name="ext_tawk_enable" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="ext_tawk_enable" 
                                           value="1" 
                                           {{ $tawkEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label-premium">{{ __('automation.ext.tawk.link_label') }}</label>
                                    <input type="text" name="ext_tawk_link" class="form-control-premium" value="{{ $tawkLink }}" placeholder="https://tawk.to/chat/...">
                                    <div class="form-text text-muted mt-2 small">{{ __('automation.ext.tawk.link_help') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @includeIf('adsense::settings')
                    
                    @includeIf('otp-auth::settings')

                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa-solid fa-check me-2"></i> {{ __('automation.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/admin-extensions.js') }}"></script>
@endpush