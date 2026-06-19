@php
    $extraAddons = $globalAddons['extra'] ?? collect([]);
@endphp

@can('view_reports')
<div class="menu-group-title">{{ __('menu.analytics') }}</div>
<div class="nav-group">
    <div class="nav-item nav-dropdown-toggle {{ $isReportActive ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ $isReportActive ? 'true' : 'false' }}"
        aria-controls="report-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>{{ __('menu.reports') }}</span>
        </div>
        <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </div>

    <div id="report-dropdown-menu" class="nav-dropdown {{ $isReportActive ? 'show' : '' }}">
        <a href="{{ route('admin.reports.subscriptions') }}" class="nav-sub-item {{ request()->routeIs('admin.reports.subscriptions') ? 'active' : '' }}">
            {{ __('menu.subscription_history') }}
        </a>
        <a href="{{ route('admin.reports.exam.history') }}" class="nav-sub-item {{ request()->routeIs('admin.reports.exam.history') ? 'active' : '' }}">
            {{ __('menu.exam_history') }}
        </a>
        <a href="{{ route('admin.reports.login.history') }}" class="nav-sub-item {{ request()->routeIs('admin.reports.login.history') ? 'active' : '' }}">
            {{ __('menu.login_history') }}
        </a>
    </div>
</div>
@endcan

@can('access_settings')
<div class="menu-group-title">{{ __('menu.system') }}</div>
<div class="nav-group">
    <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
            </svg>
            <span>{{ __('menu.system_setting') }}</span>
        </div>
    </a>

    <div class="nav-item nav-dropdown-toggle {{ $isExtraActive ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ $isExtraActive ? 'true' : 'false' }}"
        aria-controls="extra-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line>
            </svg>
            <span>{{ __('menu.extra') }}</span>
        </div>
        <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </div>
    
    <div id="extra-dropdown-menu" class="nav-dropdown {{ $isExtraActive ? 'show' : '' }}">
        <a href="{{ route('admin.extra.application') }}" class="nav-sub-item {{ request()->routeIs('admin.extra.application') ? 'active' : '' }}">
            {{ __('menu.application') }}
        </a>
        <a href="{{ route('admin.extra.addons.index') }}" class="nav-sub-item {{ request()->routeIs('admin.extra.addons.index') ? 'active' : '' }}">
            {{ __('Addons') }}
        </a>
        <a href="{{ route('admin.extra.server') }}" class="nav-sub-item {{ request()->routeIs('admin.extra.server') ? 'active' : '' }}">
            {{ __('menu.server') }}
        </a>
        <a href="{{ route('admin.extra.cache') }}" class="nav-sub-item {{ request()->routeIs('admin.extra.cache') ? 'active' : '' }}">
            {{ __('menu.cache') }}
        </a>
        <a href="{{ route('admin.extra.update') }}" class="nav-sub-item {{ request()->routeIs('admin.extra.update') ? 'active' : '' }}">
            {{ __('menu.update') }}
        </a>
    </div>

    @foreach($extraAddons as $addon)
        @if($addon->route_name == 'admin.addons.ielts-module.index')
            @continue
        @endif

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