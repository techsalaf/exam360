@extends('frontend.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/frontend/css/checkout.css') }}">
<script src="https://js.stripe.com/v3/"></script>
@if(isset($enabledGateways['razorpay']))
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

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
                    <div class="step-item completed">
                        <div class="step-circle"><i class="fa-solid fa-check"></i></div> <span>{{ __('frontend.step_details') }}</span>
                    </div>
                    <div class="step-item active">
                        <div class="step-circle">3</div> <span>{{ __('frontend.step_payment') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="paymentForm">
            @csrf
            <input type="hidden" name="amount" value="{{ $total }}">
            
            <div class="checkout-grid fade-in">
                <div class="main-content-card">
                    <h1 class="page-title">{{ __('frontend.payment_method') }}</h1>
                    <p class="page-desc">{{ __('frontend.payment_desc') }}</p>
                    
                    @if(session('error'))
                        <div class="error-notification">
                            <div class="error-icon-wrapper"><i class="fa-solid fa-exclamation"></i></div>
                            <div class="error-content">
                                <h6 class="error-title">Transaction Failed</h6>
                                <p class="error-msg">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="error-notification">
                            <div class="error-icon-wrapper"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <div class="error-content">
                                <h6 class="error-title">Please check your inputs</h6>
                                <ul class="error-msg mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="payment-list">
                        @forelse($enabledGateways as $key => $gateway)
                        <label class="pay-option {{ $loop->first ? 'selected' : '' }}">
                            <input type="radio" name="gateway" value="{{ $key }}" class="pay-radio" {{ $loop->first ? 'checked' : '' }}>
                            
                            <div class="pay-option-info">
                                @if($key === 'stripe')
                                    <span style="font-weight: 600;">{{ __('frontend.credit_debit_card') }} (Stripe)</span>
                                @elseif($key === 'paypal')
                                    <i class="fa-brands fa-paypal fa-lg text-primary me-2"></i>
                                    <span style="font-weight: 600;">{{ $gateway['name'] }}</span>
                                @elseif($key === 'razorpay')
                                    <span class="fw-bold me-1 text-primary">R<span style="font-family: Arial, sans-serif;">s</span></span>
                                    <span style="font-weight: 600;">{{ $gateway['name'] }}</span>
                                @elseif($key === 'offline')
                                    <i class="fa-solid fa-bank fa-lg text-primary me-2"></i>
                                    <span style="font-weight: 600;">{{ __('frontend.bank_transfer_offline') }}</span>
                                @else
                                    <i class="fa-solid {{ $gateway['icon'] ?? 'fa-credit-card' }} fa-lg text-primary me-2"></i>
                                    <span style="font-weight: 600;">{{ $gateway['name'] }}</span>
                                @endif
                            </div>
                            
                            <div class="pay-option-meta">
                                @if($key === 'stripe')
                                    <div class="text-muted small">
                                        <i class="fa-brands fa-cc-visa"></i> <i class="fa-solid fa-credit-card"></i>
                                    </div>
                                @endif
                            </div>
                        </label>
                        @empty
                        <div class="alert alert-warning text-center">
                            {{ __('frontend.no_payment_enabled') }}
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="payment-fields-wrapper">
                        
                        <div id="stripe-fields" class="payment-form-container" style="display: none;">
                            <div class="form-row">
                                <div class="input-group">
                                    <label for="card-holder-name" class="label">{{ __('frontend.card_holder_name') }}</label>
                                    <input type="text" id="card-holder-name" class="input" placeholder="e.g., John Doe">
                                </div>
                                <div class="input-group">
                                    <label for="email" class="label">{{ __('frontend.email') }}</label>
                                    <input type="email" id="email" class="input" placeholder="john.doe@example.com" value="{{ $billing['email'] ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="stripe-input-group">
                                <label class="stripe-input-label">{{ __('frontend.card_number') }}</label>
                                <div id="card-number-element" class="stripe-element-style form-control p-2"></div>
                            </div>

                            <div id="card-errors" role="alert"></div>

                            <div class="stripe-row mt-3">
                                <div class="stripe-input-group">
                                    <label class="stripe-input-label">{{ __('frontend.expiry_date') }}</label>
                                    <div id="card-expiry-element" class="stripe-element-style form-control p-2"></div>
                                </div>
                                <div class="stripe-input-group">
                                    <label class="stripe-input-label">{{ __('frontend.cvc') }}</label>
                                    <div id="card-cvc-element" class="stripe-element-style form-control p-2"></div>
                                </div>
                            </div>

                            <div class="stripe-icon-lock mt-3 text-muted small">
                                <i class="fa-solid fa-lock"></i>
                                {{ __('frontend.securely_processed_by_stripe') }}
                            </div>
                        </div>
                        
                        <div id="offline-fields" class="payment-form-container" style="display: none;">
                            @if(isset($enabledGateways['offline']))
                                <p class="small text-dark mb-4 fw-semibold border-bottom pb-3">
                                    <i class="fa-solid fa-circle-info me-2 text-primary"></i>
                                    {{ __('frontend.offline_gateway_note') }}
                                </p>

                                <div class="form-row">
                                    <div class="input-group">
                                        <label class="label">{{ __('frontend.account_holder_name') }}</label>
                                        <input type="text" class="input" value="{{ $enabledGateways['offline']['details']['account_holder_name'] }}" readonly>
                                    </div>
                                    <div class="input-group">
                                        <label class="label">{{ __('frontend.bank_name') }}</label>
                                        <input type="text" class="input" value="{{ $enabledGateways['offline']['details']['bank_name'] }}" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="input-group">
                                        <label class="label">{{ __('frontend.account_number_iban') }}</label>
                                        <input type="text" class="input" value="{{ $enabledGateways['offline']['details']['account_number_iban'] }}" readonly>
                                    </div>
                                    <div class="input-group">
                                        <label class="label">{{ __('frontend.ifsc_swift_code') }}</label>
                                        <input type="text" class="input" value="{{ $enabledGateways['offline']['details']['ifsc_swift_code'] }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="input-group mb-3">
                                    <label class="label">{{ __('frontend.additional_instructions') }}</label>
                                    <textarea class="input" rows="3" readonly style="resize: none; height: auto;">{{ $enabledGateways['offline']['details']['instructions'] }}</textarea>
                                </div>

                                <div class="input-group mt-3 pt-3 border-top">
                                    <label class="label text-danger">Transaction Reference / Order ID <span class="text-danger">*</span></label>
                                    <input type="text" name="offline_transaction_id" id="offline_transaction_id" 
                                           class="input @error('offline_transaction_id') input-error @enderror" 
                                           placeholder="e.g. TRX-12345678" value="{{ old('offline_transaction_id') }}">
                                    <div class="form-text text-muted small mt-1">
                                        <i class="fa-solid fa-arrow-up"></i> Please enter the transaction ID provided by your bank after transfer.
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div id="default-prompt" class="border-dashed mt-3 p-3 text-center bg-light rounded">
                            <p class="small text-muted mb-0 fw-semibold">
                                <i class="fa-solid fa-hand-pointer me-2 text-primary"></i>
                                {{ __('frontend.select_gateway_prompt') }}
                            </p>
                        </div>
                    </div>

                </div>

                <div class="summary-card">
                    <h3 class="summary-title">{{ __('frontend.order_summary') }}</h3>

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
                        <span>{{ __('frontend.total_amount') }}</span>
                        <span style="color:var(--primary);">{{ $currencySymbol }}{{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-main w-100 border-0" {{ empty($enabledGateways) ? 'disabled' : '' }}>
                        {{ __('frontend.pay_with_amount', ['amount' => $currencySymbol . number_format($total, 2)]) }}
                    </button>

                    <a href="{{ route('checkout.details') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> {{ __('frontend.back_details') }}
                    </a>

                    <div class="trust-badge">
                        <i class="fa-solid fa-shield-virus"></i> {{ __('frontend.bank_security') }}
                    </div>
                </div>
            </div>
        </form>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payOptions = document.querySelectorAll('.pay-option');
        const nonInlineGateways = ['paypal', 'razorpay', 'payu', 'paystack', 'flutterwave', 'mollie', 'paddle'];
        const fieldWrappers = {
            'stripe': document.getElementById('stripe-fields'),
            'offline': document.getElementById('offline-fields'),
        };
        const defaultPrompt = document.getElementById('default-prompt');
        const paymentForm = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitBtn');
        const offlineInput = document.getElementById('offline_transaction_id');

        let stripe = null;
        let elements = null;
        let cardNumber = null;

        @if(isset($enabledGateways['stripe']))
            stripe = Stripe("{{ $enabledGateways['stripe']['public_key'] }}");
            elements = stripe.elements();
            
            const style = {
                base: { color: '#32325d', fontFamily: '"Inter", Helvetica, sans-serif', fontSize: '15px', '::placeholder': { color: '#aab7c4' } },
                invalid: { color: '#e11d48', iconColor: '#e11d48' }
            };

            cardNumber = elements.create('cardNumber', {style: style});
            cardNumber.mount('#card-number-element');
            elements.create('cardExpiry', {style: style}).mount('#card-expiry-element');
            elements.create('cardCvc', {style: style}).mount('#card-cvc-element');

            const displayError = document.getElementById('card-errors');
            cardNumber.on('change', function(event) {
                if (event.error) {
                    displayError.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + event.error.message;
                    displayError.style.display = 'flex';
                } else {
                    displayError.style.display = 'none';
                }
            });
        @endif

        const hideAllFields = () => {
            Object.values(fieldWrappers).forEach(el => { if (el) el.style.display = 'none'; });
            if (defaultPrompt) defaultPrompt.style.display = 'none';
        };

        const updatePaymentFields = (gatewayKey) => {
            hideAllFields();
            
            if (offlineInput) {
                if (gatewayKey === 'offline') {
                    offlineInput.setAttribute('required', 'required');
                } else {
                    offlineInput.removeAttribute('required');
                }
            }

            if (fieldWrappers[gatewayKey]) {
                fieldWrappers[gatewayKey].style.display = 'block';
            } else if (nonInlineGateways.includes(gatewayKey)) {
                if (defaultPrompt) {
                    let name = gatewayKey.charAt(0).toUpperCase() + gatewayKey.slice(1);
                    let icon = 'fa-solid fa-arrow-up-right-from-square';

                    if(gatewayKey === 'paypal') icon = 'fa-brands fa-paypal';
                    if(gatewayKey === 'razorpay') icon = 'fa-solid fa-mobile-screen-button';

                    let promptMessage = `<p class="small text-muted mb-0 fw-semibold"><i class="${icon} me-2 text-primary"></i>You will be redirected to ${name} to complete your purchase securely.</p>`;
                    defaultPrompt.innerHTML = promptMessage;
                    defaultPrompt.style.display = 'block';
                }
            }
        };
        
        payOptions.forEach(item => {
            item.addEventListener('click', function() {
                payOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
                updatePaymentFields(this.querySelector('input[type="radio"]').value);
            });
        });

        const initialSelectedRadio = document.querySelector('.pay-option.selected input[type="radio"]');
        if (initialSelectedRadio) updatePaymentFields(initialSelectedRadio.value);

        paymentForm.addEventListener('submit', function(event) {
            const selectedGateway = document.querySelector('input[name="gateway"]:checked').value;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Processing...';
            
            if (selectedGateway === 'stripe') {
                event.preventDefault();
                document.getElementById('card-errors').style.display = 'none';

                stripe.createToken(cardNumber).then(function(result) {
                    if (result.error) {
                        const errorElement = document.getElementById('card-errors');
                        errorElement.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + result.error.message;
                        errorElement.style.display = 'flex';
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Pay Again';
                    } else {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', result.token.id);
                        paymentForm.appendChild(hiddenInput);
                        paymentForm.submit();
                    }
                });
            } else if (selectedGateway === 'razorpay') {
                @if(isset($enabledGateways['razorpay']))
                event.preventDefault();
                var options = {
                    "key": "{{ $enabledGateways['razorpay']['key_id'] }}",
                    "amount": "{{ round($total * 100) }}",
                    "currency": "{{ $settings['currency_code'] ?? 'USD' }}",
                    "name": "{{ config('app.name') }}",
                    "description": "Order Payment",
                    "prefill": {
                        "name": "{{ $billing['first_name'] ?? '' }} {{ $billing['last_name'] ?? '' }}",
                        "email": "{{ $billing['email'] ?? '' }}"
                    },
                    "handler": function (response){
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'razorpay_payment_id');
                        hiddenInput.setAttribute('value', response.razorpay_payment_id);
                        paymentForm.appendChild(hiddenInput);
                        paymentForm.submit();
                    },
                    "modal": {
                        "ondismiss": function(){
                           submitBtn.disabled = false;
                           submitBtn.innerHTML = '{{ __('frontend.pay_with_amount', ['amount' => $currencySymbol . number_format($total, 2)]) }}';
                        }
                    },
                    "theme": { "color": "#4338ca" }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
                @endif
            }
        });
    });
</script>
@endsection