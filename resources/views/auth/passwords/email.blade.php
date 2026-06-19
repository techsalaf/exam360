<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.email.title') }} - {{ config('app.name') }}</title>
    
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
            
            <h1 class="brand-headline">{{ __('auth.email.headline') }}</h1>
            <p class="brand-sub">{{ __('auth.email.subtitle') }}</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
                    {{ __('auth.email.features.secure_process') }}
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-envelope"></i></div>
                    {{ __('auth.email.features.instant_delivery') }}
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form">
        <div class="form-wrapper">
            
            <h2 class="page-title">{{ __('auth.email.page_title') }}</h2>
            <p class="page-sub">{{ __('auth.email.page_sub') }}</p>

            @if (session('status'))
                <div class="alert-error alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="input-group">
                    <label class="label">{{ __('auth.reset.email_label') }}</label>
                    <input type="email" name="email" class="input" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                </div>

                <button type="submit" class="btn-primary">{{ __('auth.email.submit') }}</button>
            </form>
            
            <div class="auth-footer mt-large">
                <a href="{{ route('login') }}" class="link">{{ __('auth.email.return') }}</a>
            </div>

            <div class="security-badge">
                <i class="fa-solid fa-lock"></i> {{ __('auth.email.secure_badge') }}
            </div>

        </div>
    </div>

</div>

</body>
</html>