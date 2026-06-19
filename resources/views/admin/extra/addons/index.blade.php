@extends('layouts.admin')

@section('title', 'Addons Manager')

@push('styles')
    <link href="{{ asset('assets/css/admin-addons.css') }}" rel="stylesheet">
@endpush

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <div id="addonConfig" 
         data-confirm-title="{{ __('Delete Addon?') }}"
         data-confirm-text="{{ __('Are you sure you want to remove this addon? This action cannot be undone.') }}"
         data-confirm-yes="{{ __('Yes, Delete it') }}"
         data-confirm-cancel="{{ __('Cancel') }}"
         class="d-none">
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 page-header-flex">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">{{ __('Addons Manager') }}</h1>
            <p class="text-muted small mb-0">{{ __('Extend functionality with modules and plugins.') }}</p>
        </div>
        
        <button class="btn-green-pill" data-bs-toggle="modal" data-bs-target="#installModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            {{ __('Install Addon') }}
        </button>
    </div>

    <div class="row g-4">
        @forelse($addons as $addon)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card shadow-sm card-rounded h-100 addon-card">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="addon-icon">
                                @if($addon->image)
                                    <img src="{{ asset($addon->image) }}" alt="icon" onerror="this.onerror=null;this.src='{{ asset('assets/images/addon-default.png') }}';">
                                @else
                                    <i class="{{ $addon->icon ?? 'fa-solid fa-puzzle-piece' }}"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $addon->name }}</h5>
                                <span class="badge bg-light text-dark border">v{{ $addon->version }}</span>
                            </div>
                        </div>
                        
                        <p class="text-muted small mb-4 flex-grow-1">
                            {{ Str::limit($addon->description, 90) }}
                        </p>

                        <div class="addon-footer pt-3 border-top mt-auto d-flex align-items-center justify-content-between">
                            
                            <div>
                                <span class="status-badge {{ $addon->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $addon->is_active ? __('ENABLED') : __('DISABLED') }}
                                </span>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check form-switch mb-0" 
                                     @if($addon->is_locked) 
                                         data-bs-toggle="tooltip" 
                                         title="Disable the active payment gateways first in Settings > Billing > Gateways"
                                     @endif
                                >
                                    <input class="form-check-input js-addon-toggle" type="checkbox" 
                                           data-id="{{ $addon->id }}" 
                                           {{ $addon->is_active ? 'checked' : '' }} 
                                           style="cursor: pointer;"
                                           @if($addon->is_locked) disabled @endif>
                                </div>

                                <form action="{{ route('admin.extra.addons.destroy', $addon->id) }}" method="POST" class="d-inline delete-addon-form">
                                    @csrf
                                    @method('DELETE')
                                    @if($addon->is_locked)
                                        <span data-bs-toggle="tooltip" title="Cannot uninstall while its payment gateways are active.">
                                            <button type="button" class="btn-icon-pill delete delete-addon-btn" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </span>
                                    @else
                                        <button type="submit" class="btn-icon-pill delete delete-addon-btn" title="{{ __('Delete') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm card-rounded">
                    <div class="card-body p-5 text-center">
                        <div class="text-muted opacity-50 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                        <h5 class="fw-bold text-dark">{{ __('No addons installed') }}</h5>
                        <p class="text-muted mb-0">{{ __('Upload a .zip file to get started.') }}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="modal fade" id="installModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ __('Upload Addon') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.extra.addons.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="addon-upload-area mb-4">
                            <input type="file" name="addon_zip" id="addon_zip" class="addon-file-input" required accept=".zip">
                            <div class="upload-content text-center">
                                <div class="upload-icon-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 mt-3">{{ __('Click to Upload') }}</h6>
                                <p class="text-muted small mb-0">{{ __('Select .zip file (Max 10MB)') }}</p>
                            </div>
                        </div>

                        <div class="modal-info-alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span class="small">{{ __('Ensure the zip file contains a valid manifest.json.') }}</span>
                        </div>

                        <button type="submit" class="btn-green-pill w-100 justify-content-center">
                            {{ __('Install Now') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    <script src="{{ asset('assets/js/admin-addons.js') }}"></script>
    <script src="{{ asset('assets/js/admin-addons-delete.js') }}"></script>
@endpush