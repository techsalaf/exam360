<header class="topbar">
    <div class="topbar-left d-flex align-items-center">
        {{-- Logical margin: me-3 is margin-inline-end --}}
        <button class="btn-icon d-lg-none me-3" id="mobile-toggle" title="{{ __('menu.open_menu') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>

        <button class="btn-icon d-none d-lg-grid me-3" id="sidebar-toggle" title="{{ __('menu.collapse_menu') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
        </button>

        <div class="page-title-box d-none d-lg-block">
            <h5 class="fw-bold mb-0 text-dark">
                @yield('title', __('menu.admin_dashboard'))
            </h5>
            @hasSection('topbar-subtitle')
                <p class="small text-muted mb-0">
                    @yield('topbar-subtitle')
                </p>
            @endif
        </div>
    </div>

    @hasSection('topbar-search')
        <div class="header-search-area d-none d-md-flex">
            @yield('topbar-search')
        </div>
    @endif

    <div class="topbar-right d-flex align-items-center gap-2 gap-sm-3">
        @hasSection('topbar-actions')
            <div class="topbar-page-actions d-flex align-items-center gap-2">
                @yield('topbar-actions')
            </div>
        @endif

        <a href="{{ url('/') }}" target="_blank" class="btn-visit-site d-none d-lg-flex" title="{{ __('menu.open_public_site') }}">
            <i class="fa-solid fa-arrow-up-right-from-square icon-rtl-flip"></i>
            <span>{{ __('menu.live_site') }}</span>
        </a>

        @if($isAdminLangSwitcherEnabled && $adminLangs->count() > 0)
            <div class="dropdown">
                <button class="btn-lang-trigger" 
                    type="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false" 
                    role="button"
                    aria-haspopup="true"
                    title="{{ __('menu.select_language') }}">
                    @if($currentLang)
                        <span class="fi fi-{{ $currentLang->flag }} flag-circle-cover"></span>
                    @else
                        <i class="fa-solid fa-globe text-muted"></i>
                    @endif
                </button>
                
                {{-- Bootstrap handles dropdown-menu-end logically in RTL --}}
                <ul class="dropdown-menu dropdown-menu-end lang-dropdown-menu">
                    <li class="lang-dropdown-header">{{ __('menu.select_language_caps') }}</li>
                    @foreach($adminLangs as $lang)
                        <li>
                            <a class="lang-dropdown-item {{ $currentLangCode == $lang->code ? 'active' : '' }}" href="{{ route('admin.lang.switch', $lang->code) }}">
                                <span class="fi fi-{{ $lang->flag }} rounded-circle lang-flag-circle"></span>
                                <span>{{ $lang->name }}</span>
                                @if($currentLangCode == $lang->code)
                                    <i class="fa-solid fa-check ms-auto text-success small"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($showSuperAdminFeatures)
            <div class="theme-switch-wrapper" id="theme-toggle" title="{{ __('menu.toggle_theme') }}">
                <div class="theme-switch">
                    <svg class="switch-icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg class="switch-icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/></svg>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn-icon position-relative" 
                    type="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false" 
                    role="button"
                    aria-haspopup="true"
                    title="{{ __('admin-notifications.dropdown_header') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    
                    @if(isset($unreadCount) && $unreadCount > 0)
                        {{-- start-100 is logical and flips in RTL --}}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white small">
                            {{ $unreadCount > 99 ? __('common.count_overflow') : $unreadCount }}
                        </span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-0 notification-dropdown-menu">
                    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-light rounded-top">
                        <h6 class="mb-0 fw-bold">{{ __('admin-notifications.dropdown_header') }}</h6>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <a href="{{ route('admin.notifications.read.all') }}" class="text-decoration-none small text-primary fw-medium">
                                {{ __('admin-notifications.mark_all_read') }}
                            </a>
                        @endif
                    </div>

                    <div class="list-group list-group-flush notification-list-scroll">
                        @forelse($notifications as $notification)
                            <a href="{{ route('admin.notifications.read', $notification->id) }}" class="list-group-item list-group-item-action border-bottom {{ $notification->read_at ? 'bg-white' : 'bg-light' }} p-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $notification->data['color'] ?? 'primary' }}-subtle text-{{ $notification->data['color'] ?? 'primary' }} notification-avatar">
                                            <i class="{{ $notification->data['icon'] ?? 'fa-solid fa-bell' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark small fw-semibold">
                                            {{ $notification->data['title'] ?? __('admin-notifications.default_title') }}
                                        </h6>
                                        <p class="mb-1 text-muted small text-truncate notification-message-truncate">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>
                                        <small class="text-muted notification-timestamp">
                                            {{ $notification->created_at->locale(app()->getLocale())->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if(!$notification->read_at)
                                        <div class="flex-shrink-0">
                                            <span class="d-inline-block bg-danger rounded-circle notification-unread-dot"></span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <i class="fa-regular fa-bell-slash text-muted mb-2 fs-4"></i>
                                <p class="text-muted small mb-0">{{ __('admin-notifications.no_notifications') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-2 text-center bg-white border-top rounded-bottom">
                        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none small fw-bold text-dark d-block py-1">
                            {{ __('admin-notifications.view_all') }} <i class="fa-solid fa-arrow-right ms-1 icon-rtl-flip"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @hasSection('topbar-cta')
            @yield('topbar-cta')
        @else
            @if(auth()->user() && (auth()->user()->id === 1 || auth()->user()->can('create_users')))
                <a href="{{ route('admin.users.index') }}" class="btn btn-premium rounded-pill px-4 d-none d-sm-block">
                    {{ __('menu.manage_users_action') }}
                </a>
            @endif
        @endif
    </div>
</header>