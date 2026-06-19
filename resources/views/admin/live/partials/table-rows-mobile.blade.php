@forelse($sessions as $session)
    @php
        $timeLeft = '--';
        if ($session->status === 'ongoing' && $session->end_time) {
            if ($session->end_time->isPast()) {
                $timeLeft = '00:00';
            } else {
                $diff = now()->diff($session->end_time);
                $timeLeft = ($diff->h > 0) 
                    ? $diff->format('%H:%I:%S') 
                    : $diff->format('%I:%S');
            }
        } elseif ($session->status === 'paused') {
             $timeLeft = __('live.paused_label');
        } elseif (in_array($session->status, ['completed', 'terminated'])) {
             $timeLeft = __('live.ended_label');
        }

        $riskColor = match($session->risk_level) {
            'critical' => 'text-danger',
            'warning' => 'text-warning',
            default => 'text-success'
        };
    @endphp

    <div class="mobile-session-card shadow-sm" id="mobile-card-{{ $session->id }}">
        <div class="card-header-main">{{ $session->user->name }}</div>
        <div class="card-meta-email">{{ $session->user->email }}</div>

        <div class="card-info-row">
            <span class="text-muted">{{ __('live.label_exam') }}</span>
            <span class="fw-medium text-end">{{ $session->exam->title ?? 'N/A' }}</span>
        </div>
        
        <div class="card-info-row">
            <span class="text-muted">{{ __('live.label_progress') }}</span>
            <span class="fw-medium text-end">{{ round($session->progress_percentage) }}%</span>
        </div>
        
        <div class="card-info-row">
            <span class="text-muted">{{ __('live.label_time_left') }}</span>
            <span class="fw-medium text-end {{ $timeLeft === '00:00' ? 'text-danger' : 'text-dark' }}">{{ $timeLeft }}</span>
        </div>

        <div class="card-risk-status-row">
            <div class="card-risk-score">
                {{ __('live.label_risk') }} <span class="{{ $riskColor }}">{{ $session->risk_score ?? 0 }} ({{ ucfirst(__($session->risk_level ? 'live.risk_' . $session->risk_level : 'live.risk_low')) }})</span>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                 @if($session->status === 'ongoing')
                    <span class="status-pill ongoing">{{ __('live.pill_active') }}</span>
                @elseif($session->status === 'paused')
                    <span class="status-pill paused">{{ __('live.pill_paused') }}</span>
                @elseif($session->status === 'terminated' || $session->status === 'completed')
                    <span class="status-pill terminated">{{ __('live.pill_ended') }}</span>
                @endif

                <div class="card-actions">
                    <button class="btn btn-sm btn-white border toggle-details-btn" 
                            type="button" 
                            data-target="#mobile-detail-{{ $session->id }}">
                        {{ __('live.btn_view_session') }}
                    </button>
                    
                    @if(in_array($session->status, ['terminated', 'completed']))
                        <button class="btn btn-sm btn-success action-btn" data-action="reopen" data-id="{{ $session->id }}">
                            {{ __('live.btn_reopen') }}
                        </button>
                    @elseif($session->status === 'ongoing')
                        <button class="btn btn-sm btn-warning action-btn" data-action="pause" data-id="{{ $session->id }}">
                            {{ __('live.btn_pause') }}
                        </button>
                        <button class="btn btn-sm btn-danger action-btn" data-action="terminate" data-id="{{ $session->id }}">
                            {{ __('live.btn_end') }}
                        </button>
                    @elseif($session->status === 'paused')
                        <button class="btn btn-sm btn-success action-btn" data-action="resume" data-id="{{ $session->id }}">
                            {{ __('live.btn_resume') }}
                        </button>
                        <button class="btn btn-sm btn-danger action-btn" data-action="terminate" data-id="{{ $session->id }}">
                            {{ __('live.btn_end') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="mobile-detail-content" id="mobile-detail-{{ $session->id }}">
             @include('admin.live.partials.session-detail-content', ['session' => $session])
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <div class="d-flex flex-column align-items-center justify-content-center live-empty-state">
            <i class="fa-solid fa-tower-broadcast fa-3x mb-3 text-muted"></i>
            <h5 class="fw-bold text-muted">{{ __('live.empty_title') }}</h5>
            <p class="text-sm text-muted mb-0">{{ __('live.empty_desc') }}</p>
        </div>
    </div>
@endforelse