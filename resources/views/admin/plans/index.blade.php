@extends('layouts.admin')

@section('title', __('plans.page_title'))

@push('styles')
    <link href="{{ asset('assets/css/components/admin-kpi.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components/admin-pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-plans.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/tom-select/tom-select.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div id="plansConfig" 
         data-confirm-title="{{ __('plans.default_confirm_title') }}"
         data-confirm-text="{{ __('plans.default_confirm_msg') }}"
         data-confirm-yes="{{ __('plans.confirm_yes') }}">
    </div>

    <div class="mobile-filter-bar">
        <button class="btn w-100 d-flex align-items-center justify-content-between px-3 py-2 rounded-3 shadow-sm bg-white border" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#planFilterOffcanvas">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark text-white rounded-2 d-flex align-items-center justify-content-center icon-box-28">
                    <i class="fa-solid fa-sliders icon-sm"></i>
                </div>
                <span class="fw-bold text-dark">{{ __('plans.filter_action') }}</span>
            </div>
            <i class="fa-solid fa-chevron-right text-muted"></i>
        </button>
    </div>

    <div class="desktop-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('plans.header_title') }}</h1>
            <p class="text-muted small mb-0">{{ __('plans.header_desc') }}</p>
        </div>
        
        <div class="page-header-actions">
            <form action="{{ route('admin.plans.index') }}" method="GET" class="search-filter-box">
                <i class="fa-solid fa-magnifying-glass search-filter-icon"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="search-filter-input" 
                       placeholder="{{ __('plans.search_placeholder') }}">
            </form>
            
            <button class="btn-premium rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                <i class="fa-solid fa-plus me-2"></i> {{ __('plans.btn_add_new') }}
            </button>
        </div>
    </div>
    
    <x-kpi-grid :stats="$kpis" />

    <div class="plan-list-container">
        <div class="list-header">
            <div class="col-name">{{ __('plans.th_name') }}</div>
            <div class="col-price">{{ __('plans.th_price') }}</div>
            <div class="col-limit">{{ __('plans.th_limit') }}</div>
            <div class="col-status">{{ __('plans.th_status') }}</div>
            <div class="col-action text-end">{{ __('plans.th_action') }}</div>
        </div>

        @forelse($plans as $plan)
            <div class="list-item">
                <div class="col-name">
                    <div class="fw-bold text-dark">{{ $plan->name }}</div>
                </div>
                
                <div class="col-price">
                    <div class="price-tag">{{ $currencySymbol }}{{ number_format($plan->price_monthly, 2) }} <span class="fw-normal text-muted">/{{ __('plans.mo') }}</span></div>
                    <div class="price-sub">{{ $currencySymbol }}{{ number_format($plan->price_yearly, 2) }} /{{ __('plans.yr') }}</div>
                </div>
                
                <div class="col-limit">
                    <div><i class="fa-regular fa-calendar me-1"></i> {{ __('plans.monthly') }}: <strong>{{ $plan->limit_monthly }}</strong></div>
                    <div><i class="fa-regular fa-calendar-check me-1"></i> {{ __('plans.yearly') }}: <strong>{{ $plan->limit_yearly }}</strong></div>
                </div>
                
                <div class="col-status">
                    <span class="status-badge {{ $plan->is_active ? 'status-enabled' : 'status-disabled' }}">
                        {{ $plan->is_active ? __('plans.status_enabled') : __('plans.status_disabled') }}
                    </span>
                </div>
                
                <div class="col-action">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn-circle primary" data-bs-toggle="modal" data-bs-target="#assignPlanModal{{ $plan->id }}" title="{{ __('plans.modal_assign_title') }}">
                            <i class="fa-solid fa-user-plus"></i>
                        </button>

                        <button class="btn-circle edit" data-bs-toggle="modal" data-bs-target="#editPlanModal{{ $plan->id }}" title="{{ __('plans.btn_edit') }}">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <form action="{{ route('admin.plans.toggle', $plan->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-circle toggle {{ $plan->is_active ? 'active' : '' }}">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="d-inline confirm-action">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-circle danger" data-title="{{ __('plans.delete_confirm_title') }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="assignPlanModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-bottom bg-light">
                            <h5 class="modal-title fw-bold text-dark">{{ __('plans.modal_assign_title') }}: {{ $plan->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.plans.assign', $plan->id) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">{{ __('plans.label_select_user') }}</label>
                                    <select name="user_id" class="form-select user-search-select" required>
                                        <option value="">{{ __('plans.placeholder_user') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('plans.label_billing_cycle') }}</label>
                                    <select name="billing_cycle" class="form-select" required>
                                        <option value="monthly">{{ __('plans.monthly') }}</option>
                                        <option value="yearly">{{ __('plans.yearly') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-top bg-light">
                                <button type="button" class="btn btn-light border px-4 rounded-pill" data-bs-dismiss="modal">{{ __('plans.btn_cancel') }}</button>
                                <button type="submit" class="btn btn-premium px-4 rounded-pill">{{ __('plans.btn_assign_plan') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @include('admin.plans.partials.edit-modal', ['plan' => $plan, 'categories' => $categories])
        @empty
            <div class="empty-state text-center py-5">
                <i class="fa-solid fa-tags fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('plans.empty_title') }}</h5>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            {{ __('plans.showing_results', [
                'first' => $plans->firstItem() ?? 0,
                'last' => $plans->lastItem() ?? 0,
                'total' => $plans->total()
            ]) }}
        </div>
        @if($plans->hasPages())
            <x-app-pagination :paginator="$plans" />
        @endif
    </div>

    @include('admin.plans.partials.create-modal', ['categories' => $categories])

@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/tom-select/tom-select.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.user-search-select').forEach(function(el) {
                new TomSelect(el, {
                    create: false,
                    placeholder: el.getAttribute('placeholder') || '...',
                    searchField: ['text'],
                    dropdownParent: 'body'
                });
            });

            document.querySelectorAll('.select2-categories').forEach(function(el) {
                new TomSelect(el, {
                    plugins: ['remove_button'],
                    create: false,
                    dropdownParent: 'body'
                });
            });
        });
    </script>
    <script src="{{ asset('assets/js/admin-plans.js') }}"></script>
@endpush