@extends('layouts.admin')

@section('title', __('tickets.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-tickets.css') }}" rel="stylesheet">
@endpush

@section('content')

    <!-- MOBILE FILTER BAR -->
    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#ticketFilterOffcanvas">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center icon-28">
                    <i class="fa-solid fa-sliders fs-08"></i>
                </div>
                <span class="fw-bold text-dark fs-09">{{ __('tickets.btn_filter') }}</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted fs-075"></i>
        </button>
    </div>

    <!-- DESKTOP HEADER -->
    <div class="desktop-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('tickets.header_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('tickets.header_subtitle') }}</p>
        </div>
        
        <div class="page-header-actions">
            <form action="{{ route('admin.tickets.index') }}" method="GET" class="search-filter-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="search-filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" class="search-filter-input" placeholder="{{ __('tickets.placeholder_search') }}" autocomplete="off">
            </form>
        </div>
    </div>

    <!-- KPI GRID -->
    <div class="kpi-grid">
        @foreach($stats as $stat)
            <a href="{{ route('admin.tickets.index', ['status' => $stat['key']]) }}" 
               class="kpi-card kpi-{{ $stat['color'] }} {{ request('status') == $stat['key'] ? 'active' : '' }}">
                <div class="kpi-content">
                    <h2>{{ number_format($stat['count']) }}</h2>
                    <p>
                        @php
                            $key = 'tickets.status_' . strtolower($stat['key']);
                            $label = \Lang::has($key) ? __($key) : $stat['label'];
                        @endphp
                        {{ $label }}
                    </p>
                </div>
                <div class="kpi-icon-wrapper">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
            </a>
        @endforeach
    </div>

    <!-- TICKET LIST -->
    <div class="ticket-list-container">
        <div class="list-header">
            <div class="col-id">{{ __('tickets.col_id') }}</div>
            <div class="col-subject">{{ __('tickets.col_subject') }}</div>
            <div class="col-priority">{{ __('tickets.col_priority') }}</div>
            <div class="col-status">{{ __('tickets.col_status') }}</div>
            <div class="col-date">{{ __('tickets.col_updated') }}</div>
            <div class="col-action text-end">{{ __('tickets.col_action') }}</div>
        </div>

        @forelse($tickets as $ticket)
            <div class="list-item {{ $ticket->status == 'open' ? 'unread' : '' }}">
                <div class="col-id">
                    <span class="ticket-id-badge">#{{ $ticket->ticket_id }}</span>
                </div>
                
                <div class="col-subject">
                    <div class="d-flex flex-column">
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="fw-bold text-dark text-decoration-none subject-link">
                            {{ Str::limit($ticket->subject, 50) }}
                        </a>
                        <span class="small text-muted mt-1">
                            <i class="fa-regular fa-user me-1"></i> {{ $ticket->user->name ?? __('tickets.text_unknown_user') }}
                        </span>
                    </div>
                </div>

                <div class="col-priority">
                    @php
                        $priority = strtolower($ticket->priority ?? 'low'); 
                        $prioColor = match($priority) {
                            'high', 'urgent' => 'danger',
                            'medium'         => 'warning',
                            default          => 'success' 
                        };
                        $prioIcon = match($priority) {
                            'high', 'urgent' => 'fa-solid fa-angles-up',
                            'medium'         => 'fa-solid fa-angle-up',
                            default          => 'fa-solid fa-angle-down'
                        };
                        $prioKey = 'tickets.prio_' . $priority;
                        $prioLabel = \Lang::has($prioKey) ? __($prioKey) : ucfirst($priority);
                    @endphp
                    <span class="badge bg-{{ $prioColor }}-subtle text-{{ $prioColor }} border border-{{ $prioColor }}-subtle d-inline-flex align-items-center gap-1">
                        <i class="{{ $prioIcon }} fs-07em"></i> {{ $prioLabel }}
                    </span>
                </div>

                <div class="col-status">
                    @php
                        $statusClass = match($ticket->status) {
                            'open'    => 'bg-danger-subtle text-danger',
                            'replied' => 'bg-info-subtle text-info',
                            'closed'  => 'bg-success-subtle text-success',
                            default   => 'bg-secondary-subtle text-secondary'
                        };
                        $statusKey = 'tickets.status_' . strtolower($ticket->status);
                        $statusLabel = \Lang::has($statusKey) ? __($statusKey) : ucfirst($ticket->status);
                    @endphp
                    <span class="badge {{ $statusClass }} border border-current">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="col-date small text-muted">
                    {{ $ticket->updated_at->diffForHumans() }}
                </div>

                <div class="col-action text-end">
                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn-premium btn-sm rounded-pill">
                        {{ __('tickets.btn_reply') }}
                    </a>
                </div>
            </div>
        @empty
            <div class="p-5 text-center min-h-400">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <div class="mb-3 text-muted icon-xl">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h5 class="fw-bold text-dark">{{ __('tickets.empty_title') }}</h5>
                    <p class="text-muted mb-4">{{ __('tickets.empty_text') }}</p>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">{{ __('tickets.btn_clear') }}</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">Showing {{ $tickets->firstItem() ?? 0 }}–{{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} results</div>
        @if($tickets->hasPages())
            @include('components.app-pagination', ['paginator' => $tickets])
        @endif
    </div>

    <!-- MOBILE OFFCANVAS -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="ticketFilterOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">{{ __('tickets.btn_filter') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('admin.tickets.index') }}" method="GET">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">{{ __('tickets.col_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="all">{{ __('tickets.status_all') }}</option>
                        <option value="open">{{ __('tickets.status_pending') }}</option>
                        <option value="replied">{{ __('tickets.status_answered') }}</option>
                        <option value="closed">{{ __('tickets.status_closed') }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('tickets.placeholder_search') }}" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-premium w-100 rounded-pill fw-bold">{{ __('tickets.btn_apply_filter') }}</button>
            </form>
        </div>
    </div>

@endsection