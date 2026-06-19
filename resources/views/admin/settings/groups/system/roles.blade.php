<link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">

<div class="settings-content">
    
    <div class="setting-card">
        
        <div class="setting-header">
            <h3 class="setting-title">{{ __('system.roles.title') }}</h3>
            <p class="setting-desc">{{ __('system.roles.desc') }}</p>
        </div>

        @php
            $initialRole = null;
            if ($roles->isNotEmpty()) {
                $defaultRoleId = $roles->firstWhere('name', 'Super Admin')?->id ?? $roles->first()->id;
                $initialRole = $roles->firstWhere('id', $defaultRoleId) ?? $roles->first();
            }
        @endphp

        <!-- Section 1: Roles Grid -->
        {{-- FIX 2: Removed static inline style="background-color: var(--zi-bg-subtle); border-color: var(--zi-border) !important;" --}}
        <div class="border rounded-3 p-4 mb-4 roles-grid-container"> 
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-main-color">{{ __('system.roles.active_roles') }}</h6>
                
                @if($roles->isEmpty())
                    <span class="badge bg-danger">{{ __('system.roles.no_roles') }}</span>
                @endif
            </div>
            
            <div class="row g-3" id="rolesGrid">
                @foreach ($roles as $role)
                <div class="col-xl-4 col-md-6" id="role-card-{{ $role->id }}">
                    @php
                        $is_active = $initialRole && $role->id == $initialRole->id;
                        $active_class = $is_active ? ' is-active' : '';

                        // Dynamic styles remain, as they depend on $is_active
                        $dynamic_border_color = $is_active ? 'var(--zi-primary)' : 'var(--zi-border)';
                        $dynamic_border_style = "border-color: $dynamic_border_color;";
                        
                        $role_icon_class = match ($role->name) {
                            'Super Admin' => 'fa-crown',
                            'Instructor' => 'fa-chalkboard-user',
                            default => 'fa-user-graduate',
                        };
                        $role_color_class = match ($role->name) {
                            'Super Admin' => 'role-admin',
                            'Instructor' => 'role-manager',
                            default => 'role-default',
                        };
                    @endphp
                    
                    <div class="role-card role-selector cursor-pointer h-100 role-card-inner {{ $active_class }}" 
                         style="box-shadow: {{ $is_active ? 'none' : 'var(--zi-shadow-md)' }}; {{ $dynamic_border_style }}"
                         data-role-id="{{ $role->id }}" 
                         data-role-name="{{ $role->name }}">
                        
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="role-icon {{ $role_color_class }} p-3 rounded-3 role-icon-box">
                                <i class="fa-solid {{ $role_icon_class }}"></i>
                            </div>
                            
                            <span class="badge rounded-pill {{ $role->is_locked ? 'role-badge-locked' : 'role-badge-custom' }}">
                                {{ $role->is_locked ? __('system.roles.core_role') : __('system.roles.custom_role') }}
                            </span>
                        </div>
                        
                        <h6 class="fw-bold mb-1 text-main-color">{{ $role->name }}</h6>
                        <p class="text-muted small flex-grow-1 mb-3 text-truncate">{{ $role->description }}</p>
                        
                        <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center role-footer-divider">
                            <small class="fw-bold role-user-count">
                                <i class="fa-regular fa-user me-1"></i> {{ number_format($role->users_count) }} {{ __('system.roles.users_count') }}
                            </small>
                            
                            <div class="ms-auto">
                                @if ($role->is_locked)
                                    <span class="text-muted small"><i class="fa-solid fa-lock me-1"></i></span>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary me-1 rounded-circle role-action-btn edit-role-btn" 
                                            data-role-id="{{ $role->id }}" 
                                            data-role-name="{{ $role->name }}"
                                            data-role-desc="{{ $role->description }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-role-btn rounded-circle role-action-btn" 
                                            data-role-id="{{ $role->id }}" 
                                            data-role-name="{{ $role->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: Permissions Matrix -->
        @if($initialRole)
        <div class="border rounded-3 p-4" id="permissionMatrixCard">
            <form id="permissionMatrixForm" action="{{ route('admin.roles.update.permissions', $initialRole->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 permission-matrix-header">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 me-3 shadow-sm text-primary permission-matrix-icon-box">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main-color">{{ __('system.roles.permissions_for') }} <span class="text-primary" id="matrixRoleName">{{ $initialRole->name }}</span></h6>
                            <small class="text-muted">{{ __('system.roles.role_id') }} <span id="matrixRoleId">{{ $initialRole->id }}</span></small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-white border fw-bold shadow-sm rounded-pill px-3 py-1 text-sm btn-white-premium">
                        <i class="fa-solid fa-check me-1"></i> {{ __('system.roles.update_perm') }}
                    </button>
                </div>
                
                <div class="row g-4" id="matrixContent">
                    @foreach ($permissionsStructure as $groupName => $groupPermissions)
                        <div class="col-md-6 col-lg-4">
                            <div class="px-3 py-2 rounded-2 fw-bold text-uppercase text-muted mb-3 permission-group-header-style">
                                {{ $groupName }}
                            </div>
                            
                            <div class="d-flex flex-column gap-2 ps-2">
                                @foreach ($groupPermissions as $slug => $description)
                                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom permission-item-divider">
                                        <span class="perm-label small fw-medium">{{ $description }}</span>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input permission-switch" type="checkbox" name="permissions[]" value="{{ $slug }}" 
                                                {{ in_array($slug, $initialRole->permissionSlugs) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
        @else
        <div class="alert alert-warning text-center">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ __('system.roles.no_roles_db') }}
        </div>
        @endif

        <div class="mt-4 pt-3 border-top role-footer-divider text-end">
            <button type="button" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="fa-solid fa-plus me-2"></i> {{ __('system.roles.create_btn') }}
            </button>
        </div>

    </div>
