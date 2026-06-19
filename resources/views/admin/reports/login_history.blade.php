@extends('layouts.admin')

@section('title', __('reports.login_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    {{-- Ensure this file exists locally --}}
    <link href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-reports.css') }}" rel="stylesheet"> 
@endpush

@section('content')
    
    @php
        $hasFiltersSet = (request('date_range') && request('date_range') !== '30_days') ||
                         request('user_id') ||
                         (request('status') && request('status') !== 'all') ||
                         request('search');
    @endphp
    
    <!-- Mobile Filter Bar -->
    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#reportFilterOffcanvas" 
                aria-controls="reportFilterOffcanvas">
            
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center icon-box-28">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <span class="fw-bold text-dark fs-09">{{ __('reports.btn_filter') }}</span>
            </div>
            
            <i class="fa-solid fa-chevron-right text-muted fs-075"></i>
        </button>
    </div>

    <!-- Desktop Header -->
    <div class="desktop-header mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('reports.login_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('reports.login_subtitle') }}</p>
        </div>
        
        <form action="{{ route('admin.reports.login.history') }}" method="GET" class="page-header-actions">
            
            <div class="search-filter-box">
                <i class="fa-solid fa-magnifying-glass search-filter-icon"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="search-filter-input" 
                       placeholder="{{ __('reports.placeholder_login') }}"
                       autocomplete="off">
            </div>

            <div class="filter-group date-range">
                <i class="fa-regular fa-calendar-days filter-icon"></i>
                <select name="date_range" class="form-select">
                    <option value="30_days" {{ $dateRange == '30_days' ? 'selected' : '' }}>{{ __('reports.opt_30_days') }}</option>
                    <option value="7_days" {{ $dateRange == '7_days' ? 'selected' : '' }}>{{ __('reports.opt_7_days') }}</option>
                    <option value="all" {{ $dateRange == 'all' ? 'selected' : '' }}>{{ __('reports.opt_all_time') }}</option>
                </select>
            </div>
            
            <div class="filter-group user-search">
                <i class="fa-regular fa-user filter-icon"></i>
                <select name="user_id" class="form-control select2-users w-100">
                    <option></option> <!-- Critical for placeholder -->
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <i class="fa-solid fa-circle filter-icon icon-status-dot"></i>
                <select name="status" class="form-select select2-status">
                    <option value="all">{{ __('reports.opt_all_statuses') }}</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>
                        {{ __('reports.status_success') }}
                    </option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                        {{ __('reports.status_failed') }}
                    </option>
                    <option value="suspicious" {{ request('status') == 'suspicious' ? 'selected' : '' }}>
                        {{ __('reports.status_suspicious') }}
                    </option>
                </select>
            </div>

            <button type="submit" class="btn-apply-filter">
                <i class="fa-solid fa-check"></i> {{ __('reports.btn_apply') }}
            </button>

            @if($hasFiltersSet)
                <a href="{{ route('admin.reports.login.history') }}" class="btn-clear-filters" title="{{ __('reports.btn_clear') }}">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="row g-4 mb-4 mt-4">
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

    <div class="report-list-container">
        
        <div class="list-header d-none d-lg-flex">
            <div class="col-log-user">{{ __('reports.col_user') }}</div>
            <div class="col-log-time">{{ __('reports.col_login_time') }}</div>
            <div class="col-log-ip">{{ __('reports.col_ip') }}</div>
            <div class="col-log-browser">{{ __('reports.col_browser') }}</div>
            <div class="col-log-method">{{ __('reports.col_method') }}</div>
            <div class="col-log-security">{{ __('reports.col_security') }}</div>
            <div class="col-log-status">{{ __('reports.col_status') }}</div>
        </div>

        @forelse($logs as $log)
            
            @php
                $statusKey = 'reports.status_' . strtolower($log->status ?? 'unknown');
                $statusLabel = \Lang::has($statusKey) ? __($statusKey) : ucfirst($log->status ?? 'Unknown');
                
                $statusClass = match(strtolower($log->status ?? 'unknown')) {
                    'success' => 'status-success',
                    'failed' => 'status-failed',
                    'suspicious' => 'status-pending',
                    default => 'status-default',
                };
            @endphp

            <div class="list-item">
                <div class="mobile-header-row d-lg-none">
                    <div class="mobile-user-details">
                        @if($log->user)
                            <div class="fw-bold">{{ $log->user->name }}</div>
                            <span class="small">{{ $log->user->email }}</span>
                        @else
                            <span class="text-muted fst-italic small">{{ __('reports.text_deleted_user') }}</span>
                        @endif
                    </div>
                    
                    <div class="d-flex flex-column align-items-end">
                        <span class="status-badge {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                        <div class="mobile-timestamp">
                            {{ $log->login_at->format('M d, h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="d-none d-lg-flex w-100 align-items-center">
                    <div class="col-log-user">
                        @if($log->user)
                            <div class="fw-bold text-dark">{{ $log->user->name }}</div>
                            <span class="small text-muted">{{ $log->user->email }}</span>
                        @else
                            <span class="text-muted fst-italic">{{ __('reports.text_deleted_user') }}</span>
                        @endif
                    </div>

                    <div class="col-log-time">
                        <div class="text-dark small fw-bold">{{ $log->login_at->format('M d, Y') }}</div>
                        <div class="text-muted small fs-075">{{ $log->login_at->format('h:i:s A') }}</div>
                    </div>

                    <div class="col-log-ip">
                        <div class="text-dark small fw-bold">{{ $log->ip_address ?? 'N/A' }}</div>
                        <div class="text-muted small">
                            {{ $log->city }}, {{ $log->country }}
                        </div>
                    </div>

                    <div class="col-log-browser">
                        <div class="text-dark small fw-bold">{{ $log->browser ?? 'Unknown' }}</div>
                        <div class="text-muted small">{{ $log->os ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="col-log-method">
                        <span class="badge bg-light text-muted border px-2 py-1 rounded-pill">
                            {{ ucfirst($log->login_method ?? 'Password') }}
                        </span>
                    </div>

                    <div class="col-log-security">
                        @if($log->mfa_used)
                            <span class="badge status-success px-2 py-1 rounded-pill">{{ __('reports.signal_mfa') }}</span>
                        @endif
                        @if($log->network_type === 'VPN / Proxy')
                            <span class="badge status-failed px-2 py-1 rounded-pill">{{ __('reports.signal_vpn') }}</span>
                        @elseif($log->network_type)
                            <span class="badge status-default px-2 py-1 rounded-pill">{{ $log->network_type }}</span>
                        @endif
                    </div>

                    <div class="col-log-status">
                        <span class="status-badge {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="d-lg-none">
                    <div class="d-flex justify-content-between">
                        <div class="mobile-detail-block flex-grow-1">
                            <span class="mobile-detail-label">{{ __('reports.label_ip') }}</span>
                            <div class="mobile-detail-value">{{ $log->ip_address ?? 'N/A' }}</div>
                        </div>
                        <div class="mobile-detail-block text-end">
                            <span class="mobile-detail-label">{{ __('reports.label_location') }}</span>
                            <div class="mobile-detail-value">{{ $log->city }}, {{ $log->country }}</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                         <div class="mobile-detail-block flex-grow-1">
                            <span class="mobile-detail-label">{{ __('reports.label_browser') }}</span>
                            <div class="mobile-detail-value">{{ $log->browser ?? 'Unknown' }}</div>
                            <div class="mobile-detail-value muted">{{ $log->os ?? 'N/A' }}</div>
                        </div>
                        <div class="mobile-detail-block text-end">
                            <span class="mobile-detail-label">{{ __('reports.label_method') }}</span>
                            <div class="mobile-detail-value">{{ ucfirst($log->login_method ?? 'Password') }}</div>
                        </div>
                    </div>
                    
                    <div class="mobile-detail-block">
                        <span class="mobile-detail-label">{{ __('reports.label_security') }}</span>
                        <div class="mobile-detail-value d-flex gap-2">
                            @if($log->mfa_used)
                                <span class="badge status-success">{{ __('reports.signal_mfa') }}</span>
                            @endif
                            @if($log->network_type === 'VPN / Proxy')
                                <span class="badge status-failed">{{ __('reports.signal_vpn') }}</span>
                            @elseif($log->network_type)
                                <span class="badge status-default">{{ $log->network_type }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center">
                <div class="mb-3 text-muted opacity-25 fs-3rem"><i class="fa-solid fa-fingerprint"></i></div>
                <h6 class="fw-bold text-dark">{{ __('reports.login_empty_title') }}</h6>
                <p class="text-muted small">{{ __('reports.login_empty_text') }}</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            {{ __('reports.text_showing', ['first' => $logs->firstItem() ?? 0, 'last' => $logs->lastItem() ?? 0, 'total' => $logs->total()]) }}
        </div>
        @if($logs->hasPages())
            @include('components.app-pagination', ['paginator' => $logs])
        @endif
    </div>

    <!-- Mobile Offcanvas Filter -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="reportFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">Filter & Options</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.reports.login.history') }}" method="GET">
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('reports.filter_date') }}</label>
                    <select name="date_range" class="form-select">
                        <option value="30_days" {{ $dateRange == '30_days' ? 'selected' : '' }}>{{ __('reports.opt_30_days') }}</option>
                        <option value="7_days" {{ $dateRange == '7_days' ? 'selected' : '' }}>{{ __('reports.opt_7_days') }}</option>
                        <option value="all" {{ $dateRange == 'all' ? 'selected' : '' }}>{{ __('reports.opt_all_time') }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('reports.filter_user') }}</label>
                    <select name="user_id" class="form-control select2-users-mobile">
                        <option value="">{{ __('reports.placeholder_select_user') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach 
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('reports.filter_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="all">{{ __('reports.opt_all_statuses') }}</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>{{ __('reports.status_success') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('reports.status_failed') }}</option>
                        <option value="suspicious" {{ request('status') == 'suspicious' ? 'selected' : '' }}>{{ __('reports.status_suspicious') }}</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-premium w-100 rounded-pill fw-bold py-2 mb-3">
                    {{ __('reports.btn_apply') }}
                </button>
                <a href="{{ route('admin.reports.login.history') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2">
                    {{ __('reports.btn_clear') }}
                </a>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Ensure these are present in your assets folder --}}
    <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin-reports.js') }}"></script>
@endpush