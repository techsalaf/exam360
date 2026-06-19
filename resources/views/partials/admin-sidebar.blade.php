<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            @php
                $settings = $settings ?? $gs ?? [];
                $appName = $settings['app_name'] ?? __('app.name');
            @endphp
            
            <div class="brand-content-light">
                @if(!empty($settings['app_logo_light']))
                    <img src="{{ Storage::url($settings['app_logo_light']) }}" alt="{{ $appName }}" class="brand-logo-img">
                @else
                    <div class="brand-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10v6"/><path d="M20 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        </svg>
                    </div>
                    <span class="brand-text">{{ $appName }}</span>
                @endif
            </div>

            <div class="brand-content-dark">
                @if(!empty($settings['app_logo_dark']))
                    <img src="{{ Storage::url($settings['app_logo_dark']) }}" alt="{{ $appName }}" class="brand-logo-img">
                @else
                    <div class="brand-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10v6"/><path d="M20 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        </svg>
                    </div>
                    <span class="brand-text text-white">{{ $appName }}</span>
                @endif
            </div>

        </a>
        <button class="mobile-close-btn" id="mobile-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <div class="sidebar-menu">
        @php
            use Illuminate\Support\Str;
            
            $user = auth()->user();
            
            $isReportActive = request()->routeIs('admin.reports.*');
            $isCmsActive = request()->routeIs('admin.cms.*');
            $isExtraActive = request()->routeIs('admin.extra.*');
            
            $userRoutes = ['admin.users.*', 'admin.users.notifications.*'];
            $isUserActive = request()->routeIs($userRoutes);

            $isPaymentActive = request()->routeIs('admin.payments.*') || request()->routeIs('admin.coupons.*');
            $currentPaymentStatus = request()->query('status');
            $isPaymentsDropdownOpen = $isPaymentActive || Str::startsWith(request()->url(), url('/admin/payments'));
            
            $isTicketActive = request()->routeIs('admin.tickets.*');
            $currentTicketStatus = request()->query('status');
            
            $allActiveAddons = \App\Models\Addon::where('is_active', true)->get();
            $globalAddons = $allActiveAddons->groupBy('menu_location');
            $examAddons = $globalAddons['exam'] ?? collect([]);
        @endphp

        <div class="menu-group-title">{{ __('menu.main') }}</div>
        
        @include('admin.partials.sidebar.dashboard')

        @include('admin.partials.sidebar.exam', ['examAddons' => $examAddons, 'globalAddons' => $globalAddons])

        @include('admin.partials.sidebar.cms', ['globalAddons' => $globalAddons])

        @include('admin.partials.sidebar.application', ['globalAddons' => $globalAddons])

        @include('admin.partials.sidebar.finance', ['globalAddons' => $globalAddons])

        @include('admin.partials.sidebar.extra', ['globalAddons' => $globalAddons])

        @if(auth()->user()->id !== 1 && !auth()->user()->hasRole('Super Admin'))
        <div class="menu-group-title mt-4">{{ __('menu.navigation') }}</div>
        <a href="{{ route('user.dashboard') }}" class="nav-item">
            <div class="nav-item-content">
                <svg class="icon-rtl-flip" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"></path><path d="M12 19l-7-7 7-7"></path>
                </svg>
                <span>{{ __('menu.back_to_dashboard') }}</span>
            </div>
        </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" title="{{ __('menu.sign_out') }}">
                <svg class="icon-rtl-flip" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>{{ __('menu.log_out') }}</span>
            </button>
        </form>
    </div>
</nav>