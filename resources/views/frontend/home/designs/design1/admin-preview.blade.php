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

    $statsDefaults = [
        1 => ['val' => '10,000+', 'lbl' => 'Users Supported', 'color' => 'text-primary'],
        2 => ['val' => '100%',    'lbl' => 'Role-Based Access', 'color' => 'text-primary'],
        3 => ['val' => 'Real-Time', 'lbl' => 'Revenue Tracking', 'icon' => 'fa-bolt', 'color' => 'text-warning'],
        4 => ['val' => 'AI Cost',   'lbl' => 'Usage & Cost Control', 'icon' => 'fa-robot', 'color' => '#8B5CF6']
    ];

    $featuresDefaults = [
        1 => ['icon' => 'fa-users-gear', 'color' => 'blue-soft', 'title' => 'User & Role Control', 'desc' => 'Manage admins, instructors, and students with fine-grained permissions.'],
        2 => ['icon' => 'fa-credit-card', 'color' => 'green-soft', 'title' => 'Revenue & Subscriptions', 'desc' => 'Track payments, plans, renewals, and growth in real time.'],
        3 => ['icon' => 'fa-bolt', 'color' => 'purple-soft', 'title' => 'AI Usage & Limits', 'desc' => 'Monitor AI consumption, set limits, and control operational costs.'],
        4 => ['icon' => 'fa-wrench', 'color' => 'orange-soft', 'title' => 'System Configuration', 'desc' => 'Configure payments, email, security, and platform behavior centrally.']
    ];
@endphp

<section class="section-py admin-section" id="admin-preview">
    <div class="container">
        
        <div class="section-title">
            <h2>{{ get_trans($settings['admin_preview_title'] ?? '') }}</h2>
            <p>{{ get_trans($settings['admin_preview_subtitle'] ?? '') }}</p>
        </div>

        <div class="admin-dashboard-wrapper">
            <div class="admin-mockup-frame">
                {{-- Image is a global setting --}}
                @if(!empty($settings['admin_preview_image']))
                    <img src="{{ Storage::url($settings['admin_preview_image']) }}" alt="Admin Panel">
                @endif
            </div>
        </div>

        <div class="admin-stats-row">
            @foreach($statsDefaults as $key => $stat)
                <div class="stat-item">
                    <span class="stat-value" style="color: {{ $stat['color'] }}">
                        @if(isset($stat['icon'])) <i class="fa-solid {{ $stat['icon'] }} me-1"></i> @endif
                        {{ $settings["admin_stat_{$key}_val"] ?? $stat['val'] }}
                    </span>
                    <span class="stat-label">
                        {{ get_trans($settings["admin_stat_{$key}_lbl"] ?? '') ?: $stat['lbl'] }}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="section-divider"></div>

        <div class="d-grid grid-2 admin-features-grid">
            @foreach($featuresDefaults as $key => $feat)
                <div class="admin-feature-card">
                    <div class="icon-soft {{ $feat['color'] }}">
                        <i class="{{ $settings["admin_feat_{$key}_icon"] ?? ('fa-solid ' . $feat['icon']) }}"></i>
                    </div>
                    <div class="content">
                        <h5>{{ get_trans($settings["admin_feat_{$key}_title"] ?? '') ?: $feat['title'] }}</h5>
                        <p>{{ get_trans($settings["admin_feat_{$key}_desc"] ?? '') ?: $feat['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="admin-trust-row">
            @foreach([1, 2, 3] as $key)
                @php
                    // Defaults for checkmarks are simple strings if not translated
                    $defaultCheck = $settings["admin_check_{$key}"] ?? $featuresDefaults[$key]['title']; // Using feature titles as placeholders
                @endphp
                <span>
                    <i class="fa-solid fa-check text-primary me-2"></i> 
                    {{ get_trans($settings["admin_check_{$key}"] ?? '') ?: $defaultCheck }}
                </span>
            @endforeach
        </div>

    </div>
</section>