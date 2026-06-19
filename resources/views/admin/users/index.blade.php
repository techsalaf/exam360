@extends('layouts.admin')

@section('title', $pageTitle)

@push('styles')
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-users.css') }}" rel="stylesheet"> 
@endpush

@section('content')

    <div class="user-wrapper">
        <div class="mobile-filter-bar">
            <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#userFilterOffcanvas">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-dark"></i>
                    <span class="fw-bold text-dark">{{ __('users.btn_filter') }}</span>
                </div>
                <i class="fa-solid fa-chevron-right text-muted small"></i>
            </button>
        </div>

        <div class="desktop-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $pageTitle }}</h4>
                <p class="text-muted small mb-0">{{ __('users.subtitle_list') }}</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <form action="{{ route('admin.users.index') }}" method="GET" class="search-box">
                    <input type="hidden" name="status" value="{{ $filterStatus }}">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="{{ __('users.label_search') }}"
                           value="{{ request('search') }}"
                           autocomplete="off">
                </form>

                <button class="btn-primary-pill" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fa-solid fa-user-plus"></i> {{ __('users.btn_add_new') }}
                </button>
            </div>
        </div>
        
        <div class="mb-4">
            <x-kpi-grid :stats="$kpis" />
        </div>

        <div class="user-list-card">
            <div class="list-header">
                <div class="col-user">{{ __('users.col_user') }}</div>
                <div class="col-contact">{{ __('users.col_contact') }}</div>
                <div class="col-country">{{ __('users.col_country') }}</div>
                <div class="col-joined">{{ __('users.col_joined') }}</div>
                <div class="col-action">{{ __('users.col_action') }}</div>
            </div>

            @forelse($users as $user)
                <div class="list-item">
                    <div class="col-user">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="user-name mb-0">{{ $user->name }}</span>
                            @if($user->plan)
                                <span class="badge badge-plan-pill">{{ $user->plan->name }}</span>
                            @endif
                        </div>
                        <span class="user-meta">@ {{ $user->username ?? Str::before($user->email, '@') }}</span>
                    </div>
                    
                    <div class="col-contact">
                        <div class="contact-line">
                            {{ $user->email }} 
                            @if(!$user->email_verified_at) 
                                <i class="fa-solid fa-circle-exclamation text-danger ms-1" title="Unverified Email" data-bs-toggle="tooltip"></i> 
                            @endif
                        </div>
                        <div class="contact-line">
                            {{ $user->mobile ?? 'N/A' }} 
                            @if($user->mobile && !$user->mobile_verified_at) 
                                <i class="fa-solid fa-triangle-exclamation text-warning ms-1" title="Unverified Mobile" data-bs-toggle="tooltip"></i> 
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-country">
                        <span class="country-text">{{ $user->country ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="col-joined">
                        <span class="date-primary">{{ $user->created_at->format('Y-m-d h:i A') }}</span>
                        <span class="date-secondary">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="col-action">
                        <a href="{{ route('admin.users.show', $user->id) }}" 
                           class="btn-action view" 
                           title="{{ __('users.btn_view_details') }}"
                           data-bs-toggle="tooltip">
                            <i class="fa-regular fa-eye"></i> 
                        </a>

                        <button type="button" 
                                class="btn-action plan btn-assign-plan" 
                                title="Assign Plan"
                                data-bs-toggle="modal" 
                                data-bs-target="#assignUserPlanModal"
                                data-user="{{ json_encode($user) }}">
                            <i class="fa-solid fa-credit-card"></i> 
                        </button>

                        <button type="button" 
                                class="btn-action edit btn-edit-user" 
                                title="{{ __('users.btn_edit') }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal"
                                data-user="{{ json_encode($user) }}">
                            <i class="fa-solid fa-pen"></i> 
                        </button>
                        
                        <form action="{{ route('admin.users.toggle.ban', $user->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" 
                                    class="btn-action {{ $user->is_banned ? 'is-banned' : 'toggle-active' }} confirm-action"
                                    data-title="{{ $user->is_banned ? __('users.confirm_lift_ban_title') : __('users.confirm_ban_title') }}"
                                    data-text="{{ __('users.confirm_ban_text') }}"
                                    title="{{ $user->is_banned ? __('users.action_unban') : __('users.action_ban') }}"
                                    data-bs-toggle="tooltip">
                                <i class="fa-solid fa-power-off"></i> 
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-users empty-icon"></i>
                    <h5 class="empty-title">{{ __('users.empty_title') }}</h5>
                    <p class="empty-desc">{{ __('users.empty_subtitle') }}</p>
                    <button class="btn-primary-pill mx-auto" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fa-solid fa-user-plus"></i> {{ __('users.btn_add_new') }}
                    </button>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
            </div>
            @if($users->hasPages())
                @include('components.app-pagination', ['paginator' => $users])
            @endif
        </div>
    </div>

    <div class="modal fade" id="assignUserPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Assign Plan: <span id="assign_plan_user_name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignPlanForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label-premium required">Select Plan</label>
                            <select name="plan_id" id="modal_assign_plan_id" class="form-control-premium" required>
                                <option value="" disabled selected>Choose a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-premium required">Billing Cycle</label>
                            <select name="billing_cycle" id="modal_assign_billing_cycle" class="form-control-premium" required>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-light border px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-pill">Confirm Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.users.partials.create-modal')
    @include('admin.users.partials.edit-modal')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin-users.js') }}"></script>
@endpush