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

    $activeDesign = $settings['active_homepage_design'] ?? 'design1';
    $maxCards = ($activeDesign === 'design2') ? 5 : 4;
    $colors = [1 => 'primary', 2 => 'success', 3 => 'warning', 4 => 'purple', 5 => 'info'];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-audience" aria-expanded="false" aria-controls="sec-audience">
        <div class="d-flex align-items-center">
            <span class="section-badge">03</span>
            <span><i class="fa-solid fa-users me-2"></i> {{ __('cms.audience_section') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    
    <div id="sec-audience" class="collapse">
        <div class="cms-accordion-body">
            
            <ul class="nav nav-tabs mb-3" id="audTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="aud-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#aud-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="audTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="aud-content-{{ $code }}" role="tabpanel">
                    
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.main_heading') }}</label>
                        <input type="text" 
                               name="audience_title[{{ $code }}]" 
                               class="form-control-cms mb-2" 
                               value="{{ getAdminVal('audience_title', $settings, $code) }}">
                        
                        <label class="form-label-premium mt-3">{{ __('cms.subtext_description') }}</label>
                        <textarea name="audience_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('audience_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    @for($i=1; $i<=$maxCards; $i++)
                    <div class="p-3 bg-light-cms rounded border mb-3">
                        <h6 class="fw-bold text-{{ $colors[$i] }} mb-3">{{ __('cms.card_'.$i) }} ({{ strtoupper($code) }})</h6>
                        <div class="mb-2">
                            <input type="text" 
                                   name="aud_c{{ $i }}_title[{{ $code }}]" 
                                   class="form-control-cms" 
                                   placeholder="{{ __('cms.card_title_placeholder') }}" 
                                   value="{{ getAdminVal('aud_c'.$i.'_title', $settings, $code) }}">
                        </div>
                        <input type="text" 
                               name="aud_c{{ $i }}_highlight[{{ $code }}]" 
                               class="form-control-cms mb-2 fw-bold text-muted" 
                               placeholder="{{ __('cms.highlight_text_placeholder') }}" 
                               value="{{ getAdminVal('aud_c'.$i.'_highlight', $settings, $code) }}">
                        <textarea name="aud_c{{ $i }}_desc[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2" 
                                  placeholder="{{ __('cms.description_placeholder') }}">{{ getAdminVal('aud_c'.$i.'_desc', $settings, $code) }}</textarea>
                    </div>
                    @endfor

                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label-premium">{{ __('cms.bottom_note') }}</label>
                            <input type="text" 
                                   name="audience_bottom_text[{{ $code }}]" 
                                   class="form-control-cms mb-2" 
                                   value="{{ getAdminVal('audience_bottom_text', $settings, $code) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-premium">{{ __('cms.button_text') }}</label>
                            <input type="text" 
                                   name="audience_btn_text[{{ $code }}]" 
                                   class="form-control-cms" 
                                   value="{{ getAdminVal('audience_btn_text', $settings, $code) }}">
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>

            <div class="row g-3 mb-4">
                @for($i=1; $i<=$maxCards; $i++)
                <div class="col-md-{{ $maxCards == 5 ? '4' : '3' }}">
                    <label class="small text-muted">{{ __('cms.card_'.$i) }} Icon</label>
                    <input type="text" name="aud_c{{ $i }}_icon" class="form-control-cms" value="{{ $settings['aud_c'.$i.'_icon'] ?? 'fa-solid fa-star' }}">
                    
                    @if($activeDesign === 'design2')
                    <div class="mt-3">
                        <label class="small text-muted d-block">{{ __('cms.card_'.$i) }} Background Image</label>
                        @if(!empty($settings['aud_c'.$i.'_image']))
                            <img src="{{ asset('storage/'.$settings['aud_c'.$i.'_image']) }}" class="img-thumbnail mb-2" style="max-height: 60px;">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="delete_aud_c{{ $i }}_image" value="1" id="delete_aud_c{{ $i }}_image">
                                <label class="form-check-label text-danger small" for="delete_aud_c{{ $i }}_image">Remove Image</label>
                            </div>
                        @endif
                        <input type="file" name="aud_c{{ $i }}_image_file" class="form-control-cms" accept="image/*">
                    </div>
                    @endif
                </div>
                @endfor
            </div>

            <div class="mb-3">
                <label class="form-label-premium">{{ __('cms.button_link') }}</label>
                <input type="text" name="audience_btn_link" class="form-control-cms" value="{{ $settings['audience_btn_link'] ?? '#features' }}">
            </div>

        </div>
    </div>
</div>