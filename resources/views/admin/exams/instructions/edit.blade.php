@extends('layouts.admin')

@section('title', 'Manage Instructions')

@php
    // Default instruction template defined here to keep the form clean and avoid inline HTML complexity
    $defaultInstructions = '
<div class="instruction-section">
    <h3 class="instruction-title">1. General Guidelines</h3>
    <ul>
        <li><i class="fa-solid fa-circle-check"></i> Ensure you have a stable internet connection.</li>
        <li><i class="fa-solid fa-circle-check"></i> Do not refresh the page during the exam.</li>
    </ul>
</div>

<div class="instruction-section">
    <h3 class="instruction-title">2. Time Management</h3>
    <ul>
        <li><i class="fa-solid fa-clock"></i> This exam has a strict time limit of ' . $exam->duration_minutes . ' minutes.</li>
        <li><i class="fa-solid fa-clock"></i> The timer will auto-submit when it reaches zero.</li>
    </ul>
</div>

<div class="instruction-section">
    <h3 class="instruction-title">3. Scoring</h3>
    <ul>
        <li><i class="fa-solid fa-star"></i> Each question carries equal marks.</li>
        <li><i class="fa-solid fa-ban"></i> There is no negative marking for wrong answers.</li>
    </ul>
</div>';
@endphp

@push('styles')
    <link href="{{ asset('assets/css/admin-instructions.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="instruction-page-header">
    <div>
        <h2>Exam Instructions</h2>
        <p>Customize the guidelines shown to students before they start <strong>{{ $exam->title }}</strong>.</p>
    </div>
    <a href="{{ route('admin.exams.questions', $exam->id) }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back to Questions
    </a>
</div>

<div class="instruction-card">

    <form action="{{ route('admin.exams.instructions.update', $exam->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="editor-toolbar">
            <label class="editor-label mb-0">Instruction Content</label>
            <div class="toggle-group">
                <button type="button" class="btn-toggle active" id="btn-code-mode">
                    <i class="fa-solid fa-code"></i> Code
                </button>
                <button type="button" class="btn-toggle" id="btn-visual-mode">
                    <i class="fa-solid fa-eye"></i> Visual Preview
                </button>
            </div>
        </div>

        <div class="editor-container">
            {{-- Code Mode --}}
            <textarea name="instructions" id="instruction-editor" class="custom-textarea" placeholder="Enter custom HTML instructions here...">{{ old('instructions', $exam->instructions ?? $defaultInstructions) }}</textarea>

            {{-- Visual Preview Mode --}}
            <div id="instruction-preview" class="visual-preview d-none"></div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.exams.questions', $exam->id) }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Save Instructions
            </button>
        </div>
    </form>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-instructions.js') }}"></script>
@endpush