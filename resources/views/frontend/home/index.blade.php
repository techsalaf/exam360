@extends('frontend.layout')

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

    if (!function_exists('get_trans')) {
        function get_trans($input) {
            return dynamicTransHelper($input);
        }
    }

    $activeDesign = $rawSettings['active_homepage_design'] ?? 'design1';

    // Design3 sections (Al-Qurraa' College specific)
    $design3Sections = [
        'navbar'                => true,
        'hero'                  => 'frontend_show_hero',
        'exams'                 => 'frontend_show_exams',
        'achievements-explained' => true,
        'how-it-works'          => 'frontend_show_how_it_works',
        'footer'                => true,
    ];

    // Removed 'admin-preview' and 'cms' from here if you don't want that dark gap section
    $sections = [
        'hero'          => 'frontend_show_hero',
        'categories'    => 'frontend_show_categories',
        'audience'      => 'frontend_show_audience',
        'features'      => 'frontend_show_features',
        'how-it-works'  => 'frontend_show_how_it_works',
        'exams'         => 'frontend_show_exams',
        'pricing'       => 'frontend_show_pricing',
        'testimonials'  => 'frontend_show_testimonials',
        'faq'           => 'frontend_show_faq',
        'cta'           => 'frontend_show_cta'
    ];
@endphp

@push('styles')
    @if($activeDesign == 'design2')
        @foreach($sections as $fileName => $toggleKey)
            @if(($rawSettings[$toggleKey] ?? '1') == '1')
                <link rel="stylesheet" href="{{ asset('assets/frontend/css/design2/design2-'.$fileName.'.css') }}">
            @endif
        @endforeach
    @elseif($activeDesign == 'design3')
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-main.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/home-design1.css') }}">
    @endif
@endpush

@section('content')
    @if($activeDesign == 'design3')
        {{-- Design3: Modern Exam Discovery Homepage --}}
        @foreach($design3Sections as $fileName => $toggleKey)
            @if($toggleKey === true || ($rawSettings[$toggleKey] ?? '1') == '1')
                @includeIf("frontend.home.designs.design3.{$fileName}")
            @endif
        @endforeach
    @else
        {{-- Design1 and Design2 --}}
        @foreach($sections as $fileName => $toggleKey)
            @if(($rawSettings[$toggleKey] ?? '1') == '1')
                @includeIf("frontend.home.designs.{$activeDesign}.{$fileName}")
            @endif
        @endforeach
    @endif
@endsection