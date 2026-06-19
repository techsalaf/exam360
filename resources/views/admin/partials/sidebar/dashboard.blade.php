@php
    $dashboardAddons = $globalAddons['dashboard'] ?? collect([]);
@endphp

<a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <div class="nav-item-content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        <span>{{ __('menu.dashboard') }}</span>
    </div>
</a>
@foreach($dashboardAddons as $addon)
    @if(Route::has($addon->route_name))
    <a href="{{ route($addon->route_name) }}" class="nav-item {{ request()->routeIs($addon->route_name.'*') ? 'active' : '' }}">
        <div class="nav-item-content">
            <i class="{{ $addon->icon }} fa-lg" style="width: 24px; text-align: center;"></i>
            <span>{{ $addon->name }}</span>
        </div>
    </a>
    @endif
@endforeach