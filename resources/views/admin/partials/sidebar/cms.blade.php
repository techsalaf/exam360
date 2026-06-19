@php
    $cmsAddons = $globalAddons['cms'] ?? collect([]);
    $isCmsActive = request()->routeIs('admin.cms.*');
    
    // Fail-safe detection
    $allAddons = \App\Models\Addon::where('is_active', true)->get();
    $jobBoardAddon = $allAddons->firstWhere('slug', 'job-board');
    $isJobBoardActive = request()->routeIs('admin.job-board.*');
@endphp

@can('access_settings')
<div class="menu-group-title">{{ __('menu.content') }}</div>
<div class="nav-group">
    <div class="nav-item nav-dropdown-toggle {{ $isCmsActive ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ $isCmsActive ? 'true' : 'false' }}"
        aria-controls="cms-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>{{ __('menu.cms_manager') }}</span>
        </div>
        <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </div>
    
    <div id="cms-dropdown-menu" class="nav-dropdown {{ $isCmsActive ? 'show' : '' }}">
        <a href="{{ route('admin.cms.homepage.designs') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.homepage.designs') ? 'active' : '' }}">
            {{ __('menu.homepage_design') }}
        </a>
        <a href="{{ route('admin.cms.homepage.index') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.homepage.index') ? 'active' : '' }}">
            {{ __('menu.homepage_editor') }}
        </a>
        <a href="{{ route('admin.cms.pages.index') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.pages.*') ? 'active' : '' }}">
            {{ __('menu.page_manager') }}
        </a>
        <a href="{{ route('admin.cms.menus.index') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.menus.*') ? 'active' : '' }}">
            {{ __('menu.menu_manager') }}
        </a>
        <a href="{{ route('admin.cms.header.index') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.header.index') ? 'active' : '' }}">
            {{ __('menu.header_editor') }}
        </a>
        <a href="{{ route('admin.cms.footer.index') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.footer.index') ? 'active' : '' }}">
            {{ __('menu.footer_editor') }}
        </a>
        <a href="{{ route('admin.cms.testimonials.index') }}" class="nav-sub-item {{ request()->routeIs('admin.cms.testimonials.index') ? 'active' : '' }}">
            {{ __('menu.testimonials') }}
        </a>
    </div>

    @if(Route::has('admin.job-board.index'))
        @if(auth()->user()->id === 1 || auth()->user()->can('manage_job_board'))
        <div class="nav-item nav-dropdown-toggle {{ $isJobBoardActive ? 'active' : '' }}" 
            role="button" 
            aria-expanded="{{ $isJobBoardActive ? 'true' : 'false' }}"
            aria-controls="job-board-dropdown-menu">
            <div class="nav-item-content">
                <i class="{{ $jobBoardAddon->icon ?? 'fa-solid fa-briefcase' }} fa-lg" style="width: 24px; text-align: center;"></i>
                <span>{{ $jobBoardAddon->name ?? 'Job Board' }}</span>
            </div>
            <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
        
        <div id="job-board-dropdown-menu" class="nav-dropdown {{ $isJobBoardActive ? 'show' : '' }}">
            <a href="{{ route('admin.job-board.index') }}" class="nav-sub-item {{ request()->routeIs('admin.job-board.index') ? 'active' : '' }}">
                Manage Job Posts
            </a>
            <a href="{{ route('admin.job-board.submissions') }}" class="nav-sub-item {{ request()->routeIs('admin.job-board.submissions*') ? 'active' : '' }}">
                Student Submissions
            </a>
        </div>
        @endif
    @endif

    @foreach($cmsAddons as $addon)
        @if($addon->slug !== 'job-board' && Route::has($addon->route_name))
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