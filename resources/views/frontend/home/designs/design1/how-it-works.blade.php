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

    $steps = [1, 2, 3, 4];
    // Defaults are only used for icons if DB setting is missing
    $defaultIcons = [
        1 => 'fa-download', 2 => 'fa-robot', 3 => 'fa-tags', 4 => 'fa-chart-line'
    ];
@endphp

<section class="section-py bg-light text-center" id="how-it-works">
    <div class="container">
        
        <div class="section-title">
            <h2>{{ get_trans($settings['how_it_works_title'] ?? '') }}</h2>
            <p>{{ get_trans($settings['how_it_works_subtitle'] ?? '') }}</p>
        </div>

        <div class="steps-wrapper">
            <!-- Connecting Line (Desktop Only) -->
            <div class="steps-connector d-none d-lg-block"></div>

            <div class="d-grid grid-4">
                @foreach($steps as $key)
                    <div class="step-card">
                        <div class="step-icon-wrap step-{{ $key }}">
                            <i class="{{ $settings["hiw_s{$key}_icon"] ?? ('fa-solid ' . $defaultIcons[$key]) }}"></i>
                            <span class="step-number">{{ $key }}</span>
                        </div>
                        <h5>{{ get_trans($settings["hiw_s{$key}_title"] ?? '') }}</h5>
                        <p class="text-muted small">
                            {{ get_trans($settings["hiw_s{$key}_desc"] ?? '') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>