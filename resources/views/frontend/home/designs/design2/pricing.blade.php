<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-pricing.css') }}">
<section class="d2-pricing-section" id="pricing">
    <div class="container">
        <div class="d2-pricing-header">
            <h2 class="d2-pricing-title">
                {{ get_trans($settings['pricing_title'] ?? 'Choose Your Plan') }}
            </h2>
            <p class="d2-pricing-desc">
                {{ get_trans($settings['pricing_subtitle'] ?? 'Simple, transparent pricing for all your examination needs.') }}
            </p>
        </div>
        <div class="d2-pricing-row">
            @php
                $popularId = $rawSettings['home_pricing_popular_id'] ?? null;
                $currencySymbol = $rawSettings['currency_symbol'] ?? '$';
                $colorThemes = ['theme-blue', 'theme-green', 'theme-purple', 'theme-orange'];
            @endphp
            @foreach($plans as $index => $plan)
                @php
                    $isPopular = ($popularId == $plan->id);
                    $theme = $colorThemes[$index % count($colorThemes)];
                @endphp
                <div class="d2-pricing-col">
                    <div class="d2-pricing-card {{ $isPopular ? 'is-popular' : '' }} {{ $theme }}">
                        @if($isPopular)
                            <div class="d2-popular-badge">
                                {{ __('cms.popular') }}
                            </div>
                        @endif
                        <div class="d2-plan-name">{{ $plan->name }}</div>
                        <div class="d2-plan-price-wrap">
                            <span class="d2-plan-amount">
                                <small style="font-size: 2rem;">{{ $currencySymbol }}</small>{{ number_format($plan->price_monthly, 0) }}
                            </span>
                            <span class="d2-plan-period">/mo</span>
                        </div>
                        <ul class="d2-plan-features">
                            @php 
                                $features = is_array($plan->features) ? $plan->features : json_decode($plan->features, true); 
                            @endphp
                            @if(!empty($features))
                                @foreach($features as $feature)
                                    <li class="d2-plan-feature-item">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            @endif
                            @if($plan->limit_monthly > 0)
                                <li class="d2-plan-feature-item">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ $plan->limit_monthly }} Exams Per Month</span>
                                </li>
                            @endif
                        </ul>
                        <div class="d2-plan-footer">
                            <a href="{{ route('exams.list') }}" class="d2-plan-btn">
                                Select Plan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d2-pricing-trust-strip">
            @for($i=1; $i<=4; $i++)
                @php
                    $trustText = get_trans($settings["pricing_trust_{$i}"] ?? '');
                    $trustIcon = $settings["pricing_trust_{$i}_icon"] ?? 'fa-solid fa-check';
                @endphp
                @if(!empty($trustText))
                    <div class="d2-trust-item">
                        <i class="{{ $trustIcon }}"></i>
                        <span>{{ $trustText }}</span>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</section>