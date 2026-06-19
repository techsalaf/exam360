@extends('layouts.admin')
 
@section('title', __('results.page_title_report', ['id' => $result->id]))

@push('styles')
    <link href="{{ asset('assets/css/admin-results.css') }}" rel="stylesheet">
@endpush

@section('content')

    @php
        $incorrectAnswers = $result->answers->where('is_correct', false)->count();
        $totalQuestions = $result->exam->questions->count();
        $grossScore = $result->answers->where('is_correct', true)->sum('marks_awarded');
        $netScore = floatval($result->score);
        $deductedMarks = max(0, $grossScore - $netScore);
        $negativeMarkingEnabled = $result->exam->has_negative_marking ?? false;
        $negativeValue = $result->exam->negative_mark_value ?? 0.00;
        $totalPossibleMarks = $result->exam->total_marks ?? $result->exam->questions->sum('mark');
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 no-print">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.exams.results.index') }}" 
               class="btn btn-white shadow-sm rounded-circle d-flex align-items-center justify-content-center border icon-box-sm" 
               title="{{ __('results.btn_back_list') }}">
                <i class="fa-solid fa-arrow-left text-secondary"></i>
            </a>
            <div>
                <h5 class="fw-bold text-dark mb-0">Performance Report</h5>
                <span class="text-muted small">ID: #{{ $result->id }}</span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-report-action ai" id="btnAnalyzeAI" data-url="{{ route('admin.exams.results.analyze', $result->id) }}">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Analyze
            </button>
            @if($result->is_passed)
                @if($result->certificate_issued_at)
                    <a href="{{ route('admin.exams.results.certificate', $result->id) }}" class="btn btn-report-action cert">
                        <i class="fa-solid fa-award"></i> Certificate
                    </a>
                @else
                    <form id="issueCertForm" action="{{ route('admin.exams.results.issue_certificate', $result->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-report-action issue btn-issue-action" data-title="Issue Certificate" data-text="Confirm to issue a certificate for this student." data-confirm="Issue Now">
                            <i class="fa-solid fa-stamp"></i> Issue Certificate
                        </button>
                    </form>
                @endif
            @endif
            <a href="{{ route('admin.exams.results.download', $result->id) }}" class="btn btn-report-action pdf">
                <i class="fa-solid fa-file-arrow-down"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="premium-card h-100-card">
                <div class="card-header-clean">
                    <h6 class="fw-bold mb-0 text-uppercase tracking-wide text-muted small">Student Profile</h6>
                </div>
                <div class="p-4 text-center border-bottom">
                    <div class="avatar-xl">
                        {{ substr($result->user->name ?? 'U', 0, 1) }}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $result->user->name }}</h5>
                    <p class="text-muted small mb-0">{{ $result->user->email }}</p>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Exam Status</span>
                        @if($result->is_passed)
                            <span class="badge badge-success-subtle px-3 py-1 fw-bold">Passed</span>
                        @else
                            <span class="badge badge-danger-subtle px-3 py-1 fw-bold">Failed</span>
                        @endif
                    </div>
                    @if($result->certificate_issued_at)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Cert Issued</span>
                        <span class="fw-bold text-success">{{ $result->certificate_issued_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Grade Obtained</span>
                        <span class="fw-bold text-dark">{{ $result->is_passed ? 'Pass' : 'Fail' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Attempt Date</span>
                        <span class="fw-medium text-dark">{{ $result->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="premium-card h-100-card">
                <div class="card-header-clean d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-uppercase tracking-wide text-muted small">Assessment Details</h6>
                    <span class="badge bg-light text-dark border">{{ $result->exam->category->name ?? 'General' }}</span>
                </div>
                <div class="p-4">
                    <h3 class="fw-bold text-dark mb-4">{{ $result->exam->title ?? 'Exam deleted' }}</h3>
                    <div class="score-hero-container mb-5">
                        <div class="row align-items-center">
                            <div class="col-md-5 text-center border-end-md">
                                <div class="radial-progress mx-auto {{ $result->is_passed ? 'success' : 'fail' }}" style="--progress: {{ $result->progress_percentage }}%">
                                    <div class="inner-content">
                                        <span class="percentage">{{ number_format($result->progress_percentage, 1) }}%</span>
                                        <span class="label">Score</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 ps-md-5 mt-4 mt-md-0">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="icon-box text-primary bg-primary-subtle"><i class="fa-solid fa-list-ol"></i></div>
                                            <div>
                                                <div class="small text-muted">Questions</div>
                                                <div class="fw-bold fs-5">{{ floatval($totalQuestions) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="icon-box text-success bg-success-subtle"><i class="fa-solid fa-check"></i></div>
                                            <div>
                                                <div class="small text-muted">Correct</div>
                                                <div class="fw-bold fs-5">{{ floatval($result->correct_answers) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="icon-box text-danger bg-danger-subtle"><i class="fa-solid fa-xmark"></i></div>
                                            <div>
                                                <div class="small text-muted">Incorrect</div>
                                                <div class="fw-bold fs-5">{{ floatval($incorrectAnswers) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="icon-box text-info bg-info-subtle"><i class="fa-solid fa-bullseye"></i></div>
                                            <div>
                                                <div class="small text-muted">Obtained</div>
                                                <div class="fw-bold fs-5">{{ number_format($netScore, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="icon-box text-secondary bg-secondary-subtle"><i class="fa-solid fa-star"></i></div>
                                            <div>
                                                <div class="small text-muted">Total Marks</div>
                                                <div class="fw-bold fs-5">{{ floatval($totalPossibleMarks) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-box bg-light rounded p-3 border">
                        <h6 class="fw-bold text-dark mb-3 small text-uppercase">Timeline</h6>
                        <div class="d-flex flex-wrap gap-4 justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-regular fa-clock text-muted"></i>
                                <div>
                                    <div class="text-muted fs-xss text-uppercase">Started</div>
                                    <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($result->start_time)->format('h:i:s A') }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-flag-checkered text-muted"></i>
                                <div>
                                    <div class="text-muted fs-xss text-uppercase">Completed</div>
                                    <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($result->end_time)->format('h:i:s A') }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-stopwatch text-muted"></i>
                                <div>
                                    <div class="text-muted fs-xss text-uppercase">Duration</div>
                                    <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($result->start_time)->diffInMinutes(\Carbon\Carbon::parse($result->end_time)) }} mins</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="premium-card mb-5">
        <div class="card-header-clean">
            <h6 class="fw-bold mb-0 text-uppercase tracking-wide text-muted small">Question Analysis</h6>
        </div>
        <div class="p-0">
            @forelse($questions as $index => $question)
                @php
                    $options = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
                    $userAnswerRecord = $result->answers->where('question_id', $question->id)->first();
                    $userAnswer = $userAnswerRecord ? $userAnswerRecord->selected_option_id : null;
                    $isCorrectSession = $userAnswerRecord ? $userAnswerRecord->is_correct : false;
                    $marksAwarded = $userAnswerRecord ? $userAnswerRecord->marks_awarded : 0;
                @endphp

                <div class="question-review-item {{ $loop->last ? '' : 'border-bottom' }} p-4">
                    <div class="d-flex gap-3 mb-3">
                        <span class="question-number">Q{{ $loop->iteration }}</span>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-1">{{ $question->question_text }}</h6>
                            <div class="d-flex gap-2 mt-2">
                                @if($userAnswerRecord)
                                    @if($isCorrectSession)
                                        <span class="badge badge-success-subtle"><i class="fa-solid fa-check me-1"></i> Correct</span>
                                    @else
                                        <span class="badge badge-danger-subtle"><i class="fa-solid fa-xmark me-1"></i> Incorrect</span>
                                    @endif
                                @else
                                    <span class="badge bg-light text-muted border">Skipped</span>
                                @endif
                                <span class="badge bg-light text-muted border">{{ strtoupper($question->type) }}</span>
                                <span class="badge bg-light text-muted border">{{ number_format($marksAwarded, 2) }} Marks</span>
                            </div>
                        </div>
                    </div>

                    @if($question->type === 'coding')
                        <div class="coding-review-box">
                            <span class="d-block text-muted fs-xss mb-2 text-uppercase fw-bold">{{ $result->user->name }}'s SUBMITTED CODE:</span>
                            @if($userAnswer)
                                <pre class="coding-answer-pre">{{ $userAnswer }}</pre>
                                <div class="mark-action-btns no-print">
                                    <form action="{{ route('admin.exams.results.mark-answer', $userAnswerRecord->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="correct">
                                        <button type="submit" class="btn-mark btn-mark-success {{ $isCorrectSession ? 'active-correct' : '' }}">
                                            <i class="fa-solid fa-check me-1"></i> Mark Correct
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.exams.results.mark-answer', $userAnswerRecord->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="wrong">
                                        <button type="submit" class="btn-mark btn-mark-danger {{ (!$isCorrectSession && $userAnswerRecord->marks_awarded < 0) ? 'active-wrong' : '' }}">
                                            <i class="fa-solid fa-xmark me-1"></i> Mark Wrong
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="p-3 bg-light border rounded text-muted small italic">Student did not submit any code.</div>
                            @endif
                        </div>
                    @elseif($question->type === 'mcq' && is_array($options))
                        <div class="options-review-grid">
                            @foreach($options as $key => $val)
                                @php
                                    $optionKey = (string)$key;
                                    $isUserSelected = ($optionKey === (string)$userAnswer);
                                    $isCorrect = ($optionKey === (string)$question->correct_answer);
                                    $class = 'review-option' . ($isCorrect ? ' option-correct' : ($isUserSelected ? ' option-wrong' : ''));
                                @endphp
                                <div class="{{ $class }}">
                                    <span class="opt-key">{{ $key }}</span>
                                    <span class="opt-text flex-grow-1">{{ is_array($val) ? ($val['option_text'] ?? 'N/A') : $val }}</span>
                                    @if($isUserSelected) <span class="ms-auto badge bg-primary text-white p-1 px-2" style="font-size: 0.6rem;">Choice</span> @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-light rounded border">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="d-block text-muted fs-xss mb-1">{{ strtoupper($result->user->name) }} Answer</span>
                                    <span class="fw-bold {{ $isCorrectSession ? 'text-success' : 'text-danger' }}">{{ $userAnswer ?? 'Not answered' }}</span>
                                </div>
                                <div class="col-md-6 border-start">
                                    <span class="d-block text-muted fs-xss mb-1">Correct Answer</span>
                                    <span class="fw-bold text-success">{{ $question->correct_answer }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-5 text-center text-muted">No analysis available.</div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-results-show.js') }}"></script>
@endpush