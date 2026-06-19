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

    // Default Fallbacks
    $cmsFeaturesDefaults = [
        1 => ['icon' => 'fa-clone', 'color' => 'blue-circle'],
        2 => ['icon' => 'fa-bars-staggered', 'color' => 'orange-circle'],
        3 => ['icon' => 'fa-magnifying-glass', 'color' => 'green-circle'],
        4 => ['icon' => 'fa-cube', 'color' => 'purple-circle']
    ];
@endphp

<section class="section-py cms-section" id="cms-features">
    <div class="container">
        <div class="cms-wrapper">
            
            <div class="cms-content">
                <span class="cms-badge">
                    {{ get_trans($settings['cms_badge'] ?? '') }}
                </span>
                <h2 class="cms-title">
                    {{ get_trans($settings['cms_title'] ?? '') }}
                </h2>
                <p class="cms-description">
                    {{ get_trans($settings['cms_desc'] ?? '') }}
                </p>
                
                <div class="cms-feature-grid">
                    @foreach($cmsFeaturesDefaults as $key => $feature)
                        @php
                            // Get translated text, falling back to English from lang files if CMS text is missing
                            $title = get_trans($settings["cms_feat_{$key}_title"] ?? '') ?: __('frontend.cms_feat_'.$key.'_title');
                            $desc = get_trans($settings["cms_feat_{$key}_desc"] ?? '') ?: __('frontend.cms_feat_'.$key.'_desc');
                            
                            // Only render if we have at least a title
                            if (empty($title)) continue;
                        @endphp
                        <div class="cms-feature">
                            <div class="icon-circle {{ $feature['color'] }}">
                                <i class="{{ $settings["cms_feat_{$key}_icon"] ?? ('fa-solid ' . $feature['icon']) }}"></i>
                            </div>
                            <div class="cms-feature-text">
                                <h6>{{ $title }}</h6>
                                <p>{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="cms-visuals">
                <div class="cms-glow"></div>

                <!-- Top Floating Image -->
                @if(!empty($settings['cms_image_1']))
                    <img src="{{ Storage::url($settings['cms_image_1']) }}" alt="Menu Manager" class="cms-ui-img card-top">
                @else
                    {{-- Default Skeleton UI 1 --}}
                    <div class="cms-ui-card card-top">
                        <div class="ui-header">
                            <div class="ui-dots"><span></span><span></span><span></span></div>
                            <div class="ui-title">Menu Manager</div>
                        </div>
                        <div class="ui-body">
                            <div class="ui-skeleton-line w-100"></div>
                            <div class="ui-skeleton-line w-100"></div>
                            <div class="ui-skeleton-box"></div>
                        </div>
                    </div>
                @endif

                <!-- Bottom Floating Image -->
                @if(!empty($settings['cms_image_2']))
                    <img src="{{ Storage::url($settings['cms_image_2']) }}" alt="Homepage Editor" class="cms-ui-img card-bottom">
                @else
                    {{-- Default Skeleton UI 2 --}}
                    <div class="cms-ui-card card-bottom">
                        <div class="ui-header">
                            <div class="ui-dots"><span></span><span></span><span></span></div>
                            <div class="ui-title">Homepage Editor</div>
                        </div>
                        <div class="ui-body">
                            <div class="ui-row">
                                <div class="ui-col"></div>
                                <div class="ui-col"></div>
                            </div>
                            <div class="ui-skeleton-line w-100 mt-2"></div>
                            <div class="ui-button-skele"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>