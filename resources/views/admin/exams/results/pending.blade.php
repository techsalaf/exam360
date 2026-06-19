@extends('layouts.admin')
 
@section('title', __('results.page_title_pending'))

@push('styles')
    <link href="{{ asset('assets/css/admin-results.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('results.header_title_pending') }}</h1>
            <p class="text-muted small mb-0">{{ __('results.header_desc_pending') }}</p>
        </div>
    </div>

    <div class="result-list-container mt-4">
        
        <div class="list-header">
            <div class="col-student-flex">{{ __('results.th_student') }}</div>
            <div class="col-exam-flex">{{ __('results.th_exam') }}</div>
            <div class="col-completed-flex">{{ __('results.th_completed') }}</div>
            <div class="col-scheduled-flex">{{ __('results.th_scheduled') }}</div>
            <div class="col-action-flex text-end">{{ __('results.th_action') }}</div>
        </div>

        @forelse($results as $result)
            <div class="list-item">
                
                <div class="col-student-flex">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle">
                            {{ substr($result->user->name, 0, 1) }}
                        </div>
                        <div class="d-flex flex-column student-details">
                            <span class="fw-bold text-dark text-truncate">{{ $result->user->name }}</span>
                            <span class="small text-muted text-truncate">{{ $result->user->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-exam-flex">
                    <span class="fw-medium text-secondary">{{ $result->exam->title }}</span>
                </div>

                <div class="col-completed-flex text-muted small">
                    {{ $result->created_at->format('M d, Y') }}<br>
                    <span class="opacity-75">{{ $result->created_at->format('h:i A') }}</span>
                </div>

                <div class="col-scheduled-flex text-dark fw-bold">
                    @if($result->exam->result_date)
                        {{ $result->exam->result_date->format('M d, Y h:i A') }}
                    @else
                        <span class="text-danger small">{{ __('results.not_scheduled') }}</span>
                    @endif
                </div>

                <div class="col-action-flex text-end">
                    <div class="d-flex gap-2 justify-content-end align-items-center">
                        <a href="{{ route('admin.exams.results.show', $result->id) }}" 
                           class="btn-circle view" 
                           title="{{ __('results.btn_view_report') }}"
                           data-bs-toggle="tooltip">
                           <i class="fa-regular fa-file-lines"></i>
                        </a>

                        <form action="{{ route('admin.exams.results.publish', $result->id) }}" method="POST" class="d-inline publish-form-sa">
                            @csrf
                            <button type="submit" 
                                    class="btn-publish-final"
                                    data-title="{{ __('results.publish_confirm_title') }}"
                                    data-text="{{ __('results.publish_confirm_msg') }}">
                                <i class="fa-solid fa-bullhorn me-1"></i> {{ __('results.btn_publish_now') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="d-flex align-items-center justify-content-center min-h-400">
                <x-empty-state 
                    title="{{ __('results.empty_pending_title') }}"
                    subtitle="{{ __('results.empty_pending_subtitle') }}"
                    icon="fa-regular fa-folder-open" 
                />
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            {{ __('results.showing_results', ['first' => $results->firstItem() ?? 0, 'last' => $results->lastItem() ?? 0, 'total' => $results->total()]) }}
        </div>
        
        @if($results->hasPages())
            <x-app-pagination :paginator="$results" />
        @endif
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-results-pending.js') }}"></script>
@endpush