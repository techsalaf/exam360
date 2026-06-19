@extends('layouts.admin')

@section('title', __('users.title_notifications'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-usernotifications.css') }}">
@endpush

@section('content')

<div class="sn-wrapper">
    
    <div id="notification-config" data-search-url="{{ route('admin.users.notifications.search') }}" class="d-none"></div>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="zi-page-title">{{ __('users.title_notifications') }}</h3>
            <p class="zi-subtitle">{{ __('users.subtitle_notifications') }}</p>
        </div>
        <div>
            <!-- Added ID for JS handler -->
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4">
                <i class="fa-solid fa-arrow-left me-2"></i> {{ __('users.btn_back') }}
            </a>
        </div>
    </div>

    <form action="{{ route('admin.users.notifications.send') }}" method="POST" id="notificationForm">
        @csrf

        <div class="notification-grid">
            
            <!-- Input Column -->
            <div class="input-column">
                
                <!-- Step 1: Audience -->
                <div class="wizard-step active" id="step-1">
                    <div class="sn-card">
                        <div class="sn-card-header">
                            <h6 class="fw-bold zi-text-main m-0">{{ __('users.step_audience') }}</h6>
                        </div>
                        <div class="sn-card-body">
                            
                            <div class="zi-driver-grid mb-3">
                                <label class="zi-driver-card active" id="opt-all">
                                    <input type="radio" name="audience" value="all" checked class="d-none">
                                    <span class="zi-radio-circle"></span>
                                    <div class="ms-2">
                                        <span class="d-block fw-bold zi-text-main">{{ __('users.opt_all_users') }}</span>
                                        <span class="zi-text-muted small">{{ __('users.opt_all_users_desc') }}</span>
                                    </div>
                                </label>

                                <label class="zi-driver-card" id="opt-specific">
                                    <input type="radio" name="audience" value="specific" class="d-none">
                                    <span class="zi-radio-circle"></span>
                                    <div class="ms-2">
                                        <span class="d-block fw-bold zi-text-main">{{ __('users.opt_specific_users') }}</span>
                                        <span class="zi-text-muted small">{{ __('users.opt_specific_users_desc') }}</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Replaced inline style with class d-none -->
                            <div id="specific-users-panel" class="d-none">
                                <div class="row g-2 mb-3">
                                    <div class="col-8">
                                        <input type="text" id="user-search" class="form-control-premium" placeholder="{{ __('users.label_search') }}">
                                    </div>
                                    <div class="col-4">
                                        <select class="form-select form-control-premium" id="user-filter">
                                            <option value="active">{{ __('users.status_active') }}</option>
                                            <option value="banned">{{ __('users.status_banned') }}</option>
                                            <option value="unverified">{{ __('users.status_unverified') }}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="user-selection-panel">
                                    <div class="user-list-scroll" id="user-results">
                                        <div class="p-4 text-center zi-text-muted">
                                            <i class="fa-solid fa-magnifying-glass mb-2"></i><br>
                                            {{ __('users.msg_type_search') }}
                                        </div>
                                    </div>
                                    <div class="p-2 border-top bg-subtle text-end zi-text-muted small fw-bold">
                                        <span id="selected-count" class="text-success">0</span> {{ __('users.msg_selected') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Channels -->
                <div class="wizard-step active" id="step-2">
                    <div class="sn-card">
                        <div class="sn-card-header">
                            <h6 class="fw-bold zi-text-main m-0">{{ __('users.step_channels') }}</h6>
                        </div>
                        <div class="sn-card-body">
                            <div class="d-flex gap-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="channels[]" value="email" id="chan_email" {{ $emailEnabled ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold zi-text-main ms-2 pt-1" for="chan_email">{{ __('users.chan_email') }}</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="channels[]" value="sms" id="chan_sms" {{ (!$emailEnabled && $smsEnabled) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold zi-text-main ms-2 pt-1" for="chan_sms">{{ __('users.chan_sms') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Content -->
                <div class="wizard-step active" id="step-3">
                    
                    <!-- Email Editor -->
                    <div class="sn-card" id="email-group">
                        <div class="sn-card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold zi-text-main m-0">{{ __('users.step_content') }}</h6>
                            <span class="badge bg-soft-green text-success border border-success">HTML Supported</span>
                        </div>
                        <div class="sn-card-body">
                            <div class="mb-3">
                                <label class="sn-label">{{ __('users.label_subject') }} <span class="text-danger">*</span></label>
                                <input type="text" name="email_subject" class="form-control-premium" id="inp_subject" placeholder="Important Update..." value="{{ old('email_subject') }}">
                            </div>

                            <div class="mb-3">
                                <label class="sn-label">{{ __('users.label_body') }} <span class="text-danger">*</span></label>
                                <textarea name="email_body" class="form-control-premium sn-input-textarea" id="inp_body" rows="8" placeholder="Write your message here...">{{ old('email_body') }}</textarea>
                            </div>

                            <div>
                                <label class="sn-label mb-2">{{ __('users.label_variables') }}</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <!-- Replaced onclick with data attributes and classes -->
                                    <span class="sn-badge btn-insert-var" data-var="@{{name}}">@{{name}}</span>
                                    <span class="sn-badge btn-insert-var" data-var="@{{email}}">@{{email}}</span>
                                    <span class="sn-badge btn-insert-var" data-var="@{{site_name}}">@{{site_name}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMS Editor - Replaced inline style with class d-none -->
                    <div class="sn-card d-none" id="sms-group">
                        <div class="sn-card-header">
                            <h6 class="fw-bold zi-text-main m-0">{{ __('users.label_sms_content') }}</h6>
                        </div>
                        <div class="sn-card-body">
                            <div class="mb-2">
                                <textarea name="sms_message" class="form-control-premium sn-font-mono" id="inp_sms" rows="4" maxlength="160" placeholder="Type SMS message...">{{ old('sms_message') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between small zi-text-muted">
                                <span>1 Segment</span>
                                <span id="sms-char-count">0 / 160</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Column -->
            <div class="preview-column desktop-only">
                <div class="device-frame">
                    <div class="device-notch"></div>
                    <div class="device-screen">
                        
                        <!-- Email Preview -->
                        <div id="preview-email-container">
                            <div class="email-app-bar">
                                <i class="fa-solid fa-chevron-left text-primary fs-5"></i>
                                <span class="fw-bold text-primary">Mail</span>
                                <i class="fa-regular fa-pen-to-square text-primary fs-5"></i>
                            </div>
                            
                            <div class="email-meta-area">
                                <div class="email-header-row">
                                    <span class="email-sender-name">System Admin</span>
                                    <span class="email-timestamp">Now</span>
                                </div>
                                <div class="email-subject-line" id="prev-subject">No Subject</div>
                                <div class="email-to-line">To: User Name</div>
                            </div>

                            <div class="email-body-content" id="prev-body">Start typing to see the preview...</div>
                        </div>

                        <!-- SMS Preview - Replaced inline style with CSS class -->
                        <div id="preview-sms-container" class="preview-sms-wrapper d-none">
                            <div class="sms-app-bar">
                                <i class="fa-solid fa-chevron-left text-primary fs-5"></i>
                                <div class="sms-header-center">
                                    <div class="sms-avatar-circle">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <!-- Replaced inline style with class -->
                                    <span class="fw-bold text-dark sms-sender-label">System</span>
                                </div>
                                <i class="fa-solid fa-video text-primary"></i>
                            </div>
                            
                            <div class="sms-container-body">
                                <div class="d-flex flex-column align-items-end w-100">
                                    <div class="sms-bubble" id="prev-sms">Message...</div>
                                    <!-- Replaced inline style with class -->
                                    <small class="text-muted sms-status-label">Delivered</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Sticky Footer -->
        <div class="action-bar">
            <!-- Replaced onclick with ID -->
            <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4" id="btn-cancel">
                {{ __('users.btn_cancel') }}
            </button>
            <button type="button" class="btn btn-success fw-bold rounded-pill px-4" id="btn-submit">
                <i class="fa-solid fa-paper-plane me-2"></i> {{ __('users.btn_send_now') }}
            </button>
        </div>
    </form>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark">{{ __('users.confirm_send_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">{{ __('users.confirm_send_text') }}</p>
                <div class="bg-light p-3 rounded-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-uppercase fw-bold text-muted">Audience</span>
                        <span class="fw-bold text-dark" id="conf-audience">All Users</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-uppercase fw-bold text-muted">Channels</span>
                        <span class="fw-bold text-dark" id="conf-channels">Email</span>
                    </div>
                </div>
                <div class="d-flex align-items-center text-warning small">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> 
                    <span>{{ __('users.confirm_text') }}</span>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('users.btn_cancel') }}</button>
                <!-- Replaced onclick with ID -->
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold" id="btn-confirm-final">
                    {{ __('users.confirm_yes') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-usernotifications.js') }}"></script>
@endpush