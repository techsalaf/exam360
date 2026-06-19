<div class="tab-pane fade show active" id="gateways">
    <form action="{{ route('admin.settings.payments.update') }}" method="POST">
        @csrf
        @method('POST')

        <div class="setting-card mb-4">
            <div class="row g-4 mb-4">
                <div class="col-12 d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-bold m-0 st-text-main">{{ __('payment.gateways.stripe.title') }}</h4>
                        <p class="small text-muted mt-1">{{ __('payment.gateways.stripe.desc') }}</p>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input type="hidden" name="payment_stripe_enable" value="0">
                        <input class="form-check-input" type="checkbox" name="payment_stripe_enable" value="1" id="stripeEnableSwitch" {{ ($settings['payment_stripe_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label ms-3" for="stripeEnableSwitch">{{ __('payment.enable') }}</label>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.stripe.public_key') }}</label>
                    <input type="text" name="payment_stripe_public_key" class="form-control-premium" value="{{ $settings['payment_stripe_public_key'] ?? '' }}" placeholder="{{ __('payment.gateways.stripe.public_key_placeholder') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.stripe.secret_key') }}</label>
                    <input type="password" name="payment_stripe_secret_key" class="form-control-premium" value="{{ $settings['payment_stripe_secret_key'] ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label-premium">{{ __('payment.gateways.stripe.webhook_secret') }}</label>
                    <input type="text" name="payment_stripe_webhook_secret" class="form-control-premium" value="{{ $settings['payment_stripe_webhook_secret'] ?? '' }}">
                    <small class="text-danger small mt-2 d-block">
                        {{ __('payment.gateways.stripe.webhook_url') }}: <code>{{ url('webhooks/stripe') }}</code>
                    </small>
                </div>
            </div>
        </div>

        <div class="setting-card mb-4">
            <div class="row g-4 mb-4">
                <div class="col-12 d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-bold m-0 st-text-main">{{ __('payment.gateways.paypal.title') }}</h4>
                        <p class="small text-muted mt-1">{{ __('payment.gateways.paypal.desc') }}</p>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input type="hidden" name="payment_paypal_enable" value="0">
                        <input class="form-check-input" type="checkbox" name="payment_paypal_enable" value="1" id="paypalEnableSwitch" {{ ($settings['payment_paypal_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label ms-3" for="paypalEnableSwitch">{{ __('payment.enable') }}</label>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.paypal.client_id') }}</label>
                    <input type="text" name="payment_paypal_client_id" class="form-control-premium" value="{{ $settings['payment_paypal_client_id'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.paypal.secret_key') }}</label>
                    <input type="password" name="payment_paypal_secret_key" class="form-control-premium" value="{{ $settings['payment_paypal_secret_key'] ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label-premium">{{ __('payment.gateways.paypal.env') }}</label>
                    <select name="payment_paypal_environment" class="form-control-premium">
                        <option value="sandbox" {{ ($settings['payment_paypal_environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>{{ __('payment.gateways.paypal.sandbox') }}</option>
                        <option value="live" {{ ($settings['payment_paypal_environment'] ?? 'sandbox') == 'live' ? 'selected' : '' }}>{{ __('payment.gateways.paypal.live') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="setting-card mb-4">
            <div class="row g-4 mb-4">
                <div class="col-12 d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-bold m-0 st-text-main">{{ __('payment.gateways.razorpay.title') }}</h4>
                        <p class="small text-muted mt-1">{{ __('payment.gateways.razorpay.desc') }}</p>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input type="hidden" name="payment_razorpay_enable" value="0">
                        <input class="form-check-input" type="checkbox" name="payment_razorpay_enable" value="1" id="razorpayEnableSwitch" {{ ($settings['payment_razorpay_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label ms-3" for="razorpayEnableSwitch">{{ __('payment.enable') }}</label>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.razorpay.key_id') }}</label>
                    <input type="text" name="payment_razorpay_key_id" class="form-control-premium" value="{{ $settings['payment_razorpay_key_id'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.razorpay.key_secret') }}</label>
                    <input type="password" name="payment_razorpay_key_secret" class="form-control-premium" value="{{ $settings['payment_razorpay_key_secret'] ?? '' }}">
                </div>
            </div>
        </div>

        <div class="setting-card mb-4">
            <div class="row g-4 mb-4">
                <div class="col-12 d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-bold m-0 st-text-main">{{ __('payment.gateways.offline.title') }}</h4>
                        <p class="small text-muted mt-1">{{ __('payment.gateways.offline.desc') }}</p>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input type="hidden" name="payment_offline_enable" value="0">
                        <input class="form-check-input" type="checkbox" name="payment_offline_enable" value="1" id="offlineEnableSwitch" {{ ($settings['payment_offline_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label ms-3" for="offlineEnableSwitch">{{ __('payment.enable') }}</label>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.offline.holder_name') }}</label>
                    <input type="text" name="payment_offline_account_holder" class="form-control-premium" value="{{ $settings['payment_offline_account_holder'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.offline.bank_name') }}</label>
                    <input type="text" name="payment_offline_bank_name" class="form-control-premium" value="{{ $settings['payment_offline_bank_name'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.offline.acc_number') }}</label>
                    <input type="text" name="payment_offline_account_number" class="form-control-premium" value="{{ $settings['payment_offline_account_number'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-premium">{{ __('payment.gateways.offline.swift_code') }}</label>
                    <input type="text" name="payment_offline_swift_code" class="form-control-premium" value="{{ $settings['payment_offline_swift_code'] ?? '' }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label-premium">{{ __('payment.gateways.offline.instructions') }}</label>
                    <textarea name="payment_offline_instructions" class="form-control-premium" rows="5">{{ $settings['payment_offline_instructions'] ?? __('payment.gateways.offline.default_inst') }}</textarea>
                    <small class="text-muted mt-2 d-block">{{ __('payment.gateways.offline.instructions_help') }}</small>
                </div>
            </div>
        </div>

        @php
            $paymentGatewayAddonActive = false;
            if (\Illuminate\Support\Facades\Schema::hasTable('addons')) {
                $paymentGatewayAddonActive = \Illuminate\Support\Facades\DB::table('addons')
                    ->where('slug', 'payment-gateways')
                    ->where('is_active', 1)
                    ->exists();
            }
        @endphp

        @if($paymentGatewayAddonActive)
            {{-- PayU --}}
            <div class="setting-card mb-4">
                <div class="row g-4 mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold m-0 st-text-main">{{ __('pga::payment_gateways.payu_title') }}</h4>
                            <p class="small text-muted mt-1">{{ __('pga::payment_gateways.payu_desc') }}</p>
                        </div>
                        <div class="form-check form-switch mt-1">
                            <input type="hidden" name="payment_payu_enable" value="0">
                            <input class="form-check-input" type="checkbox" name="payment_payu_enable" value="1" id="payuEnableSwitch" {{ ($settings['payment_payu_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label ms-3" for="payuEnableSwitch">{{ __('payment.enable') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.payu_merchant_key') }}</label>
                        <input type="text" name="payment_payu_merchant_key" class="form-control-premium" value="{{ $settings['payment_payu_merchant_key'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.payu_salt') }}</label>
                        <input type="password" name="payment_payu_salt" class="form-control-premium" value="{{ $settings['payment_payu_salt'] ?? '' }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.payu_env') }}</label>
                        <select name="payment_payu_environment" class="form-control-premium">
                            <option value="sandbox" {{ ($settings['payment_payu_environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>{{ __('payment.gateways.paypal.sandbox') }}</option>
                            <option value="live" {{ ($settings['payment_payu_environment'] ?? 'sandbox') == 'live' ? 'selected' : '' }}>{{ __('payment.gateways.paypal.live') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Paystack --}}
            <div class="setting-card mb-4">
                <div class="row g-4 mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold m-0 st-text-main">{{ __('pga::payment_gateways.paystack_title') }}</h4>
                            <p class="small text-muted mt-1">{{ __('pga::payment_gateways.paystack_desc') }}</p>
                        </div>
                        <div class="form-check form-switch mt-1">
                            <input type="hidden" name="payment_paystack_enable" value="0">
                            <input class="form-check-input" type="checkbox" name="payment_paystack_enable" value="1" id="paystackEnableSwitch" {{ ($settings['payment_paystack_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label ms-3" for="paystackEnableSwitch">{{ __('payment.enable') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.paystack_public_key') }}</label>
                        <input type="text" name="payment_paystack_public_key" class="form-control-premium" value="{{ $settings['payment_paystack_public_key'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.paystack_secret_key') }}</label>
                        <input type="password" name="payment_paystack_secret_key" class="form-control-premium" value="{{ $settings['payment_paystack_secret_key'] ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Flutterwave --}}
            <div class="setting-card mb-4">
                <div class="row g-4 mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold m-0 st-text-main">{{ __('pga::payment_gateways.flutterwave_title') }}</h4>
                            <p class="small text-muted mt-1">{{ __('pga::payment_gateways.flutterwave_desc') }}</p>
                        </div>
                        <div class="form-check form-switch mt-1">
                            <input type="hidden" name="payment_flutterwave_enable" value="0">
                            <input class="form-check-input" type="checkbox" name="payment_flutterwave_enable" value="1" id="flutterwaveEnableSwitch" {{ ($settings['payment_flutterwave_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label ms-3" for="flutterwaveEnableSwitch">{{ __('payment.enable') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.flutterwave_public_key') }}</label>
                        <input type="text" name="payment_flutterwave_public_key" class="form-control-premium" value="{{ $settings['payment_flutterwave_public_key'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.flutterwave_secret_key') }}</label>
                        <input type="password" name="payment_flutterwave_secret_key" class="form-control-premium" value="{{ $settings['payment_flutterwave_secret_key'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.flutterwave_encryption_key') }}</label>
                        <input type="password" name="payment_flutterwave_encryption_key" class="form-control-premium" value="{{ $settings['payment_flutterwave_encryption_key'] ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Mollie --}}
            <div class="setting-card mb-4">
                <div class="row g-4 mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold m-0 st-text-main">{{ __('pga::payment_gateways.mollie_title') }}</h4>
                            <p class="small text-muted mt-1">{{ __('pga::payment_gateways.mollie_desc') }}</p>
                        </div>
                        <div class="form-check form-switch mt-1">
                            <input type="hidden" name="payment_mollie_enable" value="0">
                            <input class="form-check-input" type="checkbox" name="payment_mollie_enable" value="1" id="mollieEnableSwitch" {{ ($settings['payment_mollie_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label ms-3" for="mollieEnableSwitch">{{ __('payment.enable') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.mollie_api_key') }}</label>
                        <input type="password" name="payment_mollie_api_key" class="form-control-premium" value="{{ $settings['payment_mollie_api_key'] ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Paddle --}}
            <div class="setting-card mb-4">
                <div class="row g-4 mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold m-0 st-text-main">{{ __('pga::payment_gateways.paddle_title') }}</h4>
                            <p class="small text-muted mt-1">{{ __('pga::payment_gateways.paddle_desc') }}</p>
                        </div>
                        <div class="form-check form-switch mt-1">
                            <input type="hidden" name="payment_paddle_enable" value="0">
                            <input class="form-check-input" type="checkbox" name="payment_paddle_enable" value="1" id="paddleEnableSwitch" {{ ($settings['payment_paddle_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label ms-3" for="paddleEnableSwitch">{{ __('payment.enable') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.paddle_vendor_id') }}</label>
                        <input type="text" name="payment_paddle_vendor_id" class="form-control-premium" value="{{ $settings['payment_paddle_vendor_id'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.paddle_auth_code') }}</label>
                        <input type="password" name="payment_paddle_auth_code" class="form-control-premium" value="{{ $settings['payment_paddle_auth_code'] ?? '' }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-premium">{{ __('pga::payment_gateways.paddle_public_key') }}</label>
                        <textarea name="payment_paddle_public_key" class="form-control-premium" rows="3">{{ $settings['payment_paddle_public_key'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-check me-2"></i> {{ __('payment.save_gateway') }}
            </button>
        </div>

    </form>
</div>