@php
    // Helper to decode JSON multilingual strings from Admin CMS
    if (!function_exists('get_trans')) {
        function get_trans($jsonString) {
            if (empty($jsonString)) return '';
            $decoded = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return $jsonString; 
            }
            $locale = app()->getLocale();
            // Fallback: Current Locale -> English -> First Available
            return $decoded[$locale] ?? $decoded['en'] ?? reset($decoded) ?? '';
        }
    }

    // --- Settings & Configuration ---
    $currencySymbol = $settings['currency_symbol'] ?? '$';
    $currencyPos = $settings['currency_position'] ?? 'before';
    $decimalSep = $settings['decimal_separator'] ?? '.';
    $thousandSep = $settings['thousands_separator'] ?? ',';
    
    // --- User Subscription Data ---
    $user = Illuminate\Support\Facades\Auth::user();
    $currentPlanId = ($user && $user->is_subscribed) ? $user->plan_id : null;

    // --- Plan Filtering Logic (Based on Admin Selection) ---
    // Decodes the list of IDs selected in the CMS
    $selectedPlanIds = json_decode($settings['home_plans_list'] ?? '[]', true);
    
    // If specific plans are selected in CMS, filter the main $plans collection.
    // Otherwise, fallback to showing all active plans.
    $displayPlans = $plans;
    if (!empty($selectedPlanIds) && is_array($selectedPlanIds) && count($selectedPlanIds) > 0) {
        $displayPlans = $plans->filter(function($plan) use ($selectedPlanIds) {
            return in_array($plan->id, $selectedPlanIds);
        });
    }

    // --- Trust Signals Data Preparation ---
    // Loops 1-4 to match the CMS structure
    $trustSignals = [];
    $defaultTrustTexts = [
        1 => 'One-Time Payment', 
        2 => 'Lifetime Use', 
        3 => 'No Monthly Fees', 
        4 => 'Envato Quality Checked'
    ];
    $defaultTrustIcons = [
        1 => 'fa-solid fa-shield-halved', 
        2 => 'fa-solid fa-infinity', 
        3 => 'fa-solid fa-ban', 
        4 => 'fa-solid fa-circle-check'
    ];

    for($i = 1; $i <= 4; $i++) {
        $iconSetting = $settings["pricing_trust_{$i}_icon"] ?? '';
        $textSetting = $settings["pricing_trust_{$i}"] ?? '';

        $trustSignals[] = [
            'icon' => !empty($iconSetting) ? $iconSetting : $defaultTrustIcons[$i],
            'text' => get_trans($textSetting) ?: $defaultTrustTexts[$i]
        ];
    }
@endphp

<section class="section-py bg-light" id="pricing">
    <div class="container">
        
        <!-- Section Title (Multilingual from CMS) -->
        <div class="section-title">
            <h2>{{ get_trans($settings['pricing_title'] ?? '') ?: 'Simple, Transparent Pricing' }}</h2>
            <p>{{ get_trans($settings['pricing_subtitle'] ?? '') ?: 'Choose the plan that suits your needs best.' }}</p>
        </div>

        <!-- Plans Grid -->
        <div class="pricing-wrapper">
            
            @if($displayPlans->count() > 0)
                @foreach($displayPlans as $plan)
                    @php
                        // --- Plan Specific Logic ---
                        $popularId = $settings['home_pricing_popular_id'] ?? null;
                        $isPopular = ($plan->id == $popularId);
                        
                        $cardClass = $isPopular ? 'popular' : '';
                        $btnClass  = $isPopular ? 'btn-primary-plan' : 'btn-outline-plan';
                        
                        // Parse features (assuming newline separated)
                        $features = explode("\n", $plan->short_description); 
                        $priceFormatted = number_format($plan->price_monthly, 0, $decimalSep, $thousandSep);
                        
                        // Button State Logic
                        $isCurrentPlan = ($currentPlanId == $plan->id);
                        $isUpgrade = ($user && $user->is_subscribed && !$isCurrentPlan);
                    @endphp

                    <div class="pricing-card {{ $cardClass }}">
                        
                        @if($isPopular)
                            <div class="popular-badge">{{ __('frontend.most_popular') }}</div>
                        @endif

                        <div class="card-header">
                            <h4 class="plan-name" style="{{ $isPopular ? 'color: #8B5CF6;' : '' }}">
                                {{ $plan->name }}
                            </h4>
                            <div class="plan-price">
                                @if(in_array($currencyPos, ['before', 'before_space']))
                                    <span class="currency">{{ $currencySymbol }}</span>
                                    @if($currencyPos === 'before_space') &nbsp; @endif
                                    {{ $priceFormatted }}
                                @else
                                    {{ $priceFormatted }}
                                    @if($currencyPos === 'after_space') &nbsp; @endif
                                    <span class="currency">{{ $currencySymbol }}</span>
                                @endif
                                <span class="period">{{ __('frontend.per_month') }}</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="feature-list">
                                @foreach($features as $feature)
                                    @if(trim($feature))
                                        <li>
                                            <i class="fa-solid fa-check"></i> 
                                            {{ trim($feature) }} 
                                        </li>
                                    @endif
                                @endforeach

                                <li>
                                    <i class="fa-solid fa-file-signature"></i> 
                                    {{ $plan->limit_monthly > 0 
                                        ? __('frontend.exams_limit_count', ['count' => $plan->limit_monthly]) 
                                        : __('frontend.exams_unlimited') }}
                                </li>
                            </ul>
                        </div>

                        <div class="card-footer">
                            @auth
                                @if($isCurrentPlan)
                                    <button class="btn btn-secondary w-100" disabled>
                                        {{ __('frontend.current_plan') }}
                                    </button>
                                @elseif($isUpgrade)
                                    <a href="{{ route('checkout.add', ['id' => $plan->id, 'type' => 'plan', 'period' => 'monthly']) }}" class="{{ $btnClass }} w-100">
                                        {{ __('frontend.upgrade_plan') }}
                                    </a>
                                @else
                                    <a href="{{ route('checkout.add', ['id' => $plan->id, 'type' => 'plan', 'period' => 'monthly']) }}" class="{{ $btnClass }} w-100">
                                        {{ __('frontend.choose_plan', ['plan' => $plan->name]) }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}?plan={{ $plan->id }}" class="{{ $btnClass }} w-100">
                                    {{ __('frontend.choose_plan', ['plan' => $plan->name]) }}
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No Plans Found State -->
                <div class="text-center py-5 w-100" style="grid-column: 1 / -1;">
                    <div class="mb-3">
                        <i class="fa-solid fa-tags text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-muted">{{ __('frontend.no_pricing_plans') }}</p>
                </div>
            @endif

        </div>

        <!-- Trust Signals Strip (Content from CMS) -->
        <div class="trust-strip">
            @foreach($trustSignals as $signal)
                <div class="trust-item">
                    <i class="{{ $signal['icon'] }}"></i> 
                    {{ $signal['text'] }}
                </div>
            @endforeach
        </div>

    </div>
</section>