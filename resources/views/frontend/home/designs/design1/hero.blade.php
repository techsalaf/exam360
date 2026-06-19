@php
    if (!function_exists('get_trans')) {
        function get_trans($jsonString) {
            if (empty($jsonString)) return '';
            
            $decoded = json_decode($jsonString, true);
            
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return $jsonString; 
            }

            $locale = app()->getLocale();
            
            return $decoded[$locale] ?? $decoded['en'] ?? reset($decoded) ?? '';
        }
    }

    // Add ?? '' to prevent undefined array key errors
    $hasHeroContent = !empty($settings['hero_title'] ?? '');
    
    $hasRatingInfo = !empty($settings['home_stat_3_count'] ?? '') || !empty(get_trans($settings['home_stat_1_label'] ?? ''));
    
    $hasTrustBadges = !empty(get_trans($settings['trust_badge_1'] ?? '')) || 
                      !empty(get_trans($settings['trust_badge_2'] ?? '')) || 
                      !empty(get_trans($settings['trust_badge_3'] ?? ''));
@endphp

@if($hasHeroContent)
<section class="section-py hero">
    <div class="container hero-wrapper">
        
        <div class="hero-content">
            
            @if(!empty(get_trans($settings['hero_badge'] ?? '')))
                <div class="hero-badge">
                    {{ get_trans($settings['hero_badge'] ?? '') }}
                </div>
            @endif

            <h1 class="hero-title">
                {!! get_trans($settings['hero_title'] ?? '') !!}
            </h1>

            <p class="hero-description">
                {{ get_trans($settings['hero_subtitle'] ?? '') }}
            </p>

            <div class="hero-btns">
                @if(!empty(get_trans($settings['hero_cta_text'] ?? '')))
                    <a href="{{ $settings['hero_cta_link'] ?? '#' }}" class="btn btn-primary">
                        <i class="fa-solid fa-play me-2"></i> {{ get_trans($settings['hero_cta_text'] ?? '') }}
                    </a>
                @endif

                @if(!empty(get_trans($settings['hero_cta2_text'] ?? '')))
                    <a href="{{ $settings['hero_cta2_link'] ?? '#' }}" class="btn btn-outline">
                        <i class="fa-solid fa-cart-shopping me-2"></i> {{ get_trans($settings['hero_cta2_text'] ?? '') }}
                    </a>
                @endif
            </div>

            @if($hasRatingInfo || $hasTrustBadges)
                <div class="rating-proof @if($hasRatingInfo && $hasTrustBadges) has-both @endif">
                    @if($hasRatingInfo)
                        <div class="rating-info">
                            @if(!empty($settings['home_stat_3_count'] ?? ''))
                                <div class="stars">
                                    <i class="fa-solid fa-star text-warning"></i>
                                    <span class="rating-score">{{ $settings['home_stat_3_count'] ?? '' }}/5</span>
                                </div>
                            @endif
                            
                            @if(!empty(get_trans($settings['home_stat_1_label'] ?? '')))
                                <p class="rating-trust mb-0">
                                    {{ get_trans($settings['home_stat_1_label'] ?? '') }}
                                </p>
                            @endif
                        </div>
                    @endif

                    @if($hasTrustBadges)
                        <div class="hero-trust-badges-container">
                            @for ($i = 1; $i <= 3; $i++)
                                @php
                                    $badgeText = get_trans($settings["trust_badge_{$i}"] ?? '');
                                    $badgeIcon = $settings["trust_icon_{$i}"] ?? '';
                                @endphp
                                @if(!empty($badgeText))
                                    <div class="hero-trust-badge-item">
                                        @if(!empty($badgeIcon))
                                            <i class="{{ $badgeIcon }} me-2"></i>
                                        @endif
                                        <span>{!! $badgeText !!}</span>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    @endif
                </div>
            @endif

        </div>
        
        @if(!empty($settings['home_hero_image'] ?? ''))
        <div class="hero-image-container">
            <div class="hero-mockup">
                <img src="{{ Storage::url($settings['home_hero_image'] ?? '') }}" alt="Platform Preview">
            </div>
        </div>
        @endif

    </div>
</section>
@endif