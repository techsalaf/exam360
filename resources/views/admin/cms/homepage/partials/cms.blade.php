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

    // Default Features for Admin view labels
    $adminCmsFeatures = [
        1 => 'Dynamic Pages', 2 => 'Menu Builder', 3 => 'SEO Ready', 4 => 'Homepage Sections'
    ];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-cms" aria-expanded="false" aria-controls="sec-cms">
        <div class="d-flex align-items-center">
            <span class="section-badge">08</span>
            <span><i class="fa-solid fa-laptop-code me-2"></i> {{ __('cms.cms_section_title') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-cms" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="cmsTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="cms-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#cms-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="cmsTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="cms-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Badge, Heading, Description --}}
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.top_badge') }}</label>
                        <input type="text" 
                               name="cms_badge[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('cms_badge', $settings, $code) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('cms.heading') }}</label>
                        <input type="text" 
                               name="cms_title[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('cms_title', $settings, $code) }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.description') }}</label>
                        <textarea name="cms_desc[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('cms_desc', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    <h6 class="fw-bold text-dark mb-3">{{ __('cms.feature_grid') }} ({{ strtoupper($code) }})</h6>
                    <div class="d-flex flex-column gap-3">
                        @foreach($adminCmsFeatures as $key => $label)
                            <div class="p-2 border rounded bg-white">
                                <label class="small text-muted fw-bold">Feature {{ $key }} Title</label>
                                <input type="text" 
                                       name="cms_feat_{{$key}}_title[{{ $code }}]" 
                                       class="form-control-cms form-control-sm" 
                                       value="{{ getAdminVal("cms_feat_{$key}_title", $settings, $code) }}">
                                
                                <label class="small text-muted fw-bold mt-2">Feature {{ $key }} Description</label>
                                <textarea name="cms_feat_{{$key}}_desc[{{ $code }}]" 
                                          class="form-control-cms form-control-sm" 
                                          rows="2">{{ getAdminVal("cms_feat_{$key}_desc", $settings, $code) }}</textarea>
                            </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <!-- Global Settings (Images & Icons) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>

            <div class="row g-3 mb-4">
                
                <!-- Image 1 -->
                <div class="col-md-6">
                    <div class="p-3 bg-light-cms rounded border h-100">
                        <h6 class="fw-bold text-dark mb-3">{{ __('cms.top_floating_image') }}</h6>
                        <div class="image-upload-container">
                            <label class="image-upload-label {{ !empty($settings['cms_image_1']) ? 'has-image' : '' }}">
                                <input type="file" name="cms_image_1_file" class="file-input generic-image-input" accept="image/*">
                                <div class="upload-content text-center">
                                    <div class="upload-icon-wrapper"><i class="fa-regular fa-image"></i></div>
                                    <div class="upload-text"><span>{{ __('cms.click_to_upload') }}</span></div>
                                </div>
                                <img src="{{ !empty($settings['cms_image_1']) ? Storage::url($settings['cms_image_1']) : '' }}" class="image-preview {{ !empty($settings['cms_image_1']) ? 'show' : '' }}">
                                <div class="btn-remove-image generic-image-remove">
                                    <i class="fa-solid fa-trash"></i> {{ __('cms.remove') }}
                                </div>
                            </label>
                            <input type="hidden" name="delete_cms_image_1" value="0">
                        </div>
                        <div class="small text-muted mt-2 text-center">{{ __('cms.replaces_menu_manager') }}</div>
                    </div>
                </div>

                <!-- Image 2 -->
                <div class="col-md-6">
                    <div class="p-3 bg-light-cms rounded border h-100">
                        <h6 class="fw-bold text-dark mb-3">{{ __('cms.bottom_floating_image') }}</h6>
                        <div class="image-upload-container">
                            <label class="image-upload-label {{ !empty($settings['cms_image_2']) ? 'has-image' : '' }}">
                                <input type="file" name="cms_image_2_file" class="file-input generic-image-input" accept="image/*">
                                <div class="upload-content text-center">
                                    <div class="upload-icon-wrapper"><i class="fa-regular fa-image"></i></div>
                                    <div class="upload-text"><span>{{ __('cms.bottom_card') }}</span></div>
                                </div>
                                <img src="{{ !empty($settings['cms_image_2']) ? Storage::url($settings['cms_image_2']) : '' }}" class="image-preview {{ !empty($settings['cms_image_2']) ? 'show' : '' }}">
                                <div class="btn-remove-image generic-image-remove">
                                    <i class="fa-solid fa-trash"></i> {{ __('cms.remove') }}
                                </div>
                            </label>
                            <input type="hidden" name="delete_cms_image_2" value="0">
                        </div>
                        <div class="small text-muted mt-2 text-center">{{ __('cms.replaces_homepage_editor') }}</div>
                    </div>
                </div>

            </div>

            <!-- Feature Icons -->
            <h6 class="fw-bold text-dark mb-3">{{ __('cms.feature_grid') }} (Icons)</h6>
            <div class="row g-2">
                @php
                    $defaultIcons = ['fa-solid fa-clone', 'fa-solid fa-bars-staggered', 'fa-solid fa-magnifying-glass', 'fa-solid fa-cube'];
                @endphp
                @foreach($adminCmsFeatures as $key => $label)
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Feature {{ $key }} Icon</label>
                        <input type="text" 
                               name="cms_feat_{{$key}}_icon" 
                               class="form-control-cms form-control-sm" 
                               placeholder="fa-solid fa-icon"
                               value="{{ $settings["cms_feat_{$key}_icon"] ?? $defaultIcons[$key-1] }}">
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>