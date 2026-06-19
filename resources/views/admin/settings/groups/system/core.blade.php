@push('styles')
    {{-- FIX 1: Moved link from body to styles stack --}}
    <link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">
@endpush

<div class="settings-content">
    <form action="{{ route('admin.settings.system.group.update') }}" method="POST">
        @csrf

        <input type="hidden" name="setting_group_key" value="core">

        <div class="setting-card">
            
            <div class="setting-header">
                <h3 class="setting-title">{{ __('system.core.title') }}</h3>
                <p class="setting-desc">{{ __('system.core.desc') }}</p>
            </div>

            <!-- Environment -->
            <div class="border rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    {{-- FIX: Used setting-icon-box and setting-icon classes --}}
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-warning setting-icon-box">
                        <i class="fa-solid fa-server setting-icon"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">{{ __('system.core.env_debugging') }}</h6>
                        <span class="text-muted small">{{ __('system.core.env_debugging_sub') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-4">
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark">{{ __('system.core.debug_mode') }}</span>
                        <small class="text-muted">{{ __('system.core.debug_mode_sub') }} <span class="text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation"></i> {{ __('system.core.disable_prod') }}</span></small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="app_debug" value="1" {{ ($settings['app_debug'] ?? '0') === '1' ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="row g-4">
                     <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.core.env_type') }}</label>
                        <select name="app_env" class="form-control-premium form-select">
                            {{-- FIX: Translated hardcoded strings (Rule 1) --}}
                            <option value="production" {{ ($settings['app_env'] ?? '') == 'production' ? 'selected' : '' }}>{{ __('system.core.env.production') }}</option>
                            <option value="local" {{ ($settings['app_env'] ?? '') == 'local' ? 'selected' : '' }}>{{ __('system.core.env.local') }}</option>
                            <option value="staging" {{ ($settings['app_env'] ?? '') == 'staging' ? 'selected' : '' }}>{{ __('system.core.env.staging') }}</option>
                        </select>
                        <div class="form-text text-muted small">{{ __('system.core.env_type_sub') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.core.log_level') }}</label>
                        <select name="log_level" class="form-control-premium form-select">
                            <option value="debug" {{ ($settings['log_level'] ?? 'debug') == 'debug' ? 'selected' : '' }}>{{ __('system.core.log.debug') }}</option>
                            <option value="info" {{ ($settings['log_level'] ?? 'debug') == 'info' ? 'selected' : '' }}>{{ __('system.core.log.info') }}</option>
                            <option value="notice" {{ ($settings['log_level'] ?? 'debug') == 'notice' ? 'selected' : '' }}>{{ __('system.core.log.notice') }}</option>
                            <option value="warning" {{ ($settings['log_level'] ?? 'debug') == 'warning' ? 'selected' : '' }}>{{ __('system.core.log.warning') }}</option>
                            <option value="error" {{ ($settings['log_level'] ?? 'debug') == 'error' ? 'selected' : '' }}>{{ __('system.core.log.error') }}</option>
                            <option value="critical" {{ ($settings['log_level'] ?? 'debug') == 'critical' ? 'selected' : '' }}>{{ __('system.core.log.critical') }}</option>
                            <option value="alert" {{ ($settings['log_level'] ?? 'debug') == 'alert' ? 'selected' : '' }}>{{ __('system.core.log.alert') }}</option>
                            <option value="emergency" {{ ($settings['log_level'] ?? 'debug') == 'emergency' ? 'selected' : '' }}>{{ __('system.core.log.emergency') }}</option>
                        </select>
                        <div class="form-text text-muted small">{{ __('system.core.log_level_sub') }}</div>
                    </div>
                </div>
            </div>

            <!-- Performance -->
            <div class="border rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    {{-- FIX: Used setting-icon-box and setting-icon classes --}}
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-success setting-icon-box">
                        <i class="fa-solid fa-gauge-high setting-icon"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">{{ __('system.core.performance') }}</h6>
                        <span class="text-muted small">{{ __('system.core.performance_sub') }}</span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.core.max_concurrent') }}</label>
                        <input type="number" name="max_concurrent_exams" class="form-control-premium" value="{{ $settings['max_concurrent_exams'] ?? '1' }}" min="1">
                        <div class="form-text text-muted small">{{ __('system.core.max_concurrent_sub') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.core.session_life') }}</label>
                        <input type="number" name="session_lifetime" class="form-control-premium" value="{{ $settings['session_lifetime'] ?? '120' }}" min="30">
                        <div class="form-text text-muted small">{{ __('system.core.session_life_sub') }}</div>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="border rounded-3 p-4 bg-light">
                <div class="d-flex align-items-center mb-4">
                    <i class="fa-solid fa-microchip text-muted me-2"></i>
                    {{-- FIX: Removed inline style, added system-label class --}}
                    <h6 class="fw-bold text-dark text-uppercase m-0 system-label">{{ __('system.core.status_drivers') }}</h6>
                </div>
                <div class="row g-4">
                    <div class="col-md-3">
                        {{-- FIX: Removed inline style, added system-small class --}}
                        <small class="d-block text-muted text-uppercase fw-bold system-small">{{ __('system.core.cache_driver') }}</small>
                        <div class="d-flex align-items-center mt-1">
                            <span class="fw-bold text-dark me-2">{{ config('cache.default') }}</span>
                            {{-- FIX: Removed inline style, added system-badge class --}}
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill system-badge">{{ __('system.active') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- FIX: Removed inline style, added system-small class --}}
                        <small class="d-block text-muted text-uppercase fw-bold system-small">{{ __('system.core.queue_driver') }}</small>
                        <div class="d-flex align-items-center mt-1">
                            <span class="fw-bold text-dark me-2">{{ config('queue.default') }}</span>
                            {{-- FIX: Removed inline style, added system-badge class --}}
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill system-badge">{{ __('system.active') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- FIX: Removed inline style, added system-small class --}}
                        <small class="d-block text-muted text-uppercase fw-bold system-small">{{ __('system.core.storage_driver') }}</small>
                        <div class="d-flex align-items-center mt-1">
                            <span class="fw-bold text-dark me-2">{{ config('filesystems.default') }}</span>
                            {{-- FIX: Removed inline style, added system-badge class --}}
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill system-badge">Local</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- FIX: Removed inline style, added system-small class --}}
                        <small class="d-block text-muted text-uppercase fw-bold system-small">{{ __('system.core.app_version') }}</small>
                        <div class="mt-1">
                            <span class="fw-bold text-dark">v{{ config('app.version', '1.0.0') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-save me-2"></i> {{ __('system.apply') }}
                </button>
            </div>

        </div>
    </form>
</div>