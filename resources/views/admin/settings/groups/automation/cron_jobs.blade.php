@php
    $cronEnabled = ($automationSettings['automation_cron_enabled'] ?? '1') === '1';
    $cronKey = $automationSettings['automation_cron_key'] ?? \Illuminate\Support\Str::random(20);
@endphp

<div class="tab-pane fade show active" id="cron-content" role="tabpanel">
    <div class="settings-content">
        <form action="{{ route('admin.settings.automation.update') }}" method="POST">
            @csrf

            <input type="hidden" name="setting_group_key" value="cron_jobs">

            <div class="setting-card">
                <div class="setting-header">
                    <h3 class="setting-title">{{ __('automation.cron.title') }}</h3>
                    <p class="setting-desc">{{ __('automation.cron.desc') }}</p>
                </div>

                <div class="border rounded-3 p-4 mb-4 bg-light">
                    <div class="d-flex align-items-start">
                        <i class="fa-solid fa-circle-info cron-info-icon"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">{{ __('automation.cron.info_title') }}</h6>
                            <p class="small text-muted mb-0">
                                {{ __('automation.cron.info_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border rounded-3 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="cron-icon-box me-3">
                            <i class="fa-solid fa-terminal cron-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('automation.cron.server_cmd') }}</h6>
                            <span class="text-muted small">{{ __('automation.cron.server_cmd_desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label-premium">{{ __('automation.cron.entry_label') }}</label>
                            <div class="cron-command-box">
                                <code id="cronCommand">* * * * * cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1</code>
                                <button type="button" class="btn btn-sm btn-outline-light ms-3 cron-copy-btn">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('automation.cron.token_label') }}</label>
                            <input type="text" 
                                   name="automation_cron_key" 
                                   class="form-control-premium" 
                                   value="{{ $cronKey }}"
                                   placeholder="{{ __('automation.cron.token_ph') }}">
                            <div class="form-text text-muted small mt-1">{{ __('automation.cron.token_help') }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="cron-switch-box">
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ __('automation.cron.enable_label') }}</span>
                                    <small class="text-muted">{{ __('automation.cron.enable_desc') }}</small>
                                </div>
                                <div class="form-check form-switch m-0 cron-switch">
                                    <input type="hidden" name="automation_cron_enabled" value="0">
                                    <input class="form-check-input cursor-pointer" 
                                           type="checkbox" 
                                           name="automation_cron_enabled" 
                                           value="1" 
                                           id="cronEnableSwitch" 
                                           {{ $cronEnabled ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script>
    window.cronTexts = {
        copied: "{{ __('automation.cron.copied') }}",
        failed: "{{ __('automation.cron.copy_fail') }}"
    };
</script>
<script src="{{ asset('assets/js/admin-cron-jobs.js') }}"></script>
@endpush