</div>

<!-- Modal: Create Role -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-main-color">{{ __('system.roles.create_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('system.roles.role_name') }}</label>
                        <input type="text" name="name" class="form-control-premium" required placeholder="{{ __('system.roles.example_role') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('system.roles.role_desc') }}</label>
                        <textarea name="description" class="form-control-premium" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('system.roles.clone_from') }}</label>
                        <select name="clone_role_id" class="form-control-premium form-select">
                            <option value="">{{ __('system.roles.start_scratch') }}</option>
                            @foreach ($roles as $r)
                                @if (!$r->is_locked)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 pe-4">
                    <button type="button" class="btn btn-light rounded-pill fw-bold" data-bs-dismiss="modal">{{ __('system.cancel') }}</button>
                    <button type="submit" class="btn btn-success text-white rounded-pill fw-bold px-4">{{ __('system.roles.create_submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Role -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-main-color">{{ __('system.roles.edit_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="role_id" id="edit_role_id">
                <div class="modal-body pt-4">
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('system.roles.role_name') }}</label>
                        <input type="text" name="name" id="edit_role_name" class="form-control-premium" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-premium">{{ __('system.roles.role_desc') }}</label>
                        <textarea name="description" id="edit_role_description" class="form-control-premium" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 pe-4">
                    <button type="button" class="btn btn-light rounded-pill fw-bold" data-bs-dismiss="modal">{{ __('system.cancel') }}</button>
                    <button type="submit" class="btn btn-success text-white rounded-pill fw-bold px-4 btn-primary-inverted">{{ __('system.roles.update_submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.RolesConfig = {
        baseUrl: '{{ url('admin/roles') }}',
        createUrl: '{{ route("admin.roles.create") }}',
        csrfToken: '{{ csrf_token() }}',
        messages: {
            loadFail: '{{ __("system.roles.msgs.load_fail") }}',
            createFail: '{{ __("system.roles.msgs.create_fail") }}',
            updateFail: '{{ __("system.roles.msgs.update_fail") }}',
            updatePermError: '{{ __("system.roles.msgs.update_perm_error") }}', 
            deleteTitle: '{{ __("system.roles.msgs.delete_confirm_title") }}',
            deleteText: '{{ __("system.roles.msgs.delete_confirm_text") }}', 
            deleteConfirmBtn: '{{ __("system.roles.msgs.yes_delete") }}',
            cancel: '{{ __("system.cancel") }}'
        }
    };
</script>
<script src="{{ asset('assets/js/admin-roles.js') }}"></script>
@endpush