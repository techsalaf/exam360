@extends('layouts.user')

@section('content')

@php
    // Define dynamic variables for progress bars
    $progress_accuracy = $stats['accuracy'] ?? 0;
    $progress_time = $stats['time_management'] ?? 0;
    $progress_consistency = $stats['consistency'] ?? 0;
@endphp

@if(Auth::check() && !Auth::user()->hasVerifiedEmail())
    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-4 shadow-sm border-0 alert-verification" role="alert">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center p-2 alert-icon-circle">
                <i class="fa-solid fa-envelope-open-text text-warning"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">{{ __('frontend.email_not_verified') }}</h6>
                <p class="mb-0 small opacity-75">{{ __('frontend.verify_email_desc') }}</p>
            </div>
        </div>
        
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm fw-bold text-white px-3 btn-alert-action">
                {{ __('frontend.resend_link') }}
            </button>
        </form>
    </div>
    
    @if (session('resent'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-success-radius" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>
            {{ __('frontend.verification_link_sent') }}
        </div>
    @endif
@endif

<div class="page-header">
    <div>
        <h1 class="page-title">
            {{ __('frontend.welcome_back') }}, {{ Auth::user()->name ?? __('frontend.student_default') }}
        </h1>
        <p class="page-subtitle">{{ __('frontend.header_subtitle') }}</p>
    </div>
    <div class="d-none d-md-block">
        <div class="header-date">
            <i class="fa-regular fa-calendar"></i>
            <span>{{ now()->translatedFormat('l, F j, Y') }}</span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        
        @if(isset($nextExam) && $nextExam)
        <div class="card-hero mb-4 {{ $nextExam->banner ? 'has-bg' : '' }}" 
             data-bg="{{ $nextExam->banner ? Storage::url($nextExam->banner) : '' }}">
            <div class="card-hero-overlay"></div>
            <div class="position-relative">
                <div class="d-flex justify-content-between align-items-start mb-5">
                    <div class="badge-hero d-flex align-items-center gap-2 text-white">
                        <span class="rounded-circle bg-{{ $nextExam->status_color ?? 'success' }} status-dot-lg status-dot-lg-success"></span> 
                        {{ __('frontend.exam_status.' . strtolower($nextExam->status)) }}
                    </div>
                    <span class="text-white opacity-75 fw-bold small">{{ $nextExam->display_date }}</span>
                </div>
                
                <h2 class="display-6 fw-bold text-white mb-2">{{ $nextExam->title }}</h2>
                <p class="text-white opacity-75 mb-4 fs-6">
                    {{ optional($nextExam->category)->name ?? __('frontend.assessment') }} • {{ $nextExam->duration_minutes }} {{ __('frontend.minutes') }}
                </p>
                
                <a href="{{ route('exam.participate', $nextExam->slug ?? $nextExam->id) }}" class="btn btn-light fw-bold px-4 py-2 d-inline-flex align-items-center gap-2 btn-card-hero">
                    {{-- RULE 3 FIX: Use raw status for comparison --}}
                    @if(strtolower($nextExam->status) === 'ongoing')
                        {{ __('frontend.continue_exam') }}
                    @elseif(strtolower($nextExam->status) === 'ready')
                        {{ __('frontend.start_now') }}
                    @else
                        {{ __('frontend.view_instructions') }}
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
        @else
        <div class="card-hero mb-4">
            <div class="card-hero-overlay"></div>
            <div class="position-relative">
                <div class="d-flex justify-content-between align-items-start mb-5">
                    <div class="badge-hero d-flex align-items-center gap-2 text-white">
                        <span class="rounded-circle bg-secondary status-dot-lg status-dot-lg-secondary"></span> {{ __('frontend.no_active') }}
                    </div>
                    <span class="text-white opacity-75 fw-bold small">{{ now()->translatedFormat('M d, h:i A') }}</span>
                </div>
                
                <h2 class="display-6 fw-bold text-white mb-2">{{ __('frontend.no_scheduled_title') }}</h2>
                <p class="text-white opacity-75 mb-4 fs-6">{{ __('frontend.no_scheduled_desc') }}</p>
                
                <a href="{{ route('my.exams') }}" class="btn btn-light fw-bold px-4 py-2 d-inline-flex align-items-center gap-2 btn-card-hero">
                    {{ __('frontend.view_all_exams') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card-stat-modern">
                    <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="fa-regular fa-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ str_pad($stats['scheduled_count'] ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="stat-label">{{ __('frontend.scheduled') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-stat-modern">
                    <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-check-double fa-lg"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ str_pad($stats['completed_count'] ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="stat-label">{{ __('frontend.completed') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-stat-modern">
                    <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                        <i class="fa-regular fa-star fa-lg"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['average_score'] ?? 0 }}%</div>
                        <div class="stat-label">{{ __('frontend.avg_score') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-premium">
            <div class="premium-header">
                <h5 class="fw-bold mb-0 text-dark">{{ __('frontend.your_exams') }}</h5>
                <div class="segmented-control" id="exam-toggle">
                    <button class="segmented-item active" data-target="#upcoming-list">
                        {{ __('frontend.tab_upcoming') }} ({{ isset($upcomingExams) ? $upcomingExams->count() : 0 }})
                    </button>
                    <button class="segmented-item" data-target="#history-list">
                        {{ __('frontend.tab_history') }} ({{ isset($completedExams) ? $completedExams->count() : 0 }})
                    </button>
                </div>
            </div>
            
            <div id="upcoming-list">
            @if(isset($upcomingExams) && count($upcomingExams) > 0)
                @foreach($upcomingExams as $exam)
                <div class="premium-item">
                    <div class="d-flex align-items-center">
                        <div class="item-icon icon-bg-purple">
                            <i class="fa-regular fa-file-lines"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $exam->title }}</h6>
                            <div class="d-flex align-items-center gap-2 small text-muted">
                                <span class="fw-medium">
                                    @if($exam->start_date && \Carbon\Carbon::parse($exam->start_date)->isFuture())
                                        {{ \Carbon\Carbon::parse($exam->start_date)->translatedFormat('M d, h:i A') }}
                                    @else
                                        {{ __('frontend.available_now') }}
                                    @endif
                                </span>
                                <span class="text-secondary">•</span>
                                <span class="bg-light border px-2 py-0 rounded fw-bold badge-small-font">
                                    {{ $exam->duration_minutes }} {{ __('frontend.mins') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('exam.participate', $exam->slug ?? $exam->id) }}" class="btn-soft-primary">
                        {{ __('frontend.view') }}
                    </a>
                </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <p class="text-muted small mb-0">{{ __('frontend.no_upcoming_exams') }}</p>
                </div>
            @endif
            </div>
            
            <div id="history-list" class="d-none">
            @if(isset($completedExams) && count($completedExams) > 0)
                @foreach($completedExams as $exam)
                <div class="premium-item">
                    <div class="d-flex align-items-center">
                        <div class="item-icon icon-bg-blue">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $exam->title }}</h6>
                            <div class="d-flex align-items-center gap-2 small text-muted">
                                <span class="fw-medium">
                                    {{ __('frontend.score_label') }} {{ optional($exam->user_session)->score ?? __('frontend.not_available') }}%
                                </span>
                                <span class="text-secondary">•</span>
                                <span class="bg-light border px-2 py-0 rounded fw-bold badge-small-font">
                                    {{ optional($exam->user_session)->updated_at ? optional($exam->user_session)->updated_at->translatedFormat('M d') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('user.results.show', $exam->id) }}" class="btn-soft-primary">{{ __('frontend.result') }}</a>
                </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <p class="text-muted small mb-0">{{ __('frontend.no_history_exams') }}</p>
                </div>
            @endif
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card-premium p-4 mb-4">
            <h6 class="fw-bold mb-4">{{ __('frontend.performance_snapshot') }}</h6>
            <div class="mb-4">
                <div class="d-flex justify-content-between small fw-bold mb-2">
                    <span class="text-secondary">{{ __('frontend.accuracy_rate') }}</span>
                    <span class="text-success">{{ $progress_accuracy }}%</span>
                </div>
                <div class="progress progress-sm-radius">
                    {{-- RULE 2 FIX: Dynamic style is allowed --}}
                    <div class="progress-bar bg-success progress-bar-full-radius" style="width: {{ $progress_accuracy }}%;"></div>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between small fw-bold mb-2">
                    <span class="text-secondary">{{ __('frontend.time_management') }}</span>
                    <span class="text-primary">{{ $progress_time }}%</span>
                </div>
                <div class="progress progress-sm-radius">
                    {{-- RULE 2 FIX: Dynamic style is allowed --}}
                    <div class="progress-bar bg-primary progress-bar-full-radius" style="width: {{ $progress_time }}%;"></div>
                </div>
            </div>
            <div>
                <div class="d-flex justify-content-between small fw-bold mb-2">
                    <span class="text-secondary">{{ __('frontend.consistency') }}</span>
                    <span class="text-warning">{{ $progress_consistency }}%</span>
                </div>
                <div class="progress progress-sm-radius">
                    {{-- RULE 2 FIX: Dynamic style is allowed --}}
                    <div class="progress-bar bg-warning progress-bar-full-radius" style="width: {{ $progress_consistency }}%;"></div>
                </div>
            </div>
        </div>

        <div class="card-premium p-0 overflow-hidden">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">{{ __('frontend.notifications') }}</h6>
            </div>
            
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = $notification->read_at === null;
                        $color = $data['color'] ?? 'primary';
                        $icon = $data['icon'] ?? 'fa-solid fa-bell';
                    @endphp
                    <div class="list-group-item p-3 d-flex gap-3 align-items-start {{ $isUnread ? 'bg-light' : '' }}">
                        <div class="bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0 note-icon-sm">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-bold small text-dark {{ $isUnread ? '' : 'text-muted' }}">
                                    {{-- RULE 4 FIX: Use translatable fallback --}}
                                    {{ $data['title'] ?? __('frontend.notification') }}
                                    @if($isUnread)<span class="rounded-circle bg-danger d-inline-block ms-1 unread-dot-sm"></span>@endif
                                </div>
                                {{-- RULE 2 FIX: diffForHumans is now locale-aware in the topbar partial, but here it's raw carbon, which is fine --}}
                                <div class="text-muted note-time-xs">{{ $notification->created_at->diffForHumans(null, true, true) }}</div>
                            </div>
                            <div class="text-muted small note-message-line-height">{{ $data['message'] ?? '' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <p class="text-muted small mb-0">{{ __('frontend.no_notifications') }}</p>
                    </div>
                @endforelse
            </div>
            @if(count($notifications) > 0)
                <div class="p-2 text-center bg-light border-top">
                    <a href="{{ route('user.notifications.index') }}" class="btn-view-all-notes">{{ __('frontend.view_all_notifications') }}</a>
                </div>
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // RULE 2 FIX: Apply card-hero background from data-attribute
        document.querySelectorAll('.card-hero.has-bg').forEach(el => {
            el.style.backgroundImage = `url(${el.dataset.bg})`;
        });

        // Toggle logic (already clean/acceptable)
        const toggle = document.getElementById('exam-toggle');
        const upcomingList = document.getElementById('upcoming-list');
        const historyList = document.getElementById('history-list');

        if (toggle) {
            toggle.addEventListener('click', function (e) {
                if (e.target.classList.contains('segmented-item')) {
                    toggle.querySelectorAll('.segmented-item').forEach(item => item.classList.remove('active'));
                    e.target.classList.add('active');

                    const targetId = e.target.getAttribute('data-target');
                    if (targetId === '#upcoming-list') {
                        upcomingList.classList.remove('d-none');
                        historyList.classList.add('d-none');
                    } else if (targetId === '#history-list') {
                        upcomingList.classList.add('d-none');
                        historyList.classList.remove('d-none');
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection