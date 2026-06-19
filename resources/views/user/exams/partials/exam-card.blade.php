@php
    $statusText = \Lang::has('frontend.' . strtolower($status)) 
        ? __('frontend.' . strtolower($status)) 
        : strtoupper($status);
    $statusClass = 'tag-' . $status;

    $iconMap = [
        'Advanced Aptitude Challenge' => ['icon' => 'fa-brain', 'bg' => 'icon-blue'],
        'BCS Preliminary 2025'        => ['icon' => 'fa-puzzle-piece', 'bg' => 'icon-green'],
        'Primary Teacher Exam'        => ['icon' => 'fa-flask', 'bg' => 'icon-yellow'],
        'Chemistry Organic Logic'     => ['icon' => 'fa-atom', 'bg' => 'icon-orange'],
        'English Grammar Mastery'     => ['icon' => 'fa-book-open', 'bg' => 'icon-blue'],
        'default'                     => ['icon' => 'fa-file-lines', 'bg' => 'icon-blue'],
    ];

    $cardStyle = $iconMap[$exam->title] ?? $iconMap['default'];
    
    // Ensure progress is a clean integer
    $progressVal = isset($exam->progress) ? (int) $exam->progress : 0;
    
    $accessStatus = $exam->relationLoaded('pivot') ? ($exam->pivot->status ?? 'active') : 'active';

    $isResultPublished = false;
    if ($status === 'completed') {
        $resultDate = $exam->result_date ? \Carbon\Carbon::parse($exam->result_date) : null;
        $isResultPublished = !$resultDate || $resultDate->isPast();
    }
@endphp

<div class="exam-card-user">
    
    {{-- Banner / Icon --}}
    @if(!empty($exam->banner) && Storage::disk('public')->exists($exam->banner))
        <div class="exam-banner-header">
            <img src="{{ Storage::url($exam->banner) }}" alt="{{ $exam->title }}">
        </div>
    @else
        <div class="exam-icon-header {{ $cardStyle['bg'] }}">
            <i class="fa-solid {{ $cardStyle['icon'] }}"></i>
        </div>
    @endif

    <div class="exam-card-content">
        
        {{-- Tags --}}
        <div class="exam-meta-tags">
            @if($accessStatus === 'pending')
                <span class="status-tag tag-pending">{{ __('frontend.payment_pending') }}</span>
            @else
                <span class="status-tag {{ $statusClass }}">{{ $statusText }}</span>
            @endif
            
            @if($status === 'completed')
                @if($isResultPublished)
                    <span class="pricing-tag tag-published">
                        <i class="fa-solid fa-check-double"></i> {{ __('frontend.result_published') }}
                    </span>
                @else
                    <span class="pricing-tag tag-pending-result">
                        <i class="fa-regular fa-clock"></i> {{ __('frontend.result_pending') }}
                    </span>
                @endif
            @endif
        </div>

        {{-- Title --}}
        <div class="exam-title-wrapper">
            <div class="exam-title" title="{{ $exam->title }}">
                {{ \Illuminate\Support\Str::limit($exam->title, 45) }}
            </div>
        </div>
        
        {{-- Description --}}
        @if($status === 'completed' && optional($exam->user_session)->updated_at)
            <div class="exam-description text-muted small mb-2">
                <i class="fa-regular fa-calendar-check me-1"></i> 
                {{ __('frontend.completed') }}: {{ $exam->user_session->updated_at->translatedFormat('M d, Y') }}
            </div>
        @else
            <div class="exam-description">
                {{ optional($exam->category)->name ?? __('frontend.assessment') }}
            </div>
        @endif

        {{-- Stats --}}
        @if ($status !== 'ongoing' && $status !== 'completed')
            <div class="exam-stats-info">
                <span><i class="fa-regular fa-clock"></i> {{ $exam->duration_minutes }} {{ __('frontend.mins') }}</span>
                <span><i class="fa-regular fa-circle-question"></i> {{ $exam->questions_count }} {{ __('frontend.questions_count') }}</span>
            </div>
        @endif

        {{-- Progress / Score Bar --}}
        @if ($status === 'ongoing' || $status === 'completed')
            <div class="progress-bar-details">
                <div class="progress-label-row">
                    <span class="label-text">
                        {{ $status === 'completed' ? 'Score' : __('frontend.progress_label') }}:
                    </span>
                    
                    @if($status === 'completed' && !$isResultPublished)
                        <span class="percentage-text blur-text">100%</span>
                    @else
                        <span class="percentage-text">{{ $progressVal }}%</span>
                    @endif
                </div>
                
                <div class="progress-bar-wrap">
                    @if($status === 'completed' && !$isResultPublished)
                        {{-- Muted Bar for hidden results --}}
                        <div class="progress-bar-fill bar-muted"></div>
                    @else
                        {{-- 
                             FIX: Direct inline style with !important to prevent CSS overrides.
                             This ensures if the text says 0%, the width IS 0%.
                        --}}
                        <div class="progress-bar-fill {{ $status === 'completed' ? 'bar-score' : 'bar-progress' }}" 
                             style="width: {{ $progressVal }}% !important;"></div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="exam-card-actions">
        @if ($accessStatus === 'pending')
            <button type="button" class="btn-exam-action btn-pending" disabled>
                <i class="fa-solid fa-clock"></i> {{ __('frontend.payment_pending') }}
            </button>

        @elseif ($status === 'ongoing')
            <a href="{{ route('exam.participate', $exam->slug) }}" class="btn-exam-action btn-start">
                <i class="fa-solid fa-play"></i> {{ __('frontend.continue_exam') }}
            </a>

        @elseif ($status === 'completed')
            @if($isResultPublished)
                <a href="{{ route('user.results.show', $exam->user_session->id ?? 0) }}" class="btn-exam-action btn-view">
                    <i class="fa-solid fa-chart-simple"></i> {{ __('frontend.view_report') }}
                </a>
            @else
                <button type="button" class="btn-exam-action btn-view" disabled>
                    <i class="fa-solid fa-lock"></i> {{ __('frontend.results_locked') }}
                </button>
            @endif

        @elseif ($status === 'upcoming')
            <button type="button" class="btn-exam-action btn-view" disabled>
                <i class="fa-regular fa-calendar-check"></i> 
                {{ __('frontend.starts') }} {{ $exam->start_date ? $exam->start_date->translatedFormat('M d') : __('frontend.soon') }}
            </button>

        @else 
            <a href="{{ route('exam.participate', $exam->slug) }}" class="btn-exam-action btn-start">
                <i class="fa-solid fa-play"></i> {{ __('frontend.start_exam_btn') }}
            </a>
        @endif
    </div>
</div>