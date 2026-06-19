@extends('layouts.admin')

@section('title', __('exams.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-exams.css') }}" rel="stylesheet">
@endpush

@section('content')

    @php
        $hasNegativeMarkingModule = \Illuminate\Support\Facades\Schema::hasColumn('exams', 'has_negative_marking');
    @endphp

    <!-- Mobile Filter Bar (Visible only on mobile < 992px) -->
    <div class="mobile-filter-bar d-lg-none mb-4">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-3 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#examFilterOffcanvas"
                aria-controls="examFilterOffcanvas">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center exam-mobile-icon-box">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div class="text-start">
                    <span class="d-block fw-bold text-dark">{{ __('exams.filter_actions') }}</span>
                    <span class="d-block text-muted small" style="font-size: 0.75rem;">{{ __('exams.search_placeholder') }} & Options</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-muted"></i>
        </button>
    </div>

    <!-- Desktop Header (Hidden on mobile) -->
    <div class="desktop-header d-none d-lg-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('exams.header_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('exams.header_desc') }}</p>
        </div>
        
        <div class="page-header-actions">
            <form action="{{ route('admin.exams.index') }}" method="GET" class="search-filter-box">
                <input type="text" name="search" value="{{ request('search') }}" class="search-filter-input" placeholder="{{ __('exams.search_placeholder') }}" autocomplete="off">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-filter-icon">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </form>
            
            @can('create_exams')
            <button type="button" class="btn btn-dark rounded-pill fw-bold px-3 d-flex align-items-center gap-2" id="triggerAiModal">
                <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                <span class="d-none d-sm-inline">{{ __('exams.btn_ai_generate') }}</span>
            </button>

            <button type="button" class="btn-premium" id="triggerCreateModal">
                <i class="fa-solid fa-plus"></i> {{ __('exams.btn_add_new') }}
            </button>
            @endcan
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="mb-4">
        <x-kpi-grid :stats="[
            ['value' => $kpi['total'], 'label' => __('exams.kpi_total'), 'icon' => 'fa-solid fa-file-signature', 'color' => 'primary'],
            ['value' => $kpi['active'], 'label' => __('exams.kpi_active'), 'icon' => 'fa-regular fa-circle-check', 'color' => 'success'],
            ['value' => $kpi['upcoming'], 'label' => __('exams.kpi_upcoming'), 'icon' => 'fa-regular fa-clock', 'color' => 'neutral']
        ]" />
    </div>

    <!-- Bulk Action Floating Bar -->
    @can('delete_exams')
    <div id="floatingBulkBar" class="bulk-floating-bar">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark"><span id="selectedCount">0</span> {{ __('exams.bulk_selected') }}</span>
        </div>
        <div class="exam-bulk-divider"></div>
        <form id="bulkDeleteForm" action="{{ route('admin.exams.bulkDestroy') }}" method="POST">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-sm btn-danger rounded-pill confirm-delete" 
                    data-title="{{ __('exams.bulk_delete_title') }}" 
                    data-text="{{ __('exams.bulk_delete_text') }}">
                <i class="fa-regular fa-trash-can me-1"></i> {{ __('exams.btn_delete') }}
            </button>
        </form>
    </div>
    @endcan

    <!-- Exam List -->
    <div class="exam-list-container mt-4">
        <!-- List Header (Desktop Only) -->
        <div class="list-header d-none d-lg-flex">
            @can('delete_exams')
            <div class="col-header col-chk">
                <input type="checkbox" id="selectAll" class="form-check-input-premium">
            </div>
            @endcan
            <div class="col-header col-title">{{ __('exams.th_title') }}</div>
            <div class="col-header col-cat">{{ __('exams.th_category') }}</div>
            <div class="col-header col-stats">{{ __('exams.th_details') }}</div>
            <div class="col-header col-schedule">{{ __('exams.th_schedule') }}</div>
            <div class="col-header col-date">{{ __('exams.th_created') }}</div>
            <div class="col-header col-status">{{ __('exams.th_status') }}</div>
            <div class="col-header col-action">{{ __('exams.th_action') }}</div>
        </div>

        @forelse($exams as $exam)
            <div class="list-item">
                @can('delete_exams')
                <div class="col-chk">
                    <input type="checkbox" name="ids[]" value="{{ $exam->id }}" form="bulkDeleteForm" class="form-check-input-premium bulk-item">
                </div>
                @endcan

                <div class="col-title item-title">
                    <h5 class="text-truncate" title="{{ $exam->title }}">{{ $exam->title }}</h5>
                    <div class="item-meta mt-1">
                        <span class="text-muted">#{{ $exam->id }}</span>
                        @if($exam->is_paid)
                            <span class="badge bg-light text-dark border ms-2">{{ $currencySymbol }}{{ number_format($exam->price, 2) }}</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle ms-2">{{ __('exams.label_free') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-cat">
                    <span class="mobile-label d-lg-none">{{ __('exams.th_category') }}:</span>
                    <span class="category-tag">{{ $exam->category->name ?? __('exams.uncategorized') }}</span>
                </div>

                <div class="col-stats">
                    <div class="d-flex flex-row flex-lg-column gap-3 gap-lg-1">
                        <div class="item-meta"><i class="fa-solid fa-list-ol text-muted me-1"></i> {{ $exam->questions_count }} {{ __('exams.label_qs') }}</div>
                        <div class="item-meta"><i class="fa-regular fa-clock text-muted me-1"></i> {{ $exam->duration_minutes }} {{ __('exams.label_min') }}</div>
                    </div>
                </div>

                <div class="col-schedule">
                    <span class="mobile-label d-lg-none">{{ __('exams.th_schedule') }}:</span>
                    @if($exam->start_date)
                        <div class="d-flex flex-column gap-1">
                            <span class="text-xs"><i class="fa-solid fa-play text-success me-1"></i> {{ \Carbon\Carbon::parse($exam->start_date)->format('M d, H:i') }}</span>
                            @if($exam->end_date)
                            <span class="text-xs"><i class="fa-solid fa-stop text-danger me-1"></i> {{ \Carbon\Carbon::parse($exam->end_date)->format('M d, H:i') }}</span>
                            @endif
                        </div>
                    @else
                        <span class="badge bg-light text-secondary border">{{ __('exams.always_open') }}</span>
                    @endif
                </div>

                <div class="col-date text-muted small">
                    <span class="mobile-label d-lg-none">{{ __('exams.th_created') }}:</span>
                    {{ $exam->created_at->format('M d, Y') }}
                </div>

                <div class="col-status">
                    <span class="mobile-label d-lg-none">{{ __('exams.th_status') }}:</span>
                    <x-status-badge :status="$exam->is_active" />
                </div>

                <div class="col-action">
                    <div class="action-group">
                        @can('view_questions')
                        <a href="{{ route('admin.exams.questions', $exam->id) }}" class="btn-circle" title="{{ __('exams.tooltip_manage_qs') }}">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                        @endcan
                        
                        @can('create_exams')
                        <button type="button" 
                                class="btn-circle edit btn-edit-exam" 
                                data-id="{{ $exam->id }}" 
                                data-title="{{ $exam->title }}" 
                                data-category="{{ $exam->category_id }}" 
                                data-duration="{{ $exam->duration_minutes }}" 
                                data-pass="{{ $exam->pass_percentage }}"
                                data-marks="{{ $exam->total_marks }}"
                                data-paid="{{ $exam->is_paid ? '1' : '0' }}"
                                data-price="{{ $exam->price }}"
                                data-plan="{{ $exam->plan_id }}"
                                data-start="{{ $exam->start_date }}"
                                data-end="{{ $exam->end_date }}"
                                data-result="{{ $exam->result_date }}"
                                data-banner="{{ $exam->banner ? asset('storage/'.$exam->banner) : '' }}"
                                data-has-negative-marking="{{ $exam->has_negative_marking ? '1' : '0' }}"
                                data-negative-mark-value="{{ $exam->negative_mark_value }}"
                                data-action="{{ route('admin.exams.update', $exam->id) }}">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <form action="{{ route('admin.exams.toggle', $exam->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-circle danger btn-toggle-status" data-active="{{ $exam->is_active }}">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </form>
                        @endcan
                        
                        @can('delete_exams')
                        <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-circle danger confirm-delete">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <x-empty-state title="{{ __('exams.empty_title') }}" subtitle="{{ __('exams.empty_desc') }}" icon="fa-solid fa-file-circle-question" />
        @endforelse
    </div>

    @if($exams->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">{{ __('exams.showing_results', ['first' => $exams->firstItem() ?? 0, 'last' => $exams->lastItem() ?? 0, 'total' => $exams->total()]) }}</div>
        @include('components.app-pagination', ['paginator' => $exams])
    </div>
    @endif

    <!-- Mobile Filter Offcanvas -->
    <div class="offcanvas offcanvas-bottom exam-filter-offcanvas rounded-top-4" tabindex="-1" id="examFilterOffcanvas" aria-labelledby="examFilterOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="examFilterOffcanvasLabel">{{ __('exams.filter_actions') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <!-- Mobile Search -->
            <form action="{{ route('admin.exams.index') }}" method="GET" class="mb-4">
                <label class="form-label-premium">{{ __('exams.search_placeholder') }}</label>
                <div class="position-relative">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control-premium ps-5" placeholder="{{ __('exams.search_placeholder') }}">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-dark rounded-pill">{{ __('exams.btn_search') }}</button>
                </div>
            </form>

            <hr class="text-muted my-4">

            <!-- Mobile Actions -->
            <div class="d-grid gap-3">
                @can('create_exams')
                <button type="button" class="btn btn-outline-dark rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center gap-2" id="triggerAiModalMobile">
                    <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                    <span>{{ __('exams.btn_ai_generate') }}</span>
                </button>

                <button type="button" class="btn-premium justify-content-center w-100" id="triggerCreateModalMobile">
                    <i class="fa-solid fa-plus"></i> {{ __('exams.btn_add_new') }}
                </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Modals -->
    @can('create_exams')
        @include('admin.exams.partials.create-modal')
        @include('admin.exams.partials.edit-modal')
        @include('admin.exams.partials.ai-modal')
        @include('admin.exams.partials.success-modal')
    @endcan

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-exams.js') }}"></script>
@endpush