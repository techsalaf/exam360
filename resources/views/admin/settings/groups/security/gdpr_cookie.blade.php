@php
    // Data passed from SettingsController
    $gdprEnabled = ($securitySettings['security_gdpr_enable'] ?? '0') === '1';
    $gdprMessage = $securitySettings['security_gdpr_message'] ?? __('security.gdpr.default_msg');
@endphp

<div class="tab-pane fade show active" id="gdpr-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.security.update') }}" method="POST">
            @csrf
            <input type="hidden" name="setting_group_key" value="gdpr">

            <div class="setting-card">
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('security.gdpr.title') }}</h3>
                    <p class="setting-desc">{{ __('security.gdpr.desc') }}</p>
                </div>

                <div class="border rounded-3 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="gdpr-icon-box me-3">
                            <i class="fa-solid fa-cookie-bite gdpr-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('security.gdpr.config_title') }}</h6>
                            <span class="text-muted small">{{ __('security.gdpr.config_desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 bg-light">
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ __('security.gdpr.enable_label') }}</span>
                                    <small class="text-muted">{{ __('security.gdpr.enable_desc') }}</small>
                                </div>
                                <div class="form-check form-switch m-0 gdpr-switch">
                                    <input type="hidden" name="security_gdpr_enable" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="security_gdpr_enable" 
                                           value="1" 
                                           {{ $gdprEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-premium">{{ __('security.gdpr.msg_label') }}</label>
                            <textarea name="security_gdpr_message" 
                                      class="form-control-premium" 
                                      rows="3"
                                      placeholder="{{ __('security.gdpr.msg_ph') }}">{{ $gdprMessage }}</textarea>
                            <div class="form-text text-muted small mt-1">{{ __('security.gdpr.msg_help') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa-solid fa-check me-2"></i> {{ __('security.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>