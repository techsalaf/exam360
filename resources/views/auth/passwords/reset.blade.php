<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.reset.title') }} - {{ config('app.name') }}</title>
    
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">
    
    <div class="auth-brand">
        <div class="brand-content">
            <a href="/" class="logo-area">
                @if(!empty($settings['app_logo_dark']))
                    <img src="{{ Storage::url($settings['app_logo_dark']) }}" alt="{{ $settings['app_name'] ?? config('app.name') }}" class="auth-logo-img">
                @elseif(!empty($settings['app_logo_light']))
                    <img src="{{ Storage::url($settings['app_logo_light']) }}" alt="{{ $settings['app_name'] ?? config('app.name') }}" class="auth-logo-img">
                @else
                    <i class="fa-solid fa-layer-group"></i> {{ config('app.name') }}
                @endif
            </a>
            <div class="logo-tagline">{{ __('auth.login.tagline') }}</div>
            
            <h1 class="brand-headline">{{ __('auth.reset.headline') }}</h1>
            <p class="brand-sub">{{ __('auth.reset.brand_sub') }}</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    {{ __('auth.reset.features.secure_token') }}
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-key"></i></div>
                    {{ __('auth.reset.features.strong_pw') }}
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form">
        <div class="form-wrapper">
            
            <h2 class="page-title">{{ __('auth.reset.title') }}</h2>
            <p class="page-sub">{{ __('auth.reset.subtitle') }}</p>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group">
                    <label class="label">{{ __('auth.reset.email_label') }}</label>
                    <input type="email" name="email" class="input" placeholder="name@company.com" value="{{ $email ?? old('email') }}" required readonly>
                </div>
                
                <div class="input-group">
                    <label class="label">{{ __('auth.reset.password_label') }}</label>
                    <input type="password" name="password" class="input" placeholder="••••••••" required autofocus>
                    {{-- Using auth.register.password_hint for consistency in structure, though auth.reset.password_hint is a better option if it existed --}}
                    <div class="input-hint">{{ __('auth.register.password_hint') }}</div>
                </div>
                
                <div class="input-group">
                    <label class="label">{{ __('auth.reset.confirm_label') }}</label>
                    <input type="password" name="password_confirmation" class="input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary">{{ __('auth.reset.submit') }}</button>
            </form>
            
            <div class="security-badge">
                <i class="fa-solid fa-lock"></i> {{ __('auth.reset.secure_badge') }}
            </div>

        </div>
    </div>

</div>

</body>
</html>