@push('styles')
@endpush

<div class="settings-content">
    <form action="{{ route('admin.settings.system.group.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="setting_group_key" value="general">

        <div class="setting-card">
            
            <div class="setting-header">
                <h3 class="setting-title">{{ __('system.general.title') }}</h3>
                <p class="setting-desc">{{ __('system.general.desc') }}</p>
            </div>

            <div class="border rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-primary setting-icon-box">
                        <i class="fa-solid fa-fingerprint setting-icon"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">{{ __('system.general.identity') }}</h6>
                        <span class="text-muted small">{{ __('system.general.identity_sub') }}</span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.general.app_name') }}</label>
                        <input type="text" name="app_name" class="form-control-premium" value="{{ $settings['app_name'] ?? config('app.name') }}" required>
                        <div class="form-text text-muted small">{{ __('system.general.app_name_sub') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.general.short_name') }}</label>
                        <input type="text" name="app_short_name" class="form-control-premium" value="{{ $settings['app_short_name'] ?? substr(config('app.name'), 0, 2) }}" required maxlength="10">
                        <div class="form-text text-muted small">{{ __('system.general.short_name_sub') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-premium">{{ __('system.general.app_title') }}</label>
                        <input type="text" name="app_title" class="form-control-premium" value="{{ $settings['app_title'] ?? '' }}" placeholder="{{ __('system.general.app_title_placeholder') }}">
                        <div class="form-text text-muted small">{{ __('system.general.app_title_sub') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-premium">{{ __('system.general.app_tagline') }}</label>
                        <input type="text" name="app_tagline" class="form-control-premium" value="{{ $settings['app_tagline'] ?? '' }}" placeholder="{{ __('system.general.app_tagline_placeholder') }}">
                        <div class="form-text text-muted small">{{ __('system.general.app_tagline_sub') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.general.support_email') }}</label>
                        <input type="email" name="support_email" class="form-control-premium" value="{{ $settings['support_email'] ?? '' }}" placeholder="{{ __('system.general.email_placeholder') }}">
                        <div class="form-text text-muted small">{{ __('system.general.support_email_sub') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.general.footer_text') }}</label>
                        <input type="text" name="footer_text" class="form-control-premium" value="{{ $settings['footer_text'] ?? '© ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.' }}">
                        <div class="form-text text-muted small">{{ __('system.general.footer_text_sub') }}</div>
                    </div>
                </div>
            </div>

            <div class="border rounded-3 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-danger setting-icon-box">
                        <i class="fa-solid fa-user-lock setting-icon"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">{{ __('system.general.admin_security') }}</h6>
                        <span class="text-muted small">{{ __('system.general.admin_security_sub') }}</span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label-premium">{{ __('system.general.current_pass') }}</label>
                        <div class="position-relative">
                            <input type="password" name="current_password" class="form-control-premium pe-5" autocomplete="off" placeholder="{{ __('system.general.password_placeholder') }}">
                            <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" data-toggle-password>
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">{{ __('system.general.new_pass') }}</label>
                        <div class="position-relative">
                            <input type="password" name="new_password" class="form-control-premium pe-5" autocomplete="new-password" placeholder="{{ __('system.general.password_placeholder') }}">
                            <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" data-toggle-password>
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">{{ __('system.general.confirm_pass') }}</label>
                        <div class="position-relative">
                            <input type="password" name="new_password_confirmation" class="form-control-premium pe-5" autocomplete="new-password" placeholder="{{ __('system.general.password_placeholder') }}">
                            <button type="button" class="btn border-0 bg-transparent text-muted position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" data-toggle-password>
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-text text-muted small"><i class="fa-solid fa-circle-info me-1"></i> {{ __('system.general.pass_note') }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-check me-2"></i> {{ __('system.save') }}
                </button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('assets/js/admin-settings.js') }}"></script>
@endpush