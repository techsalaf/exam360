@extends('layouts.admin')

@section('title', __('notifications.templates.email_title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">
@endpush

@section('content')
<div class="sn-wrapper">
    @php
        $def_signup_sub = __('notifications.templates.defaults.signup_sub');
        $def_signup_body = __('notifications.templates.defaults.signup_body');
        
        $def_exam_sub = __('notifications.templates.defaults.exam_sub');
        $def_exam_body = __('notifications.templates.defaults.exam_body');
        
        $def_pay_sub = __('notifications.templates.defaults.pay_sub');
        $def_pay_body = __('notifications.templates.defaults.pay_body');
    @endphp

    <form action="{{ route('admin.settings.notifications.templates.update', 'email') }}" method="POST">
        @csrf
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5 mt-4 sn-page-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.settings.index') }}#pane-notifications" 
                   class="zi-action-btn me-3 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="zi-title">{{ __('notifications.templates.email_title') }}</h1>
                    <p class="zi-subtitle">{{ __('notifications.templates.email_subtitle') }}</p>
                </div>
            </div>
            <!-- Desktop Save Button (Hidden on Mobile via CSS) -->
            <button type="submit" class="btn btn-success text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-none d-md-block">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

        <div class="row g-4">
            
            <!-- Sidebar Navigation (Transforms to Top Menu on Mobile) -->
            <div class="col-lg-3 col-12">
                <div class="sn-card h-100 border-0 bg-transparent shadow-none">
                    <div class="sn-list-group" id="emailTabs" role="tablist">
                        <a class="sn-list-item active" data-bs-toggle="list" href="#tpl-signup" role="tab">
                            <i class="fa-solid fa-user-plus"></i> 
                            <span>{{ __('notifications.templates.tabs.signup') }}</span>
                        </a>
                        <a class="sn-list-item" data-bs-toggle="list" href="#tpl-exam" role="tab">
                            <i class="fa-solid fa-graduation-cap"></i> 
                            <span>{{ __('notifications.templates.tabs.exam') }}</span>
                        </a>
                        <a class="sn-list-item" data-bs-toggle="list" href="#tpl-payment" role="tab">
                            <i class="fa-solid fa-receipt"></i> 
                            <span>{{ __('notifications.templates.tabs.payment') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9 col-12">
                <div class="tab-content">
                    
                    <!-- Signup Tab -->
                    <div class="tab-pane fade show active" id="tpl-signup" role="tabpanel">
                        <div class="sn-card">
                            <div class="sn-card-head">
                                <h6 class="fw-bold m-0 zi-text-main">{{ __('notifications.templates.tabs.signup') }}</h6>
                                <span class="sn-badge bg-soft-green text-success border-success border-opacity-25">{{ __('notifications.active') }}</span>
                            </div>
                            
                            <div class="sn-card-body">
                                <div class="mb-4">
                                    <label class="sn-label">{{ __('notifications.templates.fields.subject') }}</label>
                                    <input type="text" name="email_template_signup_subject" 
                                           class="sn-input fw-bold" 
                                           value="{{ $settings['email_template_signup_subject'] ?? $def_signup_sub }}">
                                </div>

                                <div>
                                    <label class="sn-label">{{ __('notifications.templates.fields.html_body') }}</label>
                                    <textarea name="email_template_signup_body" 
                                              class="sn-input sn-font-mono" 
                                              rows="12">{{ $settings['email_template_signup_body'] ?? $def_signup_body }}</textarea>
                                </div>
                            </div>
                            
                            <div class="sn-card-foot">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="text-muted small me-2">{{ __('notifications.variables') }}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{name}}">@{{name}}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{email}}">@{{email}}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{link}}">@{{link}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Exam Tab -->
                    <div class="tab-pane fade" id="tpl-exam" role="tabpanel">
                        <div class="sn-card">
                            <div class="sn-card-head">
                                <h6 class="fw-bold m-0 zi-text-main">{{ __('notifications.templates.tabs.exam') }}</h6>
                                <span class="sn-badge bg-soft-green text-success border-success border-opacity-25">{{ __('notifications.active') }}</span>
                            </div>
                            
                            <div class="sn-card-body">
                                <div class="mb-4">
                                    <label class="sn-label">{{ __('notifications.templates.fields.subject') }}</label>
                                    <input type="text" name="email_template_exam_subject" 
                                           class="sn-input fw-bold" 
                                           value="{{ $settings['email_template_exam_subject'] ?? $def_exam_sub }}">
                                </div>

                                <div>
                                    <label class="sn-label">{{ __('notifications.templates.fields.html_body') }}</label>
                                    <textarea name="email_template_exam_body" 
                                              class="sn-input sn-font-mono" 
                                              rows="12">{{ $settings['email_template_exam_body'] ?? $def_exam_body }}</textarea>
                                </div>
                            </div>
                            
                            <div class="sn-card-foot">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="text-muted small me-2">{{ __('notifications.variables') }}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{name}}">@{{name}}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{score}}">@{{score}}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{exam_title}}">@{{exam_title}}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{link}}">@{{link}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Tab -->
                    <div class="tab-pane fade" id="tpl-payment" role="tabpanel">
                        <div class="sn-card">
                            <div class="sn-card-head">
                                <h6 class="fw-bold m-0 zi-text-main">{{ __('notifications.templates.tabs.payment') }}</h6>
                                <span class="sn-badge bg-soft-green text-success border-success border-opacity-25">{{ __('notifications.active') }}</span>
                            </div>
                            
                            <div class="sn-card-body">
                                <div class="mb-4">
                                    <label class="sn-label">{{ __('notifications.templates.fields.subject') }}</label>
                                    <input type="text" name="email_template_payment_subject" 
                                           class="sn-input fw-bold" 
                                           value="{{ $settings['email_template_payment_subject'] ?? $def_pay_sub }}">
                                </div>

                                <div>
                                    <label class="sn-label">{{ __('notifications.templates.fields.html_body') }}</label>
                                    <textarea name="email_template_payment_body" 
                                              class="sn-input sn-font-mono" 
                                              rows="12">{{ $settings['email_template_payment_body'] ?? $def_pay_body }}</textarea>
                                </div>
                            </div>
                            
                            <div class="sn-card-foot">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="text-muted small me-2">{{ __('notifications.variables') }}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{amount}}">@{{amount}}</span>
                                    <span class="sn-badge sn-badge-interactive" data-insert="@{{plan_name}}">@{{plan_name}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Sticky Mobile Footer -->
        <div class="sn-form-sticky-foot d-md-none">
            <button type="submit" class="btn btn-success text-white rounded-pill fw-bold shadow-sm">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
{{-- Envato Rule: No inline scripts. Load external file. --}}
<script src="{{ asset('assets/js/notifications-templates.js') }}"></script>
@endpush