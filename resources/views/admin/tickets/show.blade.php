@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->ticket_id)

@push('styles')
    <link href="{{ asset('assets/css/admin-tickets.css') }}" rel="stylesheet">
@endpush

@section('content')

    <!-- Header Navigation -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tickets.index') }}" class="btn-back-circle" title="{{ __('tickets.btn_back') }}">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="ticket-header-meta">
                <h4 class="fw-bold text-dark mb-0 text-truncate ticket-title-truncate">{{ $ticket->subject }}</h4>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-light text-dark border">#{{ $ticket->ticket_id }}</span>
                    <span class="text-muted small">{{ __('tickets.text_updated') }} {{ $ticket->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        
        <div>
            @if($ticket->status !== 'closed')
                <form action="{{ route('admin.tickets.close', $ticket->id) }}" method="POST">
                    @csrf 
                    <button type="submit" class="btn btn-outline-danger fw-bold border-2 btn-sm-mobile">
                        <i class="fa-solid fa-check me-2"></i> <span class="d-none d-sm-inline">{{ __('tickets.btn_resolved') }}</span><span class="d-inline d-sm-none">{{ __('tickets.btn_close') }}</span>
                    </button>
                </form>
            @else
                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fw-bold">{{ __('tickets.status_resolved') }}</span>
            @endif
        </div>
    </div>

    <!-- Main Layout -->
    <div class="ticket-layout-wrapper">
        
        <!-- 1. Conversation Area (Chat + Reply) -->
        <div class="conversation-panel">
            
            <div class="premium-card h-100">
                
                <!-- Chat History -->
                <div class="chat-stream p-4">
                    
                    {{-- Initial Ticket Description --}}
                    <div class="chat-entry">
                        <div class="chat-avatar">
                            <div class="initials bg-initials-user">{{ substr($ticket->user->name, 0, 2) }}</div>
                        </div>
                        <div class="chat-content">
                            <div class="chat-meta">
                                {{ $ticket->user->name }} &bull; {{ $ticket->created_at->format('M d, h:i A') }}
                            </div>
                            <div class="chat-bubble">
                                {!! nl2br(e($ticket->subject)) !!}
                            </div>
                        </div>
                    </div>

                    @foreach($ticket->replies as $reply)
                        @php $isAdmin = $reply->user_id === auth()->id(); @endphp
                        <div class="chat-entry {{ $isAdmin ? 'admin-reply' : '' }}">
                            <div class="chat-avatar">
                                @if($isAdmin)
                                    <div class="initials bg-initials-admin">{{ __('tickets.text_me') }}</div>
                                @else
                                    <div class="initials bg-initials-user">{{ substr($reply->user->name, 0, 2) }}</div>
                                @endif
                            </div>
                            <div class="chat-content">
                                <div class="chat-meta">
                                    {{ $isAdmin ? __('tickets.text_you') : $reply->user->name }} &bull; {{ $reply->created_at->format('M d, h:i A') }}
                                </div>
                                <div class="chat-bubble">
                                    {!! nl2br(e($reply->message)) !!}
                                    
                                    @if($reply->attachments && is_array($reply->attachments) && count($reply->attachments) > 0)
                                        <div class="mt-2 file-attachments">
                                            @foreach($reply->attachments as $file)
                                                <a href="{{ asset('storage/'.$file) }}" target="_blank" class="attachment-chip">
                                                    <i class="fa-solid fa-file-arrow-down"></i> {{ __('tickets.text_attachment') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Area -->
                @if($ticket->status !== 'closed')
                    <div class="reply-section border-top bg-light-subtle p-4 rounded-bottom">
                        <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" class="form-control border-0 shadow-none bg-white p-3 rounded-3 no-resize" rows="3" placeholder="{{ __('tickets.placeholder_reply') }}" required></textarea>
                            </div>
                            
                            {{-- Container for displaying file names --}}
                            <div id="file-names-container" class="mb-2"></div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="file-upload">
                                    <label for="attachment" class="btn btn-sm btn-white border text-muted shadow-sm">
                                        <i class="fa-solid fa-paperclip me-1"></i> {{ __('tickets.btn_attach') }}
                                    </label>
                                    <input type="file" name="attachments[]" id="attachment" class="d-none" multiple>
                                </div>
                                <button type="submit" class="btn btn-premium px-4 rounded-pill">
                                    {{ __('tickets.btn_send') }} <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 border-top bg-light text-center text-muted rounded-bottom">
                        <i class="fa-solid fa-lock me-2"></i> {{ __('tickets.text_conversation_closed') }}
                    </div>
                @endif

            </div>
        </div>

        <!-- 2. Sidebar Context (Details) -->
        <div class="context-sidebar">
            <div class="info-card">
                <h6 class="sidebar-heading">{{ __('tickets.label_details') }}</h6>
                
                <div class="info-group">
                    <div class="info-label">{{ __('tickets.label_status') }}</div>
                    <div class="info-value">
                        @php
                            $statusColor = match($ticket->status) {
                                'open' => 'bg-danger',
                                'replied' => 'bg-warning',
                                'closed' => 'bg-success',
                                default => 'bg-secondary'
                            };
                            $statusKey = 'tickets.status_' . strtolower($ticket->status);
                            $statusLabel = \Lang::has($statusKey) ? __($statusKey) : ucfirst($ticket->status);
                        @endphp
                        <span class="status-dot {{ $statusColor }}"></span>
                        {{ $statusLabel }}
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label">{{ __('tickets.label_priority') }}</div>
                    @php
                        $prioKey = 'tickets.prio_' . strtolower($ticket->priority);
                        $prioLabel = \Lang::has($prioKey) ? __($prioKey) : ucfirst($ticket->priority);
                    @endphp
                    <div class="info-value text-{{ $ticket->priority == 'high' ? 'danger' : 'dark' }}">
                        <i class="fa-solid fa-flag me-1 opacity-50"></i> {{ $prioLabel }}
                    </div>
                </div>

                <hr class="my-4 border-light">

                <h6 class="sidebar-heading">{{ __('tickets.label_customer') }}</h6>
                <div class="d-flex align-items-center gap-3 mt-3">
                    <div class="chat-avatar avatar-38">
                        <div class="initials bg-initials-user fs-08">{{ substr($ticket->user->name, 0, 2) }}</div>
                    </div>
                    <div class="lh-13">
                        <div class="fw-bold text-dark">{{ $ticket->user->name }}</div>
                        <a href="mailto:{{ $ticket->user->email }}" class="text-muted small text-decoration-none">{{ $ticket->user->email }}</a>
                    </div>
                </div>
                
                <div class="d-grid mt-3">
                    <a href="{{ route('admin.users.show', $ticket->user_id) }}" class="btn btn-sm btn-white w-100">
                        {{ __('tickets.btn_view_profile') }}
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-tickets.js') }}"></script>
@endpush