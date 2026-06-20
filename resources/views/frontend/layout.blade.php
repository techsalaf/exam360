<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $settings['app_title'] ?? ($settings['app_name'] ?? config('app.name')) }} - {{ $settings['app_tagline'] ?? __('Smart Online Exam System') }}</title>

    @php
        $faviconUrl = asset('assets/img/globe.svg');
        if (!empty($settings['app_favicon'])) {
            $faviconUrl = Storage::url($settings['app_favicon']);
        } 
        elseif (!empty($settings['app_logo_light'])) {
            $faviconUrl = Storage::url($settings['app_logo_light']);
        }
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/light-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/dashboard-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/cookie-consent.css') }}">

    @stack('styles')
</head>
<body>

@php
    if (!function_exists('dynamicTransHelper')) {
        function dynamicTransHelper($input, $isKey = false, $settings = []) {
            $value = $isKey ? ($settings[$input] ?? $input) : $input;
            if (empty($value) || !is_string($value)) return $value;

            $decoded = json_decode($value, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $locale = app()->getLocale();
                $final = $decoded[$locale] ?? $decoded['en'] ?? reset($decoded);
                
                $final_decoded = json_decode($final, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($final_decoded)) {
                     return $final_decoded[$locale] ?? $final_decoded['en'] ?? $final;
                }
                return $final;
            }
            return $value;
        }
    }
@endphp

    @php
        $activeDesign = $rawSettings['active_homepage_design'] ?? ($settings['active_homepage_design'] ?? 'design1');
    @endphp

    @if($activeDesign !== 'design3')
        @include('frontend.partials.header')
    @endif

    <main>
        @yield('content')
    </main>

    @if($activeDesign !== 'design3')
        @include('frontend.partials.footer')
    @endif

    @php
        $layoutSettings = $settings ?? \App\Models\SystemSetting::pluck('value', 'key')->toArray();
        $hasChat = ($layoutSettings['ext_tawk_enable'] ?? '0') === '1';
    @endphp

    <a href="#" class="scroll-top-btn {{ $hasChat ? 'with-chat' : '' }}" id="scrollTopBtn">
        <i class="fa-solid fa-arrow-up"></i>
    </a>

    @include('frontend.partials.cookie-consent')
    @include('frontend.partials.tawk')

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        const scrollBtn = document.getElementById('scrollTopBtn');
        if(scrollBtn){
            window.onscroll = function() {
                if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                    scrollBtn.style.opacity = "1";
                    scrollBtn.style.pointerEvents = "all";
                    scrollBtn.style.transform = "translateY(0)";
                } else {
                    scrollBtn.style.opacity = "0";
                    scrollBtn.style.pointerEvents = "none";
                    scrollBtn.style.transform = "translateY(20px)";
                }
            };
            scrollBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({top: 0, behavior: 'smooth'});
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>