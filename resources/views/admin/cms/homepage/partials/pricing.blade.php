@php
    // Helper to retrieve specific language value safely
    if (!function_exists('getAdminVal')) {
        function getAdminVal($key, $settings, $lang) {
            $raw = $settings[$key] ?? '';
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded[$lang] ?? '';
            }
            return ($lang === 'en') ? $raw : '';
        }
    }

    $languages = [
        'en' => ['label' => 'English (Default)', 'flag' => 'us'],
        'bn' => ['label' => 'Bengali', 'flag' => 'bd'],
        'de' => ['label' => 'German',  'flag' => 'de'],
        'es' => ['label' => 'Spanish', 'flag' => 'es']
    ];

    $trustDefaults = [
        1 => 'One-Time Payment', 2 => 'Lifetime Use', 3 => 'No Monthly Fees', 4 => 'Envato Quality Checked'
    ];
    $iconDefaults = [
        1 => 'fa-solid fa-shield-halved', 2 => 'fa-solid fa-infinity', 3 => 'fa-solid fa-ban', 4 => 'fa-solid fa-circle-check'
    ];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-pricing" aria-expanded="false" aria-controls="sec-pricing">
        <div class="d-flex align-items-center">
            <span class="section-badge">09</span>
            <span><i class="fa-solid fa-tags me-2"></i> {{ __('cms.pricing_section_title') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-pricing" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="pricingTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="pricing-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#pricing-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="pricingTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="pricing-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Heading --}}
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.section_heading') }}</label>
                        <input type="text" 
                               name="pricing_title[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('pricing_title', $settings, $code) }}">
                    </div>
                    
                    {{-- Subtitle --}}
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.section_subtext') }}</label>
                        <textarea name="pricing_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('pricing_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    <!-- Trust Signals (Text Only) -->
                    <h6 class="fw-bold text-dark mb-3">{{ __('cms.bottom_trust_signals') }} ({{ strtoupper($code) }})</h6>
                    <div class="row g-3">
                        @foreach($trustDefaults as $i => $label)
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Signal {{ $i }} Text</label>
                                <input type="text" 
                                       name="pricing_trust_{{$i}}[{{ $code }}]" 
                                       class="form-control-cms form-control-sm" 
                                       value="{{ getAdminVal("pricing_trust_{$i}", $settings, $code) }}">
                            </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>
            
            <hr class="border-light my-4">

            <!-- Global Settings (Plan Selection, Popular ID, Icons) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>

            <!-- Plan Selection -->
            <div class="p-3 bg-white rounded border mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ __('cms.select_plans_highlight') }}</h6>
                        <p class="text-muted small mb-0">{{ __('cms.select_plans_desc') }}</p>
                    </div>
                </div>
                
                <div class="d-flex flex-column gap-2 pricing-plan-scroll" style="max-height: 250px; overflow-y: auto;">
                    @php
                        $selectedPlanIds = json_decode($settings['home_plans_list'] ?? '[]', true);
                        if(!is_array($selectedPlanIds)) $selectedPlanIds = [];
                        
                        $popularPlanId = $settings['home_pricing_popular_id'] ?? null;
                    @endphp

                    @if(isset($allPlans) && count($allPlans) > 0)
                        @foreach($allPlans as $plan)
                            <div class="d-flex align-items-center justify-content-between bg-light border rounded px-3 py-2">
                                
                                {{-- Visibility Checkbox --}}
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="checkbox" 
                                           name="selected_plans[]" 
                                           value="{{ $plan->id }}" 
                                           id="plan_{{ $plan->id }}"
                                           {{ in_array($plan->id, $selectedPlanIds) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold small ms-2" for="plan_{{ $plan->id }}">
                                        {{ $plan->name }} <span class="text-muted">({{ $plan->price_monthly }})</span>
                                    </label>
                                </div>

                                {{-- Popular Radio Button --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="home_pricing_popular_id" 
                                           value="{{ $plan->id }}" 
                                           id="pop_plan_{{ $plan->id }}"
                                           {{ $popularPlanId == $plan->id ? 'checked' : '' }}>
                                    <label class="form-check-label text-warning small fw-bold" for="pop_plan_{{ $plan->id }}">
                                        <i class="fa-solid fa-star"></i> {{ __('cms.popular') }}
                                    </label>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <div class="text-muted small p-2">{{ __('cms.no_plans_found') }}</div>
                    @endif
                </div>
            </div>
            
            <!-- Trust Signal Icons (Global) -->
            <h6 class="fw-bold text-dark mb-3">{{ __('cms.trust_signal_icons') }}</h6>
            <div class="row g-3">
                @foreach($iconDefaults as $i => $icon)
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold">Signal {{ $i }} Icon</label>
                        <input type="text" name="pricing_trust_{{$i}}_icon" 
                               class="form-control-cms form-control-sm" 
                               placeholder="fa-solid fa-icon"
                               value="{{ $settings["pricing_trust_{$i}_icon"] ?? $icon }}">
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>