@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/transactions.css') }}">
@endpush

@section('content')
<div class="page-header transaction-page-header">
    <div class="header-left">
        <h1 class="page-title">{{ __('frontend.transactions_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.transactions_subtitle') }}</p>
    </div>

    <form action="{{ route('user.transactions') }}" method="GET" class="transaction-filters">
        <input type="text" class="form-control" name="search" placeholder="{{ __('frontend.txn_id') }}" value="{{ request('search') }}">
        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
        <button type="submit" class="btn btn-primary">{{ __('frontend.filter_btn') }}</button>
        @if(request()->filled('search') || request()->filled('date_from') || request()->filled('date_to'))
            <a href="{{ route('user.transactions') }}" class="btn btn-outline-secondary">{{ __('frontend.reset_btn') }}</a>
        @endif
    </form>
</div>

<div class="transaction-container mt-4">
    
    @if ($payments->isEmpty())
        <div class="empty-state-transactions">
            <div class="empty-icon-wrapper">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <h3 class="empty-title">{{ __('frontend.no_txn_found') }}</h3>
            <p class="empty-desc">{{ __('frontend.no_txn_desc') }}</p>
            <a href="{{ route('exams.list') }}" class="btn btn-primary btn-action">{{ __('frontend.browse_plans') }}</a>
        </div>
    @else
        <div class="transaction-list-header">
            <div>{{ __('frontend.txn_id') }}</div>
            <div>{{ __('frontend.plan_item') }}</div>
            <div class="text-end">{{ __('frontend.amount') }}</div>
            <div class="text-end">{{ __('frontend.gateway') }}</div>
            <div></div> 
            <div>{{ __('frontend.status') }}</div>
            <div class="text-end">{{ __('frontend.date') }}</div>
        </div>
        
        @foreach ($payments as $payment)
            <div class="transaction-item">
                
                <div class="text-muted small transaction-id">{{ $payment->transaction_id ?? __('frontend.not_available') }}</div>
                
                <div class="plan-info">
                    <span class="fw-bold text-main">{{ optional($payment->plan)->name ?? __('frontend.standalone_purchase') }}</span>
                    @if(optional($payment->plan)->duration)
                        <div class="small text-muted">{{ $payment->plan->duration }} {{ __('frontend.days_subscription') }}</div>
                    @endif
                </div>
                
                <div class="fw-bold amount-col text-end">
                    {{ $formatCurrency($payment->amount) }}
                </div>

                <div class="text-end gateway-col">
                    <span class="badge-gateway">{{ strtoupper(str_replace('_', ' ', $payment->gateway)) }}</span>
                </div>
                
                <div></div> 
                
                <div class="status-col">
                    @php
                        $status = strtolower($payment->status);
                        $statusClass = match($status) {
                            'approved', 'success', 'successful', 'active' => 'badge-success',
                            'pending', 'initiated' => 'badge-warning',
                            'rejected', 'failed' => 'badge-danger',
                            default => 'badge-info',
                        };
                        
                        $transKey = 'frontend.' . $status;
                        $statusLabel = __($transKey); 
                    @endphp
                    <span class="badge-status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                
                <div class="text-end date-col text-muted small">
                    {{ $payment->created_at->translatedFormat('M d, Y') }}
                </div>
            </div>
        @endforeach
        
        <div class="mt-4 p-3 d-flex justify-content-center">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection