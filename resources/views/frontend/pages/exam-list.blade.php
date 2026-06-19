@extends('frontend.layout')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/frontend/css/exam-list.css') }}">

@php
    $userExamData = [];
    if(Auth::check()) {
        $rawStatus = DB::table('exam_user')
            ->where('user_id', Auth::id())
            ->select('exam_id', 'status', 'updated_at')
            ->get();

        foreach($rawStatus as $st) {
            $userExamData[$st->exam_id] = $st;
        }
    }
@endphp

<section class="page-header section-py bg-light text-center">
    <div class="container">
        <h1 class="page-title">{{ __('frontend.explore_exams_title') }}</h1>
        <p class="text-muted">{{ __('frontend.explore_exams_desc') }}</p>
    </div>
</section>

<section class="section-py">
    <div class="container">
        <div class="exam-list-layout">
            
            <aside class="exam-sidebar">
                <form action="{{ route('exams.list') }}" method="GET" id="examFilterForm">
                    <div id="sidebar-content">
                        <div class="sidebar-header">
                            <h5 class="sidebar-title">{{ __('frontend.filters_title') }}</h5>
                            <button type="button" class="btn-reset" onclick="window.location.href='{{ route('exams.list') }}'">{{ __('frontend.reset_btn') }}</button>
                        </div>

                        <div class="sidebar-widget search-widget">
                            <div class="input-icon-wrap">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('frontend.search_placeholder') }}" class="form-control">
                            </div>
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="widget-title">{{ __('frontend.categories_title') }}</h6>
                            <div class="filter-group">
                                @foreach($categories as $category)
                                    <label class="custom-checkbox">
                                        <input type="checkbox" 
                                               name="category[]" 
                                               value="{{ $category->slug }}" 
                                               {{ in_array($category->slug, (array)request('category')) ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span class="label-text">{{ $category->name }}</span>
                                        <span class="count">({{ $category->exams_count ?? 0 }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="widget-title">{{ __('frontend.price_title') }}</h6>
                            <div class="filter-group">
                                <label class="custom-radio">
                                    <input type="radio" name="price_type" value="" {{ !request('price_type') ? 'checked' : '' }}>
                                    <span class="radio-mark"></span>
                                    <span class="label-text">{{ __('frontend.all_prices') }}</span>
                                </label>
                                <label class="custom-radio">
                                    <input type="radio" name="price_type" value="free" {{ request('price_type') == 'free' ? 'checked' : '' }}>
                                    <span class="radio-mark"></span>
                                    <span class="label-text">{{ __('frontend.free_only') }}</span>
                                </label>
                                <label class="custom-radio">
                                    <input type="radio" name="price_type" value="paid" {{ request('price_type') == 'paid' ? 'checked' : '' }}>
                                    <span class="radio-mark"></span>
                                    <span class="label-text">{{ __('frontend.paid_only') }}</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="sidebar-footer">
                            <button type="submit" class="btn btn-primary w-100">{{ __('frontend.apply_filters_btn') }}</button>
                        </div>
                    </div>
                </form>
            </aside>

            <div class="exam-grid-content">
                
                @if($exams->total() > 0)
                    <div class="exam-top-bar">
                        <span class="result-text">
                            {{ __('frontend.showing_results', [
                                'first' => $exams->firstItem() ?? 0, 
                                'last' => $exams->lastItem() ?? 0, 
                                'total' => $exams->total()
                            ]) }}
                        </span>
                        <div class="sort-wrap">
                            <form action="{{ route('exams.list') }}" method="GET" id="examSortForm">
                                @foreach(request()->except(['sort_by', 'page']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $v)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                
                                <select name="sort_by" class="form-select" onchange="document.getElementById('examSortForm').submit()">
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>{{ __('frontend.sort_newest') }}</option>
                                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>{{ __('frontend.sort_price_low') }}</option>
                                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>{{ __('frontend.sort_price_high') }}</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div id="exam-grid" class="d-grid grid-3">
                        @php
                            $styles = [
                                ['banner' => 'bg-info-soft',   'icon' => 'fa-globe',    'color' => '#3B82F6'],
                                ['banner' => 'bg-success-soft','icon' => 'fa-leaf',     'color' => '#10B981'],
                                ['banner' => 'bg-warning-soft','icon' => 'fa-brain',    'color' => '#F59E0B'],
                                ['banner' => 'bg-purple-soft', 'icon' => 'fa-pen-nib',  'color' => '#8B5CF6'],
                            ];
                        @endphp

                        @foreach($exams as $index => $exam)
                            @php
                                $style = $styles[$index % count($styles)];
                                $category = $exam->category ?? (object)['name' => 'General', 'icon' => $style['icon']];
                                $catIcon = $category->icon ?? $style['icon'];
                                $qnsCount = $exam->questions_count ?? 0;
                                
                                $userData = $userExamData[$exam->id] ?? null;
                                $status = $userData ? $userData->status : null;

                                $isResultPublished = false;
                                if ($status === 'completed') {
                                    if ($exam->is_paid) {
                                        $isResultPublished = $exam->result_date && \Carbon\Carbon::parse($exam->result_date)->isPast();
                                    } else {
                                        $isResultPublished = !$exam->result_date || \Carbon\Carbon::parse($exam->result_date)->isPast();
                                    }
                                }

                                $isUpcoming = $exam->start_date && \Carbon\Carbon::parse($exam->start_date)->isFuture();
                                $hasDirectAccess = Auth::check() && Auth::user()->canAccessExam($exam);
                            @endphp

                            <div class="exam-card">
                                <div class="exam-banner-top" style="background-color: {{ $style['color'] }}20; color: {{ $style['color'] }};">
                                    @if(!empty($exam->banner))
                                        <img src="{{ Storage::url($exam->banner) }}" alt="{{ $exam->title }}" class="img-fluid w-100 h-100 object-fit-cover">
                                    @else
                                        <i class="fa-solid {{ $catIcon }} fa-3x"></i>
                                    @endif
                                </div>
                                
                                <div class="exam-header">
                                    <span class="badge-category" style="background-color: {{ $style['color'] }}20; color: {{ $style['color'] }};">
                                        {{ Str::upper($category->name) }}
                                    </span>
                                    
                                    @if($exam->is_paid)
                                        <span class="price text-primary" style="color: var(--primary) !important;">
                                            {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($exam->price, 2) }}
                                        </span>
                                    @else
                                        <span class="price free">{{ __('frontend.free_badge') }}</span>
                                    @endif
                                </div>
                                
                                <h5 class="exam-title">{{ $exam->title }}</h5>
                                <p class="exam-desc">
                                    {{ Str::limit($exam->description, 60) }}
                                </p>
                                
                                <div class="exam-meta">
                                    <span>
                                        <i class="fa-regular fa-clock"></i> {{ $exam->duration_minutes }} {{ __('frontend.mins') }}
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-circle-question"></i> {{ $qnsCount }} {{ __('frontend.questions_count') }}
                                    </span>
                                </div>
                                
                                <div class="exam-action mt-auto">
                                    @if ($hasDirectAccess)
                                        @if($isUpcoming)
                                            <button class="btn btn-light border btn-exam-full text-muted" disabled style="cursor: not-allowed; background-color: #f8fafc;">
                                                <i class="fa-regular fa-calendar-check me-2"></i> 
                                                {{ __('frontend.starts') }} {{ \Carbon\Carbon::parse($exam->start_date)->translatedFormat('M d') }}
                                            </button>
                                        @elseif($status === 'completed' && !$isResultPublished)
                                            <button class="btn btn-light border btn-exam-full text-muted" disabled style="cursor: not-allowed; background-color: #f8fafc;">
                                                <i class="fa-regular fa-clock me-2"></i> {{ __('frontend.result_pending') }}
                                            </button>
                                        @else
                                            <a href="{{ route('exam.participate', $exam->slug) }}" 
                                               class="btn btn-primary btn-exam-full" 
                                               style="background-color: #10b981; border-color: #10b981; color: white;">
                                                <i class="fa-solid fa-play me-2"></i> 
                                                {{ $status === 'completed' ? __('frontend.retake_exam_btn') : __('frontend.start_exam_btn') }}
                                            </a>
                                        @endif

                                    @elseif ($status === 'pending')
                                        <button class="btn btn-secondary btn-exam-full" disabled style="opacity: 0.8; cursor: not-allowed;">
                                            <i class="fa-solid fa-clock me-2"></i> {{ __('frontend.payment_pending') }}
                                        </button>

                                    @else
                                        @if($isUpcoming)
                                            @if($exam->is_paid)
                                                <a href="{{ route('checkout.add', $exam->id) }}" class="btn btn-primary btn-exam-full">
                                                    <i class="fa-solid fa-cart-shopping me-2"></i> {{ __('frontend.buy_exam_btn') }}
                                                </a>
                                            @else
                                                <button class="btn btn-light border btn-exam-full text-muted" disabled style="cursor: not-allowed;">
                                                    <i class="fa-regular fa-calendar-check me-2"></i> 
                                                    {{ __('frontend.starts') }} {{ \Carbon\Carbon::parse($exam->start_date)->translatedFormat('M d') }}
                                                </button>
                                            @endif
                                        @else
                                            @if($exam->is_paid)
                                                <a href="{{ route('checkout.add', $exam->id) }}" class="btn btn-primary btn-exam-full">
                                                    <i class="fa-solid fa-cart-shopping me-2"></i> {{ __('frontend.buy_exam_btn') }}
                                                </a>
                                            @else
                                                <a href="{{ route('exam.participate', $exam->slug) }}" class="btn btn-outline btn-exam-full">
                                                    <i class="fa-solid fa-play me-2"></i> {{ __('frontend.start_free_btn') }}
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($exams->hasPages())
                        <div class="exam-list-footer">
                            <span class="result-text">
                                {{ __('frontend.showing_results_footer', [
                                    'first' => $exams->firstItem() ?? 0, 
                                    'last' => $exams->lastItem() ?? 0, 
                                    'total' => $exams->total()
                                ]) }}
                            </span>
                            
                            <div class="pagination-container">
                                {{ $exams->appends(request()->input())->links('frontend.partials.pagination-list') }}
                            </div>
                        </div>
                    @endif

                @else
                    <div class="empty-state-exam">
                        <div class="empty-icon-wrap">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3 class="empty-title">{{ __('frontend.no_exams_match') }}</h3>
                        <p class="empty-desc">
                            {{ __('frontend.no_exams_suggestion') }}
                        </p>
                        <a href="{{ route('exams.list') }}" class="btn btn-outline-primary">{{ __('frontend.clear_all_filters') }}</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterForm = document.getElementById('examFilterForm');
        const searchInput = document.querySelector('input[name="search"]');
        if(searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterForm.submit();
                }
            });
        }
    });
</script>
@endsection