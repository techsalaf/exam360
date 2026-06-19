@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/results.css') }}">
@endpush

@section('content')
<div class="container d-flex justify-content-center align-items-center pending-page-wrapper">
    <div class="text-center p-5 bg-white rounded-4 shadow-sm border pending-card">
        
        <div class="mb-4">
            <div class="pending-status-icon">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <h2 class="fw-bold text-dark mb-2">{{ __('frontend.exam_completed_title') }}</h2>
        <p class="text-muted mb-4">
            {{ __('frontend.exam_completed_msg', ['title' => $session->exam->title]) }}
        </p>

        <div class="bg-light p-4 rounded mb-4 border">
            <h6 class="text-uppercase small fw-bold text-muted mb-2">{{ __('frontend.expected_date_label') }}</h6>
            
            @if(!empty($session->exam->result_date))
                <div class="fs-4 fw-bold text-primary">
                    {{ $session->exam->result_date->translatedFormat('M d, Y') }}
                </div>
                <div class="small text-muted mt-1">
                    {{ __('frontend.publish_time_msg') }} {{ $session->exam->result_date->format('h:i A') }}
                </div>
            @else
                <div class="fs-5 fw-bold text-dark">{{ __('frontend.tba_title') }}</div>
                <div class="small text-muted mt-1">{{ __('frontend.tba_msg') }}</div>
            @endif
        </div>

        <a href="{{ route('my.exams') }}" class="btn btn-primary px-4 py-2 fw-bold w-100">
            <i class="fa-solid fa-arrow-left me-2"></i> {{ __('frontend.back_to_exams') }}
        </a>
    </div>
</div>
@endsection