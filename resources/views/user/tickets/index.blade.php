@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/tickets.css') }}">
@endpush

@section('content')

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('frontend.tickets_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.tickets_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createTicketModal">
            <i class="fa-solid fa-plus me-2"></i> {{ __('frontend.create_ticket') }}
        </button>
    </div>
</div>

<div class="ticket-list-wrapper">
    <div class="ticket-header-controls">

        <h3 class="fw-bold ticket-section-title">{{ __('frontend.my_active_tickets') }}</h3>
        
        <form method="GET" action="{{ route('user.tickets') }}" class="ticket-control-group">
            <span class="text-muted small">{{ __('frontend.filter_by') }}</span>

            <select name="status" class="form-select form-select-sm ticket-filter-select js-ticket-filter">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('frontend.status_all') }}</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('frontend.status_open') }}</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>{{ __('frontend.status_replied') }}</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('frontend.status_closed') }}</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-ticket align-middle">
            <thead>
                <tr>

                    <th class="th-id">{{ __('frontend.th_ticket_id') }}</th>
                    <th class="th-subject">{{ __('frontend.th_subject') }}</th>
                    <th class="th-priority">{{ __('frontend.th_priority') }}</th>
                    <th class="th-status">{{ __('frontend.th_status') }}</th>
                    <th class="th-updated">{{ __('frontend.th_last_updated') }}</th>
                    <th class="th-action">{{ __('frontend.th_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    @php
                        $statusClass = match ($ticket->status) {
                            'open' => 'status-open',
                            'replied' => 'status-replied',
                            default => 'status-closed',
                        };
                        
                        $priorityClass = match ($ticket->priority) {
                            'high' => 'priority-high',
                            'low' => 'priority-low',
                            default => 'priority-medium',
                        };

                        $isHighlight = $ticket->status == 'replied'; 
                        
                        $categoryKey = 'frontend.category_' . strtolower(str_replace(' ', '_', $ticket->category));
                        $categoryLabel = __($categoryKey);
                        if ($categoryLabel === $categoryKey) {
                            $categoryLabel = $ticket->category; // Fallback to raw DB text if key not found
                        }
                    @endphp
                    <tr class="{{ $isHighlight ? 'highlight' : '' }}">
                        <td>
                            <a href="{{ route('user.tickets.show', $ticket->id) }}" class="ticket-id-tag">
                                #{{ $ticket->ticket_id }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('user.tickets.show', $ticket->id) }}" class="subject-line">
                                {{ $ticket->subject }}
                            </a>
                            <div class="text-muted small">
                                {{ $categoryLabel }}
                            </div>
                        </td>
                        <td>
                            <span class="badge-priority {{ $priorityClass }} text-capitalize">
                                {{ __('frontend.p_'.$ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status {{ $statusClass }} text-capitalize">
                                {{ __('frontend.status_'.$ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('user.tickets.show', $ticket->id) }}" class="btn btn-sm btn-light">{{ __('frontend.view') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            {{ __('frontend.no_tickets') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
</div>

@include('user.tickets.partials.create-modal')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-ticket-filter').forEach(select => {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });
});
</script>
@endpush