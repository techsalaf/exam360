<form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="sn-wrapper">
    @csrf

    <div class="sn-card">
        
        <div class="sn-card-header">
            <div>
                <h3 class="zi-page-title zi-title-sm">{{ __('notifications.email.title') }}</h3>
                <p class="zi-subtitle mb-0">{{ __('notifications.email.subtitle') }}</p>
            </div>
        </div>

        <div class="sn-card-body">

            <!-- Mail Driver Selection -->
            <div class="mb-4">
                <label class="sn-label mb-3">{{ __('notifications.email.driver_label') }}</label>
                <div class="zi-driver-grid">
                    
                    <label class="zi-driver-card {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'active' : '' }}">
                        <input type="radio" name="mail_driver" value="smtp" class="d-none" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'checked' : '' }}>
                        <span class="zi-radio-circle"></span>
                        <div class="zi-icon-box bg-soft-green">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        <div class="ms-2">
                            <span class="d-block fw-bold zi-text-main">{{ __('notifications.email.drivers.smtp') }}</span>
                            <span class="zi-text-muted small">{{ __('notifications.email.drivers.smtp_desc') }}</span>
                        </div>
                    </label>

                    <label class="zi-driver-card {{ ($settings['mail_driver'] ?? '') == 'sendmail' ? 'active' : '' }}">
                        <input type="radio" name="mail_driver" value="sendmail" class="d-none" {{ ($settings['mail_driver'] ?? '') == 'sendmail' ? 'checked' : '' }}>
                        <span class="zi-radio-circle"></span>
                        <div class="zi-icon-box bg-soft-green">
                            <i class="fa-brands fa-php"></i>
                        </div>
                        <div class="ms-2">
                            <span class="d-block fw-bold zi-text-main">{{ __('notifications.email.drivers.php') }}</span>
                            <span class="zi-text-muted small">{{ __('notifications.email.drivers.php_desc') }}</span>
                        </div>
                    </label>

                    <label class="zi-driver-card {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'active' : '' }}">
                        <input type="radio" name="mail_driver" value="mailgun" class="d-none" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'checked' : '' }}>
                        <span class="zi-radio-circle"></span>
                        <div class="zi-icon-box bg-soft-green">
                            <i class="fa-solid fa-wind"></i>
                        </div>
                        <div class="ms-2">
                            <span class="d-block fw-bold zi-text-main">{{ __('notifications.email.drivers.mailgun') }}</span>
                            <span class="zi-text-muted small">{{ __('notifications.email.drivers.mailgun_desc') }}</span>
                        </div>
                    </label>

                </div>
            </div>

            <!-- SMTP Panel -->
            {{-- Envato Fix: Use 'd-none' class instead of inline style="display:none" --}}
            <div id="smtp-panel" class="sn-card bg-subtle border mb-4 {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? '' : 'd-none' }}">
                <div class="sn-card-header bg-transparent border-bottom">
                    <h6 class="fw-bold zi-text-main m-0">{{ __('notifications.email.smtp.title') }}</h6>
                </div>
                <div class="sn-card-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="sn-label">{{ __('notifications.email.smtp.host') }}</label>
                            <input type="text" 
                                   name="mail_host" 
                                   class="form-control-premium" 
                                   value="{{ $settings['mail_host'] ?? '' }}" 
                                   placeholder="{{ __('notifications.email.smtp.host_placeholder') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="sn-label">{{ __('notifications.email.smtp.port') }}</label>
                            <input type="text" 
                                   name="mail_port" 
                                   class="form-control-premium" 
                                   value="{{ $settings['mail_port'] ?? '587' }}" 
                                   placeholder="{{ __('notifications.email.smtp.port_placeholder') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.smtp.username') }}</label>
                            <input type="text" name="mail_username" class="form-control-premium" value="{{ $settings['mail_username'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.smtp.password') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="password" name="mail_password" class="form-control-premium pe-5" value="{{ $settings['mail_password'] ?? '' }}">
                                <button type="button" class="zi-input-toggle" data-toggle="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.smtp.encryption') }}</label>
                            <select name="mail_encryption" class="form-control-premium form-select">
                                <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="null" {{ ($settings['mail_encryption'] ?? '') == 'null' ? 'selected' : '' }}>{{ __('notifications.email.smtp.none') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mailgun Panel -->
            {{-- Envato Fix: Use 'd-none' class instead of inline style="display:none" --}}
            <div id="mailgun-panel" class="sn-card bg-subtle border mb-4 {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? '' : 'd-none' }}">
                <div class="sn-card-header bg-transparent border-bottom">
                    <h6 class="fw-bold zi-text-main m-0">{{ __('notifications.email.mailgun.title') }}</h6>
                </div>
                <div class="sn-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.mailgun.domain') }}</label>
                            <input type="text" 
                                   name="mailgun_domain" 
                                   class="form-control-premium" 
                                   value="{{ $settings['mailgun_domain'] ?? '' }}" 
                                   placeholder="{{ __('notifications.email.mailgun.domain_placeholder') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.mailgun.secret') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="password" 
                                       name="mailgun_secret" 
                                       class="form-control-premium pe-5" 
                                       value="{{ $settings['mailgun_secret'] ?? '' }}" 
                                       placeholder="{{ __('notifications.email.mailgun.secret_placeholder') }}">
                                <button type="button" class="zi-input-toggle" data-toggle="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="sn-label">{{ __('notifications.email.mailgun.endpoint') }}</label>
                            <input type="text" 
                                   name="mailgun_endpoint" 
                                   class="form-control-premium" 
                                   value="{{ $settings['mailgun_endpoint'] ?? 'api.mailgun.net' }}" 
                                   placeholder="{{ __('notifications.email.mailgun.endpoint_placeholder') }}">
                            <small class="zi-text-muted mt-2 d-block">{{ __('notifications.email.mailgun.help') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sender Identity -->
            <div class="sn-card border mb-0">
                <div class="sn-card-header border-bottom">
                    <h6 class="fw-bold zi-text-main m-0">{{ __('notifications.email.identity.title') }}</h6>
                </div>
                <div class="sn-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.identity.name') }}</label>
                            <input type="text" name="mail_from_name" class="form-control-premium" value="{{ $settings['mail_from_name'] ?? 'System Admin' }}">
                            <small class="zi-text-muted mt-2 d-block">{{ __('notifications.email.identity.name_desc') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.email.identity.address') }}</label>
                            <input type="email" name="mail_from_address" class="form-control-premium" value="{{ $settings['mail_from_address'] ?? '' }}">
                            <small class="zi-text-muted mt-2 d-block">{{ __('notifications.email.identity.address_desc') }}</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="sn-card-foot d-flex justify-content-between">
             <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4" data-test-mail>
                <i class="fa-regular fa-paper-plane me-2"></i> {{ __('notifications.test_mail') }}
            </button>
            <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

    </div>
</form>

@push('scripts')
{{-- Configuration for JS logic --}}
<script type="application/json" id="mail-test-config">
{!! json_encode([
    'url' => route('admin.settings.notifications.test'),
    'csrf' => csrf_token(),
    'confirm' => __('notifications.email.alerts.confirm_test'),
    'loading' => '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> ' . __('notifications.email.alerts.sending'),
    'error' => __('notifications.email.alerts.error'),
]) !!}
</script>

<script src="{{ asset('assets/js/notifications-email-settings.js') }}"></script>
@endpush