@extends('layouts.admin')

@section('title', __('results.page_title_declared'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-results.css') }}" rel="stylesheet">
    <style>
        .export-date-field { border: 1px solid #eef2f7; border-radius: 20px; padding: 0.35rem 0.8rem; font-size: 0.75rem; background: #fff; outline: none; }
        .btn-export-group { border-radius: 20px; overflow: hidden; display: flex; shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn-export-item { border: none; padding: 0.4rem 0.9rem; font-size: 0.8rem; transition: opacity 0.2s; }
        .btn-export-item:hover { opacity: 0.9; }
    </style>
@endpush

@section('content')

    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#resultFilterOffcanvas" 
                aria-controls="resultFilterOffcanvas">
            
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center icon-box-sm">
                    <i class="fa-solid fa-sliders fs-xs"></i>
                </div>
                <span class="fw-bold text-dark fs-sm">{{ __('results.filter_results') }}</span>
            </div>
            
            <i class="fa-solid fa-chevron-right text-muted fs-xss"></i>
        </button>
    </div>

    <div class="desktop-header gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('results.header_title_declared') }}</h1>
            <p class="text-muted small mb-0">{{ __('results.header_desc_declared') }}</p>
        </div>
        
        <div class="page-header-actions d-flex align-items-center gap-3">
            <form action="{{ route('admin.exams.results.export') }}" method="GET" class="d-none d-xl-flex align-items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="d-flex align-items-center gap-1">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="export-date-field shadow-sm" title="Start Date">
                    <span class="text-muted small">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="export-date-field shadow-sm" title="End Date">
                </div>
                <div class="btn-export-group shadow-sm">
                    <button type="submit" name="format" value="csv" class="btn-export-item bg-success text-white" title="Export CSV">
                        <i class="fa-solid fa-file-csv"></i>
                    </button>
                    <button type="submit" name="format" value="pdf" class="btn-export-item bg-danger text-white" title="Export PDF Report">
                        <i class="fa-solid fa-file-pdf"></i>
                    </button>
                </div>
            </form>

            <form action="{{ route('admin.exams.results.index') }}" method="GET" class="search-filter-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="search-filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="search-filter-input" 
                       placeholder="{{ __('results.search_placeholder') }}"
                       autocomplete="off">
            </form>
        </div>
    </div>

    <div class="mb-4">
        <x-kpi-grid :data="$kpis" />
    </div>

    <div class="result-list-container mt-4">
        <div class="list-header">
            <div class="col-student">{{ __('results.th_student') }}</div>
            <div class="col-exam">{{ __('results.th_exam') }}</div>
            <div class="col-score">{{ __('results.th_score') }}</div>
            <div class="col-stats">{{ __('results.th_stats') }}</div>
            <div class="col-status">{{ __('results.th_status') }}</div>
            <div class="col-date">{{ __('results.th_date') }}</div>
            <div class="col-action text-end">{{ __('results.th_action') }}</div>
        </div>

        @forelse($results as $result)
            <div class="list-item">
                <div class="col-student">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle">
                            {{ substr($result->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="d-flex flex-column" style="min-width: 0;">
                            <span class="fw-bold text-dark text-max-150" title="{{ $result->user->name }}">{{ $result->user->name ?? 'Unknown' }}</span>
                            <span class="small text-muted text-max-150" title="{{ $result->user->email }}">{{ $result->user->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-exam">
                    <div class="d-flex flex-column" style="min-width: 0;">
                        <span class="fw-medium text-secondary text-max-150" title="{{ $result->exam->title }}">{{ $result->exam->title ?? 'Exam Deleted' }}</span>
                        <span class="small text-muted">ID: #{{ $result->exam_id }}</span>
                    </div>
                </div>

                <div class="col-score">
                    <div class="d-flex align-items-center mb-1">
                        <span class="fw-bold fs-6 me-2 {{ $result->is_passed ? 'text-success' : 'text-danger' }}">
                            {{ number_format($result->percentage, 1) }}%
                        </span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar {{ $result->is_passed ? 'bg-success' : 'bg-danger' }}" style="width: {{ $result->percentage }}%"></div>
                    </div>
                </div>

                <div class="col-stats">
                    <div class="small lh-base-sm">
                        <span class="text-success"><i class="fa-solid fa-check me-1"></i> {{ floatval($result->correct_answers) }} {{ __('results.correct') }}</span><br>
                        <span class="text-muted"><i class="fa-solid fa-list-ol me-1"></i> {{ floatval($result->total_questions) }} {{ __('results.total') }}</span>
                    </div>
                </div>

                <div class="col-status">
                    @if($result->is_passed)
                        <span class="badge bg-success-subtle text-success">{{ __('results.status_passed') }}</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger">{{ __('results.status_failed') }}</span>
                    @endif
                </div>

                <div class="col-date text-muted small">
                    {{ $result->created_at->format('M d, Y') }}<br>
                    <span class="opacity-75">{{ $result->created_at->format('h:i A') }}</span>
                </div>

                <div class="col-action text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.exams.results.show', $result->id) }}" 
                           class="btn-circle view" 
                           title="{{ __('results.btn_view_report') }}"
                           data-bs-toggle="tooltip">
                           <i class="fa-regular fa-file-lines"></i>
                        </a>

                        <form action="{{ route('admin.exams.results.destroy', $result->id) }}" method="POST" class="confirm-action">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="btn-circle delete"
                                    title="{{ __('results.btn_delete') }}"
                                    data-title="{{ __('results.delete_confirm_title') }}"
                                    data-text="{{ __('results.delete_confirm_msg') }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="d-flex align-items-center justify-content-center min-h-400">
                <x-empty-state 
                    title="{{ __('results.empty_declared_title') }}"
                    subtitle="{{ __('results.empty_declared_subtitle') }}"
                    icon="fa-solid fa-clipboard-check"
                />
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} results
        </div>
        
        @if($results->hasPages())
            @include('components.app-pagination', ['paginator' => $results])
        @endif
    </div>

    <div class="offcanvas offcanvas-bottom offcanvas-h-auto" tabindex="-1" id="resultFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">{{ __('results.filter_results') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.exams.results.index') }}" method="GET" class="mb-4">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('results.search_label') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="{{ __('results.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-premium w-100 rounded-pill fw-bold py-2">
                    {{ __('results.btn_apply_filter') }}
                </button>
            </form>

            <hr class="text-muted my-4">

            <div class="export-mobile-section">
                <label class="form-label text-muted small fw-bold text-uppercase mb-3">Download Data Report</label>
                <form action="{{ route('admin.exams.results.export') }}" method="GET">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small text-muted mb-1">From Date</label>
                            <input type="date" name="start_date" class="form-control rounded-3">
                        </div>
                        <div class="col-6">
                            <label class="small text-muted mb-1">To Date</label>
                            <input type="date" name="end_date" class="form-control rounded-3">
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="format" value="csv" class="btn btn-success rounded-pill fw-bold py-2">
                            <i class="fa-solid fa-file-csv me-2"></i> Export CSV
                        </button>
                        <button type="submit" name="format" value="pdf" class="btn btn-danger rounded-pill fw-bold py-2">
                            <i class="fa-solid fa-file-pdf me-2"></i> Export PDF Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-results-index.js') }}"></script>
@endpush