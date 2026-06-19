<form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="sn-wrapper">
    @csrf

    <div class="setting-card">

        <div class="setting-header mb-4">
            <h3 class="zi-page-title">{{ __('notifications.general.title') }}</h3>
            <p class="zi-subtitle">{{ __('notifications.general.subtitle') }}</p>
        </div>

        <div class="mb-4">
            <ul class="nav nav-pills nav-fill gap-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-logic" role="tab">
                        <i class="fa-solid fa-code-branch me-2"></i> {{ __('notifications.general.tabs.logic') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-sms" role="tab">
                        <i class="fa-solid fa-comment-sms me-2"></i> {{ __('notifications.general.tabs.sms') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-push" role="tab">
                        <i class="fa-solid fa-bell me-2"></i> {{ __('notifications.general.tabs.push') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-templates" role="tab">
                        <i class="fa-solid fa-file-lines me-2"></i> {{ __('notifications.general.tabs.templates') }}
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content pt-2">

            <!-- Logic & Triggers Tab -->
            <div class="tab-pane fade show active" id="tab-logic" role="tabpanel">

                <div class="zi-kpi-grid">
                    <!-- KPI Card 1: Email -->
                    <div class="sn-card kpi-card">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box bg-soft-green"><i class="fa-solid fa-envelope"></i></div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-0 zi-text-main">{{ __('notifications.general.kpi.email') }}</h6>
                                <small class="zi-text-muted">{{ __('notifications.general.kpi.global_switch') }}</small>
                            </div>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" name="system_email_enable" value="1" @checked(($settings['system_email_enable'] ?? '0') == '1')>
                        </div>
                    </div>

                    <!-- KPI Card 2: SMS -->
                    <div class="sn-card kpi-card">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box bg-soft-green"><i class="fa-solid fa-comment-dots"></i></div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-0 zi-text-main">{{ __('notifications.general.kpi.sms') }}</h6>
                                <small class="zi-text-muted">{{ __('notifications.general.kpi.global_switch') }}</small>
                            </div>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" name="system_sms_enable" value="1" @checked(($settings['system_sms_enable'] ?? '0') == '1')>
                        </div>
                    </div>

                    <!-- KPI Card 3: Push -->
                    <div class="sn-card kpi-card">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box bg-soft-green"><i class="fa-solid fa-bell"></i></div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-0 zi-text-main">{{ __('notifications.general.kpi.push') }}</h6>
                                <small class="zi-text-muted">{{ __('notifications.general.kpi.global_switch') }}</small>
                            </div>
                        </div>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" name="system_push_enable" value="1" @checked(($settings['system_push_enable'] ?? '0') == '1')>
                        </div>
                    </div>
                </div>

                <div class="sn-card overflow-hidden">
                    <div class="sn-card-header border-bottom">
                        <h6 class="fw-bold zi-text-main m-0">{{ __('notifications.general.triggers.title') }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table zi-logic-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 zi-th-event">{{ __('notifications.general.triggers.col_event') }}</th>
                                    <th class="text-center">{{ __('notifications.channels.email') }}</th>
                                    <th class="text-center">{{ __('notifications.channels.sms') }}</th>
                                    <th class="text-center">{{ __('notifications.channels.push') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4" data-label="{{ __('notifications.general.triggers.col_event') }}">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold zi-text-main">{{ __('notifications.general.triggers.signup') }}</span>
                                            <small class="zi-text-muted">{{ __('notifications.general.triggers.signup_desc') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('notifications.channels.email') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_signup_email" value="1" @checked(($settings['notify_signup_email'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('notifications.channels.sms') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_signup_sms" value="1" @checked(($settings['notify_signup_sms'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('notifications.channels.push') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_signup_push" value="1" @checked(($settings['notify_signup_push'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4" data-label="{{ __('notifications.general.triggers.col_event') }}">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold zi-text-main">{{ __('notifications.general.triggers.exam') }}</span>
                                            <small class="zi-text-muted">{{ __('notifications.general.triggers.exam_desc') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('notifications.channels.email') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_exam_email" value="1" @checked(($settings['notify_exam_email'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('notifications.channels.sms') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_exam_sms" value="1" @checked(($settings['notify_exam_sms'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="{{ __('notifications.channels.push') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_exam_push" value="1" @checked(($settings['notify_exam_push'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 border-bottom-0" data-label="{{ __('notifications.general.triggers.col_event') }}">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold zi-text-main">{{ __('notifications.general.triggers.payment') }}</span>
                                            <small class="zi-text-muted">{{ __('notifications.general.triggers.payment_desc') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center border-bottom-0" data-label="{{ __('notifications.channels.email') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_payment_email" value="1" @checked(($settings['notify_payment_email'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                    <td class="text-center border-bottom-0" data-label="{{ __('notifications.channels.sms') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_payment_sms" value="1" @checked(($settings['notify_payment_sms'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                    <td class="text-center border-bottom-0" data-label="{{ __('notifications.channels.push') }}">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="notify_payment_push" value="1" @checked(($settings['notify_payment_push'] ?? '0') == '1')>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SMS Gateway Tab -->
            <div class="tab-pane fade" id="tab-sms" role="tabpanel">
                <div class="sn-card p-4 mb-4">
                    <h6 class="fw-bold zi-text-main mb-3">{{ __('notifications.general.sms_gateways.provider') }}</h6>
                    <div class="zi-driver-grid">
                        @php $twilioActive = ($settings['sms_driver'] ?? 'twilio') == 'twilio'; @endphp
                        <label class="zi-driver-card {{ $twilioActive ? 'active' : '' }}">
                            <input type="radio" name="sms_driver" value="twilio" class="d-none" @checked($twilioActive)>
                            <span class="zi-radio-circle"></span>
                            <div class="zi-icon-box bg-soft-green">
                                <i class="fa-solid fa-comment-sms"></i>
                            </div>
                            <div class="ms-2">
                                <span class="d-block fw-bold zi-text-main">{{ __('notifications.general.sms_gateways.twilio') }}</span>
                                <span class="zi-text-muted small">{{ __('notifications.general.sms_gateways.standard') }}</span>
                            </div>
                        </label>

                        @php $vonageActive = ($settings['sms_driver'] ?? '') == 'vonage'; @endphp
                        <label class="zi-driver-card {{ $vonageActive ? 'active' : '' }}">
                            <input type="radio" name="sms_driver" value="vonage" class="d-none" @checked($vonageActive)>
                            <span class="zi-radio-circle"></span>
                            <div class="zi-icon-box bg-soft-green">
                                <i class="fa-solid fa-tower-cell"></i>
                            </div>
                            <div class="ms-2">
                                <span class="d-block fw-bold zi-text-main">{{ __('notifications.general.sms_gateways.vonage') }}</span>
                                <span class="zi-text-muted small">{{ __('notifications.general.sms_gateways.international') }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="sn-card p-4">
                    <h6 class="fw-bold zi-text-main mb-3">{{ __('notifications.general.sms_gateways.api_creds') }}</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="sn-label" id="lbl-sms-key">{{ __('notifications.general.sms_gateways.account_sid') }}</label>
                            <input type="text" name="sms_api_key" class="form-control-premium" value="{{ $settings['sms_api_key'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label" id="lbl-sms-secret">{{ __('notifications.general.sms_gateways.auth_token') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="password" name="sms_api_secret" class="form-control-premium pe-5" value="{{ $settings['sms_api_secret'] ?? '' }}">
                                <button type="button" class="zi-input-toggle" data-toggle="password-visibility">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label" id="lbl-sms-from">{{ __('notifications.general.sms_gateways.from') }}</label>
                            <input type="text" 
                                   name="sms_from" 
                                   class="form-control-premium" 
                                   value="{{ $settings['sms_from'] ?? '' }}" 
                                   placeholder="{{ __('notifications.general.sms_gateways.from_placeholder') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.general.sms_gateways.env') }}</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="smsSandbox" name="sms_sandbox_mode" value="1" @checked(($settings['sms_sandbox_mode'] ?? '0') == '1')>
                                <label class="form-check-label zi-text-muted" for="smsSandbox">{{ __('notifications.general.sms_gateways.sandbox') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Push Config Tab -->
            <div class="tab-pane fade" id="tab-push" role="tabpanel">
                <div class="sn-card p-4">
                    <h6 class="fw-bold zi-text-main mb-3">{{ __('notifications.general.firebase.title') }}</h6>
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="sn-label">{{ __('notifications.general.firebase.server_key') }}</label>
                            <input type="text" name="firebase_api_key" class="form-control-premium font-monospace" value="{{ $settings['firebase_api_key'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.general.firebase.project_id') }}</label>
                            <input type="text" name="firebase_project_id" class="form-control-premium" value="{{ $settings['firebase_project_id'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.general.firebase.app_id') }}</label>
                            <input type="text" name="firebase_app_id" class="form-control-premium" value="{{ $settings['firebase_app_id'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.general.firebase.sender_id') }}</label>
                            <input type="text" name="firebase_sender_id" class="form-control-premium" value="{{ $settings['firebase_sender_id'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="sn-label">{{ __('notifications.general.firebase.bucket') }}</label>
                            <input type="text" name="firebase_storage_bucket" class="form-control-premium" value="{{ $settings['firebase_storage_bucket'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Templates Tab -->
             <div class="tab-pane fade" id="tab-templates" role="tabpanel">
                 <div class="row g-4">
                     <div class="col-md-4">
                         <div class="sn-card p-4 h-100 text-center">
                             <div class="zi-icon-box zi-icon-box-lg bg-soft-green shadow-sm mb-3 mx-auto">
                                 <i class="fa-solid fa-envelope"></i>
                             </div>
                             <h6 class="fw-bold zi-text-main">{{ __('notifications.general.template_links.email') }}</h6>
                             <a href="{{ route('admin.settings.notifications.templates.edit', 'email') }}" class="btn btn-outline-success fw-bold w-100 shadow-sm rounded-pill mt-3">{{ __('notifications.configure') }}</a>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="sn-card p-4 h-100 text-center">
                             <div class="zi-icon-box zi-icon-box-lg bg-soft-green shadow-sm mb-3 mx-auto">
                                 <i class="fa-solid fa-comment-dots"></i>
                             </div>
                             <h6 class="fw-bold zi-text-main">{{ __('notifications.general.template_links.sms') }}</h6>
                             <a href="{{ route('admin.settings.notifications.templates.edit', 'sms') }}" class="btn btn-outline-success fw-bold w-100 shadow-sm rounded-pill mt-3">{{ __('notifications.configure') }}</a>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="sn-card p-4 h-100 text-center">
                             <div class="zi-icon-box zi-icon-box-lg bg-soft-green shadow-sm mb-3 mx-auto">
                                 <i class="fa-solid fa-bell"></i>
                             </div>
                             <h6 class="fw-bold zi-text-main">{{ __('notifications.general.template_links.push') }}</h6>
                             <a href="{{ route('admin.settings.notifications.templates.edit', 'push') }}" class="btn btn-outline-success fw-bold w-100 shadow-sm rounded-pill mt-3">{{ __('notifications.configure') }}</a>
                         </div>
                     </div>
                 </div>
             </div>

        </div>

        <div class="mt-4 pt-3 border-top text-end">
            <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

    </div>
</form>

@push('scripts')
<script type="application/json" id="sms-config">
{!! json_encode([
    'initialDriver' => $settings['sms_driver'] ?? 'twilio',
    'drivers' => [
        'twilio' => [
            'key' => __('notifications.general.sms_gateways.account_sid'),
            'secret' => __('notifications.general.sms_gateways.auth_token'),
            'from' => __('notifications.general.sms_gateways.from'),
        ],
        'vonage' => [
            'key' => __('notifications.general.sms_gateways.api_key'),
            'secret' => __('notifications.general.sms_gateways.api_secret'),
            'from' => __('notifications.general.sms_gateways.sender_id'),
        ],
    ]
]) !!}
</script>
<script src="{{ asset('assets/js/components/notifications-general.js') }}"></script>
@endpush