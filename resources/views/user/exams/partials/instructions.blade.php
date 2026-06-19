@php
    // Logic to detect if exam is upcoming
    $isUpcoming = $isUpcoming ?? (isset($exam->start_date) && \Carbon\Carbon::parse($exam->start_date)->isFuture());

    // Helper to fetch text with priority: DB Settings > Language File
    $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();
    $getText = function($key, $defaultLangKey) use ($settings) {
        return !empty($settings[$key]) ? $settings[$key] : __($defaultLangKey);
    };
@endphp

<div class="exam-instructions-screen">
    <div class="zi-card instructions-card">
        
        <h1 class="instructions-header">
            {{ $getText('instr_h_title', 'frontend.instructions_header') }}
        </h1>
        <p class="instructions-subtitle">
            @php
                $subRaw = $getText('instr_h_subtitle', 'frontend.instructions_subtitle');
                $subParsed = str_replace(':title', '<strong>' . $exam->title . '</strong>', $subRaw);
            @endphp
            {!! $subParsed !!}
        </p>

        @if($isUpcoming)
            <div class="alert alert-warning d-flex align-items-start gap-3 p-4 mb-4 border-warning shadow-sm rounded-3">
                {{-- Removed Clock Icon/Symbol --}}
                <div>
                    <h5 class="alert-heading fw-bold mb-1">{{ __('frontend.upcoming_exam_title') }}</h5>
                    <p class="mb-0">
                        {{ __('frontend.upcoming_exam_msg') }} 
                        <strong>{{ \Carbon\Carbon::parse($exam->start_date)->translatedFormat('l, M d, Y \a\t h:i A') }}</strong>.
                        <br>
                        {{ __('frontend.upcoming_exam_wait') }}
                    </p>
                </div>
            </div>
        @else
            <div class="instructions-content">
                
                {{-- PRIORITY 1: Custom Exam Instructions (HTML Editor) --}}
                @if(!empty($exam->instructions))
                    <div class="custom-exam-instructions">
                        {!! $exam->instructions !!}
                    </div>
                
                {{-- PRIORITY 2: Global Settings / Language Default --}}
                @else
                    {{-- Instruction Block 1 (Answering & Saving) --}}
                    <div class="instruction-section">
                        <h3 class="instruction-title">{{ $getText('instr_1_title', 'frontend.instruction_1_title') }}</h3>
                        <ul>
                            {{-- Removed Checkmark Symbol --}}
                            <li>{{ $getText('instr_1_text', 'frontend.instruction_1_text') }}</li>
                        </ul>
                    </div>
                    
                    {{-- Instruction Block 2 (Navigation & Review) --}}
                    <div class="instruction-section">
                        <h3 class="instruction-title">{{ $getText('instr_2_title', 'frontend.instruction_2_title') }}</h3>
                        <ul>
                            {{-- Removed Arrow Symbol --}}
                            <li>{{ $getText('instr_2_text', 'frontend.instruction_2_text') }}</li>
                        </ul>
                    </div>
                    
                    {{-- Instruction Block 3 (Time Limit & Submission) --}}
                    <div class="instruction-section">
                        <h3 class="instruction-title">{{ $getText('instr_3_title', 'frontend.instruction_3_title') }}</h3>
                        <ul>
                            <li>
                                {{-- Removed Timer Symbol --}}
                                @php
                                    $text3 = $getText('instr_3_text', 'frontend.instruction_3_text');
                                    $text3 = str_replace(':minutes', $exam->duration_minutes, $text3);
                                @endphp
                                {{ $text3 }}
                            </li>
                        </ul>
                    </div>
                    
                    {{-- Instruction Block 4 (Technical Safety) --}}
                    <div class="instruction-section">
                        <h3 class="instruction-title">{{ $getText('instr_4_title', 'frontend.instruction_4_title') }}</h3>
                        <ul>
                            {{-- Removed Globe Symbol --}}
                            <li>{{ $getText('instr_4_text', 'frontend.instruction_4_text') }}</li>
                        </ul>
                    </div>
                @endif
                
            </div>
        @endif
        
        <form action="{{ route('exam.start', $exam) }}" method="POST" class="instructions-footer">
            @csrf
            
            @if(!$isUpcoming)
                <div class="form-check">
                    <input class="form-check-input cursor-pointer" type="checkbox" id="instructionsAgree" required>
                    <label class="form-check-label user-select-none cursor-pointer" for="instructionsAgree">
                        {{ __('frontend.agree_terms') }}
                    </label>
                </div>
            @endif
            
            <div class="instructions-buttons-group">
                <a href="{{ route('my.exams') }}" class="btn-primary-action btn-ghost">
                    {{-- Removed Left Arrow Symbol --}}
                    {{ __('frontend.back_to_exams') }}
                </a>
                
                @if($isUpcoming)
                    <button type="button" class="btn-primary-action btn-green is-disabled" disabled>
                        {{-- Removed Lock Symbol --}}
                        {{ __('frontend.starts') }} {{ \Carbon\Carbon::parse($exam->start_date)->diffForHumans() }}
                    </button>
                @else
                    <button type="submit" class="btn-primary-action btn-green" id="start-exam-button" disabled>
                        {{ __('frontend.start_exam_btn') }}
                    </button>
                @endif
            </div>
        </form>
        
    </div>
</div>

{{-- Load external JS for event binding (STEP 2 from previous instruction set) --}}
<script src="{{ asset('assets/js/user/exam-instructions.js') }}"></script>