@php
    $settings = $settings ?? $gs ?? [];
    
    $switcherEnabled = false;
    $settingValue = $settings['localization_front_switcher'] ?? null;

    if ($settingValue === null) {
        $settingValue = \App\Models\SystemSetting::where('key', 'localization_front_switcher')->value('value');
    }

    $switcherEnabled = filter_var($settingValue, FILTER_VALIDATE_BOOLEAN) || $settingValue == 1;

    $frontLanguages = \App\Models\Language::where('is_active_front', true)
        ->orderBy('is_default', 'desc')
        ->get();

    $showLangSwitcher = ($switcherEnabled && $frontLanguages->count() > 1);

    $currentLocale = app()->getLocale();
    $currentLang = $frontLanguages->where('code', $currentLocale)->first() ?? $frontLanguages->first();

    $dashboardUrl = route('user.dashboard');
    
    if (auth()->check()) {
        $user = auth()->user();
        
        $isAdmin = $user->id === 1 
                   || $user->role === 'admin' 
                   || $user->hasRole('Super Admin')
                   || $user->can('view_exams') 
                   || $user->can('access_settings');

        if ($isAdmin) {
            if (Route::has('admin.dashboard')) {
                $dashboardUrl = route('admin.dashboard');
            } elseif (Route::has('dashboard')) {
                $dashboardUrl = route('dashboard');
            }
        }
    }
@endphp

<nav class="navbar">
    <div class="container d-flex align-items-center justify-between" style="width: 100%;">
        
        <a href="{{ route('frontend.home') }}" class="nav-brand d-flex align-items-center text-decoration-none" style="gap: 10px;">
            @if(!empty($settings['app_logo_light']))
                <img src="{{ Storage::url($settings['app_logo_light']) }}" 
                     alt="{{ $settings['app_name'] ?? 'ZiExam AI' }}" 
                     style="height: {{ $settings['header_logo_height'] ?? '34' }}px;">
            @else
                <i class="fa-solid fa-layer-group text-primary fa-xl"></i>
                <div class="d-flex flex-column" style="line-height: 1;">
                    <span class="fs-5 fw-bold text-primary" style="letter-spacing: -0.5px;">Zi</span>
                    <span class="fs-5 fw-bold text-dark" style="letter-spacing: -0.5px;">Exam-ai</span>
                </div>
            @endif
        </a>

        <div class="nav-center d-none d-lg-flex align-items-center gap-4 mx-auto">
            @if(isset($headerMenu) && !empty($headerMenu->items) && count($headerMenu->items) > 0)
                @foreach($headerMenu->items as $item)
                    <a href="{{ $item['url'] }}" class="nav-link text-dark fw-bold {{ request()->is(ltrim($item['url'], '/')) ? 'text-primary' : '' }}" style="font-size: 0.95rem;">
                        @jsonLang($item['label'])
                    </a>
                @endforeach
            @else
                <a href="{{ route('frontend.home') }}" class="nav-link text-dark fw-bold" style="font-size: 0.95rem;">{{ __('frontend.home_link') }}</a>
                <a href="{{ route('frontend.home') }}#features" class="nav-link text-dark fw-bold" style="font-size: 0.95rem;">{{ __('frontend.features_link') }}</a>
                <a href="{{ route('frontend.home') }}#pricing" class="nav-link text-dark fw-bold" style="font-size: 0.95rem;">{{ __('frontend.pricing_link') }}</a>
            @endif
        </div>

        <div class="nav-right d-flex gap-3 align-items-center">
            
            @if($showLangSwitcher)
            <div class="dropdown d-none d-lg-block">
                <button class="circle-flag-trigger" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        title="{{ __('frontend.select_language') }}">
                    @if($currentLang)
                        <span class="fi fi-{{ $currentLang->flag }}"></span>
                    @else
                        <i class="fa-solid fa-globe"></i>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end lang-menu shadow-lg">
                    <li class="dropdown-header-custom">{{ __('frontend.select_language_caps') }}</li>
                    
                    @foreach($frontLanguages as $lang)
                        <li>
                            <a class="dropdown-item lang-item {{ $currentLocale == $lang->code ? 'active' : '' }}" 
                               href="{{ route('lang.switch', $lang->code) }}">
                                
                                <div class="d-flex align-items-center gap-3">
                                    <span class="fi fi-{{ $lang->flag }} lang-flag-small"></span>
                                    <span class="lang-name">{{ $lang->name }}</span>
                                </div>

                                @if($currentLocale == $lang->code)
                                    <i class="fa-solid fa-check text-success ms-auto" style="font-size: 0.8rem;"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @auth
                <a href="{{ $dashboardUrl }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-none d-lg-block">{{ __('frontend.dashboard_btn') }}</a>
            @else
                <a href="{{ $settings['header_cta_link'] ?? route('register') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-none d-lg-block">
                    @dynamicTrans('header_cta_text', $settings)
                </a>
            @endauth

            <button class="d-block d-lg-none btn btn-light border-0" id="mobileMenuBtn" style="font-size:1.5rem; background: transparent;">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <div class="mobile-menu-overlay d-lg-none" id="mobileMenu" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1050;">
        
        @if($showLangSwitcher)
            <div class="mobile-lang-wrapper">
                <span class="mobile-lang-label">{{ __('frontend.select_language') }}</span>
                <div class="mobile-lang-grid">
                    @foreach($frontLanguages as $lang)
                        <a href="{{ route('lang.switch', $lang->code) }}" 
                           class="btn-mobile-lang {{ $currentLocale == $lang->code ? 'active' : '' }}">
                            <span class="fi fi-{{ $lang->flag }}"></span>
                            {{ $lang->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <ul class="list-unstyled mb-0">
            @if(isset($headerMenu) && !empty($headerMenu->items) && count($headerMenu->items) > 0)
                @foreach($headerMenu->items as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="mobile-nav-link {{ request()->is(ltrim($item['url'], '/')) ? 'text-primary' : '' }}">
                            @jsonLang($item['label'])
                        </a>
                    </li>
                @endforeach
            @else
                <li><a href="{{ route('frontend.home') }}" class="mobile-nav-link">{{ __('frontend.home_link') }}</a></li>
                <li><a href="#features" class="mobile-nav-link">{{ __('frontend.features_link') }}</a></li>
                <li><a href="#pricing" class="mobile-nav-link">{{ __('frontend.pricing_link') }}</a></li>
            @endif
        </ul>

        <div class="mobile-auth-area">
            @auth
                <a href="{{ $dashboardUrl }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">{{ __('frontend.dashboard_btn') }}</a>
            @else
                <a href="{{ $settings['header_cta_link'] ?? route('register') }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                    @dynamicTrans('header_cta_text', $settings)
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if(btn && menu) {
            btn.addEventListener('click', () => {
                const isOpen = menu.style.display === 'block';
                menu.style.display = isOpen ? 'none' : 'block';
                btn.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
            });
        }
    });
</script>