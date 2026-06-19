<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ !empty($settings['auth_register_title']) ? $settings['auth_register_title'] : __('auth.register.title') }} - {{ config('app.name') }}</title>
    
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
    
    @if(($settings['ext_recaptcha_enable'] ?? '0') === '1')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body>

<div class="auth-wrapper">
    
    <div class="auth-brand">
        <div class="brand-content">
            <a href="/" class="logo-area">
                @if(!empty($settings['app_logo_dark']))
                    <img src="{{ Storage::url($settings['app_logo_dark']) }}" alt="{{ config('app.name') }}" class="auth-logo-img">
                @elseif(!empty($settings['app_logo_light']))
                    <img src="{{ Storage::url($settings['app_logo_light']) }}" alt="{{ config('app.name') }}" class="auth-logo-img">
                @else
                    <i class="fa-solid fa-layer-group"></i> {{ config('app.name') }}
                @endif
            </a>
            <div class="logo-tagline">{{ __('auth.register.tagline') }}</div>
            
            <h1 class="brand-headline">
                {{ !empty($settings['auth_register_headline']) ? $settings['auth_register_headline'] : __('auth.register.headline') }}
            </h1>
            <p class="brand-sub">
                {{ !empty($settings['auth_register_brand_desc']) ? $settings['auth_register_brand_desc'] : __('auth.register.brand_desc') }}
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-check"></i></div>
                    {{ !empty($settings['auth_register_feature_1']) ? $settings['auth_register_feature_1'] : __('auth.register.features.ai_tests') }}
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-check"></i></div>
                    {{ !empty($settings['auth_register_feature_2']) ? $settings['auth_register_feature_2'] : __('auth.register.features.global_cert') }}
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-check"></i></div>
                    {{ !empty($settings['auth_register_feature_3']) ? $settings['auth_register_feature_3'] : __('auth.register.features.auto_results') }}
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form">
        <div class="form-wrapper">
            
            <h2 class="page-title">
                {{ !empty($settings['auth_register_title']) ? $settings['auth_register_title'] : __('auth.register.title') }}
            </h2>
            <p class="page-sub">{{ __('auth.register.subtitle') }}</p>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="input-group">
                    <label class="label">{{ __('auth.name') }}</label>
                    <input type="text" name="name" class="input" placeholder="{{ __('auth.placeholder_name') }}" value="{{ old('name') }}" required>
                </div>

                <div class="input-group">
                    <label class="label">{{ __('auth.email_label') }}</label>
                    <input type="email" name="email" class="input" placeholder="{{ __('auth.placeholder_email') }}" value="{{ old('email') }}" required>
                </div>

                @if(($settings['ext_otp_enable'] ?? '0') === '1' && ($settings['ext_otp_provider'] ?? '') === 'sms')
                <div class="input-group">
                    <label class="label">{{ __('auth.phone_label') }}</label>
                    <input type="text" name="phone" class="input" placeholder="{{ __('auth.placeholder_phone') }}" value="{{ old('phone') }}" required>
                </div>
                @endif

                {{-- --- Dynamic Registration Fields --- --}}
                @if(!empty($settings['registration_custom_fields']))
                    @php $customFields = json_decode($settings['registration_custom_fields'], true); @endphp
                    @foreach($customFields as $field)
                        @php $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label'])); @endphp
                        <div class="input-group">
                            <label class="label">{{ $field['label'] }} @if($field['required'] == '1') <span class="text-danger">*</span> @endif</label>
                            
                            @if($field['type'] === 'text')
                                <input type="text" name="{{ $fieldKey }}" class="input" placeholder="{{ is_string($field['options']) ? $field['options'] : $field['label'] }}" value="{{ old($fieldKey) }}" @if($field['required'] == '1') required @endif>
                            
                            @elseif($field['type'] === 'select')
                                <select name="{{ $fieldKey }}" class="input" @if($field['required'] == '1') required @endif>
                                    <option value="">{{ __('auth.select_option') }}</option>
                                    @if(is_array($field['options']))
                                        @foreach($field['options'] as $opt)
                                            @if(!empty(trim($opt)))
                                                <option value="{{ trim($opt) }}" {{ old($fieldKey) == trim($opt) ? 'selected' : '' }}>{{ trim($opt) }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            
                            @elseif($field['type'] === 'attachment')
                                <input type="file" name="{{ $fieldKey }}" class="input" @if($field['required'] == '1') required @endif>
                            @endif
                        </div>
                    @endforeach
                @endif

                <div class="input-group">
                    <label class="label">{{ __('auth.password_label') }}</label>
                    <input type="password" name="password" id="password-field" class="input" placeholder="••••••••" required>
                    
                    <div class="password-strength-meter">
                        <div class="strength-segment" id="seg1"></div>
                        <div class="strength-segment" id="seg2"></div>
                        <div class="strength-segment" id="seg3"></div>
                        <div class="strength-segment" id="seg4"></div>
                    </div>
                    <div class="input-hint" id="password-strength-text">{{ __('auth.register.password_hint') }}</div>
                </div>

                <div class="input-group">
                    <label class="label">{{ __('auth.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="input" placeholder="••••••••" required>
                </div>

                @if(($settings['ext_recaptcha_enable'] ?? '0') === '1')
                    <div class="input-group">
                        <div class="g-recaptcha" data-sitekey="{{ $settings['ext_recaptcha_site_key'] }}"></div>
                    </div>
                @elseif(($settings['ext_custom_captcha_enable'] ?? '0') === '1')
                    <div class="input-group">
                        <label class="label">{{ __('auth.register.captcha_label') }}</label>
                        <div class="captcha-row">
                            <img src="{{ captcha_src('flat') }}" 
                                 id="captchaImage"
                                 alt="Captcha" 
                                 class="captcha-img"
                                 data-refresh-url="{{ captcha_src('flat') }}"
                                 onclick="this.src=this.dataset.refreshUrl + '?' + Math.random()">
                            
                            <input type="text" name="captcha" class="input captcha-input" placeholder="{{ __('auth.register.captcha_placeholder') }}" required>
                        </div>
                        <small class="captcha-hint">{{ __('auth.register.captcha_help') }}</small>
                    </div>
                @endif

                <button type="submit" class="btn-primary" style="margin-top: 15px;">{{ __('auth.register.submit') }}</button>
            </form>

            @php
                $googleEnabled = ($settings['social_google_enable'] ?? '0') === '1';
                $facebookEnabled = ($settings['social_facebook_enable'] ?? '0') === '1';
            @endphp

            @if($googleEnabled || $facebookEnabled)
                <div class="divider"><span>{{ __('auth.register.or_signup') }}</span></div>

                <div class="social-grid">
                    @if($googleEnabled)
                        <a href="{{ route('social.login', 'google') }}" class="btn-social">
                            <i class="fa-brands fa-google text-danger"></i> Google
                        </a>
                    @endif
                    
                    @if($facebookEnabled)
                        <a href="{{ route('social.login', 'facebook') }}" class="btn-social">
                            <i class="fa-brands fa-facebook" style="color: #1877F2;"></i> Facebook
                        </a>
                    @endif
                </div>
            @endif

            <div class="terms-text">
                {{ __('auth.register.terms_text') }}
                <a href="{{ $settings['security_policy_terms_url'] ?? '#' }}" target="_blank" class="link">{{ __('auth.register.terms') }}</a> 
                & 
                <a href="{{ $settings['security_policy_privacy_url'] ?? '#' }}" target="_blank" class="link">{{ __('auth.register.privacy') }}</a>.
            </div>

            <div class="auth-footer" style="margin-top: 30px;">
                {{ __('auth.register.already_account') }}
                <a href="{{ route('login') }}" class="link">{{ __('auth.register.signin') }}</a>
            </div>

        </div>
    </div>

</div>

<script>
    document.getElementById('password-field').addEventListener('input', function(e) {
        let val = e.target.value;
        let score = 0;
        if (val.length > 7) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const segments = ['seg1', 'seg2', 'seg3', 'seg4'];
        const colors = ['#ef4444', '#f59e0b', '#86efac', '#10b981'];

        segments.forEach((id, index) => {
            document.getElementById(id).style.backgroundColor = (index < score) ? colors[score-1] : '#e2e8f0';
        });
    });
</script>

</body>
</html>