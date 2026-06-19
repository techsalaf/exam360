<div class="modal fade" id="createCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">{{ __('coupons.modal_create_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">{{ __('coupons.label_code') }}</label>
                    <input type="text" name="code" class="form-control text-uppercase fw-bold" placeholder="{{ __('coupons.placeholder_code') }}" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">{{ __('coupons.label_type') }}</label>
                        <select name="type" class="form-select">
                            {{-- FIXED: Removed redundant symbols from the label, leaving only the required dynamic symbol/percentage sign --}}
                            <option value="fixed">{{ __('coupons.opt_fixed') }} ({{ $currencySymbol }})</option>
                            <option value="percentage">{{ __('coupons.opt_percentage') }} (%)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">{{ __('coupons.label_value') }}</label>
                        <input type="number" name="value" step="0.01" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">{{ __('coupons.label_usage_limit') }}</label>
                        <input type="number" name="usage_limit" class="form-control" placeholder="{{ __('coupons.text_unlimited') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">{{ __('coupons.label_min_purchase') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $currencySymbol }}</span>
                            <input type="number" name="min_purchase" step="0.01" class="form-control" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">{{ __('coupons.label_expiry') }}</label>
                    <input type="datetime-local" name="expires_at" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">{{ __('coupons.btn_cancel') }}</button>
                <button type="submit" class="btn-green-pill">{{ __('coupons.btn_create') }}</button>
            </div>
        </form>
    </div>
</div>