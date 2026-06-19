@php
    use Illuminate\Support\Facades\Route;
    use App\Models\Addon;

    $ieltsAddon = $examAddons->firstWhere('slug', 'ielts-module');
    $proctoringAddon = $examAddons->firstWhere('slug', 'advanced-proctoring');
    $codingAddon = $examAddons->firstWhere('slug', 'coding-assessments');
    $jobBoardAddon = $examAddons->firstWhere('slug', 'job-board');
    $lmsAddon = $examAddons->firstWhere('slug', 'lms-addon');
@endphp

@can('monitor_live_exams')
<a href="{{ route('admin.live.index') }}" class="nav-item {{ request()->routeIs('admin.live.*') ? 'active' : '' }}">
    <div class="nav-item-content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
        </svg>
        <span>{{ __('menu.live_monitoring') }}</span>
    </div>
</a>
@endcan

@can('create_exams')
<a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
    <div class="nav-item-content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
        <span>{{ __('menu.categories') }}</span>
    </div>
</a>
@endcan

<div class="menu-group-title">{{ __('menu.academics') }}</div>

@canany(['view_exams', 'create_exams'])
<div class="nav-group">
    <div class="nav-item nav-dropdown-toggle {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.exams.results.*') ? 'active' : '' }}" 
        role="button" 
        aria-expanded="{{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.exams.results.*') ? 'true' : 'false' }}"
        aria-controls="exam-dropdown-menu">
        <div class="nav-item-content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            <span>{{ __('menu.manage_exams') }}</span>
        </div>
        <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </div>
    <div id="exam-dropdown-menu" class="nav-dropdown {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.exams.results.*') ? 'show' : '' }}">
        <a href="{{ route('admin.exams.index') }}" class="nav-sub-item {{ request()->routeIs('admin.exams.index') ? 'active' : '' }}">{{ __('menu.all_exams') }}</a>
        
        @can('create_exams')
        <a href="{{ route('admin.exams.results.pending') }}" class="nav-sub-item {{ request()->routeIs('admin.exams.results.pending') ? 'active' : '' }}">
            {{ __('menu.pending_results') }}
        </a>
        <a href="{{ route('admin.exams.results.index') }}" class="nav-sub-item {{ request()->routeIs('admin.exams.results.index') ? 'active' : '' }}">{{ __('menu.declared_results') }}</a>
        @endcan
    </div>
</div>
@endcanany

{{-- LMS / ACADEMY DROPDOWN --}}
@if($lmsAddon && $lmsAddon->is_active && Route::has('admin.lms.index'))
    @can('manage_lms')
    @php $lmsActive = request()->routeIs('admin.lms.*'); @endphp
    <div class="nav-group">
        <div class="nav-item nav-dropdown-toggle {{ $lmsActive ? 'active' : '' }}" 
            role="button" aria-expanded="{{ $lmsActive ? 'true' : 'false' }}" aria-controls="lms-dropdown-menu">
            <div class="nav-item-content">
                <i class="{{ $lmsAddon->icon ?? 'fa-solid fa-graduation-cap' }}"></i>
                <span>{{ $lmsAddon->name }}</span>
            </div>
            <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
        <div id="lms-dropdown-menu" class="nav-dropdown {{ $lmsActive ? 'show' : '' }}">
            <a href="{{ route('admin.lms.index') }}" class="nav-sub-item {{ request()->routeIs('admin.lms.index') || request()->routeIs('admin.lms.courses.index') ? 'active' : '' }}">Manage Courses</a>
            <a href="{{ route('admin.lms.courses.create') }}" class="nav-sub-item {{ request()->routeIs('admin.lms.courses.create') ? 'active' : '' }}">Create New Course</a>
        </div>
    </div>
    @endcan
@endif

@if($proctoringAddon && $proctoringAddon->is_active)
    @can('manage_advanced_proctoring')
        @if(Route::has('admin.proctoring.settingsIndex'))
        <a href="{{ route('admin.proctoring.settingsIndex') }}" class="nav-item {{ request()->routeIs('admin.proctoring.*') ? 'active' : '' }}">
            <div class="nav-item-content">
                <i class="{{ $proctoringAddon->icon ?? 'fa-solid fa-user-shield' }}"></i>
                <span>{{ __('menu.proctoring_settings') }}</span>
            </div>
        </a>
        @endif
    @endcan
@endif

@if($codingAddon && $codingAddon->is_active && Route::has('admin.addons.coding-assessments.index'))
    @can('manage_coding_assessments')
        <a href="{{ route('admin.addons.coding-assessments.index') }}" class="nav-item {{ request()->routeIs('admin.addons.coding-assessments.*') ? 'active' : '' }}">
            <div class="nav-item-content">
                <i class="{{ $codingAddon->icon ?? 'fa-solid fa-code' }}"></i>
                <span>{{ $codingAddon->name }}</span>
            </div>
        </a>
    @endcan
@endif

@if($ieltsAddon && $ieltsAddon->is_active && Route::has('admin.addons.ielts-module.index'))
    @php
        $ieltsActive = request()->routeIs('admin.addons.ielts-module.*');
    @endphp
    <div class="nav-group">
        <div class="nav-item nav-dropdown-toggle {{ $ieltsActive ? 'active' : '' }}" 
            role="button" aria-expanded="{{ $ieltsActive ? 'true' : 'false' }}" aria-controls="ielts-dropdown-menu">
            <div class="nav-item-content">
                <i class="{{ $ieltsAddon->icon }}"></i>
                <span>{{ $ieltsAddon->name }}</span>
            </div>
            <svg class="nav-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>
        <div id="ielts-dropdown-menu" class="nav-dropdown {{ $ieltsActive ? 'show' : '' }}">
            <a href="{{ route('admin.addons.ielts-module.index') }}" class="nav-sub-item {{ request()->routeIs('admin.addons.ielts-module.index') ? 'active' : '' }}">All Mock Tests</a>
            @if(Route::has('admin.addons.ielts-module.create'))
            <a href="{{ route('admin.addons.ielts-module.create') }}" class="nav-sub-item {{ request()->routeIs('admin.addons.ielts-module.create') ? 'active' : '' }}">Create New Test</a>
            @endif
        </div>
    </div>
@endif

@if($jobBoardAddon && $jobBoardAddon->is_active && Route::has('admin.addons.job-board.index'))
    @can('manage_job_board')
        <a href="{{ route('admin.addons.job-board.index') }}" class="nav-item {{ request()->routeIs('admin.addons.job-board.*') ? 'active' : '' }}">
            <div class="nav-item-content">
                <i class="{{ $jobBoardAddon->icon ?? 'fa-solid fa-briefcase' }}"></i>
                <span>{{ $jobBoardAddon->name }}</span>
            </div>
        </a>
    @endcan
@endif

@if($examAddons->isNotEmpty())
    @foreach($examAddons as $addon)
        @php $excludedSlugs = ['ielts-module', 'advanced-proctoring', 'coding-assessments', 'lms-addon', 'job-board']; @endphp
        @if(!in_array($addon->slug, $excludedSlugs) && Route::has('admin.addons.' . $addon->slug . '.index')) 
            <a href="{{ route('admin.addons.' . $addon->slug . '.index') }}" class="nav-item {{ request()->routeIs('admin.addons.'.$addon->slug.'.*') ? 'active' : '' }}">
                <div class="nav-item-content">
                    <i class="{{ $addon->icon ?? 'fa-solid fa-puzzle-piece' }}"></i>
                    <span>{{ $addon->name }}</span>
                </div>
            </a>
        @endif
    @endforeach
@endif