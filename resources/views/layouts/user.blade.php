<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $currentLang = \App\Models\Language::where('code', $locale)->first();
    $isRtl = $currentLang ? (bool)$currentLang->is_rtl : false;

    $appConfig = $settings ?? $gs ?? \App\Models\SystemSetting::pluck('value', 'key')->toArray();

    $appName = $appConfig['app_name'] ?? config('app.name');
    $faviconUrl = !empty($appConfig['app_favicon'])
        ? Storage::url($appConfig['app_favicon'])
        : asset('assets/images/favicon.png');
@endphp
<html lang="{{ str_replace('_', '-', $locale) }}" data-bs-theme="light" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('layout.dashboard')) | {{ $appName }}</title>
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">

    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta/stylesheet.css') }}">
    
    {{-- RTL vs LTR Bootstrap Switch --}}
    @if($isRtl)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user.css') }}">

    @stack('styles')

    <script>
        const ziTheme = localStorage.getItem('zi_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', ziTheme);
    </script>
</head>

<body>

@if(session()->has('impersonate_admin_id'))
    <div class="impersonation-banner">
        <div class="impersonation-meta">
            <span class="impersonation-label">{{ __('layout.viewing_as') }}</span>
            <span class="impersonation-user">{{ Auth::user()->name }}</span>
        </div>

        <a href="{{ route('stop.impersonation') }}"
           class="btn btn-danger btn-sm rounded-pill fw-bold text-uppercase">
            <i class="fa-solid fa-right-from-bracket {{ $isRtl ? '' : 'me-1' }} {{ $isRtl ? 'ms-1' : '' }}"></i>
            {{ __('layout.exit') }}
        </a>
    </div>
@endif

<div id="appBackdrop" class="app-backdrop"></div>

<aside class="app-sidebar" id="sidebar">
    @include('partials.user-sidebar', ['settings' => $appConfig])
</aside>

<div class="app-main">
    <header class="app-topbar">
        @include('partials.user-topbar')
    </header>

    <main class="app-content">
        @yield('content')
    </main>
</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/sweetalert/sweetalert2.all.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('appBackdrop');

        window.toggleSidebar = function () {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        };

        backdrop?.addEventListener('click', function () {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: '{{ $isRtl ? "toast-bottom-left" : "toast-bottom-right" }}',
            rtl: {{ $isRtl ? 'true' : 'false' }},
            timeOut: 5000
        };

        @if(session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if(session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if(session('info'))
            toastr.info(@json(session('info')));
        @endif

        @if(session('warning'))
            toastr.warning(@json(session('warning')));
        @endif

        @if($errors->any())
            toastr.error(@json($errors->first()));
        @endif
    });
</script>

@stack('scripts')

</body>
</html>