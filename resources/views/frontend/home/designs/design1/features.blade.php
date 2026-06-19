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

    $panels = [1, 2, 3, 4];
    
    // Default Icon Fallbacks
    $defaults = [
        1 => ['hint_icon' => 'fa-wand-magic-sparkles', 'items' => [1 => ['icon' => 'fa-bolt'], 2 => ['icon' => 'fa-sliders'], 3 => ['icon' => 'fa-shield-halved']]],
        2 => ['hint_icon' => 'fa-chart-pie', 'items' => [1 => ['icon' => 'fa-check-double'], 2 => ['icon' => 'fa-brain'], 3 => ['icon' => 'fa-file-export']]],
        3 => ['hint_icon' => 'fa-credit-card', 'items' => [1 => ['icon' => 'fa-lock'], 2 => ['icon' => 'fa-repeat'], 3 => ['icon' => 'fa-brands fa-stripe']]],
        4 => ['hint_icon' => 'fa-users-gear', 'items' => [1 => ['icon' => 'fa-user-shield'], 2 => ['icon' => 'fa-database'], 3 => ['icon' => 'fa-paint-roller']]]
    ];
@endphp

<section class="section-py bg-light" id="features">
    <div class="container">
        
        <div class="section-title feature-header">
            <h2>{{ get_trans($settings['features_title'] ?? '') }}</h2>
            <p>{{ get_trans($settings['features_subtitle'] ?? '') }}</p>
        </div>

        <div class="feature-stack-wrapper">

            @foreach($panels as $pKey)
                @php
                    $pData = $defaults[$pKey];
                @endphp
                <div class="feature-panel">
                    <div class="panel-depth-layer back"></div>
                    <div class="panel-depth-layer mid"></div>
                    
                    <div class="panel-content-card">
                        <div class="panel-sidebar">
                            <div class="panel-number">0{{ $pKey }}</div>
                            <h3 class="panel-title">
                                {{ get_trans($settings["feat_p{$pKey}_title"] ?? '') }}
                            </h3>
                            <p class="panel-desc">
                                {{ get_trans($settings["feat_p{$pKey}_desc"] ?? '') }}
                            </p>
                            
                            <div class="panel-visual-hint">
                                <i class="fa-solid {{ $settings["feat_p{$pKey}_hint_icon"] ?? $pData['hint_icon'] }}"></i>
                                <span>{{ get_trans($settings["feat_p{$pKey}_hint_text"] ?? '') }}</span>
                            </div>
                        </div>

                        <div class="panel-grid">
                            @foreach([1, 2, 3] as $iKey)
                                @php
                                    $iconSetting = $settings["feat_p{$pKey}_i{$iKey}_icon"] ?? ('fa-solid ' . $pData['items'][$iKey]['icon']);
                                @endphp
                                <div class="panel-item">
                                    <div class="item-icon">
                                        <i class="{{ $iconSetting }}"></i>
                                    </div>
                                    <div class="item-text">
                                        <h5>{{ get_trans($settings["feat_p{$pKey}_i{$iKey}_title"] ?? '') }}</h5>
                                        <p>{{ get_trans($settings["feat_p{$pKey}_i{$iKey}_desc"] ?? '') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>