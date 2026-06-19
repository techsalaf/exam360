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

    $audienceCards = [
        1 => ['color' => 'blue',   'default_icon' => 'fa-building-columns'],
        2 => ['color' => 'green',  'default_icon' => 'fa-graduation-cap'],
        3 => ['color' => 'orange', 'default_icon' => 'fa-laptop-code'],
        4 => ['color' => 'purple', 'default_icon' => 'fa-rocket'],
    ];
@endphp

<section class="section-py bg-light" id="audience">
    <div class="container">
        
        <div class="section-title">
            <h2 class="audience-title">
                {{ get_trans($settings['audience_title'] ?? '') }}
            </h2>
            <p style="font-size: 1.1rem; color: var(--muted);">
                {{ get_trans($settings['audience_subtitle'] ?? '') }}
            </p>
        </div>
        
        <div class="d-grid grid-4 audience-grid">
            @foreach($audienceCards as $key => $style)
                <div class="card">
                    <div class="icon-wrap {{ $style['color'] }}">
                        <i class="{{ $settings["aud_c{$key}_icon"] ?? 'fa-solid ' . $style['default_icon'] }}"></i>
                    </div>
                    
                    <h4 style="margin-bottom: 5px;">
                        {{ get_trans($settings["aud_c{$key}_title"] ?? '') }}
                    </h4>
                    
                    <p class="text-muted small mb-3" style="font-weight: 600; opacity: 0.9;">
                        {{ get_trans($settings["aud_c{$key}_highlight"] ?? '') }}
                    </p>
                    
                    <p class="text-muted small mb-0">
                        {{ get_trans($settings["aud_c{$key}_desc"] ?? '') }}
                    </p>
                </div>
            @endforeach
        </div>

        <!-- Bottom CTA -->
        <div class="text-center" style="margin-top: 50px;">
            <p class="text-muted small mb-3">
                {{ get_trans($settings['audience_bottom_text'] ?? '') }}
            </p>
            
            @if(!empty($settings['audience_btn_text']))
                <a href="{{ $settings['audience_btn_link'] ?? '#' }}" class="btn btn-outline" style="border-color: var(--border); color: var(--dark); padding: 8px 25px;">
                    <i class="fa-solid fa-arrow-right me-2"></i> {{ get_trans($settings['audience_btn_text']) }}
                </a>
            @endif
        </div>

    </div>
</section>