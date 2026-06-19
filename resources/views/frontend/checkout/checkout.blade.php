@extends('frontend.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/frontend/css/checkout.css') }}">

@php
    $countries = $countries ?? [];
    $billingDetails = Session::get('billing_details', []);
    $currentCountry = old('country', Auth::user()->country ?? ($billingDetails['country'] ?? 'US'));
    $currentFirstName = old('first_name', Auth::user()->first_name ?? ($billingDetails['first_name'] ?? ''));
    $currentLastName = old('last_name', Auth::user()->last_name ?? ($billingDetails['last_name'] ?? ''));
    $currentEmail = old('email', Auth::user()->email ?? ($billingDetails['email'] ?? ''));
@endphp

<section class="checkout-section">
    <div class="checkout-container">
        
        <div class="checkout-card">
            <div class="checkout-header">
                <div class="brand-area">
                    <i class="fa-solid fa-shield-halved text-primary"></i> {{ __('frontend.checkout_title') }}
                </div>
                <div class="step-nav">
                    <div class="step-item completed">
                        <div class="step-circle"><i class="fa-solid fa-check"></i></div> <span>{{ __('frontend.step_cart') }}</span>
                    </div>
                    <div class="step-item active">
                        <div class="step-circle">2</div> <span>{{ __('frontend.step_details') }}</span>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">3</div> <span>{{ __('frontend.step_payment') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-grid fade-in">
            <div class="main-content-card">
                <h1 class="page-title">{{ __('frontend.billing_details') }}</h1>
                <p class="page-desc">{{ __('frontend.billing_desc') }}</p>

                @if(session('error') || empty($enabledGateways))
                    <div class="error-notification">
                        <div class="error-icon-wrapper">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                        <div class="error-content">
                            <h6 class="error-title">Unable to Proceed</h6>
                            <p class="error-msg">
                                @if(session('error'))
                                    {{ session('error') }}
                                @else
                                    No payment gateways are currently configured.
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('checkout.details.store') }}" method="POST" id="billingForm">
                    @csrf
                    <div class="form-row">
                        <div class="input-group">
                            <label class="label">{{ __('frontend.first_name') }}</label>
                            <input type="text" name="first_name" class="input @error('first_name') input-error @enderror" value="{{ $currentFirstName }}" required>
                            @error('first_name') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                        <div class="input-group">
                            <label class="label">{{ __('frontend.last_name') }}</label>
                            <input type="text" name="last_name" class="input @error('last_name') input-error @enderror" value="{{ $currentLastName }}" required>
                            @error('last_name') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="label">{{ __('frontend.email_address') }}</label>
                        <input type="email" name="email" class="input @error('email') input-error @enderror" value="{{ $currentEmail }}" required>
                        @error('email') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="input-group">
                        <label class="label">{{ __('frontend.country') }}</label>
                        <select name="country" class="input @error('country') input-error @enderror" required>
                            <option value="">{{ __('frontend.select_country') }}</option>
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}" {{ $currentCountry === $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('country') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </form>
            </div>

            <div class="summary-card">
                <h3 class="summary-title">{{ __('frontend.your_order') }}</h3>
                
                <div class="mini-product-list">
                    @foreach($cart as $item)
                    <div class="mini-product">
                        <div class="mini-thumb"><i class="fa-solid fa-file-contract"></i></div>
                        <div class="mini-details">
                            <div class="mini-name">{{ $item['title'] }}</div>
                        </div>
                        <div class="mini-price">{{ $currencySymbol }}{{ number_format($item['price'], 2) }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="summary-line">
                    <span>{{ __('frontend.subtotal') }}</span>
                    <span>{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>{{ __('frontend.taxes') }} ({{ number_format($taxRate, 0) }}%)</span>
                    <span>{{ $currencySymbol }}{{ number_format($tax, 2) }}</span>
                </div>

                <div class="summary-total">
                    <span>{{ __('frontend.total_to_pay') }}</span>
                    <span>{{ $currencySymbol }}{{ number_format($total, 2) }}</span>
                </div>

                <button type="submit" form="billingForm" class="btn-main w-100 border-0" {{ empty($enabledGateways) ? 'disabled style=opacity:0.6;cursor:not-allowed;' : '' }}>
                    {{ __('frontend.continue_payment') }}
                </button>

                <a href="{{ route('checkout.cart') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> {{ __('frontend.return_cart') }}
                </a>

                <div class="trust-badge">
                    <i class="fa-solid fa-lock"></i> {{ __('frontend.ssl_secure') }}
                </div>
            </div>
        </div>

    </div>
</section>
@endsection