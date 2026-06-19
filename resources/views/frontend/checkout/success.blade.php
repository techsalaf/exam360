@extends('frontend.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/frontend/css/checkout.css') }}">

@php
    $symbol = $currencySymbol ?? '$';
@endphp

<section class="checkout-section">
    <div class="checkout-container">
        
        <div class="success-container fade-in">
            <div class="success-box">
                
                @if($payment_status === 'pending')
                    <div class="check-icon" style="background: #fff7ed; color: #ea580c;">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <h1 class="page-title" style="text-align: center;">{{ __('frontend.payment_pending') }}</h1>
                    <p class="page-desc" style="text-align: center; margin-bottom: 24px;">
                        {{ __('frontend.offline_processing') }}
                    </p>
                @else
                    <div class="check-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h1 class="page-title" style="text-align: center;">{{ __('frontend.payment_successful') }}</h1>
                    <p class="page-desc" style="text-align: center; margin-bottom: 24px;">
                        {{ __('frontend.exam_access_active') }}
                    </p>
                @endif
                
                <div class="access-card">
                    <div class="item-icon" style="width: 48px; height: 48px; font-size: 20px; display: flex; align-items: center; justify-content: center; background: #ecfdf5; border-radius: 8px; color: var(--primary);">
                        <i class="fa-solid fa-unlock-keyhole"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--dark);">{{ __('frontend.purchased_exams') }}</div>
                        <div style="font-size: 13px; color: var(--primary); font-weight: 600; margin-top: 4px;">
                            <i class="fa-solid fa-circle-check"></i> 
                            @if($payment_status === 'pending')
                                {{ __('frontend.access_pending') }}
                            @else
                                {{ __('frontend.access_activated') }}
                            @endif
                        </div>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 16px 20px; border-radius: 8px; margin-bottom: 32px; text-align: left;">
                    <div class="summary-line" style="margin-bottom: 8px;">
                        <span>{{ __('frontend.order_id') }}</span>
                        <span style="color:var(--dark); font-weight:600;">#{{ $order_id }}</span>
                    </div>
                    
                    @if(isset($transaction_ref) && $transaction_ref && $transaction_ref !== $order_id)
                    <div class="summary-line" style="margin-bottom: 8px;">
                        <span>Ref ID</span>
                        <span style="color:var(--dark); font-weight:600;">{{ $transaction_ref }}</span>
                    </div>
                    @endif

                    <div class="summary-line" style="margin-bottom: 0;">
                        <span>{{ __('frontend.amount_paid') }} ({{ $gateway }})</span>
                        <span style="color:var(--primary); font-weight:700;">{{ $symbol }}{{ number_format($paid_amount, 2) }}</span>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <a href="{{ route('user.dashboard') }}" class="btn-main" style="margin-top:0; width: auto; padding: 12px 32px;">{{ __('frontend.go_to_dashboard') }}</a>
                    <a href="{{ route('home') }}" class="btn-main" style="margin-top:0; width: auto; padding: 12px 32px; background: white; color: var(--dark); border: 1px solid #e2e8f0;">{{ __('frontend.home') }}</a>
                </div>

                <div class="note-text">
                    <i class="fa-regular fa-clock" style="margin-right: 6px;"></i> 
                    {{ __('frontend.not_ready_note') }}
                </div>
            </div>
        </div>

    </div>
</section>
@endsection