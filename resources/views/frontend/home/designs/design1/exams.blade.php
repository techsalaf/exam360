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

    $styles = [
        ['banner' => 'blue-banner',   'badge' => 'blue-badge',   'icon' => 'fa-code'],
        ['banner' => 'green-banner',  'badge' => 'green-badge',  'icon' => 'fa-puzzle-piece'],
        ['banner' => 'orange-banner', 'badge' => 'orange-badge', 'icon' => 'fa-flask'],
        ['banner' => 'purple-banner', 'badge' => 'purple-badge', 'icon' => 'fa-chart-line'],
    ];

    $btnTextRaw = get_trans($settings['exams_sub_btn_text'] ?? '');
    $btnTextClean = trim(preg_replace('/\s+/', ' ', $btnTextRaw));
@endphp

<section class="section-py bg-light" id="exams">
    <div class="container">
        
        <div class="section-title">
            <h2>{{ get_trans($settings['exams_title'] ?? '') ?: __('frontend.exams_title_default') }}</h2>
            <p>{{ get_trans($settings['exams_subtitle'] ?? '') ?: __('frontend.exams_subtitle_default') }}</p>
        </div>

        <div class="d-grid grid-3 exam-grid-wrapper">
            
            @if(isset($featuredExams) && count($featuredExams) > 0)
                @foreach($featuredExams as $index => $exam)
                    @php
                        $style = $styles[$index % count($styles)];
                        $catNameRaw = optional($exam->category)->name ?? 'General';
                        // Assuming category name is either plain text or translated by its model/helper
                        $catName = $catNameRaw; 
                        
                        // NOTE: Exam title/description should ideally be translated via its model's json field
                        $examTitle = get_trans($exam->title);
                        $examDesc = \Illuminate\Support\Str::limit(get_trans($exam->description), 60);
                    @endphp

                    <div class="exam-card">
                        
                        @if(!empty($exam->banner))
                            <div class="exam-banner-img-wrapper">
                                <img src="{{ Storage::url($exam->banner) }}" alt="{{ $examTitle }}" class="img-fluid w-100 h-100 object-fit-cover">
                            </div>
                        @else
                            <div class="exam-banner-top {{ $style['banner'] }}">
                                <i class="fa-solid {{ $style['icon'] }} fa-3x"></i>
                            </div>
                        @endif
                        
                        <div class="exam-header">
                            <span class="badge-category {{ $style['badge'] }}">
                                {{ \Illuminate\Support\Str::upper($catName) }}
                            </span>
                            
                            @if($exam->is_paid)
                                <span class="price text-primary">
                                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($exam->price, 2) }}
                                </span>
                            @else
                                <span class="price free">{{ __('frontend.free_badge') }}</span>
                            @endif
                        </div>
                        
                        <h5 class="exam-title">{{ $examTitle }}</h5>
                        <p class="exam-desc">{{ $examDesc }}</p>
                        
                        <div class="exam-meta">
                            <span>
                                <i class="fa-regular fa-clock"></i> {{ $exam->duration_minutes }} {{ __('frontend.mins') }}
                            </span>
                            <span>
                                <i class="fa-regular fa-circle-question"></i> {{ $exam->questions_count ?? 0 }} {{ __('frontend.questions_count') }}
                            </span>
                        </div>
                        
                        <div class="exam-action mt-auto">
                            @if($exam->is_paid)
                                <a href="{{ route('exams.list') }}" class="btn btn-primary btn-exam-full">
                                    <i class="fa-solid fa-cart-shopping me-1"></i> {{ __('frontend.buy_exam_btn') }}
                                </a>
                            @else
                                <a href="{{ route('exams.list') }}" class="btn btn-outline btn-exam-full">
                                    <i class="fa-solid fa-play me-1"></i> {{ __('frontend.start_free_btn') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5 w-100" style="grid-column: 1 / -1;">
                    <div class="mb-3">
                        <i class="fa-solid fa-file-invoice text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-muted">{{ __('frontend.no_active_exams') }}</p>
                    <p class="text-muted small mt-2">{{ __('frontend.exams_admin_panel_note') }}</p>
                </div>
            @endif

        </div>

        <div class="subscription-wrapper">
            <div class="subscription-strip">
                <div class="sub-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="sub-content">
                    <h5>{{ get_trans($settings['exams_sub_title'] ?? '') ?: __('frontend.sub_strip_title_default') }}</h5>
                    <p>{{ get_trans($settings['exams_sub_desc'] ?? '') ?: __('frontend.sub_strip_desc_default') }}</p>
                </div>
                @if(!empty($settings['exams_sub_btn_text']))
                    <a href="{{ $settings['exams_sub_btn_link'] ?? '#' }}" class="btn btn-primary" style="white-space: nowrap;">
                        {{ $btnTextClean }}
                    </a>
                @endif
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                {{ get_trans($settings['exams_bottom_text'] ?? '') ?: __('frontend.exams_bottom_text_default') }}
            </p>
        </div>
    </div>
</section>