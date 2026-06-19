@php
    // Smart Translation Helper
    if (!function_exists('get_trans')) {
        function get_trans($jsonString) {
            if (empty($jsonString)) return '';
            $decoded = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return $jsonString; 
            }
            $locale = app()->getLocale();
            return $decoded[$locale] ?? $decoded['en'] ?? reset($decoded) ?? '';
        }
    }

    $bgStyles = ['blue-light', 'green-light', 'purple-light', 'orange-light', 'teal-light', 'pink-light'];
    $defaultIcons = ['fa-brain', 'fa-graduation-cap', 'fa-code', 'fa-book-open-reader', 'fa-building-user', 'fa-calendar-check'];
@endphp

<section class="section-py bg-white" id="categories">
    <div class="container">
        
        <div class="section-title">
            <h2>
                @if(!empty($settings['categories_title']))
                    {{ get_trans($settings['categories_title']) }}
                @else
                    {{ __('frontend.categories_title_default') }}
                @endif
            </h2>
            <p class="text-muted">
                @if(!empty($settings['categories_subtitle']))
                    {{ get_trans($settings['categories_subtitle']) }}
                @else
                    {{ __('frontend.categories_subtitle_default') }}
                @endif
            </p>
        </div>

        <div class="d-grid grid-3 category-grid-wrapper">
            
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $index => $category)
                    @php
                        $styleClass = $bgStyles[$index % count($bgStyles)];
                        $iconClass = $category->icon ?? $defaultIcons[$index % count($defaultIcons)];
                    @endphp

                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-icon-wrap {{ $styleClass }}">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                                @else
                                    <i class="fa-solid {{ $iconClass }}"></i>
                                @endif
                            </div>
                            <a href="{{ route('exams.list', ['category' => $category->slug]) }}" class="exam-count-badge">
                                <span>{{ __('frontend.category_exams_count', ['count' => $category->exams_count ?? 0]) }}</span> 
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        {{-- Note: Category Names come from Database, usually stored as simple string or translatable model --}}
                        {{-- If your category model supports translation, use $category->name --}}
                        <h5 class="category-title">
                            {{ $category->name }}
                        </h5>
                        
                        <p class="category-desc">
                            {{ Str::limit($category->description, 80) }}
                        </p>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5 w-100" style="grid-column: 1 / -1;">
                    <div class="mb-3">
                        <i class="fa-solid fa-folder-open text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-muted">{{ __('frontend.no_categories_found') }}</p>
                </div>
            @endif

        </div>

        <div class="text-center mt-5">
            <p class="text-muted small mb-3">
                @if(!empty($settings['categories_bottom_text']))
                    {{ get_trans($settings['categories_bottom_text']) }}
                @else
                    {{ __('frontend.categories_bottom_text_default') }}
                @endif
            </p>
            @if(!empty($settings['categories_btn_text']))
                <a href="{{ $settings['categories_btn_link'] ?? '#' }}" class="btn btn-outline">
                    <i class="fa-solid fa-layer-group me-2"></i> {{ get_trans($settings['categories_btn_text']) }}
                </a>
            @endif
        </div>

    </div>
</section>