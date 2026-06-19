<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.503.default_title') }} - {{ config('app.name') }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/fonts/plus-jakarta/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/error.css') }}">
</head>
<body>

@php
    $data = [
        'app_name' => __('errors.defaults.app_name'),
        'title' => __('errors.503.default_title'),
        'message' => __('errors.503.default_message'),
        'image' => null,
        'logo' => null,
        'email' => null
    ];

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
            $settings = \App\Models\SystemSetting::whereIn('key', [
                'app_name', 
                'maintenance_title', 
                'maintenance_message', 
                'maintenance_image', 
                'app_logo_light', 
                'support_email'
            ])->pluck('value', 'key');

            if ($settings->isNotEmpty()) {
                $data['app_name'] = $settings['app_name'] ?? $data['app_name'];
                $data['title'] = $settings['maintenance_title'] ?? $data['title'];
                $data['message'] = $settings['maintenance_message'] ?? $data['message'];
                $data['email'] = $settings['support_email'] ?? null;
                
                if (!empty($settings['maintenance_image']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['maintenance_image'])) {
                    $data['image'] = \Illuminate\Support\Facades\Storage::url($settings['maintenance_image']);
                }

                $logoPath = $settings['app_logo_light'] ?? null;
                if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
                    $data['logo'] = \Illuminate\Support\Facades\Storage::url($logoPath);
                }
            }
        }
    } catch (\Exception $e) {}
@endphp

<div class="error-container">
    
    <div class="logo-area">
        @if($data['logo'])
            <a href="{{ url('/') }}">
                <img src="{{ $data['logo'] }}" alt="{{ $data['app_name'] }}">
            </a>
        @else
            <a href="{{ url('/') }}" class="brand-fallback">
                <i class="fa-solid fa-layer-group"></i> 
                <span>{{ $data['app_name'] }}</span>
            </a>
        @endif
    </div>

    @if($data['image'])
        <div class="maintenance-img-wrapper">
            <img src="{{ $data['image'] }}" alt="{{ __('errors.503.image_alt') }}" class="maintenance-img">
        </div>
    @else
        <div class="maintenance-icon-wrapper">
            <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>
    @endif

    <h1 class="error-message">
        {{ $data['title'] }}
    </h1>

    <p class="error-subtext">
        {!! nl2br(e($data['message'])) !!}
    </p>

    <div class="btn-group">
        @if($data['email'])
            <a href="mailto:{{ $data['email'] }}" class="btn btn-primary">
                <i class="fa-regular fa-envelope"></i> {{ __('errors.503.contact_support') }}
            </a>
        @else
            {{-- Envato Fix: Removed inline JS, added class for external handler --}}
            <a href="#" class="btn btn-primary reload-page">
                <i class="fa-solid fa-rotate-right"></i> {{ __('errors.503.check_status') }}
            </a>
        @endif
    </div>

</div>

{{-- Envato Fix: Load External JS --}}
<script src="{{ asset('assets/js/error.js') }}"></script>

</body>
</html>