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

        $startTime = $session->start_time ? \Carbon\Carbon::parse($session->start_time) : null;
    @endphp

    <tr class="align-middle border-bottom session-row" id="row-{{ $session->id }}">
        <!-- User Info -->
        <td class="ps-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-white fw-bold user-avatar-circle">
                    {{ substr($session->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark text-sm">{{ $session->user->name ?? 'Unknown' }}</h6>
                    <span class="text-muted text-xs">{{ $session->user->email ?? '' }}</span>
                    <div class="d-md-none mt-1">
                        <span class="badge bg-light text-dark border">{{ $session->ip_address }}</span>
                    </div>
                </div>
            </div>
        </td>
        
        <!-- Exam Info -->
        <td>
            <div class="d-flex flex-column">
                <span class="fw-bold text-dark text-sm">{{ $session->exam->title ?? 'Unknown Exam' }}</span>
                <span class="text-muted text-xs">
                    <i class="fa-regular fa-clock me-1"></i>{{ __('live.started') }}: 
                    {{ $startTime ? $startTime->format('h:i A') : 'N/A' }}
                </span>
            </div>
        </td>

        <!-- Progress Bar (Dynamic Style Allowed) -->
        <td>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="progress-slim-wrapper">
                    <div class="progress-slim-bar" style="width: {{ $session->progress_percentage }}%;"></div>
                </div>
                <span class="text-xs fw-bold">{{ round($session->progress_percentage) }}%</span>
            </div>
            <span class="text-xs text-muted">{{ __('live.label_time_left') }} <span class="fw-bold {{ $timeLeft === '00:00' ? 'text-danger' : 'text-dark' }}">{{ $timeLeft }}</span></span>
        </td>

        <!-- Risk Score -->
        <td>
            @php
                $riskLevel = $session->risk_level ?? 'low';
                $riskClass = match($riskLevel) {
                    'critical' => 'risk-high',
                    'warning' => 'risk-med',
                    default => 'risk-low'
                };
            @endphp
            <div class="d-flex align-items-center gap-2">
                <div class="risk-badge {{ $riskClass }}">
                    {{ $session->risk_score ?? 0 }}
                </div>
                @if($riskLevel === 'critical')
                    <i class="fa-solid fa-triangle-exclamation text-danger fa-beat-fade" title="{{ __('live.high_risk_detected') }}"></i>
                @endif
            </div>
        </td>

        <!-- Status -->
        <td>
            @if($session->status === 'ongoing')
                <span class="status-pill ongoing">
                    <span class="pulse-indicator me-1"></span> {{ __('live.status_active') }}
                </span>
            @elseif($session->status === 'paused')
                <span class="status-pill paused">
                    <i class="fa-solid fa-pause me-1"></i> {{ __('live.status_paused') }}
                </span>
            @else
                <span class="status-pill terminated">
                    <i class="fa-solid fa-stop me-1"></i> {{ __('live.status_terminated') }}
                </span>
            @endif
        </td>

        <!-- Actions -->
        <td class="text-end pe-4">
            <button class="btn btn-sm btn-light border me-1 toggle-details-btn" type="button" data-target="#detail-{{ $session->id }}">
                <i class="fa-solid fa-chevron-down text-muted"></i>
            </button>
            
            <div class="btn-group">
                @if(in_array($session->status, ['terminated', 'completed']))
                    <button class="btn btn-sm btn-white border text-success action-btn" 
                            data-action="reopen" 
                            data-id="{{ $session->id }}" 
                            title="{{ __('live.action_reopen') }}">
                        <i class="fa-solid fa-play"></i>
                    </button>
                @else
                    @if($session->status !== 'paused')
                        <button class="btn btn-sm btn-white border text-warning action-btn" 
                                data-action="pause" 
                                data-id="{{ $session->id }}" 
                                title="{{ __('live.action_pause') }}">
                            <i class="fa-solid fa-pause"></i>
                        </button>
                    @else
                        <button class="btn btn-sm btn-white border text-success action-btn" 
                                data-action="resume" 
                                data-id="{{ $session->id }}" 
                                title="{{ __('live.action_resume') }}">
                            <i class="fa-solid fa-play"></i>
                        </button>
                    @endif
                    
                    <button class="btn btn-sm btn-white border text-danger action-btn" 
                            data-action="terminate" 
                            data-id="{{ $session->id }}" 
                            title="{{ __('live.action_terminate') }}">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                @endif
            </div>
        </td>
    </tr>

    <!-- Hidden Details Row -->
    <tr class="detail-row" id="detail-{{ $session->id }}">
        <td colspan="6" class="p-0">
             @include('admin.live.partials.session-detail-content', ['session' => $session])
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5">
            <div class="d-flex flex-column align-items-center justify-content-center live-empty-state">
                <i class="fa-solid fa-tower-broadcast fa-3x mb-3 text-muted"></i>
                <h5 class="fw-bold text-muted">{{ __('live.empty_title') }}</h5>
                <p class="text-sm text-muted mb-0">{{ __('live.empty_desc_table') }}</p>
            </div>
        </td>
    </tr>
@endforelse