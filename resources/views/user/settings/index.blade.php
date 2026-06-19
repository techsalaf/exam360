@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/account.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.settings_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.settings_subtitle') }}</p>
    </div>
</div>

<form action="{{ route('user.settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="account-card">
        <div class="account-card-header">
            <h3 class="account-card-title">{{ __('frontend.notification_prefs') }}</h3>
        </div>
        
        <div class="setting-option">
            <div class="setting-info">
                {{-- FIX 4: Changed h5 to label for accessibility --}}
                <label for="email_notify" class="setting-title">{{ __('frontend.email_notify') }}</label>
                <p>{{ __('frontend.email_notify_desc') }}</p>
            </div>
            <div class="form-check form-switch">
                {{-- FIX 2: Removed inline style, added cursor-pointer class --}}
                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="email_notify" name="email_notify" 
                {{ ($preferences['email_notify'] ?? false) ? 'checked' : '' }}>
            </div>
        </div>

        <div class="setting-option">
            <div class="setting-info">
                {{-- FIX 4: Changed h5 to label for accessibility --}}
                <label for="app_alert" class="setting-title">{{ __('frontend.in_app_alerts') }}</label>
                <p>{{ __('frontend.in_app_alerts_desc') }}</p>
            </div>
            <div class="form-check form-switch">
                {{-- FIX 2: Removed inline style, added cursor-pointer class --}}
                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="app_alert" name="app_alert" 
                {{ ($preferences['app_alert'] ?? false) ? 'checked' : '' }}>
            </div>
        </div>
    </div>

    <div class="account-card mt-4">
        <div class="account-card-header">
            <h3 class="account-card-title">{{ __('frontend.regional_settings') }}</h3>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="timezone" class="form-label">{{ __('frontend.timezone') }}</label>
                    <select class="form-select" id="timezone" name="timezone">
                        @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ ($preferences['timezone'] ?? config('app.timezone')) == $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="language" class="form-label">{{ __('frontend.language') }}</label>
                    <select class="form-select" id="language" name="language">
                        @foreach($languages as $lang)
                            <option value="{{ $lang->code }}" {{ ($preferences['language'] ?? config('app.locale')) == $lang->code ? 'selected' : '' }}>
                                {{ $lang->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-3 text-end">
            <button type="submit" class="btn-save rounded-pill">{{ __('frontend.save_settings') }}</button>
        </div>
    </div>
</form>

@endsection