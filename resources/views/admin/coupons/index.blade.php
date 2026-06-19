@extends('layouts.admin')

@section('title', __('coupons.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/admin-coupons.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div id="couponConfig" 
         data-confirm-title="{{ __('coupons.confirm_delete_title') }}"
         data-confirm-text="{{ __('coupons.confirm_delete_text') }}"
         data-confirm-yes="{{ __('coupons.confirm_delete_btn') }}"
         data-confirm-cancel="{{ __('coupons.btn_cancel') }}">
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 page-header-flex">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('coupons.header_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('coupons.header_subtitle') }}</p>
        </div>
        
        <button class="btn-green-pill" data-bs-toggle="modal" data-bs-target="#createCouponModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ __('coupons.btn_create') }}
        </button>
    </div>

    <div class="card shadow-sm card-rounded">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">{{ __('coupons.col_code') }}</th>
                            <th>{{ __('coupons.col_discount') }}</th>
                            <th>{{ __('coupons.col_usage') }}</th>
                            <th>{{ __('coupons.col_expiry') }}</th>
                            <th>{{ __('coupons.col_status') }}</th>
                            <th class="pe-4 text-end">{{ __('coupons.col_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td class="ps-4">
                                    <span class="coupon-code-badge">{{ $coupon->code }}</span>
                                    <div class="small text-muted mt-2 fw-medium">
                                        {{ __('coupons.label_min_order') }}: {{ $coupon->min_purchase ? $currencySymbol . number_format($coupon->min_purchase, 2) : __('coupons.text_none') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark text-size-1rem">
                                        {{ $coupon->type == 'fixed' ? $currencySymbol : '' }}{{ number_format($coupon->value, 2) }}{{ $coupon->type == 'percentage' ? '%' : '' }}
                                    </div>
                                    <span class="small text-muted text-capitalize">
                                        {{ $coupon->type == 'fixed' ? __('coupons.type_fixed_off') : __('coupons.type_percent_off') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="small fw-bold text-dark">{{ $coupon->used_count }} <span class="text-muted">/ {{ $coupon->usage_limit ?? '∞' }}</span></span>
                                        @if($coupon->usage_limit)
                                            @php 
                                                $percent = min(($coupon->used_count / $coupon->usage_limit) * 100, 100); 
                                            @endphp
                                            <div class="usage-progress">
                                                <div class="usage-bar" data-progress="{{ $percent }}"></div>
                                            </div>
                                        @else
                                            <span class="small text-success fw-bold mt-1">{{ __('coupons.text_unlimited') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($coupon->expires_at)
                                        <div class="fw-medium {{ $coupon->expires_at->isPast() ? 'text-danger' : 'text-dark' }}">
                                            {{ $coupon->expires_at->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted">{{ $coupon->expires_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-success small fw-bold">{{ __('coupons.text_lifetime') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn p-0 border-0">
                                            @if($coupon->is_active)
                                                <span class="status-badge status-active">{{ __('coupons.status_active') }}</span>
                                            @else
                                                <span class="status-badge status-inactive">{{ __('coupons.status_inactive') }}</span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn-icon-pill" 
                                                data-bs-toggle="modal" 
                                                title="{{ __('coupons.btn_edit') }}"
                                                data-bs-target="#editCouponModal{{ $coupon->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5L2 22l1.5-5.5L17 3z"></path></svg>
                                        </button>
                                        
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline delete-form-sa">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon-pill delete" title="{{ __('coupons.btn_delete') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    @include('admin.coupons.partials.edit-modal', ['coupon' => $coupon, 'currencySymbol' => $currencySymbol])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-3"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                        <p class="fw-medium mb-0">{{ __('coupons.empty_state') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($coupons->hasPages())
            <div class="card-footer border-0 py-3">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

    @include('admin.coupons.partials.create-modal', ['currencySymbol' => $currencySymbol])

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-coupons.js') }}"></script>
@endpush