@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/results.css') }}">
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.results_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.results_subtitle') }}</p>
    </div>
</div>

<div class="results-grid">
    
    @if ($results->isEmpty())
        <div class="empty-state-wrapper">
            <div class="empty-state-content">
                <div class="empty-icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h3>{{ __('frontend.no_results_title') }}</h3>
                <p>{{ __('frontend.no_results_desc') }}</p>
                <a href="{{ route('my.exams') }}" class="btn-primary-action">
                    {{ __('frontend.browse_exams_btn') }}
                </a>
            </div>
        </div>
    @else
        @foreach ($results as $result)
            @php
                $isPublished = $result->is_published;

                if ($isPublished) {
                    $isPassed = $result->is_passed;
                    $statusClass = $isPassed ? 'badge-passed' : 'badge-failed';
                    $statusText = $isPassed ? __('frontend.status_passed') : __('frontend.status_failed');
                    $scoreClass = $isPassed ? 'score-passed' : 'score-failed';
                    $scoreText = round($result->percentage) . '%';
                } else {
                    $statusClass = 'badge-pending';
                    $statusText = __('frontend.status_pending');
                    $scoreClass = 'score-pending';
                    $scoreText = '--';
                }
            @endphp
            
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title" title="{{ $result->exam->title ?? __('frontend.default_exam_title') }}">
                        {{ \Illuminate\Support\Str::limit($result->exam->title ?? __('frontend.default_exam_title'), 35) }}
                    </div>
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </div>
                
                <p class="result-meta">
                    @if(!$isPublished && $result->exam->result_date)
                        <span class="text-primary fw-bold">
                            <i class="fa-regular fa-calendar-days"></i> {{ __('frontend.result_available') }} 
                            {{ $result->exam->result_date->translatedFormat('M d, Y') }}
                        </span>
                    @else
                        {{ __('frontend.completed_on') }} {{ $result->created_at->translatedFormat('M d, Y') }}
                    @endif
                </p>
                
                <div class="score-metric">
                    <span>{{ __('frontend.your_score') }}</span>
                    <span class="score-value {{ $scoreClass }}">{{ $scoreText }}</span>
                </div>
                <div class="score-metric">
                    <span>{{ __('frontend.passing_mark') }}</span>
                    <span>{{ number_format($result->exam->pass_percentage ?? 0, 2) }}%</span>
                </div>
                
                <a href="{{ route('user.results.show', $result->exam_id) }}" class="btn-report">
                    @if($isPublished)
                        {{ __('frontend.view_full_report') }}
                    @else
                        {{ __('frontend.view_status') }}
                    @endif
                </a>
            </div>
        @endforeach
    @endif
    
</div>

@if($results->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} results
        </div>
        @include('components.app-pagination', ['paginator' => $results])
    </div>
@endif

@endsection