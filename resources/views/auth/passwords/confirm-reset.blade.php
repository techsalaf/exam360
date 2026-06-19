<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.success.title') }} - {{ config('app.name') }}</title>
    
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper auth-wrapper-center">
    
    <div class="success-box">
        <div class="success-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        
        <h2 class="page-title mb-small text-primary">{{ __('auth.success.title') }}</h2>
        <p class="page-sub mb-medium text-dark">
            {{ __('auth.success.subtitle') }}
        </p>
        
        <a href="{{ route('login') }}" class="btn-primary mt-medium">{{ __('auth.success.signin') }}</a>

        <div class="security-badge">
            <i class="fa-solid fa-lock"></i> {{ __('auth.success.secure_badge') }}
        </div>
    </div>

</div>

</body>
</html>