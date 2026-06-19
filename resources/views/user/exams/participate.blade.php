@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('addons/coding-assessments/codemirror/lib/codemirror.css') }}">
@php
    $isCodingEnabled = class_exists(\Addons\CodingAssessments\Models\CodingAssessment::class);
@endphp
@if($session && $isCodingEnabled && $questions->where('type', 'coding')->count() > 0)
<link rel="stylesheet" href="{{ asset('addons/coding-assessments/css/coding.css') }}">
@endif
<style>
    .CodeMirror {
        height: 400px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: 'Fira Code', monospace;
        font-size: 14px;
        background: #fff;
    }
</style>
@endpush

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/user/exam-participation.css') }}">

@if(!$session)
    @include('user.exams.partials.instructions')
@else
    @php
        $isProctoringEnabled = false;
        if (class_exists(\Addons\AdvancedProctoring\Models\ProctoringSetting::class)) {
            $proctoringSetting = \Addons\AdvancedProctoring\Models\ProctoringSetting::where('exam_id', $exam->id)->first();
            $isProctoringEnabled = $proctoringSetting ? $proctoringSetting->is_enabled : false;
        }

        $isCodingActive = class_exists(\Addons\CodingAssessments\Models\CodingAssessment::class);

        $examData = [
            "examId" => $exam->id,
            "questions" => $questions,
            "existingAnswers" => $existingAnswers,
            "secondsRemaining" => $secondsRemaining,
            "saveUrl" => route("exam.save-answer", $exam),
            "csrfToken" => csrf_token(),
            "isCodingActive" => $isCodingActive,
            "strings" => [
                "noQuestions" => __("frontend.error_no_questions"),
                "questionMissingText" => __("frontend.question_missing_text"),
                "noOptions" => __("frontend.no_options_available"),
                "saving" => __("frontend.status_saving"),
                "saved" => __("frontend.status_saved_success"),
                "saveError" => __("frontend.status_save_error"),
                "timeUpTitle" => __("frontend.timer_time_up_title"),
                "timeUpText" => __("frontend.timer_time_up_text"),
                "validationTitle" => __("frontend.validation_action_required_title"),
                "validationText" => __("frontend.validation_answer_or_mark"),
                "validationConfirm" => __("frontend.validation_understood"),
                "unmarkedWarning" => __("frontend.mark_unmarked_warning"),
                "markedInfo" => __("frontend.mark_marked_info"),
                "reviewTitle" => __("frontend.submission_pending_reviews_title"),
                "reviewText" => __("frontend.submission_pending_reviews_text"),
                "submitAnyway" => __("frontend.submission_submit_anyway"),
                "reviewQuestions" => __("frontend.submission_review_questions"),
                "finishTitle" => __("frontend.submission_finish_title"),
                "finishText" => __("frontend.submission_finish_text"),
                "yesSubmit" => __("frontend.submission_yes_submit"),
            ]
        ];

        $proctorConfig = [];
        if ($isProctoringEnabled) {
            $proctorConfig = [
                "sessionId" => $session->id,
                "logUrl" => \Illuminate\Support\Facades\Route::has("proctoring.api.log") ? route("proctoring.api.log") : "",
                "csrf" => csrf_token(),
                "models" => asset("addons/advanced-proctoring/models"),
                "lang" => [
                    "multipleFaces" => __("proctoring::proctoring.warning_multiple_faces"),
                    "noFace" => __("proctoring::proctoring.warning_no_face"),
                    "cameraRequired" => __("proctoring::proctoring.error_camera_required")
                ]
            ];
        }
    @endphp

    @if($isProctoringEnabled)
        <link rel="stylesheet" href="{{ asset('assets/css/user/proctoring.css') }}">
        <input type="hidden" id="proctor-config" value='{{ json_encode($proctorConfig) }}'>
        <div id="proctoring-fixed-layer">
            <div id="proctoring-drag-handle"><div></div></div>
            <div id="proctoring-video-wrapper">
                <video id="proctoring-video" autoplay muted playsinline></video>
                <div id="proctor-ai-status"><div id="status-dot"></div> AI Monitoring Active</div>
                <div id="proctoring-warning-overlay">
                    <i class="fas fa-exclamation-triangle mb-2 fa-lg"></i>
                    <span id="warning-text"></span>
                </div>
            </div>
        </div>
    @endif

    <input type="hidden" id="exam-data" data-exam='{{ json_encode($examData) }}'>
    
    <div id="exam-ui-container">
        <div class="exam-top-header">
            <div class="exam-identity">
                <h2>{{ $exam->title }}</h2>
                <p>{{ $exam->category->name ?? __('frontend.assessment') }} • {{ $questions->count() }} {{ __('frontend.questions_count') }}</p>
            </div>
            <div class="exam-controls">
                <div class="exam-timer" id="exam-timer">--:--</div> 
                <form action="{{ route('exam.submit', $exam) }}" method="POST" id="submit-exam-form">
                    @csrf
                    <button type="button" class="btn-end-exam" id="btn-end-exam">{{ __('frontend.end_exam') }}</button>
                </form>
            </div>
        </div>

        <div class="exam-body-container">
            <div class="question-workspace">
                <div class="zi-card question-card">
                    <div class="question-index-badge">
                        {{ __('frontend.question_label') }} <span id="current-q-num">1</span> {{ __('frontend.of_label') }} {{ $questions->count() }}
                    </div>
                    <div id="question-container">
                        <div class="question-content">
                            <div id="q-text-wrapper">
                                <p id="q-text">{{ __('frontend.loading') }}</p>
                            </div>
                            <div id="exam-test-cases-container" class="d-none"></div>
                            <p id="js-error-display" class="js-error"></p>
                        </div>
                        <div class="question-options-list" id="answer-options-list"></div>
                        
                        <div id="coding-arena-container" class="ca-arena" style="display: none;">
                            <div class="ca-editor-panel">
                                <div class="ca-editor-nav">
                                    <select id="language-select" class="form-select form-select-sm w-auto bg-dark text-white shadow-none border-secondary"></select>
                                    <button id="run-btn" class="btn btn-sm btn-dark border-secondary px-3"><i class="fa-solid fa-play me-2"></i> Run</button>
                                </div>
                                <textarea id="code-editor"></textarea>
                                <div class="ca-terminal">
                                    <div class="ca-term-header">Consoles & Results</div>
                                    <div class="ca-term-body" id="terminal">> Initializing environment...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="question-nav-actions">
                        <button type="button" class="btn-primary-action btn-ghost" id="btn-prev" disabled>← {{ __('frontend.previous_btn') }}</button>
                        <button type="button" class="btn-mark-review" id="mark-review-toggle">⚐ {{ __('frontend.mark_review') }}</button>
                        <div>
                            <button type="button" class="btn-primary-action btn-green" id="btn-next">{{ __('frontend.next_btn') }} →</button>
                            <button type="button" class="btn-primary-action btn-green d-none" id="btn-finish">{{ __('frontend.submit_finish') }} ✔</button>
                        </div>
                    </div>
                    <div class="auto-save-feedback" id="save-status">{{ __('frontend.auto_save_msg') }}</div>
                </div>
            </div>
            <div class="control-panel">
                <div class="zi-card">
                    <div class="panel-header">{{ __('frontend.progress_overview') }}</div>
                    <div class="progress-stats-row"><span>{{ __('frontend.stat_answered') }}</span><span><span id="stat-answered">0</span> / {{ $questions->count() }}</span></div>
                    <div class="panel-progress-bar"><div class="panel-progress-fill" id="progress-bar-fill"></div></div>
                </div>
                <div class="zi-card">
                    <div class="panel-header">{{ __('frontend.question_navigator') }}</div>
                    <div class="question-navigator-grid" id="nav-grid"></div>
                </div>
            </div>
        </div>
    </div>

    @if($isProctoringEnabled)
        <script src="{{ asset('addons/advanced-proctoring/js/face-api.min.js') }}"></script>
        <script src="{{ asset('addons/advanced-proctoring/js/proctoring.js') }}"></script>
        <script src="{{ asset('assets/js/user/proctoring-init.js') }}"></script>
    @endif

    <script src="{{ asset('assets/vendor/sweetalert/sweetalert2.all.min.js') }}"></script>

    @if($isCodingActive && $questions->where('type', 'coding')->count() > 0)
    <script src="{{ asset('addons/coding-assessments/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('addons/coding-assessments/codemirror/javascript/javascript.js') }}"></script>
    <script src="{{ asset('addons/coding-assessments/codemirror/python/python.js') }}"></script>
    <script src="{{ asset('addons/coding-assessments/codemirror/clike/clike.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
                lineNumbers: true,
                mode: "javascript",
                theme: "default",
                indentUnit: 4,
                viewportMargin: Infinity
            });

            window.codingEditor = editor;

            let typingTimer;
            window.codingEditor.on('change', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    if (window.currentQuestion && window.currentQuestion.type === 'coding') {
                        if (typeof window.saveAnswer === 'function') {
                            window.saveAnswer(window.currentQuestion);
                        }
                    }
                }, 1500);
            });
        });
    </script>
    @endif
    <script src="{{ asset('assets/js/user/exam-participate.js') }}"></script>
@endif
@endsection