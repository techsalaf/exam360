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

    // Context labels to guide the admin on what each slot represents
    // These are just headers, not default values.
    $faqContext = [
        1 => 'SaaS Readiness', 
        2 => 'Reselling Rights', 
        3 => 'Payment Gateways', 
        4 => 'AI Features'
    ];

    // Default icons (we keep these as fallbacks for the global settings)
    $iconDefaults = [
        1 => 'fa-solid fa-server', 
        2 => 'fa-solid fa-money-bill-transfer', 
        3 => 'fa-brands fa-stripe', 
        4 => 'fa-solid fa-wand-magic-sparkles'
    ];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-faq">
        <div class="d-flex align-items-center">
            <span class="section-badge">11</span>
            {{-- Fixed Title Label --}}
            <span><i class="fa-solid fa-circle-question me-2"></i> FAQ Section</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-faq" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="faqTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="faq-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#faq-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="faqTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="faq-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Heading --}}
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.section_heading') }}</label>
                        <input type="text" 
                               name="faq_title[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('faq_title', $settings, $code) }}">
                    </div>
                    
                    {{-- Subtitle --}}
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.subtext') }}</label>
                        <textarea name="faq_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('faq_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    <!-- FAQ Loop -->
                    {{-- Fixed Header: Replaced broken translation key with static text --}}
                    <h6 class="fw-bold text-dark mb-3">FAQ Questions ({{ strtoupper($code) }})</h6>
                    
                    <div class="d-flex flex-column gap-3">
                        @foreach($faqContext as $key => $label)
                            <div class="p-3 bg-light-cms rounded border">
                                {{-- Section Label (Just for context, not input value) --}}
                                <h6 class="fw-bold text-dark mb-3">Q{{ $key }}: {{ $label }}</h6>
                                
                                <div class="mb-2">
                                    <label class="small text-muted fw-bold">{{ __('cms.question') }}</label>
                                    <input type="text" 
                                           name="faq_q{{$key}}_title[{{ $code }}]" 
                                           class="form-control-cms" 
                                           value="{{ getAdminVal("faq_q{$key}_title", $settings, $code) }}">
                                </div>
                                
                                <label class="small text-muted fw-bold">{{ __('cms.answer') }}</label>
                                <textarea name="faq_q{{$key}}_desc[{{ $code }}]" 
                                          class="form-control-cms" 
                                          rows="2">{{ getAdminVal("faq_q{$key}_desc", $settings, $code) }}</textarea>
                            </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <!-- Global Settings (Icons) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-icons me-2"></i>Global Icons (All Languages)</h6>

            <div class="row g-3">
                @foreach($faqContext as $i => $label)
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold">Q{{ $i }} Icon</label>
                        <input type="text" name="faq_q{{$i}}_icon" 
                               class="form-control-cms form-control-sm" 
                               placeholder="{{ $iconDefaults[$i] }}"
                               value="{{ $settings["faq_q{$i}_icon"] ?? $iconDefaults[$i] }}">
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>