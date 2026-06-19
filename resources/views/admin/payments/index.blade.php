@extends('layouts.admin')

@section('title', __('payments.title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-payments.css') }}" rel="stylesheet"> 
@endpush

@section('content')
    
    {{-- Config for JS --}}
    <div id="paymentConfig" 
         data-confirm-title="{{ __('payments.confirm_title') }}"
         data-confirm-text="{{ __('payments.confirm_text') }}"
         data-confirm-yes="{{ __('payments.confirm_yes') }}">
    </div>

    @php
        $cf = function($amount, $currencyCode) use ($systemFormatting, $formatCurrency, $getDisplaySymbol) {
            $symbol = $getDisplaySymbol; 
            return $formatCurrency($amount, $symbol, $systemFormatting['position'], $systemFormatting['decimal_sep'], $systemFormatting['thousands_sep']) . ' ' . $currencyCode; 
        };

        $cf_kpi = function($amount) use ($systemFormatting, $formatCurrency, $getDisplaySymbol) {
            return $formatCurrency($amount, $getDisplaySymbol, $systemFormatting['position'], $systemFormatting['decimal_sep'], $systemFormatting['thousands_sep']);
        };
        
        $pendingCount = $payments->where('status', 'pending')->count();
    @endphp

    <div class="mobile-search-filter-bar">
        <button class="btn btn-light border shadow-sm d-flex align-items-center gap-2 px-3 radius-12 h-46 bg-white" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#paymentFilterOffcanvas">
            <i class="fa-solid fa-sliders text-dark"></i>
            <span class="fw-bold text-dark small">{{ __('payments.btn_filter') }}</span>
        </button>

        <form action="{{ route('admin.payments.index') }}" method="GET" class="flex-grow-1">
            <div class="position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute text-muted top-50-start-14"></i>
                <input type="text" name="search" class="form-control border shadow-sm ps-5 radius-12 h-46 fs-095" 
                       placeholder="{{ __('payments.placeholder_search') }}" 
                       value="{{ request('search') }}" autocomplete="off">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            </div>
        </form>
    </div>

    @if($pendingCount > 0)
    <div class="mobile-sticky-pending">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-warning"></i>
            <span>{{ trans_choice('payments.alert_pending_count', $pendingCount) }}</span>
        </div>
        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="text-decoration-none fw-bold text-orange fs-08">
            {{ __('payments.btn_review_all') }} &rarr;
        </a>
    </div>
    @endif

    <div class="desktop-header">
        <div class="page-title">
            <h4 class="fw-bold text-dark m-0">{{ __('payments.header_title') }}</h4>
            <p class="text-muted small m-0 mt-1">{{ __('payments.header_subtitle') }}</p>
        </div>
        
        <div class="action-group d-flex gap-3">
            <form action="{{ route('admin.payments.index') }}" method="GET" class="position-relative w-300">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <i class="fa-solid fa-magnifying-glass position-absolute text-muted top-50-start-15 pointer-none"></i>
                <input type="text" name="search" class="form-control ps-5 rounded-3 border shadow-sm h-44 fs-09" placeholder="{{ __('payments.placeholder_search') }}" value="{{ request('search') }}" autocomplete="off">
            </form>

            <a href="{{ route('admin.payments.export', request()->query()) }}" class="btn btn-success rounded-pill px-4 fw-bold d-flex align-items-center gap-2 h-44 border-0 btn-export-green">
                <i class="fa-solid fa-download"></i> {{ __('payments.btn_export') }}
            </a>
        </div>
    </div>
    
    <div class="kpi-grid d-none d-lg-grid">
        @foreach($stats as $stat)
            <a href="{{ route('admin.payments.index', ['status' => $stat['key']]) }}" 
               class="kpi-card kpi-{{ $stat['color'] }} {{ request('status') == $stat['key'] ? 'active' : '' }}">
                <div class="kpi-content">
                    <h2>{{ $stat['display_amount'] }}</h2>
                    <p>{{ __('payments.opt_' . $stat['key']) }}</p>
                </div>
                <div class="kpi-icon-wrapper">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
            </a>
        @endforeach
    </div>

    <div class="payment-list-container">
        
        <div class="list-header d-none d-lg-flex">
            <div class="col-trx">{{ __('payments.col_trx') }}</div>
            <div class="col-user">{{ __('payments.col_user') }}</div>
            <div class="col-amount">{{ __('payments.col_amount', ['currency' => $systemFormatting['code']]) }}</div>
            <div class="col-status">{{ __('payments.col_status') }}</div>
            <div class="col-date">{{ __('payments.col_date') }}</div>
            <div class="col-action text-end">{{ __('payments.col_action') }}</div>
        </div>

        @forelse($payments as $payment)
            <div class="list-item">
                <div class="col-trx">
                    <div class="d-flex flex-column d-lg-block">
                        <div class="d-flex align-items-center justify-content-between d-lg-block">
                            <span class="trx-id">{{ $payment->transaction_id }}</span>
                            <span class="d-lg-none gateway-label">{{ ucfirst($payment->gateway) }}</span>
                        </div>
                        
                        <span class="d-none d-lg-block gateway-label-desk">
                            <i class="fa-solid fa-credit-card gateway-icon"></i> {{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}
                        </span>
                    </div>
                </div>

                <div class="col-user">
                    @if($payment->user)
                        <span class="user-name">{{ $payment->user->name }}</span>
                        <span class="user-email">{{ $payment->user->email }}</span>
                    @else
                        <span class="text-muted fst-italic">{{ __('payments.text_user_deleted') }}</span>
                    @endif
                </div>

                <div class="col-amount">
                    <span class="amount-text">{{ $cf($payment->amount, $payment->currency) }}</span>
                </div>

                <div class="col-status">
                    @php
                        $status = strtolower($payment->status);
                        $statusClass = match($status) {
                            'success', 'approved', 'successful' => 'status-success',
                            'pending' => 'status-pending',
                            'initiated' => 'status-initiated',
                            'failed', 'rejected' => 'status-failed',
                            default => 'status-default',
                        };
                        $statusKey = 'payments.status_' . $status;
                        $statusLabel = \Lang::has($statusKey) ? __($statusKey) : ucfirst($payment->status);
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="col-date">
                    <span class="date-text">
                        {{ $payment->created_at->format('M d, Y') }} 
                        <span class="d-lg-none">&middot;</span> 
                        <span class="text-secondary d-none d-lg-inline d-xl-inline"><br></span>
                        {{ $payment->created_at->diffForHumans() }}
                    </span>
                </div>

                <div class="col-action">
                    <div class="action-btn-group d-lg-none">
                        <button type="button" class="btn-mobile-action" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $payment->id }}">{{ __('payments.btn_view') }}</button>
                        
                        @if($payment->status === 'pending')
                            <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-contents confirm-action">
                                @csrf @method('PATCH')
                                <button type="submit" 
                                        class="btn-mobile-action approve"
                                        data-title="{{ __('payments.confirm_approve_title') }}" 
                                        data-text="{{ __('payments.confirm_approve_text', ['trx' => $payment->transaction_id]) }}"
                                        data-type="success">
                                    {{ __('payments.btn_approve') }}
                                </button>
                            </form>
                            <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="d-contents confirm-action">
                                @csrf @method('PATCH')
                                <button type="submit" 
                                        class="btn-mobile-action reject"
                                        data-title="{{ __('payments.confirm_reject_title') }}" 
                                        data-text="{{ __('payments.confirm_reject_text', ['trx' => $payment->transaction_id]) }}"
                                        data-type="warning">
                                    {{ __('payments.btn_reject') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="d-none d-lg-flex justify-content-end gap-2">
                        <button type="button" class="btn-icon-action view" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $payment->id }}">
                            <i class="fa-solid fa-eye"></i> 
                        </button>
                        @if($payment->status === 'pending')
                        <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-inline confirm-action">
                            @csrf @method('PATCH')
                            <button type="submit" 
                                    class="btn-icon-action approve" 
                                    title="{{ __('payments.btn_approve') }}"
                                    data-title="{{ __('payments.confirm_approve_title') }}"
                                    data-text="{{ __('payments.confirm_approve_text', ['trx' => $payment->transaction_id]) }}"
                                    data-type="success">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @include('admin.payments.partials.details-modal', ['payment' => $payment, 'cf' => $cf_kpi, 'systemFormatting' => $systemFormatting])
        @empty
            <div class="p-5 text-center">
                <div class="mb-3 text-muted fs-3rem"><i class="fa-solid fa-credit-card"></i></div>
                <h5 class="fw-bold text-dark">{{ __('payments.empty_title') }}</h5>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm mt-2">{{ __('payments.btn_clear_filters') }}</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
        <div class="text-muted small">
            Showing {{ $payments->firstItem() ?? 0 }}–{{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} results
        </div>
        @if($payments->hasPages())
            @include('components.app-pagination', ['paginator' => $payments])
        @endif
    </div>

    <div class="offcanvas offcanvas-bottom offcanvas-h-auto offcanvas-payment-filter" 
         tabindex="-1" 
         id="paymentFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">Filter Options</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.payments.index') }}" method="GET">
                <label class="form-label text-muted small fw-bold text-uppercase mb-2">{{ __('payments.label_status') }}</label>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button type="submit" name="status" value="all" class="btn btn-light border btn-sm {{ !request('status') || request('status') == 'all' ? 'active bg-dark text-white' : '' }}">{{ __('payments.opt_all') }}</button>
                    <button type="submit" name="status" value="pending" class="btn btn-light border btn-sm {{ request('status') == 'pending' ? 'active bg-warning text-dark' : '' }}">{{ __('payments.opt_pending') }}</button>
                    <button type="submit" name="status" value="success" class="btn btn-light border btn-sm {{ request('status') == 'success' ? 'active bg-success text-white' : '' }}">{{ __('payments.opt_success') }}</button>
                    <button type="submit" name="status" value="failed" class="btn btn-light border btn-sm {{ request('status') == 'failed' ? 'active bg-danger text-white' : '' }}">{{ __('payments.opt_failed') }}</button>
                </div>
                
                <hr class="text-muted opacity-25 my-4">
                
                <a href="{{ route('admin.payments.export', request()->query()) }}" class="btn btn-outline-success w-100 rounded-pill fw-bold py-2">
                    <i class="fa-solid fa-file-csv me-2"></i> {{ __('payments.btn_export') }}
                </a>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-payments.js') }}"></script>
@endpush