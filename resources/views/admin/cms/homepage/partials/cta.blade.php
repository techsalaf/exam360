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
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-cta">
        <div class="d-flex align-items-center">
            <span class="section-badge">12</span>
            <span><i class="fa-solid fa-bullhorn me-2"></i> {{ __('cms.cta_section_title') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-cta" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="ctaTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="cta-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#cta-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="ctaTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="cta-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.cta_title_html') }}</label>
                        <textarea name="cta_title[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('cta_title', $settings, $code) }}</textarea>
                    </div>
                    
                    {{-- Subtitle --}}
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.cta_description') }}</label>
                        <textarea name="cta_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('cta_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    <!-- Primary Button Text -->
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.primary_button') }} (Text)</label>
                        <input type="text" 
                               name="cta_btn_text[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('cta_btn_text', $settings, $code) }}">
                    </div>

                    <!-- Secondary Button Text -->
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.secondary_button') }} (Text)</label>
                        <input type="text" 
                               name="cta_btn2_text[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('cta_btn2_text', $settings, $code) }}">
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <!-- Global Settings (Links) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('cms.primary_button') }} (Link)</label>
                    <input type="text" name="cta_btn_link" class="form-control-cms" value="{{ $settings['cta_btn_link'] ?? '/register' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('cms.secondary_button') }} (Link)</label>
                    <input type="text" name="cta_btn2_link" class="form-control-cms" value="{{ $settings['cta_btn2_link'] ?? '#' }}">
                </div>
            </div>

        </div>
    </div>
</div>