@extends('layouts.admin')

@section('title', __('questions.page_title', ['exam' => $exam->title]))

@push('styles')
    <link href="{{ asset('assets/css/admin-exams.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-questions.css') }}" rel="stylesheet">
    @includeIf('rich-media::partials.styles')
    <style>
        .coding-preview-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; }
        .lang-tag { font-size: 0.65rem; background: #eef2ff; color: #4f46e5; padding: 2px 8px; border-radius: 4px; font-weight: 700; border: 1px solid #c7d2fe; }
    </style>
@endpush

@section('content')
    @php $isCodingAddonInstalled = class_exists('\Addons\CodingAssessments\Models\CodingAssessment'); @endphp
    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#questionActionsOffcanvas">
            <div class="d-flex align-items-center gap-2">
                <div class="filter-icon-box bg-dark text-white rounded-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-ellipsis"></i>
                </div>
                <span class="fw-bold text-dark fs-sm">{{ __('questions.filter_action') }}</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted fs-icon-sm"></i>
        </button>
    </div>

    <div class="question-page-header desktop-header mb-4">
        <div class="header-content-wrapper d-flex justify-content-between align-items-center w-100">
            <div class="header-left">
                <div class="text-uppercase text-muted fw-bold small mb-1 ls-wide fs-xs">{{ __('questions.header_subtitle') }}</div>
                <h2 class="mb-1 text-dark fw-bold heading-lg">{{ $exam->title }}</h2>
                <div class="d-flex align-items-center gap-2 text-muted small mt-1">
                    <span class="badge bg-white border text-muted rounded-pill px-2">
                        <i class="fa-regular fa-folder me-1"></i> {{ $exam->category->name ?? __('exams.uncategorized') }}
                    </span>
                    <span class="text-muted">|</span>
                    <span>{{ __('questions.total_questions') }}: <strong class="text-dark">{{ $exam->questions_count }}</strong></span>
                </div>
            </div>
            
            <div class="header-right d-flex align-items-center gap-2">
                <a href="{{ route('admin.exams.index') }}" class="btn btn-light bg-white border shadow-sm btn-icon-only rounded-circle" data-bs-toggle="tooltip" title="{{ __('questions.back_tooltip') }}">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <a href="{{ route('admin.exams.instructions', $exam->id) }}" class="btn btn-white shadow-sm d-flex align-items-center gap-2 border px-3 py-2 fw-bold text-dark rounded-pill-custom">
                    <i class="fa-solid fa-file-pen text-primary"></i> <span>Instructions</span>
                </a>
                <button class="btn btn-dark btn-pill shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#aiQuestionModal">
                    <i class="fa-solid fa-wand-magic-sparkles text-warning"></i> <span>{{ __('questions.btn_ai') }}</span>
                </button>
                <button class="btn btn-success bg-success border-success text-white btn-pill shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importQuestionsModal">
                    <i class="fa-solid fa-file-import"></i> <span>{{ __('questions.btn_import') }}</span>
                </button>
                @includeIf('rich-media::buttons')
                <button class="btn btn-premium btn-pill shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
                    <i class="fa-solid fa-plus"></i> <span>{{ __('questions.btn_add') }}</span>
                </button>
            </div>
        </div>
    </div>
    
    <input type="hidden" id="exam_id_hidden" value="{{ $exam->id }}">

    <div class="question-list-wrapper">
        <div class="question-list-header d-flex justify-content-between align-items-center">
            <span>{{ __('questions.list_header') }}</span>
            <span class="text-muted fw-normal fs-xs">{{ __('questions.drag_hint') }}</span>
        </div>

        @forelse($questions as $question)
            @php $isRichMediaQuestion = isset($question->media_type) && !empty($question->media_type) && $question->media_type !== 'text'; @endphp
            <div class="question-list-item">
                <div class="question-content-area">
                    <div class="question-text-row d-flex align-items-start gap-3">
                        <span class="q-number">#{{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}</span>
                        <div class="q-text-wrapper">
                            @if($isRichMediaQuestion)
                                <div class="mb-3">@includeIf('rich-media::display', ['question' => $question])</div>
                            @elseif(!$isRichMediaQuestion && !empty($question->media_url))
                                <div class="mb-3"><img src="{{ asset('storage/'.$question->media_url) }}" class="img-fluid rounded border bg-light" style="height: 120px; object-fit: contain;"></div>
                            @endif
                            <div class="q-text">{{ $question->question_text }}</div>
                            @if($question->type === 'mcq' && $question->options)
                                @php $opts = is_array($question->options) ? $question->options : json_decode($question->options, true); @endphp
                                @if(is_array($opts))
                                    <div class="options-grid mt-3">
                                        @foreach($opts as $key => $optionValue)
                                            @php $isCorrect = ($key === $question->correct_answer || $optionValue === $question->correct_answer); @endphp
                                            <div class="option-item {{ $isCorrect ? 'is-correct' : '' }}">
                                                <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                                                    <span class="opt-letter">{{ $key }}</span>
                                                    <span class="opt-val text-truncate">{{ $optionValue }}</span>
                                                </div>
                                                @if($isCorrect) <i class="fa-solid fa-circle-check text-success fs-5"></i> @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @elseif($question->type === 'true_false')
                                <div class="options-grid mt-3">
                                    <div class="option-item {{ $question->correct_answer === 'True' ? 'is-correct' : '' }}"><div class="d-flex align-items-center gap-2"><span class="opt-letter"><i class="fa-solid fa-check"></i></span><span class="opt-val">True</span></div>@if($question->correct_answer === 'True') <i class="fa-solid fa-circle-check text-success fs-5"></i> @endif</div>
                                    <div class="option-item {{ $question->correct_answer === 'False' ? 'is-correct' : '' }}"><div class="d-flex align-items-center gap-2"><span class="opt-letter"><i class="fa-solid fa-xmark"></i></span><span class="opt-val">False</span></div>@if($question->correct_answer === 'False') <i class="fa-solid fa-circle-check text-success fs-5"></i> @endif</div>
                                </div>
                            @elseif($question->type === 'short_answer')
                                <div class="mt-3 p-2 bg-light rounded border border-success-subtle"><span class="text-success fw-bold"><i class="fa-solid fa-key me-1"></i> Answer:</span> {{ $question->correct_answer }}</div>
                            @elseif($question->type === 'coding' && $isCodingAddonInstalled)
                                @php $codingData = is_array($question->options) ? $question->options : json_decode($question->options, true); @endphp
                                <div class="coding-preview-box mt-3">
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @foreach($codingData['allowed_languages'] ?? [] as $lang)
                                            <span class="lang-tag">{{ strtoupper($lang) }}</span>
                                        @endforeach
                                    </div>
                                    <div class="small text-muted"><i class="fa-solid fa-vials me-1 text-primary"></i> {{ count($codingData['test_cases'] ?? []) }} Test Cases configured.</div>
                                </div>
                            @endif
                            <div class="question-meta mt-3 d-flex align-items-center gap-3">
                                @if($question->type === 'coding' && !$isCodingAddonInstalled)
                                    <span class="badge bg-danger text-white rounded-2 px-2 py-1 fs-xs border border-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i> UNSUPPORTED (ADDON MISSING)</span>
                                @else
                                    <span class="badge bg-light border text-muted rounded-2 px-2 py-1 fs-xs">{{ strtoupper(str_replace('_', ' ', $question->type)) }}</span>
                                @endif
                                @if($isRichMediaQuestion) <span class="badge bg-info text-white rounded-2 px-2 py-1 fs-xs border border-info"><i class="fa-solid fa-photo-film me-1"></i> {{ strtoupper($question->media_type) }}</span> @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-group">
                    @if($isRichMediaQuestion)
                        <button type="button" class="btn-circle edit" style="color: var(--bs-info); border-color: var(--bs-info);" onclick="openRichEditModal({{ json_encode($question) }})"><i class="fa-solid fa-pen-nib"></i></button>
                    @else
                        <button type="button" 
                                class="btn-circle edit btn-edit-question" 
                                data-id="{{ $question->id }}"
                                data-type="{{ $question->type }}"
                                data-text="{{ $question->question_text }}"
                                data-correct="{{ $question->correct_answer }}"
                                data-explanation="{{ $question->explanation }}"
                                data-options='@json($question->options)'
                                data-media-url="{{ $question->media_url ?? '' }}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    @endif
                    <form action="{{ route('admin.exams.questions.destroy', ['examId' => $exam->id, 'questionId' => $question->id]) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-circle danger confirm-action" data-confirm="true" data-title="Delete Question?"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                </div>
            </div>
        @empty
            <x-empty-state title="{{ __('questions.empty_title') }}" icon="fa-solid fa-folder-open">
                <button class="btn btn-dark btn-pill" data-bs-toggle="modal" data-bs-target="#aiQuestionModal"><i class="fa-solid fa-wand-magic-sparkles text-warning"></i> <span>{{ __('questions.btn_ai') }}</span></button>
                <button class="btn btn-premium btn-pill" data-bs-toggle="modal" data-bs-target="#createQuestionModal"><i class="fa-solid fa-plus"></i> <span>{{ __('questions.btn_add_manually') }}</span></button>
            </x-empty-state>
        @endforelse

        <div class="question-list-footer">
            <div class="results-text text-muted small">{{ __('questions.showing_results', ['first' => $questions->firstItem() ?? 0, 'last' => $questions->lastItem() ?? 0, 'total' => $questions->total()]) }}</div>
            @if($questions->hasPages()) <div class="pagination-container">@include('components.app-pagination', ['paginator' => $questions])</div> @endif
        </div>
    </div>

    @include('admin.exams.questions.partials.create-modal', ['codingAssessments' => $codingAssessments])
    @include('admin.exams.questions.partials.edit-modal', ['codingAssessments' => $codingAssessments]) 
    @include('admin.exams.questions.partials.ai-modal')
    @include('admin.exams.questions.partials.import-modal')

    @includeIf('rich-media::create')
    @includeIf('rich-media::edit')
@endsection

@push('scripts')
    <script>
        function addTestCase(containerId, data = null) {
            const container = document.getElementById(containerId);
            const index = container.querySelectorAll('.test-case-row').length;
            const inputVal = data ? data.input : '';
            const outputVal = data ? data.output : '';
            const html = `
                <div class="row g-2 mb-2 test-case-row">
                    <div class="col-md-5">
                        <input type="text" name="test_cases[${index}][input]" class="form-control shadow-none border py-2 fs-085" value="${inputVal}" placeholder="Input" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="test_cases[${index}][output]" class="form-control shadow-none border py-2 fs-085" value="${outputVal}" placeholder="Expected Output" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-white w-100 h-100 border text-danger shadow-sm" onclick="this.closest('.test-case-row').remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
    <script src="{{ asset('assets/js/admin-questions.js') }}" data-store-url="{{ route('admin.exams.questions.store', ['examId' => $exam->id]) }}" data-generate-url="{{ route('admin.exams.questions.generate', ['examId' => $exam->id]) }}"></script>
    @includeIf('rich-media::partials.scripts')
@endpush