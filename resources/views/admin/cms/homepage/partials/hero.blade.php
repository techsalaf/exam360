@php
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
    <button class="cms-accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#sec-hero" aria-expanded="true">
        <div class="d-flex align-items-center">
            <span class="section-badge">01</span>
            <span><i class="fa-solid fa-image me-2"></i> {{ __('cms.hero_section') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    
    <div id="sec-hero" class="collapse show">
        <div class="cms-accordion-body">
            
            <ul class="nav nav-tabs mb-3" id="heroTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="hero-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#hero-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="heroTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="hero-content-{{ $code }}" role="tabpanel">
                    
                    <div class="mb-3">
                        <label class="form-label-premium">
                            {{ __('cms.top_badge') }} 
                            <span class="text-muted small ms-1">
                                (<span class="fi fi-{{ $lang['flag'] }}"></span> {{ strtoupper($code) }})
                            </span>
                        </label>
                        <input type="text" 
                               name="hero_badge[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('hero_badge', $settings, $code) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-premium">
                            {{ __('cms.main_headline') }} 
                            <span class="text-muted small ms-1">
                                (<span class="fi fi-{{ $lang['flag'] }}"></span> {{ strtoupper($code) }})
                            </span>
                        </label>
                        <textarea name="hero_title[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="3">{{ getAdminVal('hero_title', $settings, $code) }}</textarea>
                        @if($code === 'en')
                            <div class="form-text text-muted small">{{ __('cms.html_allowed') }}</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">
                            {{ __('cms.subtext') }} 
                            <span class="text-muted small ms-1">
                                (<span class="fi fi-{{ $lang['flag'] }}"></span> {{ strtoupper($code) }})
                            </span>
                        </label>
                        <textarea name="hero_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="3">{{ getAdminVal('hero_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-premium text-primary">
                                {{ __('cms.primary_button') }} (Text)
                            </label>
                            <input type="text" 
                                   name="hero_cta_text[{{ $code }}]" 
                                   class="form-control-cms" 
                                   value="{{ getAdminVal('hero_cta_text', $settings, $code) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium text-dark">
                                {{ __('cms.secondary_button') }} (Text)
                            </label>
                            <input type="text" 
                                   name="hero_cta2_text[{{ $code }}]" 
                                   class="form-control-cms" 
                                   value="{{ getAdminVal('hero_cta2_text', $settings, $code) }}">
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('cms.primary_button') }} (Link)</label>
                    <input type="text" name="hero_cta_link" class="form-control-cms" value="{{ $settings['hero_cta_link'] ?? '#' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('cms.secondary_button') }} (Link)</label>
                    <input type="text" name="hero_cta2_link" class="form-control-cms" value="{{ $settings['hero_cta2_link'] ?? '#' }}">
                </div>
            </div>

        </div>
    </div>
</div>