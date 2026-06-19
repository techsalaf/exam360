<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $currentLang = \App\Models\Language::where('code', $locale)->first();
    $isRtl = $currentLang ? (bool)$currentLang->is_rtl : false;

    $config = $settings ?? $gs ?? [];
    $appName = $config['app_name'] ?? config('app.name');
    $faviconPath = $config['app_favicon'] ?? null;
@endphp
<html lang="{{ str_replace('_', '-', $locale) }}" data-bs-theme="light" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('app.admin_console')) | {{ $appName }}</title>

    <link rel="icon" type="image/png" href="{{ $faviconPath ? asset('storage/' . $faviconPath) : asset('assets/images/default-favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta/stylesheet.css') }}">
    
    @if($isRtl)
        {{-- Load Bootstrap RTL specifically --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/admin-empty-state.css') }}">

    @stack('styles')

    <script>
        const ziTheme = localStorage.getItem('zi_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', ziTheme);

        if (localStorage.getItem('zi_sidebar_state') === 'collapsed') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>

    @if(isset($googleAdSenseScript))
    {!! $googleAdSenseScript !!}
    @endif
</head>

<body>

@php
    if (!isset($errors) || !($errors instanceof \Illuminate\Support\ViewErrorBag)) {
        $errors = new \Illuminate\Support\ViewErrorBag;
    }
@endphp

<div class="sidebar-overlay"></div>

<div class="app-layout">
    @include('partials.admin-sidebar', ['settings' => $config])

    <div class="main-content">
        @include('partials.admin-topbar')

        <main class="content-pad">
            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: '{{ $isRtl ? "toast-bottom-left" : "toast-bottom-right" }}',
            preventDuplicates: true,
            timeOut: 5000,
            rtl: {{ $isRtl ? 'true' : 'false' }}
        };

        @if(is_string(session('success')))
            toastr.success(@json(session('success')));
        @endif

        @if(is_string(session('error')))
            toastr.error(@json(session('error')));
        @endif

        @if(is_string(session('warning')))
            toastr.warning(@json(session('warning')));
        @endif

        @if(is_string(session('info')))
            toastr.info(@json(session('info')));
        @endif

        @if ($errors instanceof \Illuminate\Support\ViewErrorBag && $errors->any())
            toastr.error(@json((string) $errors->first()));
        @endif
    });
</script>

@stack('scripts')

</body>
</html>