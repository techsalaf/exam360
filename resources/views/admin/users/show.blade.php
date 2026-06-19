@extends('layouts.admin')

@section('title', __('users.title_show', ['name' => $user->name]))

@push('styles') 
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-users.css') }}" rel="stylesheet"> 
@endpush

@section('content')

<div class="user-show-header d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        
        <a href="{{ route('admin.users.index') }}" class="btn-back-circle" title="{{ __('users.btn_back') }}">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold text-dark mb-0">{{ $user->name }}</h4>
                
                @php
                    $roleName = $user->getRoleNames()->first() ?? 'Student';
                    $roleBadgeClass = match($roleName) {
                        'Super Admin' => 'bg-dark text-white',
                        'Instructor' => 'bg-primary bg-opacity-10 text-primary',
                        default => 'bg-secondary bg-opacity-10 text-secondary'
                    };
                @endphp
                
                <span class="badge rounded-pill {{ $roleBadgeClass }} border px-2 py-1 role-badge">
                    {{ ucfirst($roleName) }}
                </span>
            </div>
            
            <div class="d-flex align-items-center gap-2 text-muted small mt-1">
                <span>User ID: #{{ $user->id }}</span>
                <span class="mx-1">•</span>
                <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
    
    <div class="action-buttons d-flex gap-2">
        <button class="btn btn-white border rounded-pill px-3" data-bs-toggle="offcanvas" data-bs-target="#loginHistoryOffcanvas">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> {{ __('users.btn_logins') }}
        </button>

        <a href="{{ route('admin.users.login', $user->id) }}" class="btn btn-white border rounded-pill px-3">
            <i class="fa-solid fa-right-to-bracket me-1"></i> {{ __('users.btn_login_as') }}
        </a>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-3">
        <div class="console-card text-center pb-4">
            <div class="console-body">
                
                <div class="mb-3 position-relative d-inline-block">
                    @if($user->avatar_url)
                        <img src="{{ asset($user->avatar_url) }}" alt="{{ $user->name }}" class="user-avatar-lg">
                    @else
                        <div class="user-avatar-lg user-avatar-initials">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                    <span class="avatar-status-dot bg-{{ $user->is_banned ? 'danger' : 'success' }}"></span>
                </div>
                
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                    <span class="badge rounded-pill bg-{{ $user->is_banned ? 'danger' : 'success' }} bg-opacity-10 text-{{ $user->is_banned ? 'danger' : 'success' }} px-3 py-2">
                        {{ $user->is_banned ? __('users.status_banned') : __('users.status_active') }}
                    </span>

                    @if(isset($openTicketsCount) && $openTicketsCount > 0)
                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2" title="User has open support tickets">
                            <i class="fa-solid fa-ticket me-1"></i> {{ trans_choice('users.status_open_tickets', $openTicketsCount) }}
                        </span>
                    @endif
                </div>

                <hr class="text-muted opacity-25 my-4">

                <div class="d-flex flex-column gap-3 text-start">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-muted text-uppercase text-label-small-bold">{{ __('users.sect_email_status') }}</span>
                        <form action="{{ route('admin.users.verify.email', $user->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="verification-toggle {{ $user->email_verified_at ? 'verified' : 'unverified' }}"
                                    title="Click to change" data-bs-toggle="tooltip">
                                <i class="fa-solid {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-circle-xmark' }} me-1"></i>
                                {{ $user->email_verified_at ? __('users.status_verified') : __('users.status_unverified') }}
                            </button>
                        </form>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-muted text-uppercase text-label-small-bold">{{ __('users.sect_mobile_status') }}</span>
                        <form action="{{ route('admin.users.verify.mobile', $user->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="verification-toggle {{ $user->mobile_verified_at ? 'verified' : 'unverified' }}"
                                    title="Click to change" data-bs-toggle="tooltip">
                                <i class="fa-solid {{ $user->mobile_verified_at ? 'fa-check-circle' : 'fa-circle-xmark' }} me-1"></i>
                                {{ $user->mobile_verified_at ? __('users.status_verified') : __('users.status_unverified') }}
                            </button>
                        </form>
                    </div>
                </div>
                
                <hr class="text-muted opacity-25 my-4">
                
                <div class="row g-2 text-center">
                    <div class="col-12">
                        <div class="p-2 bg-light rounded-3 border">
                            <span class="d-block text-muted small mb-1 text-uppercase text-label-xs">{{ __('users.sect_current_plan') }}</span>
                            <span class="d-block fw-bold text-dark">{{ $user->plan->name ?? 'Free Plan' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        
        <x-kpi-grid :stats="$kpis" class="mb-4" />

        <div class="console-card mb-4">
            <div class="console-header">
                <h5 class="console-title">{{ __('users.sect_profile') }}</h5>
                <button type="submit" form="updateUserForm" class="btn btn-premium btn-sm rounded-pill">
                    <i class="fa-solid fa-save me-1"></i> {{ __('users.btn_save') }}
                </button>
            </div>
            <div class="console-body">
                <form id="updateUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-premium required">{{ __('users.label_name') }}</label>
                            <input type="text" name="name" class="form-control-premium" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium required">{{ __('users.label_email') }}</label>
                            <input type="email" name="email" class="form-control-premium" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('users.label_mobile') }}</label>
                            <input type="text" name="mobile" class="form-control-premium" value="{{ $user->mobile }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('users.label_country') }}</label>
                            <input type="text" name="country" class="form-control-premium" value="{{ $user->country }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-premium">{{ __('users.label_address') }}</label>
                            <input type="text" name="address" class="form-control-premium" value="{{ $user->address }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">{{ __('users.label_city') }}</label>
                            <input type="text" name="city" class="form-control-premium" value="{{ $user->city }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">{{ __('users.label_state') }}</label>
                            <input type="text" name="state" class="form-control-premium" value="{{ $user->state }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">{{ __('users.label_zip') }}</label>
                            <input type="text" name="zip" class="form-control-premium" value="{{ $user->zip }}">
                        </div>

                        @if(!empty($fieldDefinitions))
                            <div class="col-12">
                                <hr class="my-2 opacity-25">
                                <h6 class="small fw-bold text-muted text-uppercase mb-3">{{ __('branding.registration.dynamic_fields') }}</h6>
                            </div>
                            @foreach($fieldDefinitions as $field)
                                @php 
                                    $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
                                    $currentValue = $user->custom_fields[$field['label']] ?? '';
                                @endphp
                                <div class="col-md-6">
                                    <label class="form-label-premium @if($field['required'] == '1') required @endif">{{ $field['label'] }}</label>
                                    
                                    @if($field['type'] === 'select')
                                        <select name="{{ $fieldKey }}" class="form-select form-control-premium" @if($field['required'] == '1') required @endif>
                                            <option value="">{{ __('auth.select_option') }}</option>
                                            @if(is_array($field['options']))
                                                @foreach($field['options'] as $opt)
                                                    <option value="{{ trim($opt) }}" {{ $currentValue == trim($opt) ? 'selected' : '' }}>{{ trim($opt) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @elseif($field['type'] === 'attachment')
                                        <input type="file" name="{{ $fieldKey }}" class="form-control form-control-premium" @if($field['required'] == '1' && empty($currentValue)) required @endif>
                                        @if(!empty($currentValue))
                                            <div class="mt-1">
                                                <a href="{{ Storage::url($currentValue) }}" target="_blank" class="small text-primary text-decoration-none">
                                                    <i class="fa-solid fa-file-arrow-down me-1"></i> {{ __('users.btn_download') }}
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <input type="text" name="{{ $fieldKey }}" class="form-control-premium" value="{{ $currentValue }}" @if($field['required'] == '1') required @endif>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        
                        @if(isset($allRoles))
                        <div class="col-12">
                            <hr class="my-2 opacity-25">
                            <label class="form-label-premium required">{{ __('users.label_role') }}</label>
                            <select name="role_name" class="form-select form-control-premium">
                                @foreach($allRoles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="console-card">
            <div class="console-header">
                <h5 class="console-title">{{ __('users.sect_transactions') }}</h5>
                <a href="{{ route('admin.payments.index', ['search' => $user->email]) }}" class="text-muted small text-decoration-none">{{ __('users.btn_view_details') }}</a>
            </div>
            <div class="console-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted small text-uppercase fw-bold">{{ __('users.col_date') }}</th>
                                <th class="py-3 text-muted small text-uppercase fw-bold">{{ __('users.col_plan') }}</th>
                                <th class="py-3 text-muted small text-uppercase fw-bold">{{ __('users.col_amount') }}</th>
                                <th class="py-3 text-muted small text-uppercase fw-bold text-end pe-4">{{ __('users.col_status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($paymentHistory as $payment)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="fw-bold text-dark">{{ $payment->plan_name ?? 'N/A' }}</td>
                                    <td class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</td>
                                    <td class="text-end pe-4">
                                        @if($payment->status == 'success')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('users.status_paid') }}</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">{{ __('users.status_pending') }}</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('users.status_failed') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        {{ __('users.empty_transactions') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-3">
        
        <div class="console-card mb-4">
            <div class="console-header align-items-center">
                <h5 class="console-title">{{ __('users.sect_notifications') }}</h5>
                <div class="d-flex gap-2">
                    @if($notifications->count() > 0)
                        <form id="clearNotificationsForm" action="{{ route('admin.users.notifications.clear', $user->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm text-danger border-0 p-0 me-2 btn-clear-all" title="{{ __('users.btn_clear_all') }}">
                                <small>{{ __('users.btn_clear_all') }}</small>
                            </button>
                        </form>
                    @endif
                    <button class="btn btn-sm btn-light border rounded-circle" data-bs-toggle="modal" data-bs-target="#sendNotificationModal" title="{{ __('users.btn_add_new') }}">
                        <i class="fa-solid fa-paper-plane text-primary"></i>
                    </button>
                </div>
            </div>
            
            <div class="console-body p-0 notification-area">
                <div class="notification-timeline px-4 pb-4">
                    @forelse($notifications as $notification)
                        <div class="timeline-item {{ $notification->is_important ? 'important' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="timeline-date">{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                                <form id="delete-notification-form-{{ $notification->id }}" action="{{ route('admin.users.notifications.delete', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-link p-0 text-muted btn-delete-notification btn-icon-xs" data-id="{{ $notification->id }}" title="Delete">
                                        <i class="fa-solid fa-trash-can hover-danger"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold mb-1 fs-085">{{ $notification->subject }}</h6>
                                <p class="small text-muted mb-0 text-truncate">{{ $notification->message }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="d-flex flex-column align-items-center justify-content-center pt-5 pb-4 text-muted small h-100">
                            <i class="fa-regular fa-bell-slash fa-2x mb-2 opacity-25"></i>
                            <span>{{ __('users.empty_notifications') }}</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="console-card mb-4">
            <div class="console-header align-items-center">
                <h5 class="console-title">{{ __('users.sect_support') }}</h5>
            </div>
            <div class="console-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($supportTickets as $ticket)
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="list-group-item list-group-item-action p-3 border-start-0 border-end-0">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <span class="badge bg-light text-dark border">#{{ $ticket->ticket_id }}</span>
                                <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 fw-bold text-dark small text-truncate ticket-truncate">{{ $ticket->subject }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                @php
                                    $statusClass = match($ticket->status) {
                                        'open' => 'text-danger',
                                        'replied' => 'text-warning',
                                        'closed' => 'text-success',
                                        default => 'text-muted'
                                    };
                                @endphp
                                <small class="fw-bold {{ $statusClass }} text-uppercase text-label-xs">
                                    {{ ucfirst($ticket->status) }}
                                </small>
                                <small class="text-muted">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </small>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted small">
                            <i class="fa-solid fa-headset fa-lg mb-2 opacity-50"></i>
                            <p class="mb-0">{{ __('users.empty_support') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="console-card {{ $user->is_banned ? 'border-success' : 'border-danger' }} card-thick-top">
            <div class="console-header {{ $user->is_banned ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                <h5 class="console-title {{ $user->is_banned ? 'text-success' : 'text-danger' }}">{{ __('users.sect_actions') }}</h5>
            </div>
            <div class="console-body">
                <form action="{{ route('admin.users.toggle.ban', $user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn w-100 {{ $user->is_banned ? 'btn-outline-success' : 'btn-outline-danger' }} confirm-action py-2"
                            data-title="{{ $user->is_banned ? __('users.confirm_lift_ban_title') : __('users.confirm_ban_title') }}"
                            data-text="{{ __('users.confirm_ban_text') }}">
                        <i class="fa-solid {{ $user->is_banned ? 'fa-unlock' : 'fa-ban' }} me-2"></i> {{ $user->is_banned ? __('users.action_unban') : __('users.action_ban') }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<x-modal 
    id="sendNotificationModal" 
    :title="__('users.title_notifications')" 
    action="{{ route('admin.users.notify', $user->id) }}"
    :submit-text="__('users.btn_send_now')"
>
    <div class="mb-3">
        <label class="form-label-premium required">{{ __('users.label_subject') }}</label>
        <input type="text" name="subject" class="form-control-premium" required>
    </div>
    <div class="mb-3">
        <label class="form-label-premium required">{{ __('users.label_message') }}</label>
        <textarea name="message" class="form-control-premium" rows="4" required></textarea>
    </div>
</x-modal>

<div class="offcanvas offcanvas-end" tabindex="-1" id="loginHistoryOffcanvas" aria-labelledby="loginHistoryLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="loginHistoryLabel">{{ __('users.sect_login_history') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
            @forelse($loginLogs as $log)
                <div class="list-group-item p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark">{{ $log->ip_address }}</span>
                        <span class="badge bg-light text-dark border">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="small text-muted">
                        <i class="fa-solid fa-location-dot me-1"></i> {{ $log->location ?? 'Unknown' }} <br>
                        <i class="fa-solid fa-desktop me-1"></i> {{ $log->browser }} / {{ $log->os }}
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-muted small">
                    <i class="fa-solid fa-clock-rotate-left fa-2x mb-3 opacity-50"></i>
                    <p>{{ __('users.empty_logins') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-users.js') }}"></script>
@endpush