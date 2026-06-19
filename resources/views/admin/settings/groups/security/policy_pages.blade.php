@php
    $termsUrl   = $securitySettings['security_policy_terms_url'] ?? url('terms');
    $privacyUrl = $securitySettings['security_policy_privacy_url'] ?? url('privacy');
    $showFooter = ($securitySettings['security_policy_show_footer'] ?? '1') === '1';
@endphp

<div class="tab-pane fade show active" id="policy-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.security.update') }}" method="POST">
            @csrf
            <input type="hidden" name="setting_group_key" value="policy">

            <div class="setting-card">
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('security.policy.title') }}</h3>
                    <p class="setting-desc">{{ __('security.policy.desc') }}</p>
                </div>

                <div class="border rounded-3 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="policy-icon-box me-3">
                            <i class="fa-solid fa-file-contract policy-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('security.policy.config_title') }}</h6>
                            <span class="text-muted small">{{ __('security.policy.config_desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('security.policy.terms_label') }}</label>
                            <input type="url" 
                                   name="security_policy_terms_url" 
                                   class="form-control-premium" 
                                   value="{{ $termsUrl }}" 
                                   placeholder="{{ __('security.policy.terms_ph') }}">
                            <div class="form-text text-muted small">{{ __('security.policy.terms_help') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('security.policy.privacy_label') }}</label>
                            <input type="url" 
                                   name="security_policy_privacy_url" 
                                   class="form-control-premium" 
                                   value="{{ $privacyUrl }}" 
                                   placeholder="{{ __('security.policy.privacy_ph') }}">
                            <div class="form-text text-muted small">{{ __('security.policy.privacy_help') }}</div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 bg-light">
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ __('security.policy.footer_label') }}</span>
                                    <small class="text-muted">{{ __('security.policy.footer_desc') }}</small>
                                </div>
                                <div class="form-check form-switch m-0 policy-switch">
                                    <input type="hidden" name="security_policy_show_footer" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="security_policy_show_footer" 
                                           value="1" 
                                           {{ $showFooter ? 'checked' : '' }}>
                                </div>
                            </div>
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