@extends('layouts.admin')

@section('title', __('notifications.templates.push_title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">
@endpush

@section('content')
<div class="sn-wrapper">
    <form action="{{ route('admin.settings.notifications.templates.update', 'push') }}" method="POST">
        @csrf

        <div class="d-flex justify-content-between align-items-center mb-5 mt-4 sn-page-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.settings.index') }}#pane-notifications" 
                   class="zi-action-btn me-3 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="zi-title">{{ __('notifications.templates.push_title') }}</h1>
                    <p class="zi-subtitle">{{ __('notifications.templates.push_subtitle') }}</p>
                </div>
            </div>
            <button type="submit" class="btn btn-success text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-none d-md-block">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

        <div class="row g-4">
            @php
                $triggers = [
                    [
                        'key' => 'signup', 
                        'icon' => 'fa-user-plus', 
                        'bg' => 'bg-soft-purple', 
                        'label' => __('notifications.general.triggers.signup'), 
                        'def_t' => __('notifications.templates.defaults.push_signup_t'), 
                        'def_b' => __('notifications.templates.defaults.push_signup_b'),
                        'vars' => ['{{name}}']
                    ],
                    [
                        'key' => 'exam', 
                        'icon' => 'fa-graduation-cap', 
                        'bg' => 'bg-soft-green', 
                        'label' => __('notifications.general.triggers.exam'), 
                        'def_t' => __('notifications.templates.defaults.push_exam_t'), 
                        'def_b' => __('notifications.templates.defaults.push_exam_b'),
                        'vars' => ['{{name}}', '{{score}}', '{{exam}}']
                    ],
                    [
                        'key' => 'payment', 
                        'icon' => 'fa-receipt', 
                        'bg' => 'bg-soft-orange', 
                        'label' => __('notifications.general.triggers.payment'), 
                        'def_t' => __('notifications.templates.defaults.push_pay_t'), 
                        'def_b' => __('notifications.templates.defaults.push_pay_b'),
                        'vars' => ['{{amount}}', '{{plan}}']
                    ]
                ];
            @endphp

            @foreach($triggers as $trigger)
            @php 
                $tKey = 'push_template_'.$trigger['key'].'_title';
                $bKey = 'push_template_'.$trigger['key'].'_body';
            @endphp

            <div class="col-xl-4 col-md-6 col-12">
                <div class="sn-card h-100 d-flex flex-column">
                    <div class="sn-card-head">
                        <div class="d-flex align-items-center">
                            <div class="zi-icon-box {{ $trigger['bg'] }} me-3">
                                <i class="fa-solid {{ $trigger['icon'] }}"></i>
                            </div>
                            <h6 class="fw-bold m-0 zi-text-main">{{ $trigger['label'] }}</h6>
                        </div>
                    </div>

                    <div class="sn-card-body flex-grow-1">
                        <div class="mb-3">
                            <label class="sn-label">{{ __('notifications.templates.fields.alert_title') }}</label>
                            <input type="text" 
                                   name="{{ $tKey }}" 
                                   class="sn-input fw-bold @error($tKey) is-invalid @enderror" 
                                   value="{{ $settings[$tKey] ?? $trigger['def_t'] }}"
                                   data-preview="title-{{ $trigger['key'] }}"
                                   maxlength="100">
                            @error($tKey) <div class="invalid-feedback small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="sn-label">{{ __('notifications.templates.fields.alert_body') }}</label>
                            <textarea name="{{ $bKey }}" 
                                      class="sn-input @error($bKey) is-invalid @enderror" 
                                      rows="3" 
                                      data-preview="body-{{ $trigger['key'] }}"
                                      maxlength="200">{{ $settings[$bKey] ?? $trigger['def_b'] }}</textarea>
                            @error($bKey) <div class="invalid-feedback small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Variables -->
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($trigger['vars'] as $var)
                                <span class="sn-badge sn-badge-interactive" data-insert="{{ $var }}">{{ $var }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="sn-card-foot d-block mt-auto">
                        <div class="sn-device-label">{{ __('notifications.templates.preview.label') }}</div>
                        
                        <div class="sn-mobile-preview">
                            <div class="sn-preview-icon">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div class="sn-preview-content">
                                <div class="sn-preview-header">
                                    <span class="sn-preview-app-name">{{ __('notifications.templates.preview.app_name') }}</span>
                                    <span class="sn-preview-time">{{ __('notifications.templates.preview.now') }}</span>
                                </div>
                                <div class="sn-preview-title" id="preview-title-{{ $trigger['key'] }}">
                                    {{ $settings[$tKey] ?? $trigger['def_t'] }}
                                </div>
                                <div class="sn-preview-body" id="preview-body-{{ $trigger['key'] }}">
                                    {{ $settings[$bKey] ?? $trigger['def_b'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
<script src="{{ asset('assets/js/notifications-push-templates.js') }}"></script>
@endpush