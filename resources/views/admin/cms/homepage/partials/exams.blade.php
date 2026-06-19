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
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-exams">
        <div class="d-flex align-items-center">
            <span class="section-badge">06</span>
            <span><i class="fa-solid fa-file-signature me-2"></i> {{ __('cms.featured_exams') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-exams" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="examsTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="exams-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#exams-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="examsTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="exams-content-{{ $code }}" role="tabpanel">
                    
                    <!-- Section Heading & Subtitle -->
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.section_heading') }}</label>
                        <input type="text" 
                               name="exams_title[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('exams_title', $settings, $code) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.subtext') }}</label>
                        <textarea name="exams_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('exams_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    <!-- Subscription Promo Strip (Translatable Fields) -->
                    <div class="p-3 bg-light-cms rounded border mb-3">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-layer-group text-primary me-1"></i> {{ __('cms.sub_promo_strip') }} ({{ strtoupper($code) }})
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">{{ __('cms.strip_title') }}</label>
                                <input type="text" 
                                       name="exams_sub_title[{{ $code }}]" 
                                       class="form-control-cms" 
                                       value="{{ getAdminVal('exams_sub_title', $settings, $code) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">{{ __('cms.strip_description') }}</label>
                                <input type="text" 
                                       name="exams_sub_desc[{{ $code }}]" 
                                       class="form-control-cms" 
                                       value="{{ getAdminVal('exams_sub_desc', $settings, $code) }}">
                            </div>
                            <div class="col-md-12">
                                <label class="small text-muted fw-bold">{{ __('cms.button_text') }}</label>
                                <input type="text" 
                                       name="exams_sub_btn_text[{{ $code }}]" 
                                       class="form-control-cms" 
                                       value="{{ getAdminVal('exams_sub_btn_text', $settings, $code) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Text -->
                    <div class="mb-0">
                        <label class="form-label-premium">{{ __('cms.bottom_small_text') }}</label>
                        <input type="text" 
                               name="exams_bottom_text[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('exams_bottom_text', $settings, $code) }}">
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <!-- Global Settings (Count & Link) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label-premium">{{ __('cms.exams_to_show') }}</label>
                    <input type="number" name="exams_count" class="form-control-cms" min="1" max="12" value="{{ $settings['exams_count'] ?? 3 }}">
                    <div class="form-text text-primary small mt-1">
                        <i class="fa-solid fa-circle-info me-1"></i> {{ __('cms.exams_count_hint') }}
                    </div>
                </div>
                <div class="col-md-8">
                    <label class="form-label-premium">{{ __('cms.sub_promo_strip_button_link') }}</label>
                    <input type="text" name="exams_sub_btn_link" class="form-control-cms" value="{{ $settings['exams_sub_btn_link'] ?? '#' }}">
                </div>
            </div>

        </div>
    </div>
</div>