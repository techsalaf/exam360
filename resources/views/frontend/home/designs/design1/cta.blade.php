@php
    // Smart Translation Helper
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
@endphp

<section class="section-py cta-section" id="cta">
    <div class="container">
        <div class="cta-card">
            <div class="cta-shape shape-1"></div>
            <div class="cta-shape shape-2"></div>
            
            <div class="cta-content">
                <h2 class="cta-title">
                    {!! get_trans($settings['cta_title'] ?? '') ?: __('frontend.cta_title_default') !!}
                </h2>
                
                <p class="cta-desc">
                    {{ get_trans($settings['cta_subtitle'] ?? '') ?: __('frontend.cta_subtitle_default') }}
                </p>
                
                <div class="cta-btns">
                    @if(!empty($settings['cta_btn_text']))
                        <a href="{{ $settings['cta_btn_link'] ?? '#' }}" class="btn btn-black">
                            {{ get_trans($settings['cta_btn_text']) }}
                        </a>
                    @endif

                    @if(!empty($settings['cta_btn2_text']))
                        <a href="{{ $settings['cta_btn2_link'] ?? '#' }}" class="btn btn-outline-black">
                            <i class="fa-solid fa-play me-2"></i> {{ get_trans($settings['cta_btn2_text']) }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>