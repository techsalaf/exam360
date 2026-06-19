@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/my-exams.css') }}">
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.my_exams_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.my_exams_subtitle') }}</p>
    </div>
</div>

<div class="tab-nav-wrapper">
    <div class="tab-nav">
        <div class="tab-item active" data-tab="available">
            {{ __('frontend.tab_available') }} ({{ $exams['available']->count() }})
        </div>
        <div class="tab-item" data-tab="ongoing">
            {{ __('frontend.tab_ongoing') }} ({{ $exams['ongoing']->count() }})
        </div>
        <div class="tab-item" data-tab="completed">
            {{ __('frontend.tab_completed') }} ({{ $exams['completed']->count() }})
        </div>
        <div class="tab-item" data-tab="upcoming">
            {{ __('frontend.tab_upcoming') }} ({{ $exams['upcoming']->count() }})
        </div>
    </div>
</div>

<div id="exam-tabs-content">
    
    <div class="exam-tab-pane" id="available">
        @if($exams['available']->isNotEmpty())
            <div class="exam-list-grid">
                @foreach ($exams['available'] as $exam)
                    @include('user.exams.partials.exam-card', ['exam' => $exam, 'status' => 'available'])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>{{ __('frontend.no_exams_ready') }}</p>
                <a href="{{ route('exams.list') }}" class="btn btn-sm btn-outline-primary mt-3">
                    {{ __('frontend.browse_exams_btn') }}
                </a>
            </div>
        @endif
    </div>

    <div class="exam-tab-pane d-none" id="ongoing">
        @if($exams['ongoing']->isNotEmpty())
            <div class="exam-list-grid">
                @foreach ($exams['ongoing'] as $exam)
                    @include('user.exams.partials.exam-card', ['exam' => $exam, 'status' => 'ongoing'])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>{{ __('frontend.no_exams_progress') }}</p>
            </div>
        @endif
    </div>

    <div class="exam-tab-pane d-none" id="completed">
        @if($exams['completed']->isNotEmpty())
            <div class="exam-list-grid">
                @foreach ($exams['completed'] as $exam)
                    @include('user.exams.partials.exam-card', ['exam' => $exam, 'status' => 'completed'])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>{{ __('frontend.no_exams_completed') }}</p>
            </div>
        @endif
    </div>

    <div class="exam-tab-pane d-none" id="upcoming">
        @if($exams['upcoming']->isNotEmpty())
            <div class="exam-list-grid">
                @foreach ($exams['upcoming'] as $exam)
                    @include('user.exams.partials.exam-card', ['exam' => $exam, 'status' => 'upcoming'])
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>{{ __('frontend.no_exams_scheduled') }}</p>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-item');
    const panes = document.querySelectorAll('.exam-tab-pane');

    function showTab(targetTab) {
        tabs.forEach(tab => tab.classList.remove('active'));
        panes.forEach(pane => pane.classList.add('d-none'));

        const activeTab = document.querySelector(`.tab-item[data-tab="${targetTab}"]`);
        const activePane = document.getElementById(targetTab);

        if (activeTab && activePane) {
            activeTab.classList.add('active');
            activePane.classList.remove('d-none');
            localStorage.setItem('user_exam_tab', targetTab);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            showTab(this.getAttribute('data-tab'));
        });
    });

    const savedTab = localStorage.getItem('user_exam_tab');
    // Initialize the correct tab state
    showTab(savedTab && document.getElementById(savedTab) ? savedTab : 'available');
});
</script>
@endpush