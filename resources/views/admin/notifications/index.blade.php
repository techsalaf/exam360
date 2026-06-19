@extends('layouts.admin')

@section('title', __('admin-notifications.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-notifications.css') }}" rel="stylesheet"> 
@endpush

@section('content')

    {{-- Safe JS Configuration --}}
    <div id="notifConfig" 
         data-delete-title="{{ __('admin-notifications.swal_delete_title') }}"
         data-delete-text="{{ __('admin-notifications.swal_delete_text') }}"
         data-confirm-btn="{{ __('admin-notifications.swal_confirm_btn') }}"
         data-cancel-btn="{{ __('admin-notifications.swal_cancel_btn') }}"
         data-mark-title="{{ __('admin-notifications.swal_mark_all_title') }}"
         data-mark-text="{{ __('admin-notifications.swal_mark_all_text') }}"
         data-confirm-read="{{ __('admin-notifications.swal_confirm_read') }}">
    </div>

    @php
        $unreadCount = $stats['unread'] ?? 0;
        $totalCount  = $stats['total'] ?? 0;
        $readCount   = $totalCount - $unreadCount;
        
        $kpiStats = [
            [
                'label' => __('admin-notifications.total_notifications'),
                'count' => $totalCount,
                'icon'  => 'fa-solid fa-bell',
                'color' => 'primary'
            ],
            [
                'label' => __('admin-notifications.unread_messages'),
                'count' => $unreadCount,
                'icon'  => 'fa-solid fa-envelope-open-text',
                'color' => 'warning'
            ],
            [
                'label' => __('admin-notifications.read_messages'),
                'count' => $readCount,
                'icon'  => 'fa-solid fa-check-double',
                'color' => 'success'
            ]
        ];
    @endphp

    <div class="mobile-search-filter-bar">
        <button class="btn btn-light border shadow-sm d-flex align-items-center gap-2 px-3 radius-12 h-46 bg-white" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#notifFilterOffcanvas">
            <i class="fa-solid fa-sliders text-dark"></i>
            <span class="fw-bold text-dark small">{{ __('admin-notifications.filter') }}</span>
        </button>

        <div class="flex-grow-1 position-relative">
            <i class="fa-solid fa-search position-absolute text-muted icon-center"></i>
            <input type="text" class="form-control border shadow-sm ps-5 radius-12 h-46 fs-095 cursor-default" 
                   placeholder="{{ __('admin-notifications.search_placeholder') }}" readonly>
        </div>
    </div>

    @if($unreadCount > 0)
    <div class="mobile-sticky-unread">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-primary"></i>
            <span>{{ $unreadCount }} {{ __('admin-notifications.unread_sticky') }}</span>
        </div>
        <a href="{{ route('admin.notifications.read.all') }}" class="text-decoration-none fw-bold btn-confirm-read-all text-dark-blue fs-08">
            {{ __('admin-notifications.mark_all_read') }} &rarr;
        </a>
    </div>
    @endif

    <div class="desktop-header">
        <div class="page-title">
            <h4 class="fw-bold text-dark m-0">{{ __('admin-notifications.page_title') }}</h4>
            <p class="text-muted small m-0 mt-1">{{ __('admin-notifications.page_subtitle') }}</p>
        </div>
        
        <div class="action-group d-flex gap-3">
            @if($unreadCount > 0)
                <a href="{{ route('admin.notifications.read.all') }}" class="btn btn-white border rounded-pill px-4 fw-bold d-flex align-items-center gap-2 btn-confirm-read-all h-44">
                    <i class="fa-solid fa-check-double text-success"></i> {{ __('admin-notifications.mark_all_read') }}
                </a>
            @endif
        </div>
    </div>
    
    <div class="kpi-grid d-none d-lg-grid">
        @foreach($kpiStats as $stat)
            <div class="kpi-card kpi-{{ $stat['color'] }}">
                <div class="kpi-content">
                    <h2>{{ number_format($stat['count']) }}</h2>
                    <p>{{ $stat['label'] }}</p>
                </div>
                <div class="kpi-icon-wrapper">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
            </div>
        @endforeach
    </div>

    <div class="notif-list-container">
        
        <div class="list-header d-none d-lg-flex">
            <div class="col-icon">{{ __('admin-notifications.col_type') }}</div>
            <div class="col-content">{{ __('admin-notifications.col_message') }}</div>
            <div class="col-type">{{ __('admin-notifications.col_category') }}</div>
            <div class="col-date text-end">{{ __('admin-notifications.col_date') }}</div>
            <div class="col-action text-end">{{ __('admin-notifications.col_action') }}</div>
        </div>

        @forelse($notifications as $notification)
            @php
                $isUnread = $notification->read_at === null;
                $data     = $notification->data;
                $type     = $data['type'] ?? 'system';
                $color    = $data['color'] ?? 'secondary';
                $icon     = $data['icon'] ?? 'fa-solid fa-bell';
            @endphp

            <div class="list-item {{ $isUnread ? 'unread' : '' }}">
                
                <div class="col-icon">
                    <div class="icon-box bg-{{ $color }}-subtle">
                        <i class="{{ $icon }}"></i>
                    </div>
                </div>

                <div class="col-content">
                    <div class="notif-title">{{ $data['title'] ?? 'Notification' }}</div>
                    <div class="notif-desc">{{ $data['message'] ?? '' }}</div>
                    
                    <div class="col-meta-mobile">
                        <span class="type-badge type-{{ $type }}">{{ ucfirst($type) }}</span>
                        <span class="mobile-date">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="col-type d-none d-lg-block">
                    <span class="type-badge type-{{ $type }}">
                        {{ ucfirst($type) }}
                    </span>
                </div>

                <div class="col-date d-none d-lg-block">
                    <span class="date-text">{{ $notification->created_at->format('M d, h:i A') }}</span>
                    <span class="date-rel">{{ $notification->created_at->diffForHumans() }}</span>
                </div>

                <div class="col-action">
                    <div class="action-btn-group d-lg-none">
                        @if(isset($data['url']) && $data['url'] !== '#')
                            <a href="{{ route('admin.notifications.read', $notification->id) }}" class="btn-mobile-action">{{ __('admin-notifications.view') }}</a>
                        @endif
                        
                        @if($isUnread)
                            <a href="{{ route('admin.notifications.read', $notification->id) }}" class="btn-mobile-action mark-read">{{ __('admin-notifications.mark_read') }}</a>
                        @endif

                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-contents form-confirm-delete">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-mobile-action delete">{{ __('admin-notifications.delete') }}</button>
                        </form>
                    </div>

                    <div class="d-none d-lg-flex justify-content-end gap-2">
                        @if(isset($data['url']) && $data['url'] !== '#')
                            <a href="{{ $data['url'] }}" class="btn-icon-action view" title="{{ __('admin-notifications.view_details') }}">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> 
                            </a>
                        @endif
                        
                        @if($isUnread)
                            <a href="{{ route('admin.notifications.read', $notification->id) }}" class="btn-icon-action read" title="{{ __('admin-notifications.mark_read') }}">
                                <i class="fa-solid fa-check"></i>
                            </a>
                        @endif

                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline form-confirm-delete">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon-action delete btn-trigger-delete" title="{{ __('admin-notifications.delete') }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-5 text-center">
                <div class="mb-3 text-muted fs-3rem opacity-30">
                    <i class="fa-regular fa-folder-open"></i>
                </div>
                <h5 class="fw-bold text-dark">{{ __('admin-notifications.all_caught_up') }}</h5>
                <p class="text-muted small">{{ __('admin-notifications.no_notifications') }}</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
        <div class="text-muted small">
            {{ __('admin-notifications.showing_results', [
                'first' => $notifications->firstItem() ?? 0,
                'last' => $notifications->lastItem() ?? 0,
                'total' => $notifications->total()
            ]) }}
        </div>
        @if($notifications->hasPages())
            @include('components.app-pagination', ['paginator' => $notifications])
        @endif
    </div>

    <!-- Mobile Filter Offcanvas -->
    <div class="offcanvas offcanvas-bottom offcanvas-auto" tabindex="-1" id="notifFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark">{{ __('admin-notifications.actions_title') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="d-grid gap-3">
                <a href="{{ route('admin.notifications.read.all') }}" class="btn btn-outline-primary fw-bold py-2 btn-confirm-read-all">
                    <i class="fa-solid fa-check-double me-2"></i> {{ __('admin-notifications.mark_all_read') }}
                </a>
                <button type="button" class="btn btn-light border py-2 text-muted" disabled>
                    {{ __('admin-notifications.delete_all_read') }}
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-notifications.js') }}"></script>
@endpush