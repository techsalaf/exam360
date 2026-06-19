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
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-testi">
        <div class="d-flex align-items-center">
            <span class="section-badge">10</span>
            <span><i class="fa-solid fa-comment-dots me-2"></i> {{ __('cms.testimonials_section_title') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-testi" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="testiTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="testi-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#testi-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="testiTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="testi-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Heading --}}
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.section_heading') }}</label>
                        <input type="text" 
                               name="testimonials_title[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('testimonials_title', $settings, $code) }}">
                    </div>
                    
                    {{-- Subtitle --}}
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.subtext') }}</label>
                        <textarea name="testimonials_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('testimonials_subtitle', $settings, $code) }}</textarea>
                    </div>

                </div>
                @endforeach
            </div>

            <div class="alert alert-light border small text-muted">
                <i class="fa-solid fa-info-circle me-1"></i> 
                {{ __('cms.testimonials_manager_hint') }}
            </div>

        </div>
    </div>
</div>