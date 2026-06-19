@extends('frontend.layout')

@section('content')

    {{-- Load Page Specific CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages.css') }}">
    
    <div class="dynamic-page-wrapper">
        @foreach($page->sections as $section)
            
            {{-- 1. HERO SECTION --}}
            @if($section->type === 'hero')
                <section class="page-hero">
                    <div class="container">
                        {{-- Assuming content is already localized in Controller or via Accessor --}}
                        <h1 class="hero-title">{{ $section->content['heading'] ?? '' }}</h1>
                        <p class="hero-lead">
                            {{ $section->content['subtext'] ?? '' }}
                        </p>
                    </div>
                </section>
            
            {{-- 2. RICH TEXT SECTION --}}
            @elseif($section->type === 'text')
                <section class="page-text">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <div class="content-body">
                                    {!! $section->content['body'] ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            
            {{-- 3. FEATURES LIST SECTION --}}
            @elseif($section->type === 'features')
                <section class="page-features">
                    <div class="container">
                        <div class="row g-4">
                            @php
                                $features = explode("\n", $section->content['items'] ?? '');
                            @endphp

                            @foreach($features as $feature)
                                @if(!empty(trim($feature)))
                                    <div class="col-md-6 col-lg-3">
                                        <div class="feature-card">
                                            <div class="feature-icon">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                            <h6 class="feature-title">{{ trim($feature) }}</h6>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>

            @endif

        @endforeach
    </div>

@endsection