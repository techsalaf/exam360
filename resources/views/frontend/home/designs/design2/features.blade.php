<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-features.css') }}">
<section class="d2-features-premium-section">
    <div class="container">
        <div class="d2-premium-header">
            <h2 class="d2-premium-main-title">
                {{ get_trans($settings['features_title'] ?? 'Powerful Features for Modern Assessment') }}
            </h2>
            <p class="d2-premium-main-desc">
                {{ get_trans($settings['features_subtitle'] ?? 'Our platform offers everything you need to manage assessments efficiently.') }}
            </p>
        </div>
        <div class="d2-grid-row">
            @php
                $themes = ['theme-blue', 'theme-green', 'theme-purple', 'theme-orange'];
            @endphp
            @for($p=1; $p<=4; $p++)
                @php
                    $title = get_trans($settings["feat_p{$p}_title"] ?? '');
                    if(empty($title)) continue;
                    $icons = [
                        1 => 'fa-wand-magic-sparkles', 
                        2 => 'fa-robot', 
                        3 => 'fa-chart-line', 
                        4 => 'fa-shield-halved'
                    ];
                    $icon = $settings["feat_p{$p}_i1_icon"] ?? $icons[$p] ?? 'fa-star';
                    $currentTheme = $themes[($p-1) % count($themes)];
                @endphp
                <div class="d2-grid-col">
                    <div class="d2-premium-feat-card {{ $currentTheme }}">
                        <div class="d2-premium-badge">
                            {{ get_trans($settings["feat_p{$p}_hint_text"] ?? 'PRO') }}
                        </div>
                        <div class="d2-premium-icon">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <h3 class="d2-premium-title">{{ $title }}</h3>
                        <p class="d2-premium-text">
                            {{ get_trans($settings["feat_p{$p}_desc"] ?? '') }}
                        </p>
                        <ul class="d2-premium-list">
                            @for($i=1; $i<=3; $i++)
                                @php
                                    $subTitle = get_trans($settings["feat_p{$p}_i{$i}_title"] ?? '');
                                    $subIcon = $settings["feat_p{$p}_i{$i}_icon"] ?? 'fa-circle-check';
                                @endphp
                                @if(!empty($subTitle))
                                <li class="d2-premium-list-item">
                                    <i class="fa-solid {{ $subIcon }}"></i>
                                    <span>{{ $subTitle }}</span>
                                </li>
                                @endif
                            @endfor
                        </ul>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>