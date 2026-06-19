@php
    $financeAddons = $globalAddons['finance'] ?? collect([]);
@endphp

@can('access_settings')
<a href="{{ route('admin.plans.index') }}" class="nav-item {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
    <div class="nav-item-content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 3h12l4 6-10 13L2 9z"></path><path d="M12 22V9"></path>
        </svg>
        <span>{{ __('menu.pricing_plans') }}</span>
    </div>
</a>
@endcan

@can('access_settings')
<div class="menu-group-title">{{ __('menu.finance') }}</div>
<div class="nav-group">
   <div class="nav-item nav-dropdown-toggle {{ $isPaymentActive ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ $isPaymentsDropdownOpen ? 'true' : 'false' }}"
        aria-controls="payment-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span>{{ __('menu.payments') }}</span>
        </div>
        <div class="d-flex align-items-center">
            <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
    </div>
    
    <div id="payment-dropdown-menu" class="nav-dropdown {{ $isPaymentsDropdownOpen ? 'show' : '' }}">
        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="nav-sub-item {{ $currentPaymentStatus === 'pending' ? 'active' : '' }}">
            {{ __('menu.pending_payments') }}
            @if(isset($pendingPaymentsCount) && $pendingPaymentsCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $pendingPaymentsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'successful']) }}" class="nav-sub-item {{ $currentPaymentStatus === 'successful' || $currentPaymentStatus === 'approved' ? 'active' : '' }}">
            {{ __('menu.successful_payments') }}
            @if(isset($successfulPaymentsCount) && $successfulPaymentsCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $successfulPaymentsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" class="nav-sub-item {{ $currentPaymentStatus === 'rejected' ? 'active' : '' }}">
            {{ __('menu.rejected_payments') }}
            @if(isset($rejectedPaymentsCount) && $rejectedPaymentsCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $rejectedPaymentsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'initiated']) }}" class="nav-sub-item {{ $currentPaymentStatus === 'initiated' ? 'active' : '' }}">
            {{ __('menu.initiated_payments') }}
            @if(isset($initiatedPaymentsCount) && $initiatedPaymentsCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $initiatedPaymentsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-sub-item {{ $isPaymentActive && is_null($currentPaymentStatus) ? 'active' : '' }}">
            {{ __('menu.all_history') }}
        </a>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <span>{{ __('menu.coupons') }}</span>
        </div>
    </a>

    @foreach($financeAddons as $addon)
        @if(Route::has($addon->route_name))
        <a href="{{ route($addon->route_name) }}" class="nav-item {{ request()->routeIs($addon->route_name.'*') ? 'active' : '' }}">
            <div class="nav-item-content">
                <i class="{{ $addon->icon }} fa-lg" style="width: 24px; text-align: center;"></i>
                <span>{{ $addon->name }}</span>
            </div>
        </a>
        @endif
    @endforeach
</div>
@endcan