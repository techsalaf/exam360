@extends('layouts.admin')

@section('title', __('notifications.templates.sms_title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">
@endpush

@section('content')
<div class="sn-wrapper">
    @php
        $def_signup  = __('notifications.templates.defaults.sms_signup');
        $def_exam    = __('notifications.templates.defaults.sms_exam');
        $def_payment = __('notifications.templates.defaults.sms_pay');
    @endphp

    <form action="{{ route('admin.settings.notifications.templates.update', 'sms') }}" method="POST">
        @csrf

        <div class="d-flex justify-content-between align-items-center mb-5 mt-4 sn-page-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.settings.index') }}#pane-notifications" 
                   class="zi-action-btn me-3 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="zi-title">{{ __('notifications.templates.sms_title') }}</h1>
                    <p class="zi-subtitle">{{ __('notifications.templates.sms_subtitle') }}</p>
                </div>
            </div>
            <button type="submit" class="btn btn-success text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-none d-md-block">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

        <div class="row g-4">
            
            <!-- Signup SMS Card -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="sn-card h-100">
                    <div class="sn-card-head">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box bg-soft-purple me-3">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <h6 class="fw-bold m-0 zi-text-main">{{ __('notifications.templates.tabs.signup') }}</h6>
                        </div>
                    </div>

                    <div class="sn-card-body d-flex flex-column">
                        <label class="sn-label">{{ __('notifications.templates.fields.content') }}</label>
                        <textarea name="sms_template_signup" 
                                  class="sn-input sn-input-textarea form-control" 
                                  data-countable>{{ $settings['sms_template_signup'] ?? $def_signup }}</textarea>
                    </div>

                    <div class="sn-card-foot mt-auto">
                        <div class="d-flex gap-2">
                            <span class="sn-badge interactive" data-insert="@{{name}}">@{{name}}</span>
                            <span class="sn-badge interactive" data-insert="@{{link}}">@{{link}}</span>
                        </div>
                        <span class="sn-char-count fw-bold text-muted sn-char-small">0/160</span>
                    </div>
                </div>
            </div>

            <!-- Exam SMS Card -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="sn-card h-100">
                    <div class="sn-card-head">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box bg-soft-green me-3">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <h6 class="fw-bold m-0 zi-text-main">{{ __('notifications.templates.tabs.exam') }}</h6>
                        </div>
                    </div>

                    <div class="sn-card-body d-flex flex-column">
                        <label class="sn-label">{{ __('notifications.templates.fields.content') }}</label>
                        <textarea name="sms_template_exam" 
                                  class="sn-input sn-input-textarea form-control" 
                                  data-countable>{{ $settings['sms_template_exam'] ?? $def_exam }}</textarea>
                    </div>

                    <div class="sn-card-foot mt-auto">
                        <div class="d-flex gap-2">
                            <span class="sn-badge interactive" data-insert="@{{score}}">@{{score}}</span>
                            <span class="sn-badge interactive" data-insert="@{{exam}}">@{{exam}}</span>
                        </div>
                        <span class="sn-char-count fw-bold text-muted sn-char-small">0/160</span>
                    </div>
                </div>
            </div>

            <!-- Payment SMS Card -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="sn-card h-100">
                    <div class="sn-card-head">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box bg-soft-orange me-3">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <h6 class="fw-bold m-0 zi-text-main">{{ __('notifications.templates.tabs.payment') }}</h6>
                        </div>
                    </div>

                    <div class="sn-card-body d-flex flex-column">
                        <label class="sn-label">{{ __('notifications.templates.fields.content') }}</label>
                        <textarea name="sms_template_payment" 
                                  class="sn-input sn-input-textarea form-control" 
                                  data-countable>{{ $settings['sms_template_payment'] ?? $def_payment }}</textarea>
                    </div>

                    <div class="sn-card-foot mt-auto">
                        <div class="d-flex gap-2">
                            <span class="sn-badge interactive" data-insert="@{{amount}}">@{{amount}}</span>
                            <span class="sn-badge interactive" data-insert="@{{plan}}">@{{plan}}</span>
                        </div>
                        <span class="sn-char-count fw-bold text-muted sn-char-small">0/160</span>
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
{{-- Envato Rule: External JS only --}}
<script src="{{ asset('assets/js/notifications-sms-templates.js') }}"></script>
@endpush