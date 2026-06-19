@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/results.css') }}">
<style>
    .status-under_review { background-color: #fef9c3; color: #a16207; }
    .text-review { color: #a16207; font-weight: bold; }
</style>
@endpush

@section('content')

@php
    $passStatusClass = $result->is_passed ? 'passed' : 'failed';
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.report_prefix') }} {{ $result->exam->title }}</h1>
        <p class="page-subtitle">{{ __('frontend.report_subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('user.results') }}" class="btn btn-sm btn-light fw-bold">
            <i class="fa-solid fa-arrow-left me-2"></i> {{ __('frontend.back_to_results') }}
        </a>
    </div>
</div>

<div class="report-summary-grid">
    <div class="summary-metric-card">
        <div class="progress-circle {{ $passStatusClass }}"><span>{{ round($result->percentage) }}%</span></div>
        <div class="metric-label">{{ __('frontend.overall_score') }}</div>
        <div class="metric-value-status text-uppercase {{ $passStatusClass }}">{{ $result->is_passed ? __('frontend.status_passed') : __('frontend.status_failed') }}</div>
    </div>
    <div class="summary-metric-card">
        <div class="metric-value text-success">{{ $result->correct_count }} / {{ $result->total_questions }}</div>
        <div class="metric-label">{{ __('frontend.metric_correct') }}</div>
        @if($result->under_review_count > 0)
            <div class="mt-2 badge bg-warning text-dark">{{ $result->under_review_count }} Pending Review</div>
        @endif
    </div>
    <div class="summary-metric-card">
        <div class="metric-value">{{ number_format($result->score_obtained, 2) }} / {{ number_format($result->total_marks, 2) }}</div>
        <div class="metric-label">{{ __('frontend.metric_net_score') }}</div>
        <div class="mt-3 metric-value text-warning">- {{ number_format($result->deducted_marks, 2) }}</div>
    </div>
    <div class="summary-metric-card">
        <div class="metric-value">{{ $result->exam->pass_percentage ?? 0 }}%</div>
        <div class="metric-label">{{ __('frontend.metric_pass_percentage') }}</div>
        <div class="mt-3 metric-value metric-value-muted">{{ $result->total_time_taken }} {{ __('frontend.mins') }}</div>
    </div>
</div>

<div class="analysis-section">
    <h3 class="page-title analysis-title">{{ __('frontend.analysis_title') }}</h3>
    @foreach ($questionBreakdown as $index => $q)
        <div class="question-list-item">
            <div class="d-flex align-items-center w-100">
                <div class="question-status-icon {{ 'status-' . $q->status }}">
                    @if ($q->status == 'correct') <i class="fa-solid fa-check"></i>
                    @elseif ($q->status == 'wrong') <i class="fa-solid fa-xmark"></i>
                    @elseif ($q->status == 'under_review') <i class="fa-solid fa-clock"></i>
                    @else <i class="fa-solid fa-minus"></i> @endif
                </div>
                <div class="question-text flex-grow-1">
                    {{ $index + 1 }}. {{ strip_tags($q->text) }}
                    @if($q->status == 'under_review')
                        <span class="ms-2 badge bg-light text-review border small">PENDING REVIEW</span>
                    @endif
                </div>
                <button class="btn btn-sm btn-link text-decoration-none shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $index }}">
                    {{ __('frontend.review_answer_btn') }} <i class="fa-solid fa-chevron-down ms-1"></i>
                </button>
            </div>
            
            <div class="collapse w-100 mt-3 ps-5" id="details-{{ $index }}">
                <div class="card card-body bg-light border-0">
                    @if($q->status == 'under_review')
                        <div class="alert alert-info py-2 small mb-0">
                            <i class="fa-solid fa-circle-info me-1"></i> 
                            This coding answer requires manual review by an admin. Your score for this question will update once approved.
                        </div>
                        <div class="code-wrapper mt-3">
                            <pre class="code-content">{{ $q->selected_option }}</pre>
                        </div>
                    @else
                        <div class="mb-3">
                            <strong>{{ __('frontend.label_your_answer') }}:</strong> 
                            @if($q->type === 'coding')
                                <div class="code-wrapper mt-2">
                                    <div class="line-numbers">
                                        @foreach(explode("\n", $q->selected_option) as $i => $line)
                                            <div>{{ $i + 1 }}</div>
                                        @endforeach
                                    </div>
                                    <pre class="code-content">{{ $q->selected_option }}</pre>
                                </div>
                            @else
                                <span class="{{ $q->status == 'correct' ? 'text-success' : 'text-danger' }}">{{ $q->selected_option }}</span>
                            @endif
                        </div>
                        <p class="mb-2 text-success"><strong>{{ __('frontend.label_correct_answer') }}:</strong> {{ $q->correct_option }}</p>
                        @if($q->explanation)
                            <div class="mt-2 pt-2 border-top">
                                <small class="text-muted fw-bold"><i class="fa-solid fa-lightbulb me-1"></i> {{ __('frontend.label_explanation') }}</small>
                                <p class="mb-0 small text-dark mt-1">{{ strip_tags($q->explanation) }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection