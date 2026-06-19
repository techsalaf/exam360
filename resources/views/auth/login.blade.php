<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login.title') }} - {{ config('app.name') }}</title>
    
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">

    @if(($settings['ext_recaptcha_enable'] ?? '0') === '1')
        @push('scripts')
            <script src="{{ asset('assets/js/recaptcha-loader.js') }}"></script>
        @endpush
    @endif
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
            
            <h1 class="brand-headline">{{ __('auth.login.headline') }}</h1>
            <p class="brand-sub">{{ __('auth.login.brand_sub') }}</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                    {{ __('auth.login.features.instant') }}
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    {{ __('auth.login.features.security') }}
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-chart-pie"></i></div>
                    {{ __('auth.login.features.analytics') }}
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form">
        <div class="form-wrapper">
            
            <h2 class="page-title">{{ __('auth.login.welcome') }}</h2>
            <p class="page-sub">{{ __('auth.login.welcome_sub') }}</p>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="input-group">

                    <label class="label">{{ __('auth.email_label') }}</label>
                    <input type="email" name="email" class="input" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="input-group">
                    <label class="label">{{ __('auth.password_label') }}</label>
                    <input type="password" name="password" class="input" placeholder="••••••••" required>
                </div>

                <div class="form-actions">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" class="checkbox-input"> 
                        {{ __('auth.remember_me') }}
                    </label>
                    <a href="{{ route('password.request') }}" class="link">{{ __('auth.forgot_password') }}</a>
                </div>

                @if(($settings['ext_recaptcha_enable'] ?? '0') === '1')
                    <div class="input-group mt-3">
                        <div class="g-recaptcha" data-sitekey="{{ $settings['ext_recaptcha_site_key'] }}"></div>
                    </div>
                @elseif(($settings['ext_custom_captcha_enable'] ?? '0') === '1')
                    <div class="input-group mt-3">
                        <label class="label">{{ __('auth.login.captcha_label') }}</label>
                        <div class="captcha-row">
                            <img src="{{ captcha_src('flat') }}" 
                                 id="captchaImage"
                                 alt="Captcha" 
                                 class="captcha-img"
                                 data-refresh-url="{{ captcha_src('flat') }}"
                                 title="{{ __('auth.login.captcha_help') }}">
                            
                            <input type="text" name="captcha" class="input captcha-input" placeholder="{{ __('auth.login.captcha_placeholder') }}" required>
                        </div>
                        <small class="captcha-hint">{{ __('auth.login.captcha_help') }}</small>
                    </div>
                @endif

                <button type="submit" class="btn-primary mt-3">{{ __('auth.login.submit') }}</button>
            </form>

            @php
                $googleEnabled = ($settings['social_google_enable'] ?? '0') === '1';
                $facebookEnabled = ($settings['social_facebook_enable'] ?? '0') === '1';
            @endphp

            @if($googleEnabled || $facebookEnabled)
                <div class="divider"><span>{{ __('auth.login.or_continue') }}</span></div>

                <div class="social-grid">
                    @if($googleEnabled)
                        <a href="{{ route('social.login', 'google') }}" class="btn-social">
                            <i class="fa-brands fa-google text-danger"></i> Google
                        </a>
                    @endif
                    
                    @if($facebookEnabled)
                        <a href="{{ route('social.login', 'facebook') }}" class="btn-social">
                            <i class="fa-brands fa-facebook text-primary"></i> Facebook
                        </a>
                    @endif
                </div>
            @endif

            <div class="auth-footer">
                {{ __('auth.login.no_account') }} 
                <a href="{{ route('register') }}" class="link">{{ __('auth.login.create_account') }}</a>
            </div>

            <div class="security-badge">
                <i class="fa-solid fa-lock"></i> {{ __('auth.login.secure_badge') }}
            </div>

        </div>
    </div>

</div>

<script src="{{ asset('assets/js/auth-login.js') }}"></script>
@stack('scripts')

</body>
</html>