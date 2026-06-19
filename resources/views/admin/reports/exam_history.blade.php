@extends('layouts.admin')

@section('title', __('reports.exam_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-reports.css') }}" rel="stylesheet"> 
@endpush

@section('content')

    <!-- Mobile Filter Bar -->
    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#reportFilterOffcanvas" 
                aria-controls="reportFilterOffcanvas">
            
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center icon-box-28">
                    <i class="fa-solid fa-sliders fs-08"></i>
                </div>
                <span class="fw-bold text-dark fs-09">{{ __('reports.btn_filter') }}</span>
            </div>
            
            <i class="fa-solid fa-chevron-right text-muted fs-075"></i>
        </button>
    </div>

    <!-- Desktop Header -->
    <div class="desktop-header mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('reports.exam_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('reports.exam_subtitle') }}</p>
        </div>
        
        <div class="page-header-actions">
            <form action="{{ route('admin.reports.exam.history') }}" method="GET" class="search-filter-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="search-filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="search-filter-input" 
                       placeholder="{{ __('reports.placeholder_exam') }}"
                       autocomplete="off">
            </form>
        </div>
    </div>
    
    <!-- KPI Grid -->
    <div class="row g-4 mb-4">
        @foreach($kpis as $stat)
            <div class="col-lg-3 col-md-3 col-12">
                <div class="zi-kpi-card kpi-{{ $stat['color'] ?? 'primary' }}">
                    <div class="zi-kpi-content">
                        <h3>{{ $stat['value'] }}</h3>
                        <p>{{ $stat['label'] }}</p> 
                    </div>
                    <div class="zi-kpi-icon-wrapper">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- List Container -->
    <div class="report-list-container mt-4">
        
        <div class="list-header">
            <div class="col-exam-title">{{ __('reports.col_exam') }}</div>
            <div class="col-student">{{ __('reports.col_student') }}</div>
            <div class="col-score">{{ __('reports.col_score') }}</div>
            <div class="col-time">{{ __('reports.col_time') }}</div>
            <div class="col-date-col-exam">{{ __('reports.col_date') }}</div>
            <div class="col-action-exam">{{ __('reports.col_action') }}</div>
        </div>

        @forelse($results as $session)
            <div class="list-item">
                
                <div class="col-exam-title">
                    <div class="fw-bold text-dark text-truncate">{{ $session->exam->title ?? 'N/A' }}</div>
                    <span class="small text-muted">
                        {{ $session->exam->category->name ?? __('reports.text_no_category') }}
                    </span>
                </div>

                <div class="col-student">
                    @if($session->user)
                        <div class="user-name text-truncate">{{ $session->user->name }}</div>
                        <div class="user-email text-truncate">{{ $session->user->email }}</div>
                    @else
                        <span class="text-muted fst-italic">{{ __('reports.text_deleted_user') }}</span>
                    @endif
                </div>

                <div class="col-score">
                    @php
                        $resultData = $session->result; 
                        $score = $resultData ? $resultData->obtained_score : 0;
                        $total = $resultData ? $resultData->total_score : ($session->exam->total_marks ?? 0);
                        $isPassed = $resultData ? $resultData->is_passed : false;
                    @endphp

                    <div class="fw-bold text-dark">
                        {{ floatval($score) }}/{{ floatval($total) }} {{ __('reports.text_marks') }}
                    </div>
                    
                    @if($resultData)
                        <span class="status-badge {{ $isPassed ? 'status-success' : 'status-failed' }} mt-1">
                            {{ $isPassed ? __('reports.status_passed') : __('reports.status_failed') }}
                        </span>
                    @else
                        <span class="status-badge status-pending mt-1">Pending</span>
                    @endif
                </div>

                <div class="col-time">
                    <div class="text-dark small">
                        @php
                            $duration = 'N/A';
                            if(isset($session->time_taken_seconds) && $session->time_taken_seconds > 0) {
                                $minutes = floor($session->time_taken_seconds / 60);
                                $seconds = $session->time_taken_seconds % 60;
                                $duration = $minutes . 'm ' . $seconds . 's';
                            }
                        @endphp
                        {{ $duration }}
                    </div>
                    <div class="text-muted small mt-1">
                        {{ __('reports.text_duration', ['mins' => $session->exam->duration_minutes ?? 'N/A']) }}
                    </div>
                </div>

                <div class="col-date-col-exam">
                    <div class="text-dark small fw-bold">{{ $session->created_at->format('M d, Y') }}</div>
                    <div class="text-muted small fs-075">{{ $session->created_at->format('h:i A') }}</div>
                </div>

                <div class="col-action-exam">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.exams.results.show', $session->id) }}" 
                           class="btn-circle edit" 
                           title="{{ __('reports.btn_view') }}"
                           data-bs-toggle="tooltip">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.exams.results.destroy', $session->id) }}" method="POST" class="d-inline confirm-action"
                              data-confirm="true"
                              data-title="{{ __('reports.confirm_delete_title') }}"
                              data-text="{{ __('reports.confirm_delete_text') }}"
                              data-type="danger">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="btn-circle danger"
                                    title="{{ __('reports.btn_delete') }}"
                                    data-bs-toggle="tooltip">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center">
                <div class="mb-3 text-muted opacity-25 fs-3rem"><i class="fa-solid fa-chart-line"></i></div>
                <h6 class="fw-bold text-dark">{{ __('reports.exam_empty_title') }}</h6>
                <p class="text-muted small">{{ __('reports.exam_empty_text') }}</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            {{ __('reports.text_showing', ['first' => $results->firstItem() ?? 0, 'last' => $results->lastItem() ?? 0, 'total' => $results->total()]) }}
        </div>
        @if($results->hasPages())
            @include('components.app-pagination', ['paginator' => $results])
        @endif
    </div>

    <!-- Mobile Offcanvas Filter -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="reportFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">Filter & Options</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.reports.exam.history') }}" method="GET">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('reports.label_search_exam') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="{{ __('reports.placeholder_exam') }}" value="{{ request('search') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 mb-3 btn-green-solid">
                    {{ __('reports.btn_apply') }}
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-reports.js') }}"></script>
@endpush