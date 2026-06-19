@extends('layouts.admin')

@section('title', __('live.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-live.css') }}" rel="stylesheet">
@endpush

@section('content')

<!-- Header -->
<div class="live-header-bar d-flex align-items-center justify-content-between mb-4">
    <div class="live-header-text">
        <h1 class="h3 fw-bold text-dark mb-1">
            <span class="text-danger me-2"><i class="fa-solid fa-satellite-dish"></i></span>
            {{ __('live.header_title') }}
        </h1>
        <p class="text-muted small mb-0">{{ __('live.header_desc') }}</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <div id="statusIndicator" class="live-connection-pill badge px-3 py-1 rounded-pill d-flex align-items-center gap-1">
            <span class="live-sync-dot"></span> 
            <span>{{ __('live.status_connected') }}</span>
        </div>
        <button class="btn refresh-btn rounded-circle d-flex align-items-center justify-content-center" id="manualRefreshBtn" title="{{ __('live.refresh') }}">
            <i class="fa-solid fa-rotate"></i>
        </button>
    </div>
</div>

<!-- KPI Stats -->
<x-kpi-grid :stats="[
    [
        'value' => $kpi['active_users'], 
        'label' => __('live.kpi_active'), 
        'icon' => 'fa-solid fa-user-group', 
        'color' => 'primary', 
        'extra_class' => 'live-kpi-card', 
        'id' => 'kpi-card-active'
    ],
    [
        'value' => $kpi['critical_risk'], 
        'label' => __('live.kpi_critical'), 
        'icon' => 'fa-solid fa-triangle-exclamation', 
        'color' => 'danger', 
        'extra_class' => 'live-kpi-card kpi-critical-risk', 
        'id' => 'kpi-card-critical'
    ],
    [
        'value' => $kpi['paused'], 
        'label' => __('live.kpi_paused'), 
        'icon' => 'fa-solid fa-pause', 
        'color' => 'warning', 
        'extra_class' => 'live-kpi-card', 
        'id' => 'kpi-card-paused'
    ],
    [
        'value' => $kpi['completed_today'], 
        'label' => __('live.kpi_finished'), 
        'icon' => 'fa-solid fa-calendar-check', 
        'color' => 'success', 
        'extra_class' => 'live-kpi-card', 
        'id' => 'kpi-card-completed'
    ]
]" cols="4" />

<!-- Monitoring Command Bar -->
<div class="monitoring-command-bar mb-4">
    
    <!-- Mobile Filter & Search -->
    <div class="command-row filters-row p-3 d-flex d-lg-none">
        <div class="mobile-filter-search-group">
            <button class="mobile-filter-btn" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#liveFilterOffcanvas"
                    title="{{ __('live.filters') }}">
                <i class="fa-solid fa-filter"></i>
            </button>
            <div class="search-input-mobile-wrapper">
                <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
                <input type="text" id="searchInputMobile" placeholder="{{ __('live.search_placeholder') }}">
            </div>
        </div>
    </div>
    
    <!-- Desktop Filters -->
    <div class="command-row filters-row p-3 d-none d-lg-flex">
        <div class="d-flex align-items-center gap-2 flex-grow-1">
            <div class="input-filter-select">
                <select id="filterExam" class="form-select-sm form-select">
                    <option value="all">{{ __('live.filter_all_exams') }}</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-filter-select">
                <select id="filterRisk" class="form-select-sm form-select">
                    <option value="all">{{ __('live.filter_risk_level') }}</option>
                    <option value="normal">{{ __('live.risk_normal') }}</option>
                    <option value="warning">{{ __('live.risk_warning') }}</option>
                    <option value="critical">{{ __('live.risk_critical') }}</option>
                </select>
            </div>
            <div class="input-filter-select me-4">
                <select id="filterStatus" class="form-select-sm form-select">
                    <option value="all">{{ __('live.filter_status') }}</option>
                    <option value="ongoing">{{ __('live.status_active') }}</option>
                    <option value="paused">{{ __('live.status_paused') }}</option>
                    <option value="terminated">{{ __('live.status_flagged') }}</option>
                    <option value="completed">{{ __('live.status_completed') }}</option>
                </select>
            </div>
        </div>
        <div class="input-group input-group-sm search-input-group ms-auto">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" id="searchInputDesktop" class="form-control border-start-0 ps-0" placeholder="{{ __('live.search_placeholder') }}">
        </div>
    </div>
    
    <!-- Info Status Row -->
    <div class="command-row status-row p-3">
        <div class="ai-status-zone">
            <div class="ai-title-line mb-1">
                <span class="ai-engine-label fw-bold text-dark me-2">{{ __('live.ai_engine_label') }}</span>
                <span class="ai-mode fw-medium">{{ __('live.ai_mode_adaptive') }}</span>
            </div>
            <div class="ai-signals-line text-muted small">
                <span class="ai-signals-label">{{ __('live.active_signals') }}</span> 
                <span class="ai-signal-list fw-medium">{{ __('live.signal_types') }}</span>
            </div>
        </div>
        
        <div class="system-status-zone text-muted small ms-auto d-flex align-items-center gap-3">
            <span class="live-sync-indicator text-success fw-bold">
                <span class="live-sync-dot"></span> {{ __('live.live_sync') }}
            </span>
            <span>|</span>
            <span class="fw-medium">{{ __('live.auto_refresh') }}</span>
            <span>|</span>
            <span class="fw-medium">{{ __('live.updated') }}: <span id="lastUpdatedTime">--:-- --</span></span>
        </div>
    </div>
