<div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"></h5> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-2">
                
                @php
                    $details = is_array($payment->gateway_response) ? $payment->gateway_response : json_decode($payment->gateway_response, true);
                    $charge = $details['charge'] ?? 0;
                    $rate = $details['rate'] ?? 1;
                    $user_info = $details['user_info'] ?? null;
                    $payment_status = strtolower($payment->status);
                    $totalPayable = ($payment->amount + $charge) * $rate;

                    $cf_modal = function($amount) use ($payment, $systemFormatting, $formatCurrency) {
                        return $formatCurrency(
                            $amount, 
                            $systemFormatting['symbol'], 
                            $systemFormatting['position'], 
                            $systemFormatting['decimal_sep'], 
                            $systemFormatting['thousands_sep']
                        ) . ' ' . $payment->currency; 
                    };

                    $statusClass = match($payment_status) {
                        'success', 'approved' => 'status-success',
                        'pending' => 'status-pending',
                        'failed', 'rejected' => 'status-failed',
                        'initiated' => 'status-initiated',
                        default => 'status-default'
                    };
                    $statusKey = 'payments.status_' . $payment_status;
                    $statusLabel = \Lang::has($statusKey) ? __($statusKey) : ucfirst($payment->status);
                @endphp

                <div class="pm-hero-section">
                    <div class="pm-hero-amount">{{ $cf_modal($payment->amount) }}</div>
                    
                    <span class="status-badge {{ $statusClass }} px-3 py-1 fs-08">
                        <i class="fa-solid fa-circle me-1 icon-circle-xs"></i>
                        {{ $statusLabel }}
                    </span>
                    
                    <div class="text-muted small mt-2">
                        {{ $payment->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>

                @if($user_info && $payment_status !== 'initiated')
                <div class="user-info-box">
                    <div class="user-info-icon"><i class="fa-solid fa-user"></i></div>
                    <div class="user-info-content">
                        <h6 class="fw-bold mb-2 text-dark">{{ __('payments.sect_user_info') }}</h6>
                        <div class="row g-2 small">
                            <div class="col-6">
                                <span class="d-block text-muted">{{ __('payments.label_fname') }}</span>
                                <span class="fw-bold text-dark">{{ $user_info['first_name'] ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6">
                                <span class="d-block text-muted">{{ __('payments.label_lname') }}</span>
                                <span class="fw-bold text-dark">{{ $user_info['last_name'] ?? 'N/A' }}</span>
                            </div>
                            @if(isset($user_info['trx_ref']))
                            <div class="col-12 mt-2 pt-2 border-top">
                                <span class="d-block text-muted">{{ __('payments.label_trx') }}</span>
                                <span class="font-monospace text-dark">{{ $user_info['trx_ref'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="pm-grid-section">
                    <div class="pm-detail-group">
                        <label>{{ __('payments.label_trx') }}</label>
                        <div class="font-monospace user-select-all text-dark">{{ $payment->transaction_id }}</div>
                    </div>
                    
                    <div class="pm-detail-group">
                        <label>{{ __('payments.label_method') }}</label>
                        <div>
                            <i class="fa-solid fa-credit-card me-1 text-muted"></i> 
                            {{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}
                        </div>
                    </div>
                    
                    <div class="pm-detail-group">
                        <label>{{ __('payments.label_username') }}</label>
                        <div>
                             @if($payment->user)
                                <a href="{{ route('admin.users.show', $payment->user_id) }}" class="text-decoration-none fw-bold text-dark">
                                    {{ '@' . ($payment->user->username ?? \Illuminate\Support\Str::before($payment->user->email, '@')) }}
                                </a>
                                <div class="small text-muted fw-normal fs-08">{{ $payment->user->name }}</div>
                            @else
                                <span class="text-muted fst-italic">{{ __('payments.text_user_deleted') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pm-detail-group">
                        <label>{{ __('payments.label_rate') }}</label>
                        <div>1 {{ $payment->currency }} = {{ $rate }} {{ $payment->currency }}</div>
                    </div>
                </div>

                <div class="pm-financial-box">
                    <div class="pm-row">
                        <span class="pm-label">{{ __('payments.label_amount') }}</span>
                        <span class="pm-val">{{ $cf_modal($payment->amount) }}</span>
                    </div>
                    
                    @if($charge > 0)
                    <div class="pm-row">
                        <span class="pm-label">{{ __('payments.label_charge') }}</span>
                        <span class="pm-val text-danger">+ {{ $cf_modal($charge) }}</span>
                    </div>
                    @endif
                    
                    <div class="pm-row total">
                        <span class="pm-label fw-bold text-dark">{{ __('payments.label_total') }}</span>
                        <span class="pm-val total">{{ $cf_modal($totalPayable) }}</span>
                    </div>
                </div>

            </div>

            <div class="modal-footer border-top-0 pt-0 pb-4 px-4 justify-content-center">
                @if($payment->status === 'pending')
                    <div class="d-flex gap-2 w-100">
                        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="flex-grow-1 confirm-action">
                            @csrf @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-outline-danger w-100 rounded-pill fw-bold"
                                    data-title="{{ __('payments.confirm_reject_title') }}" 
                                    data-text="{{ __('payments.confirm_reject_text', ['trx' => $payment->transaction_id]) }}" 
                                    data-type="warning">
                                {{ __('payments.btn_reject') }}
                            </button>
                        </form>

                        <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="flex-grow-1 confirm-action">
                            @csrf @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-success w-100 rounded-pill fw-bold text-white"
                                    data-title="{{ __('payments.confirm_approve_title') }}" 
                                    data-text="{{ __('payments.confirm_approve_text', ['trx' => $payment->transaction_id]) }}" 
                                    data-type="success">
                                {{ __('payments.btn_approve') }}
                            </button>
                        </form>
                    </div>
                @else
                    <button type="button" class="btn btn-light border w-100 rounded-pill fw-bold" data-bs-dismiss="modal">
                        {{ __('payments.btn_close') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>