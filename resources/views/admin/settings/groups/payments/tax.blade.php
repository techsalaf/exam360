@php
    // Envato Fix: Data should be passed from Controller. 
    // Using standard $settings array to avoid DB calls in View.
    $taxName = $settings['tax_name'] ?? 'VAT';
    $taxRate = $settings['tax_default_rate'] ?? '0.00';
    $isInclusive = ($settings['tax_inclusive'] ?? '0') === '1';
@endphp

<div class="settings-content">
    <form action="{{ route('admin.settings.payments.update') }}" method="POST">
        @csrf
        
        <div class="setting-card">
            
            <div class="setting-header">
                <h3 class="setting-title">{{ __('payment.tax.title') }}</h3>
                <p class="setting-desc">{{ __('payment.tax.desc') }}</p>
            </div>

            <div class="border rounded-3 p-4">
                <div class="d-flex align-items-center mb-4">
                    {{-- Envato Fix: Replaced inline styles with CSS class --}}
                    <div class="tax-icon-box me-3">
                        <i class="fa-solid fa-file-invoice-dollar tax-icon"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">{{ __('payment.tax.global_rules') }}</h6>
                        <span class="text-muted small">{{ __('payment.tax.global_rules_desc') }}</span>
                    </div>
                </div>

                <div class="row g-4">
                    
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('payment.tax.name') }}</label>
                        {{-- Envato Fix: Translatable placeholder --}}
                        <input type="text" 
                               name="tax_name" 
                               class="form-control-premium" 
                               value="{{ $taxName }}" 
                               placeholder="{{ __('payment.tax.name_placeholder') }}">
                        <div class="form-text text-muted small">{{ __('payment.tax.name_help') }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('payment.tax.rate') }}</label>
                        
                        <div class="d-flex align-items-center gap-2">
                            {{-- Envato Fix: Translatable placeholder --}}
                            <input type="number" 
                                   step="0.01" 
                                   name="tax_default_rate" 
                                   class="form-control-premium" 
                                   value="{{ $taxRate }}" 
                                   placeholder="{{ __('payment.tax.rate_placeholder') }}">
                            
                            {{-- Envato Fix: Replaced inline styles with CSS class --}}
                            <div class="tax-percent-box">
                                %
                            </div>
                        </div>
                        
                        <div class="form-text text-muted small">{{ __('payment.tax.rate_help') }}</div>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 bg-light">
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-muted">
                                    <i class="fa-solid fa-tag fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ __('payment.tax.inclusive') }}</span>
                                    <small class="text-muted">{{ __('payment.tax.inclusive_help') }}</small>
                                </div>
                            </div>
                            
                            {{-- Envato Fix: Replaced inline style with class & added Hidden Input --}}
                            <div class="form-check form-switch m-0 tax-switch"> 
                                <input type="hidden" name="tax_inclusive" value="0">
                                <input class="form-check-input cursor-pointer" 
                                       type="checkbox" 
                                       name="tax_inclusive" 
                                       value="1" 
                                       id="taxInclusiveSwitch" 
                                       {{ $isInclusive ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-check me-2"></i> {{ __('payment.save_settings') }}
                </button>
            </div>

        </div>
    </form>
</div>