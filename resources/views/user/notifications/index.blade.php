@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/notifications.css') }}">
@endpush

@section('content')

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('frontend.notifications_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.notifications_subtitle') }}</p>
    </div>
    @if($notifications->count() > 0)
    <a href="{{ route('user.notifications.markAllRead') }}" class="btn-mark-read">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> 
        {{ __('frontend.mark_all_read') }}
    </a>
    @endif
</div>

<div class="notification-card">
    
    @forelse($notifications as $note)
        @php
            $data = $note->data;
            $isRead = $note->read_at !== null;
            $type = $data['type'] ?? 'info';
            $title = $data['title'] ?? __('frontend.notification');
            $message = $data['message'] ?? '';
            $url = $data['url'] ?? $data['action_url'] ?? null;
        @endphp

        <div class="notification-item {{ $isRead ? 'read' : 'unread' }}">
            
            <div class="notification-icon-wrapper">
                @php
                    $iconClass = 'icon-soft-green';
                    if($type == 'success' || $type == 'payment') { $iconClass = 'icon-success'; }
                    elseif($type == 'warning' || $type == 'ticket') { $iconClass = 'icon-warning'; }
                    elseif($type == 'danger' || $type == 'live') { $iconClass = 'icon-danger'; }
                @endphp
                
                <div class="notification-icon-circle {{ $iconClass }}">
                    @if($type == 'success' || $type == 'payment')
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @elseif($type == 'warning' || $type == 'ticket')
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    @elseif($type == 'danger' || $type == 'live')
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    @endif
                </div>
            </div>
            
            <div class="notification-content">
                <div class="notification-header">
                    <h6 class="notification-title">{{ $title }}</h6>
                    <span class="notification-time">{{ $note->created_at->translatedFormat('M d, h:i A') }}</span>
                </div>
                
                <p class="notification-message">{{ $message }}</p>
                
                @if($url && $url !== '#')
                    <a href="{{ $url }}" class="link-details">
                        {{ __('frontend.view_details') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                @endif
            </div>

            <form action="{{ route('user.notifications.delete', $note->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn-delete-notification js-confirm-delete" 
                        title="{{ __('frontend.remove_notification') }}"
                        data-confirm="{{ __('frontend.confirm_delete') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </form>
        </div>
    @empty
        <div class="notification-empty">
            <div class="empty-icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><line x1="13.73" y1="21" x2="10.27" y2="21"></line><line x1="2" y1="2" x2="22" y2="22"></line></svg>
            </div>
            <h6 class="fw-bold mb-1 notification-empty-title">{{ __('frontend.no_notifications') }}</h6>
            <p class="text-muted small mb-0">{{ __('frontend.no_notifications_desc') }}</p>
        </div>
    @endforelse

    @if($notifications->hasPages())
        <div class="notifications-pagination">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-confirm-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush