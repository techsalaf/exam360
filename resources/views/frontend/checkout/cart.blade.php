@extends('frontend.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/frontend/css/checkout.css') }}">

<section class="checkout-section">
    <div class="checkout-container">
        
        <div class="checkout-card">
            <div class="checkout-header">
                <div class="brand-area">
                    <i class="fa-solid fa-shield-halved text-primary"></i> {{ __('frontend.checkout_title') }}
                </div>
                <div class="step-nav">
                    <div class="step-item active">
                        <div class="step-circle">1</div> <span>{{ __('frontend.step_cart') }}</span>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">2</div> <span>{{ __('frontend.step_details') }}</span>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">3</div> <span>{{ __('frontend.step_payment') }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if(count($cart) > 0)
        <div class="checkout-grid fade-in">
            <div class="main-content-card">
                <h1 class="page-title">{{ __('frontend.review_selection') }}</h1>
                <p class="page-desc">{{ __('frontend.confirm_exams') }}</p>

                <div class="cart-list">
                    @foreach($cart as $id => $item)
                    <div class="cart-item">
                        <div class="item-icon" style="background: var(--primary-soft); color: var(--primary);">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="item-info">
                            <div class="item-name">{{ $item['title'] ?? 'Unknown Item' }}</div>
                            <div class="item-sub">
                                @if($item['type'] !== 'plan')
                                <span><i class="fa-regular fa-clock"></i> {{ $item['duration'] ?? 0 }} {{ __('frontend.mins') }}</span>
                                <span><i class="fa-solid fa-list-ul"></i> {{ $item['questions'] ?? 0 }} {{ __('frontend.questions_count') }}</span>
                                @else
                                <span><i class="fa-solid fa-sync-alt"></i> Recurring: {{ ucfirst($item['plan_period']) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="item-price-block">
                            <div class="price-tag">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item['price'], 2) }}</div>
                            <a href="{{ route('checkout.remove', $id) }}" class="remove-link">{{ __('frontend.remove_item') }}</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="summary-card">
                <h3 class="summary-title">{{ __('frontend.order_summary') }}</h3>
                <div class="summary-line">
                    <span>{{ __('frontend.subtotal') }}</span>
                    <span>{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>{{ __('frontend.taxes') }} ({{ $taxRate }}%)</span>
                    <span>{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($tax, 2) }}</span>
                </div>
                <div class="summary-total">
                    <span>{{ __('frontend.total_amount') }}</span>
                    <span>{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout.details') }}" class="btn-main">
                    {{ __('frontend.continue_checkout') }}
                </a>

                <div class="trust-badge">
                    <i class="fa-solid fa-shield-cat"></i> {{ __('frontend.money_back_guarantee') }}
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
            <i class="fa-solid fa-cart-shopping fa-3x text-muted mb-3"></i>
            <h3>{{ __('frontend.cart_empty') }}</h3>
            <p class="text-muted">{{ __('frontend.cart_empty_desc') }}</p>
            <a href="{{ route('exams.list') }}" class="btn btn-primary mt-2">{{ __('frontend.browse_exams_btn') }}</a>
        </div>
        @endif

    </div>
</section>
@endsection