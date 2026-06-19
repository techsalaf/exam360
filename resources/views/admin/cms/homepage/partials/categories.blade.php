@php
    // Helper to retrieve specific language value safely
    if (!function_exists('getAdminVal')) {
        function getAdminVal($key, $settings, $lang) {
            $raw = $settings[$key] ?? '';
            $decoded = json_decode($raw, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded[$lang] ?? '';
            }
            // Fallback for legacy plain text (only show in English tab)
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
    <button class="cms-accordion-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-cats" aria-expanded="false" aria-controls="sec-cats">
        <div class="d-flex align-items-center">
            <span class="section-badge">02</span>
            <span><i class="fa-solid fa-layer-group me-2"></i> {{ __('cms.categories_section') }}</span>
        </div>
        <i class="fa-solid fa-chevron-down small"></i>
    </button>
    
    <div id="sec-cats" class="collapse">
        <div class="cms-accordion-body">
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-3" id="catTabs" role="tablist">
                @foreach($languages as $code => $lang)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $code === 'en' ? 'active' : '' }}" 
                            id="cat-tab-{{ $code }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#cat-content-{{ $code }}" 
                            type="button" role="tab">
                        <span class="fi fi-{{ $lang['flag'] }} me-2"></span> {{ $lang['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="catTabContent">
                @foreach($languages as $code => $lang)
                <div class="tab-pane fade {{ $code === 'en' ? 'show active' : '' }}" id="cat-content-{{ $code }}" role="tabpanel">
                    
                    {{-- Heading --}}
                    <div class="mb-3">
                        <label class="form-label-premium">
                            {{ __('cms.section_heading') }}
                            <span class="text-muted small ms-1">
                                (<span class="fi fi-{{ $lang['flag'] }}"></span> {{ strtoupper($code) }})
                            </span>
                        </label>
                        <input type="text" 
                               name="categories_title[{{ $code }}]" 
                               class="form-control-cms" 
                               value="{{ getAdminVal('categories_title', $settings, $code) }}">
                    </div>
                    
                    {{-- Subtitle --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            {{ __('cms.section_subtext') }}
                            <span class="text-muted small ms-1">
                                (<span class="fi fi-{{ $lang['flag'] }}"></span> {{ strtoupper($code) }})
                            </span>
                        </label>
                        <textarea name="categories_subtitle[{{ $code }}]" 
                                  class="form-control-cms" 
                                  rows="2">{{ getAdminVal('categories_subtitle', $settings, $code) }}</textarea>
                    </div>

                    {{-- Bottom Area Translatable Fields --}}
                    <div class="p-3 bg-light-cms rounded border mb-3">
                        <h6 class="fw-bold text-dark mb-3">{{ __('cms.bottom_action_area') }} ({{ strtoupper($code) }})</h6>
                        
                        <div class="mb-3">
                            <label class="form-label-premium">{{ __('cms.small_note_text') }}</label>
                            <input type="text" 
                                   name="categories_bottom_text[{{ $code }}]" 
                                   class="form-control-cms" 
                                   value="{{ getAdminVal('categories_bottom_text', $settings, $code) }}">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label-premium">{{ __('cms.button_label') }}</label>
                                <input type="text" 
                                       name="categories_btn_text[{{ $code }}]" 
                                       class="form-control-cms" 
                                       value="{{ getAdminVal('categories_btn_text', $settings, $code) }}">
                            </div>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <hr class="border-light my-4">

            <!-- Global Settings (Category Selection & Links) -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-globe me-2"></i>Global Settings (All Languages)</h6>

            {{-- Category Selection --}}
            <div class="p-3 bg-white border rounded mb-4">
                <h6 class="fw-bold text-dark mb-2">{{ __('cms.select_categories') }}</h6>
                <p class="text-muted small mb-3">{{ __('cms.select_categories_desc') }}</p>
                
                <div class="d-flex flex-wrap gap-2 category-scroll-box" style="max-height: 200px; overflow-y: auto;">
                    @php
                        $selectedIds = json_decode($settings['home_categories_list'] ?? '[]', true);
                        if(!is_array($selectedIds)) $selectedIds = [];
                    @endphp

                    @foreach($allCategories as $cat)
                        <div class="form-check form-check-inline bg-light border rounded px-3 py-2 m-0">
                            <input class="form-check-input" type="checkbox" 
                                   name="selected_categories[]" 
                                   value="{{ $cat->id }}" 
                                   id="cat_{{ $cat->id }}"
                                   {{ in_array($cat->id, $selectedIds) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small ms-1" for="cat_{{ $cat->id }}">
                                {{ $cat->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="form-text mt-2 text-primary small"><i class="fa-solid fa-info-circle"></i> {{ __('cms.categories_auto_hint') }}</div>
            </div>

            {{-- Button Link --}}
            <div class="mb-3">
                <label class="form-label-premium">{{ __('cms.button_link') }}</label>
                <input type="text" name="categories_btn_link" class="form-control-cms" value="{{ $settings['categories_btn_link'] ?? '#' }}">
            </div>

        </div>
    </div>
</div>