</div>

<!-- Sessions Table -->
<div class="zi-table-live">
    <!-- Desktop View -->
    <div class="table-responsive d-none d-lg-block">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">{{ __('live.table_candidate') }}</th>
                    <th>{{ __('live.table_exam') }}</th>
                    <th>{{ __('live.table_progress') }}</th>
                    <th class="text-center">{{ __('live.table_risk') }}</th>
                    <th>{{ __('live.table_status') }}</th>
                    <th class="text-end pe-4">{{ __('live.table_actions') }}</th>
                </tr>
            </thead>
            <tbody id="liveSessionsTable">
                @include('admin.live.partials.table-rows', ['sessions' => $sessions])
            </tbody>
        </table>
    </div>

    <!-- Mobile View -->
     <div class="p-3 d-lg-none mobile-session-list" id="liveSessionsCardList">
        @include('admin.live.partials.table-rows-mobile', ['sessions' => $sessions])
     </div>
</div>

<!-- Mobile Offcanvas Filters -->
<div class="offcanvas offcanvas-bottom live-filter-offcanvas" tabindex="-1" id="liveFilterOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold text-dark">{{ __('live.filter_sessions') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('live.filter_exam_label') }}</label>
        <select class="form-select mb-3" id="mobileFilterExam">
            <option value="all">{{ __('live.filter_all_exams') }}</option>
            @foreach($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->title }}</option>
            @endforeach
        </select>

        <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('live.filter_risk_label') }}</label>
        <select class="form-select mb-3" id="mobileFilterRisk">
            <option value="all">{{ __('live.filter_all_risks') }}</option>
            <option value="normal">{{ __('live.risk_normal') }}</option>
            <option value="warning">{{ __('live.risk_warning') }}</option>
            <option value="critical">{{ __('live.risk_critical') }}</option>
        </select>

        <label class="form-label text-muted small fw-bold text-uppercase mb-4">{{ __('live.filter_status_label') }}</label>
        <select class="form-select mb-3" id="mobileFilterStatus">
            <option value="all">{{ __('live.filter_all_statuses') }}</option>
            <option value="ongoing">{{ __('live.status_active') }}</option>
            <option value="paused">{{ __('live.status_paused') }}</option>
            <option value="terminated">{{ __('live.status_flagged') }}</option>
            <option value="completed">{{ __('live.status_completed') }}</option>
        </select>

        <button id="applyMobileFilters" class="btn btn-success w-100 rounded-pill fw-bold">{{ __('live.apply_filters') }}</button>
    </div>
</div>

<!-- Config Block: Used by JS to get routes/tokens without inline script blocks -->
<div id="liveMonitoringConfig" 
     class="d-none"
     data-update="{{ route('admin.live.update') }}"
     data-action="{{ url('admin/live-monitoring/action') }}"
     data-csrf="{{ csrf_token() }}">
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-live.js') }}"></script>
@endpush