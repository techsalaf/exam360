<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.sent.title') }} - {{ config('app.name') }}</title>
    
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper auth-wrapper-center">
    
    <div class="success-box">
        <div class="success-icon">
            <i class="fa-solid fa-envelope-circle-check"></i>
        </div>
        
        <h2 class="page-title mb-small">{{ __('auth.sent.title') }}</h2>
        <p class="page-sub mb-medium text-dark">
            {{ __('auth.sent.subtitle') }}
        </p>

        <p class="auth-footer mt-none">
            {{-- This links to the form to resend the email (password.request) --}}
            {{ __('auth.sent.help') }} <a href="{{ route('password.request') }}" class="link">{{ __('auth.sent.try_again') }}</a>.
        </p>
        
        <a href="{{ route('login') }}" class="btn-primary mt-medium">{{ __('auth.sent.return') }}</a>

        <div class="security-badge">
            <i class="fa-solid fa-lock"></i> {{ __('auth.sent.secure_badge') }}
        </div>
    </div>

</div>

</body>
</html>