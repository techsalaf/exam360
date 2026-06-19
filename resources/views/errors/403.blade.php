<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.403.title') }} - {{ config('app.name') }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/error.css') }}">
</head>
<body>

<div class="error-container">
    
    <div class="logo-area">
        @php
            $logoUrl = null;
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                    $logoPath = \App\Models\SystemSetting::where('key', 'app_logo_light')->value('value');
                    if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
                        $logoUrl = \Illuminate\Support\Facades\Storage::url($logoPath);
                    }
                }
            } catch (\Exception $e) {}
        @endphp

        @if($logoUrl)
            <a href="{{ url('/') }}">
                <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}">
            </a>
        @else
            <a href="{{ url('/') }}" class="brand-fallback">
                <i class="fa-solid fa-layer-group"></i> 
                <span>{{ config('app.name') }}</span>
            </a>
        @endif
    </div>

    <div class="error-code">{{ __('errors.403.code') }}</div>

    <h1 class="error-message">{{ __('errors.403.title') }}</h1>

    <p class="error-subtext">
        @if($exception->getMessage())
            {{ $exception->getMessage() }}
        @else
            {{ __('errors.403.message') }}
        @endif
    </p>

    <div class="btn-group">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> {{ __('errors.403.go_back') }}
        </a>
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="fa-solid fa-house"></i> {{ __('errors.403.home') }}
        </a>
    </div>

</div>

</body>
</html>