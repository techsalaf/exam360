<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-hero.css') }}">
<section class="lms-hero-dark lms-animated-bg">
    <div class="lms-grid-overlay"></div>
    <div class="lms-glow-effect"></div>

    <div class="container position-relative w-100" style="z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center">
                    
                    <div class="lms-badge-pill mb-4">
                        <span class="lms-badge-tag">NEW</span>
                        <span class="lms-badge-text">{{ dynamicTransHelper($settings['hero_badge'] ?? 'Start Learning Now') }}</span>
                    </div>

                    <h1 class="lms-hero-title-dark mb-4">
                        {!! dynamicTransHelper($settings['hero_title'] ?? 'Start Learning Today') !!}
                    </h1>

                    <p class="lms-hero-desc-dark mb-5">
                        {{ dynamicTransHelper($settings['hero_subtitle'] ?? 'Experience AI-powered proctoring, instant grading, and detailed analytics.') }}
                    </p>

                    <!-- Buttons Group -->
                    <div class="lms-button-group-center">
                        <a href="{{ route('register') }}" class="btn-lms-glass-primary">
                            <div class="btn-icon-circle"><i class="fa-solid fa-graduation-cap"></i></div>
                            <span>{{ dynamicTransHelper($settings['hero_cta_text'] ?? 'Get Started Now') }}</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-lms-glass-secondary">
                            <i class="fa-solid fa-rocket me-2"></i>
                            <span>{{ dynamicTransHelper($settings['hero_cta2_text'] ?? 'Join the Community') }}</span>
                        </a>
                    </div>

                    <!-- Dynamic Trust Badges from CMS -->
                    <div class="lms-hero-features-center">
                        @for($i=1; $i<=3; $i++)
                            @php
                                $badgeText = dynamicTransHelper($settings['trust_badge_'.$i] ?? '');
                                $badgeIcon = $settings['trust_badge_'.$i.'_icon'] ?? 'fa-solid fa-circle-check';
                            @endphp

                            @if(!empty($badgeText))
                                <div class="lms-h-feat">
                                    <i class="{{ $badgeIcon }}"></i>
                                    <span>{{ $badgeText }}</span>
                                </div>
                            @endif
                        @endfor
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="floating-item item-1"><i class="fa-solid fa-sparkles"></i></div>
    <div class="floating-item item-2"><i class="fa-solid fa-star"></i></div>
</section>