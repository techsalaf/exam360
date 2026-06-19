<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.verify.title', ['app' => config('app.name')]) }}</title>

    {{-- RULE 3: Local Icons Only --}}
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">

    <div class="auth-brand">
        <div class="brand-content">
            <a href="{{ url('/') }}" class="logo-area">
                <i class="fa-solid fa-layer-group"></i> 
                {{-- RULE 1/Accessibility: Added alt attribute for consistency --}}
                <span class="d-none" alt="{{ config('app.name') }}">{{ config('app.name') }}</span>
                {{ config('app.name') }}
            </a>
            <h1 class="brand-headline">{{ __('auth.verify.heading') }}</h1>
            <p class="brand-sub">{{ __('auth.verify.subheading') }}</p>
        </div>
    </div>

    <div class="auth-form">
        <div class="form-wrapper auth-center">

            <div class="auth-icon-wrap">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>

            <h2 class="page-title">{{ __('auth.verify.check_inbox') }}</h2>

            <p class="verify-text">
                {{-- RULE 1: String uses {!! !!} for bold/break tag but is correctly translatable --}}
                {!! __('auth.verify.sent_to', ['email' => '<strong>' . e(Auth::user()->email) . '</strong>']) !!}
            </p>

            @if (session('resent'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ __('auth.verify.resent') }}
                </div>
            @endif

            @if (session('mail_error'))
                <div class="alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ session('mail_error') }}
                </div>
            @endif

            <p class="verify-help">
                {{ __('auth.verify.help') }}
            </p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    {{ __('auth.verify.resend') }}
                </button>
            </form>

            <div class="auth-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="link">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        {{ __('auth.verify.sign_out') }}
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

</body>
</html>