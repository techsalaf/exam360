@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/results.css') }}">
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.my_certificates') }}</h1>
        <p class="page-subtitle">{{ __('frontend.certificates_subtitle') }}</p>
    </div>
</div>

@if($earnedCertificates->isNotEmpty())
    <div class="certificates-section-title">{{ __('frontend.earned_section') }}</div>
    <div class="certificates-grid">
        @foreach($earnedCertificates as $cert)
            <div class="certificate-card">
                <div class="cert-icon">
                    <i class="fa-solid fa-medal"></i>
                </div>
                <h2 class="cert-title">{{ __('frontend.cert_achievement') }}</h2>
                <p class="cert-exam-title">{{ $cert->exam->title }}</p>
                
                <p class="mb-4 cert-issued-date">
                    {{ __('frontend.issued_on') }} {{ $cert->certificate_issued_at->translatedFormat('M d, Y') }}
                </p>

                <div class="cert-action-wrapper">
                    <a href="{{ route('user.certificate.download', $cert->id) }}" class="btn-download">
                        <i class="fa-solid fa-download me-2"></i> {{ __('frontend.download_pdf') }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($pendingCertificates->isNotEmpty())
    <div class="certificates-section-title mt-5">{{ __('frontend.processing_section') }}</div>
    <div class="certificates-grid">
        @foreach($pendingCertificates as $cert)
            <div class="pending-cert-card">
                <div class="pending-icon">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div class="pending-info">
                    <h4>{{ $cert->exam->title }}</h4>
                    <p>
                        {{ __('frontend.passed_on') }} {{ $cert->created_at->translatedFormat('M d, Y') }}. 
                        {{ __('frontend.waiting_admin') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($lockedCertificates->isNotEmpty())
    <div class="certificates-section-title mt-5">{{ __('frontend.locked_section') }}</div>
    <div class="certificates-grid">
        @foreach($lockedCertificates as $cert)
            <div class="certificate-card cert-locked">
                <div class="cert-icon cert-icon-muted">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h2 class="cert-title cert-text-muted">{{ __('frontend.not_earned') }}</h2>
                <p class="cert-exam-title cert-text-muted">{{ $cert->exam->title }}</p>
                
                <p class="mb-4 cert-score-muted">
                    {{ __('frontend.highest_score') }} <span class="fw-bold">{{ round($cert->percentage) }}%</span> <br>
                    {{ __('frontend.required_score') }} {{ $cert->exam->pass_percentage }}%
                </p>

                <div class="cert-action-wrapper">
                    <a href="{{ route('exam.participate', $cert->exam->slug) }}" class="btn-download btn-download-muted">
                        <i class="fa-solid fa-rotate-right me-2"></i> {{ __('frontend.retake_exam') }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($earnedCertificates->isEmpty() && $pendingCertificates->isEmpty() && $lockedCertificates->isEmpty())
    <div class="empty-state-wrapper mt-4">
        <div class="empty-state-content">
            <div class="empty-icon">
                <i class="fa-solid fa-award"></i>
            </div>
            <h3>{{ __('frontend.no_certs_title') }}</h3>
            <p>{{ __('frontend.no_certs_desc') }}</p>
            <a href="{{ route('my.exams') }}" class="btn-primary-action">
                {{ __('frontend.go_to_exams') }}
            </a>
        </div>
    </div>
@endif

@endsection