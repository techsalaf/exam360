@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">
{{-- NOTE: If the dedicated maintenance CSS section above is in a separate file, load it here --}}
@endpush

<div class="settings-content">
    <form action="{{ route('admin.settings.system.group.update') }}" method="POST" enctype="multipart/form-data" id="maintenanceForm">
        @csrf
        
        <input type="hidden" name="setting_group_key" value="maintenance">
        <input type="hidden" name="delete_maintenance_image" value="0" data-delete-field>

        <div class="setting-card">
            
            <div class="setting-header">
                <h3 class="setting-title">{{ __('system.maintenance.title') }}</h3>
                <p class="setting-desc">{{ __('system.maintenance.desc') }}</p>
            </div>

            {{-- 
                BLOCK 1: Maintenance Status Control
                Uses semantic classes for styling instead of inline PHP logic 
            --}}
            <div class="border rounded-3 p-4 mb-4 maintenance-status-card {{ $isActive ? 'maintenance--active' : 'maintenance--inactive' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm maintenance-icon-box">
                            <i class="fa-solid fa-power-off fs-4 status-icon"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">{{ __('system.maintenance.system_status') }}</h5>
                            @if($isActive)
                                <span class="badge bg-danger rounded-pill px-3">{{ __('system.maintenance.offline') }}</span>
                            @else
                                {{-- Using the standard success class, as background color was defined inline originally --}}
                                <span class="badge bg-success rounded-pill px-3">{{ __('system.maintenance.online') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-check form-switch maintenance-toggle-scale">
                        <input class="form-check-input cursor-pointer" type="checkbox" name="maintenance_mode" value="1" {{ $isActive ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            {{-- BLOCK 2: Display Message & Custom Image --}}
            <div class="border rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    {{-- Standardized admin-settings icon box structure --}}
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-primary setting-icon-box">
                        <i class="fa-solid fa-bullhorn setting-icon"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark m-0">{{ __('system.maintenance.display_msg') }}</h6>
                        <span class="text-muted small">{{ __('system.maintenance.display_msg_sub') }}</span>
                    </div>
                </div>

                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label-premium">{{ __('system.maintenance.headline') }}</label>
                            <input type="text" name="maintenance_title" class="form-control-premium" 
                                   value="{{ $settings['maintenance_title'] ?? __('system.maintenance.default_title') }}">
                        </div>
                        
                        <div>
                            <label class="form-label-premium">{{ __('system.maintenance.description') }}</label>
                            <textarea name="maintenance_message" class="form-control-premium" rows="5">{{ $settings['maintenance_message'] ?? __('system.maintenance.default_msg') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label-premium">{{ __('system.maintenance.custom_img') }}</label>
                        
                        @if($hasImage)
                            <div class="border rounded-3 p-3 mb-3 bg-light text-center position-relative" data-existing-image-container>
                                <img src="{{ asset('storage/' . $imagePath) }}?v={{ time() }}" class="img-fluid rounded mb-2 shadow-sm maintenance-image-preview-existing">
                                
                                {{-- Data attributes provide translation strings for JS --}}
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" data-delete-image-btn
                                    data-swal-title="{{ __('system.maintenance.remove_img_title') }}"
                                    data-swal-text="{{ __('system.maintenance.remove_img_text') }}"
                                    data-swal-confirm="{{ __('system.maintenance.yes_remove') }}"
                                    data-swal-cancel="{{ __('system.cancel') }}"
                                    style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endif

                        <div data-upload-container class="{{ $hasImage ? 'd-none' : '' }}">
                            {{-- Use CSS class for dashed border --}}
                            <div class="border rounded-3 p-4 text-center bg-light border-dashed">
                                <i class="fa-solid fa-cloud-arrow-up fs-3 text-muted mb-3"></i>
                                <input type="file" name="maintenance_image" class="form-control form-control-premium mb-2" data-image-input accept="image/*">
                                <small class="text-muted d-block">{{ __('system.maintenance.img_help') }}</small>
                                <div data-image-preview class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BLOCK 3: Admin Bypass --}}
            <div class="border rounded-3 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm bg-light text-info maintenance-access-icon-box">
                            <i class="fa-solid fa-user-shield setting-icon"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark m-0">{{ __('system.maintenance.admin_access') }}</h6>
                            <span class="text-muted small">{{ __('system.maintenance.admin_access_sub') }}</span>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input cursor-pointer" type="checkbox" name="maintenance_bypass_admin" value="1" {{ $isBypassActive ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-danger text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fa-solid fa-save me-2"></i> {{ __('system.maintenance.update_status') }}
                </button>
            </div>

        </div>
    </form>
</div>

{{-- Load external JS file --}}
@push('scripts')
<script src="{{ asset('assets/js/admin-maintenance.js') }}"></script>
@endpush