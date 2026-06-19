@extends('layouts.admin')

@section('title', __('dashboard.overview'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
@endpush

@php
    $isSuperAdmin = auth()->user()->id === 1 || auth()->user()->hasRole('Super Admin');
@endphp

@section('content')

@if(!$isSuperAdmin)
    <div class="container-fluid p-0">
        <div class="row align-items-center justify-content-center welcome-wrapper">
            <div class="col-md-6 text-center">
                <div class="mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center welcome-icon">
                        <i class="fa-solid fa-user-tie fa-2x text-primary"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">Welcome, {{ auth()->user()->name }}</h3>
                <p class="text-muted mb-4">You have limited access to the administration panel. Please select an option from the sidebar to proceed.</p>
                
                <div class="d-flex justify-content-center gap-3">
                    @can('create_exams')
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-file-pen me-2"></i> {{ __('menu.manage_exams') }}
                    </a>
                    @endcan
                    <a href="{{ route('user.dashboard') }}" class="btn btn-light border rounded-pill px-4">
                        <i class="fa-solid fa-arrow-left me-2"></i> {{ __('menu.back_to_dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- KPI STRIP -->
    <div class="row g-4 mb-4">
        <!-- Live Assessments -->
        <div class="col-md-4">
            <div class="card-panel flex-row align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-muted text-uppercase mb-1 ls-1">{{ __('dashboard.live_assessments') }}</div>
                    <div class="d-flex align-items-baseline gap-2">
                        @if($liveCount > 0)
                        <span class="live-pulse-wrapper">
                          <span class="pulse-anim"></span>
                          <span class="pulse-static"></span>
                        </span>
                        @endif
                        <h2 class="kpi-number text-dark">{{ str_pad($liveCount, 2, '0', STR_PAD_LEFT) }}</h2>
                    </div>
                </div>
                <div class="kpi-icon-wrapper bg-success bg-opacity-10 text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10v6"/><path d="M20 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/></svg>
                </div>
            </div>
        </div>
        
        <!-- Candidates -->
        <div class="col-md-4">
            <div class="card-panel flex-row align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-muted text-uppercase mb-1 ls-1">{{ __('dashboard.registered_candidates') }}</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="kpi-number text-dark">{{ number_format($totalCandidates) }}</h2>
                        <span class="kpi-trend text-success small">@if($growthPercentage > 0) ↑ @endif {{ $growthPercentage }}%</span>
                    </div>
                </div>
                <div class="kpi-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Pass Rate -->
        <div class="col-md-4">
            <div class="card-panel flex-row align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-muted text-uppercase mb-1 ls-1">{{ __('dashboard.avg_pass_rate') }}</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="kpi-number text-dark">{{ $passRate }}%</h2>
                        <span class="kpi-trend text-muted small fw-normal">{{ __('dashboard.this_cycle') }}</span>
                    </div>
                </div>
                <div class="kpi-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        <!-- LEFT COLUMN -->
        <div class="col-xl-8">
            <!-- LIVE MONITORING HERO -->
            <div class="hero-card mb-4">
                @if($activeExam)
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="badge bg-black bg-opacity-25 border border-white border-opacity-25 rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2">
                            <div class="live-indicator-dot"></div> {{ __('dashboard.monitoring_live') }}
                        </div>
                        <div class="text-white-50 font-monospace small">#EX-{{ $activeExam->id }}</div>
                    </div>
                    <h2 class="fw-bold mb-1">{{ Str::limit($activeExam->title, 40) }}</h2>
                    <p class="text-white-50 mb-4">{{ optional($activeExam->category)->name ?? __('dashboard.general') }}</p>
                    <div class="row">
                        <div class="col-6">
                            <div class="h2 fw-bold mb-0">{{ $activeExam->active_count }}</div>
                            <span class="text-white-50 small text-uppercase fw-bold ls-1">{{ __('dashboard.active_students') }}</span>
                        </div>
                        <div class="col-6 text-end">
                            <div class="h2 fw-bold text-warning mb-0 font-monospace">{{ $activeExam->remaining_formatted }}</div>
                            <span class="text-white-50 small text-uppercase fw-bold ls-1">{{ __('dashboard.avg_duration') }}</span>
                        </div>
                    </div>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5">
                        <div class="badge bg-white bg-opacity-10 rounded-pill px-3 py-2 mb-3">{{ __('dashboard.offline') }}</div>
                        <h3 class="fw-bold">{{ __('dashboard.no_live_exam') }}</h3>
                        <p class="text-white-50">{{ __('dashboard.schedule_new_exam_msg') }}</p>
                        <a href="{{ route('admin.exams.index') }}" class="btn btn-sm btn-light mt-2">{{ __('dashboard.manage_exams') }}</a>
                    </div>
                @endif
            </div>

            <!-- PERFORMANCE ANALYTICS -->
            <div class="card-panel mb-4">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ __('dashboard.perf_analytics') }}</h5>
                        <p class="text-muted small mb-0">{{ __('dashboard.perf_analytics_desc') }}</p>
                    </div>
                    <div class="d-flex gap-3 small">
                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box attendance"></span> {{ __('dashboard.attendance') }}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-box pass"></span> {{ __('dashboard.pass_rate') }}
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    @foreach($chartData as $data)
                        <div class="chart-group {{ $data['is_current'] ? 'current' : '' }}">
                            <div class="chart-bars chart-bar-group">
                                <div class="bar-fill bar-attendance" data-height="{{ $data['attendance_height'] }}" data-bs-toggle="tooltip" title="{{ $data['full_date'] }}: {{ $data['attendance_val'] }}"></div>
                                <div class="bar-fill bar-pass" data-height="{{ $data['pass_height'] }}" data-bs-toggle="tooltip" title="{{ $data['full_date'] }}: {{ $data['pass_val'] }}%"></div>
                            </div>
                            <span class="chart-month-label">{{ $data['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- FINANCIAL & SYSTEM REPORTS -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card-panel h-100">
                        <h6 class="fw-bold text-dark mb-3">{{ __('dashboard.financial_overview') }}</h6>
                        <div class="report-list">
                            <div class="report-item">
                                <div class="d-flex align-items-center">
                                    {{-- DYNAMIC CURRENCY ICON/SYMBOL --}}
                                    <div class="report-icon-box bg-icon-green">
                                        <h4 class="fw-bold mb-0 text-success" style="font-size: 1.25rem;">{{ $currencySymbol }}</h4>
                                    </div>
                                    <div><h6 class="fw-bold text-dark mb-0">{{ number_format($totalRevenue, 2) }}</h6><span class="small text-muted">{{ __('dashboard.total_payment') }}</span></div>
                                </div>
                            </div>
                            <div class="report-item">
                                <div class="d-flex align-items-center">
                                    <div class="report-icon-box bg-icon-orange"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                                    <div><h6 class="fw-bold text-dark mb-0">{{ $pendingPayments }}</h6><span class="small text-muted">{{ __('dashboard.pending_txn') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-panel h-100">
                        <h6 class="fw-bold text-dark mb-3">{{ __('dashboard.system_health') }}</h6>
                        <div class="report-list">
                            <div class="report-item">
                                <div class="d-flex align-items-center">
                                    <div class="report-icon-box bg-icon-green"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg></div>
                                    <div><h6 class="fw-bold text-dark mb-0">{{ $activePlans }}</h6><span class="small text-muted">{{ __('dashboard.active_plans') }}</span></div>
                                </div>
                            </div>
                             <div class="report-item">
                                <div class="d-flex align-items-center">
                                    <div class="report-icon-box bg-primary bg-opacity-10 text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></div>
                                    <div><h6 class="fw-bold text-dark mb-0">Stable</h6><span class="small text-muted">{{ __('dashboard.server_status') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-xl-4">
            <!-- SUBSCRIPTION GROWTH -->
            <div class="card-panel mb-4 align-items-center justify-content-center text-center">
                <h6 class="fw-bold text-dark w-100 text-start mb-4">{{ __('dashboard.subscription_growth') }}</h6>
                <div class="donut-chart mb-3" data-percent="{{ $premiumPercentage }}">
                    <div class="donut-inner">
                        <h4 class="fw-bold text-dark mb-0">{{ $premiumPercentage }}%</h4>
                        <span class="small text-muted label">PREMIUM</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-4 w-100">
                    <div class="text-start">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="legend-dot premium"></span>
                            <span class="small fw-bold text-dark">{{ __('dashboard.premium') }}</span>
                        </div>
                        <span class="small text-muted ps-3">{{ $premiumUsers }} {{ __('dashboard.users') }}</span>
                    </div>
                    <div class="text-start">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="legend-dot basic"></span>
                            <span class="small fw-bold text-dark">{{ __('dashboard.basic') }}</span>
                        </div>
                        <span class="small text-muted ps-3">{{ $basicUsers }} {{ __('dashboard.users') }}</span>
                    </div>
                </div>
            </div>

            <!-- AI RISK DETECTION -->
            <div class="card-panel mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">{{ __('dashboard.ai_risk_detection') }}</h6>
                    @if($riskData['count'] > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger">{{ $riskData['count'] }} {{ __('dashboard.active_signals') }}</span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success">{{ __('dashboard.all_clean') }}</span>
                    @endif
                </div>

                @forelse($riskData['alerts'] as $alert)
                    @php
                        $isCritical = $alert->level === 'critical';
                        $bgClass = $isCritical ? 'bg-danger bg-opacity-10 text-danger' : 'bg-warning bg-opacity-10 text-warning';
                    @endphp
                    
                    <div class="d-flex gap-3 mb-3">
                        <div class="{{ $bgClass }} p-2 rounded flex-shrink-0 risk-item-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                @if ($isCritical)
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                @else
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">{{ $alert->title }}</div>
                            <div class="text-muted small lh-sm mt-1">{{ Str::limit($alert->message, 60) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <p class="text-muted small mb-0">{{ __('dashboard.no_risks_detected') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- UPCOMING EXAMS -->
            <div class="card-panel upcoming-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ __('dashboard.upcoming_exams') }}</h6>
                        <p class="small text-muted mb-0">{{ __('dashboard.next_7_days') }}</p>
                    </div>
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-sm btn-light border rounded-pill fw-bold small px-3">{{ __('dashboard.view_all') }}</a>
                </div>
                
                <div class="upcoming-list">
                    @forelse($upcomingExams as $exam)
                        @php 
                            $isUrgent = $exam->start_date->isTomorrow() || $exam->start_date->isToday();
                            $iconBg = $isUrgent ? 'bg-primary bg-opacity-10 text-primary' : 'bg-purple bg-opacity-10 text-purple';
                            $dateLabel = $exam->start_date->isToday() ? 'TODAY' : ($exam->start_date->isTomorrow() ? 'TOM' : $exam->start_date->format('M'));
                        @endphp
                        <div class="upcoming-item {{ $isUrgent ? 'urgent' : '' }}">
                            <div class="upcoming-icon {{ $iconBg }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">{{ Str::limit($exam->title, 20) }}</h6>
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <span class="badge bg-light text-dark border">{{ optional($exam->category)->name ?? 'Exam' }}</span>
                                    <span>• {{ $exam->start_date->format('h:i A') }}</span>
                                </div>
                            </div>
                            <div class="date-badge">
                                <span class="date-day {{ $isUrgent ? 'text-warning' : '' }}">{{ $exam->start_date->format('d') }}</span>
                                <span class="date-month">{{ $dateLabel }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <span class="text-muted small">{{ __('dashboard.no_upcoming_exams') }}</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endif

</div>
@endsection

@if($isSuperAdmin)
    @push('scripts')
        <script src="{{ asset('assets/js/chart.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin-dashboard-charts.js') }}"></script>
    @endpush
@endif