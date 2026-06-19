@php
    $events = $session->flagged_events;
    if (is_string($events)) {
        $events = json_decode($events, true);
    }
    if (!is_array($events)) {
        $events = [];
    }

    $startTime = $session->start_time ? \Carbon\Carbon::parse($session->start_time) : null;
    $lastActivity = $session->last_activity_at ? \Carbon\Carbon::parse($session->last_activity_at) : null;
@endphp

<div class="detail-grid">
    
    <!-- Environment Details -->
    <div class="detail-box">
        <h6>{{ __('live.detail_env') }}</h6>
        <ul class="list-unstyled text-sm mb-0">
            <li class="d-flex align-items-start gap-2 detail-box-list-item">
                <i class="fa-solid fa-desktop text-muted me-1 mt-1 icon-w-16"></i> 
                <div>
                    <span class="text-dark fw-medium">{{ __('live.ip') }}: {{ $session->ip_address }}</span>
                </div>
            </li>
            <li class="d-flex align-items-start gap-2 detail-box-list-item">
                <i class="fa-solid fa-location-dot text-muted me-1 mt-1 icon-w-16"></i> 
                <span class="text-dark">{{ $session->location ?? __('live.unknown_location') }}</span>
            </li>
            <li class="d-flex align-items-start gap-2">
                <i class="fa-solid fa-laptop-code text-muted me-1 mt-1 icon-w-16"></i> 
                <span class="text-dark text-wrap">{{ Str::limit($session->device_info, 40) }}</span>
            </li>
        </ul>
    </div>
    
    <!-- AI Flagged Events -->
    <div class="detail-box">
        <h6>{{ __('live.detail_ai_events') }}</h6>
        @if(count($events) > 0)
            <div class="d-flex flex-column gap-1">
                @foreach(array_slice($events, -3) as $event)
                    <div class="badge bg-danger-subtle text-danger border border-danger-subtle text-start fw-normal px-2 py-1 text-wrap">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> 
                        {{ is_array($event) ? json_encode($event) : $event }}
                    </div>
                @endforeach
                @if(count($events) > 3)
                    <small class="text-muted mt-1">+ {{ count($events) - 3 }} {{ __('live.more_events') }}</small>
                @endif
            </div>
        @else
            <div class="text-success text-sm d-flex align-items-center gap-2 pt-1">
                <i class="fa-solid fa-circle-check"></i> 
                <span class="fw-medium">{{ __('live.no_suspicious_activity') }}</span>
            </div>
        @endif
    </div>
    
    <!-- Timeline Stats -->
    <div class="detail-box">
        <h6>{{ __('live.detail_timeline') }}</h6>
        <div class="text-sm text-dark d-flex flex-column gap-1">
            <div class="d-flex justify-content-between">
                <span class="text-muted">{{ __('live.started') }}:</span> 
                <span class="fw-medium">{{ $startTime ? $startTime->format('H:i A') : '-' }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">{{ __('live.last_active') }}:</span> 
                <span class="fw-medium">{{ $lastActivity ? $lastActivity->diffForHumans() : __('live.never') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">{{ __('live.duration_limit') }}:</span> 
                <span class="fw-medium">{{ $session->exam->duration_minutes ?? 0 }} {{ __('live.mins') }}</span>
            </div>
        </div>
    </div>
</div>