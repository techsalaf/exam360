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

    // Defaults for Admin UI labels
    $stepLabels = [
        1 => 'Install & Configure',
        2 => 'Create Exams',
        3 => 'Set Pricing',
        4 => 'Track & Scale'
    ];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-works" aria-expanded="false" aria-controls="sec-works">
        <div class="d-flex align-items-center">
            <span class="section-badge">05</span>
            <span><i class="fa-solid fa-signs-post me-2"></i> {{ __('cms.how_it_works') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    
    <div id="sec-works" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="hiwTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="hiw-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#hiw-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="hiwTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="hiw-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Main Heading --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            {{ __('cms.main_heading') }}
                            <span class="text-muted small ms-1">({{ strtoupper($code) }})</span>
                        </label>
                        <input type="text" 
                               name="how_it_works_title[{{ $code }}]" 
                               class="form-control-cms mb-2" 
                               value="{{ getAdminVal('how_it_works_title', $settings, $code) }}">
                        
                        <label class="form-label-premium mt-3">
                            {{ __('cms.subtext_description') }}
                            <span class="text-muted small ms-1">({{ strtoupper($code) }})</span>
                        </label>
                        <textarea name="how_it_works_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('how_it_works_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    {{-- Loop for 4 Steps (Text Only) --}}
                    <div class="d-flex flex-column gap-3">
                        @foreach($stepLabels as $key => $label)
                            <div class="p-3 bg-light-cms rounded border">
                                <h6 class="fw-bold text-dark mb-3">Step 0{{ $key }}: {{ $label }} ({{ strtoupper($code) }})</h6>
                                
                                <div class="mb-2">
                                    <label class="small text-muted fw-bold">{{ __('cms.title') }}</label>
                                    <input type="text" 
                                           name="hiw_s{{$key}}_title[{{ $code }}]" 
                                           class="form-control-cms" 
                                           value="{{ getAdminVal("hiw_s{$key}_title", $settings, $code) }}">
                                </div>
                                
                                <label class="small text-muted fw-bold">{{ __('cms.description') }}</label>
                                <textarea name="hiw_s{{$key}}_desc[{{ $code }}]" 
                                          class="form-control-cms" 
                                          rows="2">{{ getAdminVal("hiw_s{$key}_desc", $settings, $code) }}</textarea>
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
                @php
                    $defaultIcons = [
                        1 => 'fa-solid fa-download', 
                        2 => 'fa-solid fa-robot', 
                        3 => 'fa-solid fa-tags', 
                        4 => 'fa-solid fa-chart-line'
                    ];
                @endphp

                @foreach($stepLabels as $key => $label)
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted">Step {{ $key }} Icon</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="{{ $settings["hiw_s{$key}_icon"] ?? $defaultIcons[$key] }}"></i></span>
                            <input type="text" name="hiw_s{{$key}}_icon" class="form-control form-control-cms" value="{{ $settings["hiw_s{$key}_icon"] ?? $defaultIcons[$key] }}">
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>