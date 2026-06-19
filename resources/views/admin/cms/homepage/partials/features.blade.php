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

    // Panel Defaults Structure (Just titles for admin reference)
    $panels = [
        1 => 'AI Exam Creation',
        2 => 'Automated Evaluation',
        3 => 'Monetization',
        4 => 'Management'
    ];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-features">
        <div class="d-flex align-items-center">
            <span class="section-badge">04</span>
            <span><i class="fa-solid fa-star me-2"></i> {{ __('cms.features_section') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    
    <div id="sec-features" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="featTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="feat-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#feat-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="featTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="feat-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Global Heading --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            {{ __('cms.main_heading') }}
                            <span class="text-muted small ms-1">({{ strtoupper($code) }})</span>
                        </label>
                        <input type="text" 
                               name="features_title[{{ $code }}]" 
                               class="form-control-cms mb-2" 
                               value="{{ getAdminVal('features_title', $settings, $code) }}">
                        
                        <label class="form-label-premium mt-3">
                            {{ __('cms.subtext_description') }}
                            <span class="text-muted small ms-1">({{ strtoupper($code) }})</span>
                        </label>
                        <textarea name="features_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('features_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    {{-- Loop through 4 Panels --}}
                    @foreach($panels as $key => $panelName)
                        <div class="cms-card mb-4 border h-auto">
                            <div class="cms-header bg-light-cms py-2 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold text-dark m-0">Panel 0{{ $key }}: {{ $panelName }} ({{ strtoupper($code) }})</h6>
                            </div>
                            
                            <div class="cms-body">
                                
                                <!-- Panel Sidebar Info -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small fw-bold text-muted">{{ __('cms.panel_title') }}</label>
                                        <input type="text" 
                                               name="feat_p{{$key}}_title[{{ $code }}]" 
                                               class="form-control-cms" 
                                               value="{{ getAdminVal("feat_p{$key}_title", $settings, $code) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-bold text-muted">{{ __('cms.hint_text') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="fa-solid fa-tag"></i></span>
                                            <input type="text" 
                                                   name="feat_p{{$key}}_hint_text[{{ $code }}]" 
                                                   class="form-control form-control-cms" 
                                                   value="{{ getAdminVal("feat_p{$key}_hint_text", $settings, $code) }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="small fw-bold text-muted">{{ __('cms.description') }}</label>
                                        <textarea name="feat_p{{$key}}_desc[{{ $code }}]" 
                                                  class="form-control-cms" 
                                                  rows="2">{{ getAdminVal("feat_p{$key}_desc", $settings, $code) }}</textarea>
                                    </div>
                                </div>

                                <!-- 3 Grid Items -->
                                <label class="form-label-premium mb-2">{{ __('cms.grid_features') }} ({{ strtoupper($code) }})</label>
                                <div class="d-flex flex-column gap-3">
                                    @for($i = 1; $i <= 3; $i++)
                                        <div class="p-2 border rounded bg-white">
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="flex-grow-1">
                                                    <label class="small text-muted mb-0">Feature {{ $i }} Title</label>
                                                    <input type="text" 
                                                           name="feat_p{{$key}}_i{{$i}}_title[{{ $code }}]" 
                                                           class="form-control-cms form-control-sm" 
                                                           placeholder="{{ __('cms.feature_title') }}" 
                                                           value="{{ getAdminVal("feat_p{$key}_i{$i}_title", $settings, $code) }}">
                                                </div>
                                            </div>
                                            <textarea name="feat_p{{$key}}_i{{$i}}_desc[{{ $code }}]" 
                                                      class="form-control-cms form-control-sm" 
                                                      rows="2" 
                                                      placeholder="{{ __('cms.feature_desc') }}">{{ getAdminVal("feat_p{$key}_i{$i}_desc", $settings, $code) }}</textarea>
                                        </div>
                                    @endfor
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
                @endforeach
            </div>
            
            <hr class="border-light my-4">

            <!-- Global Settings (Icons Only) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-icons me-2"></i>Global Icons (All Languages)</h6>

            @foreach($panels as $key => $panelName)
                <div class="mb-3 p-3 bg-light border rounded">
                    <h6 class="small fw-bold text-dark mb-2">Panel 0{{ $key }} Icons</h6>
                    <div class="row g-2">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Item {{ $i }}</span>
                                    <input type="text" name="feat_p{{$key}}_i{{$i}}_icon" 
                                           class="form-control" 
                                           placeholder="fa-solid fa-star"
                                           value="{{ $settings["feat_p{$key}_i{$i}_icon"] ?? '' }}">
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>