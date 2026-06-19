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

    // Defaults for Admin UI labels (for placeholders/fallbacks)
    $adminStats = [
        1 => 'Users Supported', 2 => 'Role-Based Access', 3 => 'Revenue Tracking', 4 => 'Usage & Cost Control'
    ];
    $adminFeatures = [
        1 => 'User & Role Control', 2 => 'Revenue & Subscriptions', 3 => 'AI Usage & Limits', 4 => 'System Configuration'
    ];
    $adminChecks = [
        1 => 'No Coding Required', 2 => 'Enterprise-Ready Architecture', 3 => 'Built on Laravel 10'
    ];
@endphp

<div class="cms-accordion-item">
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-admin" aria-expanded="false" aria-controls="sec-admin">
        <div class="d-flex align-items-center">
            <span class="section-badge">07</span>
            <span><i class="fa-solid fa-desktop me-2"></i> {{ __('cms.admin_preview_section') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    <div id="sec-admin" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="admin-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#admin-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="adminTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="admin-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Heading --}}
                    <div class="mb-4">
                        <label class="form-label-premium">{{ __('cms.heading') }}</label>
                        <input type="text" 
                               name="admin_preview_title[{{ $code }}]" 
                               class="form-control-cms mb-2" 
                               value="{{ getAdminVal('admin_preview_title', $settings, $code) }}">
                        <textarea name="admin_preview_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('admin_preview_subtitle', $settings, $code) }}</textarea>
                    </div>

                    <hr class="border-light my-4">

                    <h6 class="fw-bold text-dark mb-3">{{ __('cms.stats_row') }} (Labels - {{ strtoupper($code) }})</h6>
                    <div class="row g-2 mb-4">
                        @foreach($adminStats as $i => $label)
                            <div class="col-md-3">
                                <div class="p-2 border rounded bg-white">
                                    <label class="small text-muted fw-bold">{{ __('cms.stat') }} {{ $i }} Label</label>
                                    <input type="text" 
                                           name="admin_stat_{{$i}}_lbl[{{ $code }}]" 
                                           class="form-control-cms form-control-sm" 
                                           placeholder="{{ __('cms.label') }}" 
                                           value="{{ getAdminVal("admin_stat_{$i}_lbl", $settings, $code) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h6 class="fw-bold text-dark mb-3">{{ __('cms.feature_grid') }} ({{ strtoupper($code) }})</h6>
                    <div class="d-flex flex-column gap-3 mb-4">
                        @foreach($adminFeatures as $i => $title)
                            <div class="p-2 border rounded bg-white">
                                <label class="small text-muted fw-bold">Feature {{ $i }} Title</label>
                                <input type="text" 
                                       name="admin_feat_{{$i}}_title[{{ $code }}]" 
                                       class="form-control-cms form-control-sm" 
                                       value="{{ getAdminVal("admin_feat_{$i}_title", $settings, $code) }}">

                                <label class="small text-muted fw-bold mt-2">Feature {{ $i }} Description</label>
                                <textarea name="admin_feat_{{$i}}_desc[{{ $code }}]" 
                                          class="form-control-cms form-control-sm" 
                                          rows="2" 
                                          placeholder="{{ __('cms.description') }}">{{ getAdminVal("admin_feat_{$i}_desc", $settings, $code) }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <h6 class="fw-bold text-dark mb-3">{{ __('cms.trust_checkmarks') }} ({{ strtoupper($code) }})</h6>
                    <div class="row g-2">
                        @foreach($adminChecks as $i => $check)
                            <div class="col-md-4">
                                <input type="text" 
                                       name="admin_check_{{$i}}[{{ $code }}]" 
                                       class="form-control-cms" 
                                       value="{{ getAdminVal("admin_check_{$i}", $settings, $code) }}">
                            </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <!-- Global Settings (Image, Stat Values, Icons) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>

            {{-- Image Upload Area --}}
            <div class="mb-4 p-3 bg-light-cms rounded border">
                <h6 class="fw-bold text-dark mb-3">{{ __('cms.dashboard_screenshot') }}</h6>
                <div class="image-upload-container">
                    <label class="image-upload-label {{ !empty($settings['admin_preview_image']) ? 'has-image' : '' }}" id="admin_img_label">
                        <input type="file" 
                               name="admin_preview_image_file" 
                               class="file-input generic-image-input" 
                               accept="image/*">
                        
                        <div class="upload-content text-center">
                            <div class="upload-icon-wrapper"><i class="fa-regular fa-image"></i></div>
                            <div class="upload-text"><span>{{ __('cms.click_to_upload') }}</span></div>
                        </div>

                        <img id="adminPreviewImg" 
                             src="{{ !empty($settings['admin_preview_image']) ? Storage::url($settings['admin_preview_image']) : '' }}" 
                             class="image-preview {{ !empty($settings['admin_preview_image']) ? 'show' : '' }}">
                        
                        <div class="btn-remove-image generic-image-remove">
                            <i class="fa-solid fa-trash"></i> {{ __('cms.remove') }}
                        </div>
                    </label>
                </div>
                <input type="hidden" name="delete_admin_preview_image" value="0">
                <div class="upload-help-text mt-2"><i class="fa-solid fa-circle-info"></i> {{ __('cms.upload_help_text') }}</div>
            </div>

            {{-- Stat Values (Non-Translatable) --}}
            <h6 class="fw-bold text-dark mb-3">{{ __('cms.stats_row') }} (Values)</h6>
            <div class="row g-2 mb-4">
                @foreach($adminStats as $i => $label)
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold">Stat {{ $i }} Value</label>
                        <input type="text" 
                               name="admin_stat_{{$i}}_val" 
                               class="form-control-cms form-control-sm" 
                               value="{{ $settings["admin_stat_{$i}_val"] ?? '' }}">
                    </div>
                @endforeach
            </div>
            
            {{-- Feature Icons (Non-Translatable) --}}
            <h6 class="fw-bold text-dark mb-3">{{ __('cms.feature_grid') }} (Icons)</h6>
            <div class="row g-2">
                @php
                    $defaultIcons = ['fa-users-gear', 'fa-credit-card', 'fa-bolt', 'fa-wrench'];
                @endphp
                @foreach($adminFeatures as $i => $title)
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold">Feature {{ $i }} Icon</label>
                        <input type="text" 
                               name="admin_feat_{{$i}}_icon" 
                               class="form-control-cms form-control-sm" 
                               placeholder="fa-solid fa-icon"
                               value="{{ $settings["admin_feat_{$i}_icon"] ?? $defaultIcons[$i-1] }}">
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>