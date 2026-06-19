@extends('layouts.admin')

@section('title', __('reports.sub_title'))

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
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('reports.sub_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('reports.sub_subtitle') }}</p>
        </div>
        
        <div class="page-header-actions">
            <form action="{{ route('admin.reports.subscriptions') }}" method="GET" class="search-filter-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="search-filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="search-filter-input" 
                       placeholder="{{ __('reports.placeholder_sub_input') }}"
                       autocomplete="off">
            </form>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="row g-4 mb-4">
        @foreach($kpis as $stat)
            <div class="col-lg-4 col-md-4 col-12">
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
    <div class="report-list-container">
        
        <div class="list-header">
            <div class="col-trx">{{ __('reports.col_trx') }}</div>
            <div class="col-user">{{ __('reports.col_subscriber') }}</div>
            <div class="col-plan">{{ __('reports.col_plan') }}</div>
            <div class="col-validity">{{ __('reports.col_validity') }}</div>
            <div class="col-amount">{{ __('reports.col_amount') }}</div>
            <div class="col-status">{{ __('reports.col_status') }}</div>
            <div class="col-date-col">{{ __('reports.col_date') }}</div>
        </div>

        @forelse($payments as $payment)
            <div class="list-item">
                
                <div class="col-trx">
                    <div class="trx-id text-truncate">{{ $payment->transaction_id }}</div>
                    <span class="gateway-label">
                        @php
                            $gatewayIcon = match(strtolower($payment->gateway)) {
                                'paypal' => 'fa-brands fa-paypal',
                                'stripe' => 'fa-brands fa-stripe-s',
                                'razorpay' => 'fa-solid fa-rupee-sign',
                                default => 'fa-regular fa-credit-card'
                            };
                        @endphp
                        <i class="{{ $gatewayIcon }} me-1"></i> 
                        {{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}
                    </span>
                </div>

                <div class="col-user">
                    @if($payment->user)
                        <div class="user-name text-truncate">{{ $payment->user->name }}</div>
                        <div class="user-email text-truncate">{{ $payment->user->email }}</div>
                    @else
                        <span class="text-muted fst-italic">{{ __('reports.text_unknown_user') }}</span>
                    @endif
                </div>

                <div class="col-plan">
                    @if($payment->plan)
                        <span class="badge-plan">
                            {{ $payment->plan->name }}
                        </span>
                    @else
                        <span class="text-muted small">–</span>
                    @endif
                </div>

                <div class="col-validity">
                    @if($payment->start_date && $payment->end_date)
                        <div class="validity-date">
                            <span class="validity-label">{{ __('reports.label_start') }}</span>
                            <span class="fw-medium">{{ $payment->start_date->format('M d, Y') }}</span>
                        </div>
                        <div class="validity-date">
                            <span class="validity-label">{{ __('reports.label_end') }}</span>
                            <span class="fw-medium">{{ $payment->end_date->format('M d, Y') }}</span>
                        </div>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                </div>

                <div class="col-amount">
                    @php
                        $formatted = number_format($payment->amount, 2, $currencyConfig['decimal'], $currencyConfig['thousands']);
                        $display = match($currencyConfig['position']) {
                            'after' => $formatted . $currencyConfig['symbol'],
                            'before_space' => $currencyConfig['symbol'] . ' ' . $formatted,
                            'after_space' => $formatted . ' ' . $currencyConfig['symbol'],
                            default => $currencyConfig['symbol'] . $formatted,
                        };
                    @endphp
                    <span class="fw-bold text-dark">{{ $display }}</span>
                </div>

                <div class="col-status">
                    @php
                        $statusClass = match(strtolower($payment->status)) {
                            'success', 'approved', 'successful', 'paid' => 'status-success',
                            'pending', 'processing' => 'status-pending',
                            'failed', 'rejected', 'cancelled' => 'status-failed',
                            default => 'status-default',
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>

                <div class="col-date-col">
                    <div class="text-dark small fw-bold">{{ $payment->created_at->format('M d, Y') }}</div>
                    <div class="text-muted small fs-075">{{ $payment->created_at->format('h:i A') }}</div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center">
                <div class="mb-3 text-muted opacity-25 fs-3rem"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <h6 class="fw-bold text-dark">{{ __('reports.sub_empty_title') }}</h6>
                <p class="text-muted small">{{ __('reports.sub_empty_text') }}</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            {{ __('reports.text_showing', ['first' => $payments->firstItem() ?? 0, 'last' => $payments->lastItem() ?? 0, 'total' => $payments->total()]) }}
        </div>
        @if($payments->hasPages())
            @include('components.app-pagination', ['paginator' => $payments])
        @endif
    </div>

    <!-- Mobile Offcanvas Filter -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="reportFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">Filter & Options</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.reports.subscriptions') }}" method="GET">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('reports.label_search_trx') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="{{ __('reports.placeholder_sub_input') }}" value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="mb-4">
                     <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('reports.col_status') }}</label>
                     <select name="status" class="form-select">
                         <option value="all">All Statuses</option>
                         <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                         <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                         <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                     </select>
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