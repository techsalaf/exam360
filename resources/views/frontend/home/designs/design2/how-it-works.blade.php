<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-how-it-works.css') }}">
<section class="d2-hiw-premium-section" id="how-it-works">
    <div class="container">
        <div class="d2-hiw-header">
            <h2 class="d2-hiw-main-title">
                {{ get_trans($settings['how_it_works_title'] ?? 'Simple Steps to Launch') }}
            </h2>
            <p class="d2-hiw-main-desc">
                {{ get_trans($settings['how_it_works_subtitle'] ?? 'Get your first exam live in under 10 minutes.') }}
            </p>
        </div>
        <div class="d2-hiw-row">
            @php
                $icons = [
                    1 => 'fa-server',
                    2 => 'fa-wand-sparkles',
                    3 => 'fa-tags',
                    4 => 'fa-rocket'
                ];
                $themes = ['theme-blue', 'theme-green', 'theme-purple', 'theme-orange'];
            @endphp
            @for($i=1; $i<=4; $i++)
                @php
                    $title = get_trans($settings["hiw_s{$i}_title"] ?? '');
                    if(empty($title)) continue;
                    $currentTheme = $themes[($i-1) % count($themes)];
                @endphp
                <div class="d2-hiw-col">
                    <div class="d2-hiw-step-card {{ $currentTheme }}">
                        <div class="d2-hiw-icon-wrap">
                            <i class="fa-solid {{ $icons[$i] ?? 'fa-check' }}"></i>
                            <div class="d2-hiw-number-badge">{{ $i }}</div>
                        </div>
                        <h4 class="d2-hiw-step-title">{{ $title }}</h4>
                        <p class="d2-hiw-step-desc">
                            {{ get_trans($settings["hiw_s{$i}_desc"] ?? '') }}
                        </p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>