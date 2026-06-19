<div class="d-flex align-items-center flex-grow-1">
    <button class="btn btn-light d-lg-none me-3" id="mobile-toggle" title="{{ __('menu.open_menu') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>

    <form action="{{ route('exams.list') }}" method="GET" class="search-wrapper d-none d-md-block position-relative w-100 search-form-wide">
        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        <input type="text" name="search" class="form-control bg-light border-0 ps-5 rounded-pill search-input-lg" 
               placeholder="{{ __('frontend.topbar_search_placeholder') }}" 
               value="{{ request('search') }}">
    </form>
</div>

<div class="d-flex align-items-center gap-3">
    
    <a href="{{ url('/') }}" target="_blank" class="btn btn-light rounded-pill border d-none d-md-inline-flex align-items-center gap-2 px-3 py-2 fw-bold text-muted small hover-primary btn-visit-site" title="{{ __('frontend.visit_website') }}">
        <i class="fa-solid fa-globe"></i> <span>{{ __('frontend.visit_website') }}</span>
    </a>

    @php
        $switcherEnabled = false;
        $settingValue = $settings['localization_front_switcher'] ?? \App\Models\SystemSetting::where('key', 'localization_front_switcher')->value('value');
        $switcherEnabled = filter_var($settingValue, FILTER_VALIDATE_BOOLEAN) || $settingValue == 1;

        if ($switcherEnabled) {
            $frontLanguages = \App\Models\Language::where('is_active_front', true)->orderBy('is_default', 'desc')->get();
            $showLangSwitcher = $frontLanguages->count() > 1;
            $currentLocale = app()->getLocale();
            $currentLang = $frontLanguages->where('code', $currentLocale)->first();
            if (!$currentLang) {
                $currentLang = $frontLanguages->where('is_default', true)->first() ?? $frontLanguages->first();
            }
        } else {
            $showLangSwitcher = false;
        }
    @endphp

    @if($showLangSwitcher)
    <div class="dropdown d-none d-lg-block">
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center transition-base border btn-circle-40" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                title="{{ __('frontend.topbar_select_language') }}">
            @if($currentLang)
                <span class="fi fi-{{ $currentLang->flag }} rounded-circle flag-icon-20"></span>
            @else
                <i class="fa-solid fa-globe text-muted"></i>
            @endif
        </button>
        
        <ul class="dropdown-menu dropdown-menu-end lang-dropdown-menu shadow border-0 mt-2">
            <li class="dropdown-header text-uppercase small fw-bold text-muted">{{ __('frontend.topbar_select_language_caps') }}</li>
            @foreach($frontLanguages as $lang)
                <li>
                    <a class="lang-dropdown-item {{ $currentLocale == $lang->code ? 'active' : '' }}" href="{{ route('lang.switch', $lang->code) }}">
                        <span class="fi fi-{{ $lang->flag }} rounded-circle flag-icon-18"></span>
                        <span>{{ $lang->name }}</span>
                        @if($currentLocale == $lang->code)
                            <i class="fa-solid fa-check ms-auto text-success small"></i>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="dropdown">
        <button class="btn btn-light rounded-circle position-relative p-0 d-flex align-items-center justify-content-center transition-base border btn-circle-40" 
                data-bs-toggle="dropdown" aria-expanded="false" 
                title="{{ __('frontend.topbar_notifications') }}">
            <i class="fa-regular fa-bell text-muted fs-5"></i>
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light badge-note-fix">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </button>

        <div class="dropdown-menu dropdown-menu-end dropdown-menu-premium border-0 p-0 shadow-lg mt-2 note-dropdown-wide">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white rounded-top">
                <h6 class="fw-bold mb-0 text-dark">{{ __('frontend.topbar_notifications') }}</h6>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <a href="{{ route('user.notifications.markAllRead') }}" class="small text-decoration-none text-primary fw-bold">{{ __('frontend.topbar_mark_read') }}</a>
                @endif
            </div>
            
            <div class="list-group list-group-flush note-list-scroll">
                @if(isset($topbarNotifications) && $topbarNotifications->count() > 0)
                    @foreach($topbarNotifications as $note)
                        @php
                            $data = $note->data;
                            $isRead = $note->read_at !== null;
                            $title = $data['title'] ?? 'New Notification';
                            $message = $data['message'] ?? '';
                            $url = $data['url'] ?? $data['action_url'] ?? '#';
                            $icon = $data['icon'] ?? 'fa-regular fa-bell';
                            $type = $data['type'] ?? 'info';
                            
                            $bgClass = match($type) {
                                'success', 'payment' => 'text-success bg-success-subtle',
                                'warning', 'ticket' => 'text-warning bg-warning-subtle',
                                'danger', 'live' => 'text-danger bg-danger-subtle',
                                default => 'text-primary bg-primary-subtle'
                            };
                        @endphp
                        
                        <a href="{{ $url }}" class="list-group-item list-group-item-action d-flex gap-3 py-3 px-3 border-bottom {{ $isRead ? 'bg-white' : 'list-group-item-unread' }}">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center {{ $bgClass }} note-icon-circle">
                                    <i class="{{ $icon }}"></i>
                                </div>
                            </div>
                            <div class="w-100 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 small fw-bold text-dark text-truncate note-title-truncate">{{ $title }}</h6>
                                    <small class="text-muted ms-2 note-time-small">{{ $note->created_at->diffForHumans(null, true, true) }}</small>
                                </div>
                                <p class="mb-0 text-muted small text-truncate">{{ $message }}</p>
                            </div>
                            @if(!$isRead)
                                <div class="align-self-center"><span class="bg-primary d-inline-block note-unread-dot"></span></div>
                            @endif
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <div class="text-muted mb-2 opacity-50">
                            <i class="fa-regular fa-bell-slash fa-2x"></i>
                        </div>
                        <p class="text-muted small mb-0 fw-medium">{{ __('frontend.topbar_no_notifications') }}</p>
                    </div>
                @endif
            </div>
            
            <div class="p-2 border-top text-center bg-light rounded-bottom">
                <a href="{{ route('user.notifications.index') }}" class="small text-decoration-none fw-bold text-dark d-block py-1">{{ __('frontend.topbar_view_activity') }} &rarr;</a>
            </div>
        </div>
    </div>
    
    <div class="dropdown">
        <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="user-avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm border border-2 border-white avatar-circle-40">
                @if(Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-100 h-100 rounded-circle object-fit-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                @endif
            </div>
        </div>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-premium shadow-lg mt-2 border-0" style="width: 240px;">
            <li>
                <div class="dropdown-header-user px-3 py-3 bg-light border-bottom mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-initials-circle bg-white text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm avatar-circle-42">
                             @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-100 h-100 rounded-circle object-fit-cover">
                            @else
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name ?? __('frontend.topbar_default_student') }}</div>
                            <div class="text-muted small text-truncate text-email-sm">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </li>
            
            <li>
                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="{{ route('user.profile') }}">
                    <i class="fa-regular fa-user text-muted dropdown-icon-w"></i> {{ __('frontend.topbar_my_profile') }}
                </a>
            </li>
            <li>
                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="{{ route('user.settings') }}">
                    <i class="fa-solid fa-gear text-muted dropdown-icon-w"></i> {{ __('frontend.topbar_menu_settings') }}
                </a>
            </li>
            
            <li><hr class="dropdown-divider my-2 opacity-10"></li>
            
            <li>
                <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-danger">
                        <i class="fa-solid fa-arrow-right-from-bracket dropdown-icon-w"></i> {{ __('frontend.topbar_logout') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>