@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/tickets.css') }}">
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.ticket_label') }} #{{ $ticket->ticket_id }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('user.tickets') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i> {{ __('frontend.back_btn') }}
        </a>
    </div>
</div>

<div class="ticket-show-card">
    <div class="ticket-detail-info border-bottom pb-3 mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <h3 class="ticket-detail-title">{{ $ticket->subject }}</h3>
            @php
                $statusColor = ($ticket->status === 'open') ? 'danger' : (
                               ($ticket->status === 'replied') ? 'info' : (
                               ($ticket->status === 'closed') ? 'success' : 'secondary'
                               ));

                $categoryKey = 'frontend.category_' . strtolower(str_replace(' ', '_', $ticket->category));
                $categoryLabel = __($categoryKey);
                if ($categoryLabel === $categoryKey) {
                    $categoryLabel = $ticket->category;
                }
                
                $userName = Auth::user()->name ?? 'U';
                $userInitials = strtoupper(implode('', array_map(function($part) { return substr($part, 0, 1); }, explode(' ', trim($userName)))));
                $userInitials = substr($userInitials, 0, 2);
                $adminShort = __('frontend.admin_short');
            @endphp
            <span class="badge bg-{{ $statusColor }} text-capitalize">
                {{ __('frontend.status_'.$ticket->status) }}
            </span>
        </div>
        <div class="mt-2 text-muted small">
            <span class="me-3"><i class="fa-solid fa-layer-group me-1"></i> {{ $categoryLabel }}</span>
            <span class="me-3">
                <i class="fa-solid fa-signal me-1"></i> 
                <span class="text-capitalize">{{ __('frontend.p_'.$ticket->priority) }}</span> {{ __('frontend.priority_suffix') }}
            </span>
            <span><i class="fa-regular fa-clock me-1"></i> {{ __('frontend.created_prefix') }} {{ $ticket->created_at->translatedFormat('M d, Y') }}</span>
        </div>
    </div>

    <div class="ticket-conversation mb-4">
        @forelse ($ticket->replies as $reply)
            @php
                $isMe = $reply->user_id === Auth::id();
                
                $messageModifier = $isMe ? 'is-me' : 'is-agent'; 
                $alignDiv = $isMe ? 'flex-row-reverse' : ''; 
                $margin = $isMe ? 'me-3' : 'ms-3';
                
                $avatarLabel = $isMe ? $userInitials : $adminShort;
                $avatarColor = $isMe ? 'bg-primary' : 'bg-dark';
                
                $author = $isMe ? Auth::user() : $reply->user;
                $authorAvatar = $author->avatar ?? null;
            @endphp
            
            <div class="d-flex mb-4 {{ $alignDiv }} ticket-message-entry ticket-message-entry-{{ $messageModifier }}">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-sm rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm ticket-avatar {{ $avatarColor }}">
                        @if($authorAvatar)
                            <img src="{{ Storage::url($authorAvatar) }}" alt="{{ $author->name }}" class="w-100 h-100 rounded-circle object-fit-cover">
                        @else
                            {{ $avatarLabel }}
                        @endif
                    </div>
                </div>

                <div class="flex-grow-1 {{ $margin }} ticket-message-wrap">
                    <div class="card shadow-sm ticket-message-card ticket-message-card-{{ $messageModifier }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong class="small">{{ $isMe ? Auth::user()->name : __('frontend.support_agent') }}</strong>
                                <small class="text-muted ticket-time">{{ $reply->created_at->translatedFormat('M d, H:i') }}</small>
                            </div>
                            <div class="text-dark ticket-message">{{ $reply->message }}</div>
                            
                            @if(!empty($reply->attachments))
                                <div class="mt-3 pt-2 border-top border-dark-subtle">
                                    <small class="fw-bold mb-2 d-block text-muted">{{ __('frontend.attachments_label') }}</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($reply->attachments as $file)
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-xs btn-white border px-2 py-1 small">
                                                <i class="fa-solid fa-paperclip me-1"></i> {{ __('frontend.view_file') }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <p>{{ __('frontend.no_messages') }}</p>
            </div>
        @endforelse
    </div>

    @if($ticket->status !== 'closed')
        <div class="card border-0 bg-light mt-4">
            <div class="card-body p-4">
                <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="message" class="form-label fw-bold text-dark">{{ __('frontend.reply_label') }}</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="{{ __('frontend.reply_placeholder') }}" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted">{{ __('frontend.attachments_optional') }}</label>
                        <input type="file" class="form-control" name="attachments[]" multiple>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fa-solid fa-paper-plane me-2"></i> {{ __('frontend.send_reply') }}
                        </button>
                        
                        <button type="submit" formaction="{{ route('user.tickets.close', $ticket->id) }}" class="btn btn-outline-danger btn-sm js-close-ticket">
                            {{ __('frontend.close_ticket') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-secondary text-center py-4 mt-4">
            <i class="fa-solid fa-lock me-2"></i> {{ __('frontend.ticket_closed_msg') }} <a href="#" data-bs-toggle="modal" data-bs-target="#createTicketModal">{{ __('frontend.open_new_link') }}</a>.
        </div>
    @endif
</div>

@include('user.tickets.partials.create-modal')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmMessage = @json(__('frontend.close_confirm'));
    
    document.querySelectorAll('.js-close-ticket').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush