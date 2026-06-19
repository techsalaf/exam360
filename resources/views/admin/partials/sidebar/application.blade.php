@php
    $appAddons = $globalAddons['application'] ?? collect([]);
@endphp

@canany(['view_users', 'create_users'])
<div class="menu-group-title">{{ __('menu.management') }}</div>
<div class="nav-group">

    <div class="nav-item nav-dropdown-toggle {{ $isUserActive ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ $isUserActive ? 'true' : 'false' }}"
        aria-controls="user-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>{{ __('menu.manage_users') }}</span>
        </div>
        <div class="d-flex align-items-center">
            <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
    </div>
    
    <div id="user-dropdown-menu" class="nav-dropdown {{ $isUserActive ? 'show' : '' }}">
        <a href="{{ route('admin.users.active') }}" class="nav-sub-item {{ request()->routeIs('admin.users.active') ? 'active' : '' }}">
            {{ __('menu.active_users') }}
            @if(isset($activeUsersCount) && $activeUsersCount > 0) <span class="nav-badge badge-info ms-auto">{{ $activeUsersCount }}</span> @endif
        </a>
        
        <a href="{{ route('admin.users.banned') }}" class="nav-sub-item {{ request()->routeIs('admin.users.banned') ? 'active' : '' }}">
            {{ __('menu.banned_users') }}
            @if(isset($bannedUsersCount) && $bannedUsersCount > 0) <span class="nav-badge badge-info ms-auto">{{ $bannedUsersCount }}</span> @endif
        </a>
        
        @can('create_users')
        <a href="{{ route('admin.users.unverified.email') }}" class="nav-sub-item {{ request()->routeIs('admin.users.unverified.email') ? 'active' : '' }}">
            {{ __('menu.email_unverified') }} 
            @if(isset($pendingEmailCount) && $pendingEmailCount > 0) <span class="nav-badge badge-info ms-auto">{{ $pendingEmailCount }}</span> @endif
        </a>
        
        <a href="{{ route('admin.users.unverified.mobile') }}" class="nav-sub-item {{ request()->routeIs('admin.users.unverified.mobile') ? 'active' : '' }}">
            {{ __('menu.mobile_unverified') }}
            @if(isset($pendingMobileCount) && $pendingMobileCount > 0) <span class="nav-badge badge-info ms-auto">{{ $pendingMobileCount }}</span> @endif
        </a>
        @endcan

        <a href="{{ route('admin.users.index') }}" class="nav-sub-item {{ request()->routeIs('admin.users.index') && !request()->routeIs('admin.users.active') && !request()->routeIs('admin.users.banned') && !request()->routeIs('admin.users.unverified.*') ? 'active' : '' }}">
            {{ __('menu.all_users') }}
            @if(isset($totalUsersCount) && $totalUsersCount > 0) <span class="nav-badge badge-info ms-auto">{{ $totalUsersCount }}</span> @endif
        </a>
        
        @can('create_users')
        <a href="{{ route('admin.users.notifications.create') }}" class="nav-sub-item {{ request()->routeIs('admin.users.notifications.*') ? 'active' : '' }}">
            {{ __('menu.send_notification') }}
        </a>
        @endcan
    </div>
</div>
@endcanany

@can('manage_tickets')
<div class="nav-group">
    <div class="nav-item nav-dropdown-toggle {{ $isTicketActive ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ $isTicketActive ? 'true' : 'false' }}"
        aria-controls="ticket-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 17H2V5h10V3H2c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V10h-2v7z"/><path d="M18 10h-2V7h2V5h2v2h2v3h-2v2h-2v-2z"/>
            </svg>
            <span>{{ __('menu.support_ticket') }}</span>
        </div>
        <div class="d-flex align-items-center">
            <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
    </div>
    
    <div id="ticket-dropdown-menu" class="nav-dropdown {{ $isTicketActive ? 'show' : '' }}">
        <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="nav-sub-item {{ $currentTicketStatus === 'open' ? 'active' : '' }}">
            {{ __('menu.pending_ticket') }}
            @if(isset($pendingTicketCount) && $pendingTicketCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $pendingTicketCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" class="nav-sub-item {{ $currentTicketStatus === 'closed' ? 'active' : '' }}">
            {{ __('menu.closed_ticket') }}
            @if(isset($closedTicketCount) && $closedTicketCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $closedTicketCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'replied']) }}" class="nav-sub-item {{ $currentTicketStatus === 'answered' ? 'active' : '' }}">
            {{ __('menu.answered_ticket') }}
            @if(isset($answeredTicketCount) && $answeredTicketCount > 0)
                <span class="nav-badge badge-info ms-auto">{{ $answeredTicketCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="nav-sub-item {{ $isTicketActive && !$currentTicketStatus ? 'active' : '' }}">
            {{ __('menu.all_tickets') }}
        </a>
    </div>
</div>
@endcan

@php
    $appAddons = $globalAddons['application'] ?? collect([]);
@endphp

@foreach($appAddons as $addon)
    @if(Route::has($addon->route_name))
    <a href="{{ route($addon->route_name) }}" class="nav-item {{ request()->routeIs($addon->route_name.'*') ? 'active' : '' }}">
        <div class="nav-item-content">
            <i class="{{ $addon->icon }} fa-lg" style="width: 24px; text-align: center;"></i>
            <span>{{ $addon->name }}</span>
        </div>
    </a>
    @endif
@endforeach