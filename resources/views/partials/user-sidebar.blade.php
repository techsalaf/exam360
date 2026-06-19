@php
    $config = $settings ?? \App\Models\SystemSetting::pluck('value', 'key')->toArray();
    $user = auth()->user();
    $activeModules = \App\Models\Addon::where('is_active', true)->get();
@endphp

<div class="sidebar-brand">
    <a href="{{ route('user.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        @if(!empty($config['app_logo_light']))
            <img src="{{ Storage::url($config['app_logo_light']) }}" 
                 alt="{{ __('frontend.app_logo_alt', ['name' => $config['app_name'] ?? config('app.name')]) }}" 
                 class="img-fluid sidebar-logo-img">
        @else
            <div class="d-flex align-items-center gap-3">
                <div class="sidebar-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <span class="sidebar-brand-text">{{ $config['app_name'] ?? config('app.name') }}</span>
            </div>
        @endif
    </a>
</div>

<div class="sidebar-nav">
    
    <div class="nav-group">
        <div class="nav-label">{{ __('frontend.sidebar_main_menu') }}</div>
        
        <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all me-2"></i> {{ __('frontend.sidebar_dashboard') }}
        </a>
        
        <a href="{{ route('my.exams') }}" class="nav-item {{ request()->routeIs('my.exams') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines me-2"></i> {{ __('frontend.sidebar_my_exams') }}
        </a>
        
        <a href="{{ route('user.results') }}" class="nav-item {{ request()->routeIs('user.results') || request()->routeIs('user.results.show') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-simple me-2"></i> {{ __('frontend.sidebar_results') }}
        </a>
        
        <a href="{{ route('user.certificates') }}" class="nav-item {{ request()->routeIs('user.certificates') ? 'active' : '' }}">
            <i class="fa-solid fa-certificate me-2"></i> {{ __('frontend.sidebar_certificates') }}
        </a>
    </div>

    {{-- Academy / LMS Section --}}
    @if(Route::has('lms.courses.index'))
    <div class="nav-group">
        <div class="nav-label">Academy</div>
        
        <a href="{{ route('lms.courses.index') }}" class="nav-item {{ request()->routeIs('lms.courses.*') ? 'active' : '' }}">
            <i class="fa-solid fa-graduation-cap me-2"></i> Browse Courses
        </a>
    </div>
    @endif

    {{-- Job Board Section --}}
    @if(Route::has('jobs.index'))
    <div class="nav-group">
        <div class="nav-label">Careers</div>
        
        <a href="{{ route('jobs.index') }}" class="nav-item {{ request()->routeIs('jobs.index') || request()->routeIs('jobs.show') ? 'active' : '' }}">
            <i class="fa-solid fa-briefcase me-2"></i> Job Board
        </a>

        @if(Route::has('jobs.my-applications'))
        <a href="{{ route('jobs.my-applications') }}" class="nav-item {{ request()->routeIs('jobs.my-applications') ? 'active' : '' }}">
            <i class="fa-solid fa-paper-plane me-2"></i> My Applications
        </a>
        @endif
    </div>
    @endif

    {{-- Dynamic Modules Section --}}
    @if($activeModules->isNotEmpty())
    <div class="nav-group">
        <div class="nav-label">{{ __('frontend.sidebar_modules') }}</div>
        @foreach($activeModules as $module)
            @php
                $moduleSlug = $module->slug; 
                
                // Exclude modules already handled explicitly above
                if(in_array($moduleSlug, ['lms-addon', 'job-board'])) continue;

                $indexRoute = 'user.addons.' . $moduleSlug . '.index';
                $resultRoute = 'user.addons.' . $moduleSlug . '.results'; 
                
                $isModuleActive = request()->routeIs('user.addons.'.$moduleSlug.'.*');
                $isResultActive = request()->routeIs($resultRoute) || request()->routeIs($resultRoute . '.detail');
            @endphp

            @if(Route::has($indexRoute))
                <div class="module-group-item">
                    <a href="{{ route($indexRoute) }}" class="nav-item {{ $isModuleActive && !$isResultActive ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group me-2"></i> {{ $module->name }}
                    </a>

                    @if(Route::has($resultRoute))
                        <a href="{{ route($resultRoute) }}" 
                           class="nav-item nav-sub-item {{ $isResultActive ? 'active' : '' }}"
                           style="padding-left: 2.5rem; font-size: 0.95rem;">
                            <i class="fa-solid fa-square-poll-vertical me-2"></i> {{ __('frontend.results') }}
                        </a>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="nav-group">
        <div class="nav-label">{{ __('frontend.sidebar_account') }}</div>
        
        <a href="{{ route('user.profile') }}" class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <i class="fa-regular fa-user me-2"></i> {{ __('frontend.sidebar_profile') }}
        </a>
        
        <a href="{{ route('user.transactions') }}" class="nav-item {{ request()->routeIs('user.transactions') ? 'active' : '' }}">
            <i class="fa-solid fa-receipt me-2"></i> {{ __('frontend.sidebar_transactions') }}
        </a>

        <a href="{{ route('user.settings') }}" class="nav-item {{ request()->routeIs('user.settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear me-2"></i> {{ __('frontend.sidebar_settings') }}
        </a>
    </div>

    <div class="nav-group">
        <div class="nav-label">{{ __('frontend.sidebar_support') }}</div>
        <a href="{{ route('user.tickets') }}" class="nav-item {{ request()->routeIs('user.tickets') ? 'active' : '' }}">
            <i class="fa-regular fa-life-ring me-2"></i> {{ __('frontend.sidebar_tickets') }}
        </a>
    </div>

    @php
        $isSuperAdmin = $user->id === 1 || $user->hasRole('Super Admin');
        $canManageExams = $isSuperAdmin || $user->can('view_exams') || $user->can('create_exams');
        $canManageUsers = $isSuperAdmin || $user->can('view_users') || $user->can('create_users');
        $canManageSettings = $isSuperAdmin || $user->can('access_settings');
        
        $showManagement = $canManageExams || $canManageUsers || $canManageSettings;
    @endphp

    @if($showManagement)
    <div class="nav-group mt-2">
        <div class="nav-label text-primary">{{ __('frontend.sidebar_administration') }}</div>

        @if($canManageExams && Route::has('admin.exams.index'))
        <a href="{{ route('admin.exams.index') }}" class="nav-item {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}">
            <i class="fa-solid fa-pen-to-square me-2"></i> {{ __('frontend.manage_exams') }}
        </a>
        @endif

        @if($canManageUsers && Route::has('admin.users.index'))
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear me-2"></i> {{ __('frontend.manage_users') }}
        </a>
        @endif

        @if($canManageSettings && Route::has('admin.settings.index'))
        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-sliders me-2"></i> {{ __('frontend.system_settings') }}
        </a>
        @endif
        
        @if($isSuperAdmin && Route::has('admin.dashboard'))
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <i class="fa-solid fa-gauge-high me-2"></i> {{ __('frontend.admin_dashboard') }}
        </a>
        @endif
    </div>
    @endif

</div>

<div class="sidebar-footer">
    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="nav-item nav-item-logout w-100 bg-transparent border-0 text-start ps-3">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
            {{ __('frontend.sidebar_logout') }}
        </button>
    </form>
</div>