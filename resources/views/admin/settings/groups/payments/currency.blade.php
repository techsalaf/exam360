<div class="tab-pane fade show active" id="currency">
    <div class="settings-content">
        <form action="{{ route('admin.settings.payments.update') }}" method="POST">
            @csrf
            
            <div class="setting-card">
                
                <div class="setting-header">
                    {{-- Assumed keys based on context --}}
                    <h3 class="setting-title">{{ __('payments.currency_title') }}</h3>
                    <p class="setting-desc">{{ __('payments.currency_desc') }}</p>
                </div>

                <div class="border rounded-3 p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="currency-icon-box">
                            <i class="fa-solid fa-coins currency-icon"></i>
                        </div>
                        <div>
                            {{-- Assumed keys based on context --}}
                            <h6 class="fw-bold text-dark m-0">{{ __('payments.currency_global_currency') }}</h6>
                            <span class="text-muted small">{{ __('payments.currency_global_desc') }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        
                        <div class="col-md-6">
                            {{-- Corrected key prefix to 'payments' --}}
                            <label class="form-label-premium">{{ __('payments.currency_primary') }}</label>
                            <select name="currency_code" id="currency_code_select" class="form-control-premium form-select" required>
                                
                                @foreach($currencies as $code => $data)
                                    <option value="{{ $code }}" {{ ($settings['currency_code'] ?? 'USD') == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $data['name'] }} ({{ $data['symbol'] }})
                                    </option>
                                @endforeach
                                
                                <option value="CUSTM" {{ !isset($currencies[$settings['currency_code'] ?? '']) && ($settings['currency_code'] ?? '') ? 'selected' : '' }}>
                                    {{-- Corrected key prefix --}}
                                    {{ __('payments.currency_custom_opt') }}
                                </option>
                            </select>
                        </div>
                        
                        {{-- Custom Fields Container controlled by JS class --}}
                        <div class="col-md-6 custom-currency-fields-container">
                            <div class="row g-2">
                                <div class="col-6">
                                    {{-- CORRECTED KEY USAGE --}}
                                    <label class="form-label-premium">{{ __('payments.custom_code_label', ['example' => 'QAR']) }}</label>
                                    <input type="text" 
                                           name="custom_currency_code" 
                                           class="form-control-premium" 
                                           value="{{ !isset($currencies[$settings['currency_code'] ?? '']) ? ($settings['currency_code'] ?? '') : '' }}" 
                                           maxlength="5" 
                                           placeholder="{{ __('payments.example_code_placeholder') }}">
                                </div>
                                <div class="col-6">
                                    {{-- CORRECTED KEY USAGE --}}
                                    <label class="form-label-premium">{{ __('payments.custom_symbol_label', ['example' => '₹']) }}</label>
                                    <input type="text" 
                                           name="custom_currency_symbol" 
                                           class="form-control-premium" 
                                           value="{{ !isset($currencies[$settings['currency_code'] ?? '']) ? ($settings['currency_symbol'] ?? '') : '' }}" 
                                           maxlength="4" 
                                           placeholder="{{ __('payments.example_symbol_placeholder') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Corrected key prefix --}}
                            <label class="form-label-premium">{{ __('payments.currency_position') }}</label>
                            <select name="currency_position" class="form-control-premium form-select" required>
                                <option value="before" {{ ($settings['currency_position'] ?? 'before') == 'before' ? 'selected' : '' }}>{{ __('payments.currency_pos_before') }}</option>
                                <option value="after" {{ ($settings['currency_position'] ?? 'before') == 'after' ? 'selected' : '' }}>{{ __('payments.currency_pos_after') }}</option>
                                <option value="before_space" {{ ($settings['currency_position'] ?? 'before') == 'before_space' ? 'selected' : '' }}>{{ __('payments.currency_pos_before_space') }}</option>
                                <option value="after_space" {{ ($settings['currency_position'] ?? 'before') == 'after_space' ? 'selected' : '' }}>{{ __('payments.currency_pos_after_space') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            {{-- Corrected key prefix --}}
                            <label class="form-label-premium">{{ __('payments.currency_decimal_sep') }}</label>
                            <input type="text" 
                                   name="decimal_separator" 
                                   class="form-control-premium font-monospace currency-sep-input" 
                                   value="{{ $settings['decimal_separator'] ?? '.' }}" 
                                   maxlength="1"
                                   required>
                            <div class="form-text text-muted small">{{ __('payments.currency_decimal_help') }}</div>
                        </div>
                        
                        <div class="col-md-6">
                            {{-- Corrected key prefix --}}
                            <label class="form-label-premium">{{ __('payments.currency_thousands_sep') }}</label>
                            <input type="text" 
                                   name="thousands_separator" 
                                   class="form-control-premium font-monospace currency-sep-input" 
                                   value="{{ $settings['thousands_separator'] ?? ',' }}" 
                                   maxlength="1"
                                   required>
                            <div class="form-text text-muted small">{{ __('payments.currency_thousands_help') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa-solid fa-check me-2"></i> {{ __('payments.save_settings') }}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/admin-currency.js') }}"></script>
@endpush