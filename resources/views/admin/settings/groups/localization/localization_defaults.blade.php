@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-localization.css') }}">
@endpush

<div class="tab-pane fade show active" id="language-content" role="tabpanel">
    <div class="settings-content">
        
        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('admin.settings.localization.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting_group_key" value="toggles">

                    <div class="setting-card h-100">
                        <div class="setting-header">
                            <h3 class="setting-title">{{ __('localization.switchers.title') }}</h3>
                            <p class="setting-desc">{{ __('localization.switchers.desc') }}</p>
                        </div>

                        <div class="border rounded-3 p-3 mb-3">
                            <div class="form-check form-switch d-flex align-items-center ps-0 mb-3 gap-3">
                                <input type="hidden" name="localization_front_switcher" value="0">
                                <input class="form-check-input ms-0 locale-switch-lg cursor-pointer" 
                                       type="checkbox" 
                                       id="frontSwitch" 
                                       name="localization_front_switcher" 
                                       value="1" 
                                       {{ ($settings['localization_front_switcher'] ?? '0') == '1' ? 'checked' : '' }}>
                                <div>
                                    <label class="form-check-label fw-bold d-block cursor-pointer" for="frontSwitch">{{ __('localization.switchers.front_label') }}</label>
                                    <small class="text-muted">{{ __('localization.switchers.front_help') }}</small>
                                </div>
                            </div>
                            
                            <hr class="text-muted opacity-25">

                            <div class="form-check form-switch d-flex align-items-center ps-0 gap-3">
                                <input type="hidden" name="localization_admin_switcher" value="0">
                                <input class="form-check-input ms-0 locale-switch-lg cursor-pointer" 
                                       type="checkbox" 
                                       id="adminSwitch" 
                                       name="localization_admin_switcher" 
                                       value="1"
                                       {{ ($settings['localization_admin_switcher'] ?? '0') == '1' ? 'checked' : '' }}>
                                <div>
                                    <label class="form-check-label fw-bold d-block cursor-pointer" for="adminSwitch">{{ __('localization.switchers.admin_label') }}</label>
                                    <small class="text-muted">{{ __('localization.switchers.admin_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-dark rounded-pill fw-bold px-4">{{ __('localization.switchers.update_btn') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="setting-card">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="setting-title">{{ __('localization.table.title') }}</h3>
                    <p class="setting-desc mb-0">{{ __('localization.table.desc') }}</p>
                </div>
                <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('localization.table.add_new') }}
                </button>
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">{{ __('localization.table.headers.name') }}</th>
                            <th>{{ __('localization.table.headers.code') }}</th>
                            <th>{{ __('localization.table.headers.rtl') }}</th>
                            <th>{{ __('localization.table.headers.front') }}</th>
                            <th>{{ __('localization.table.headers.admin') }}</th>
                            <th class="text-end pe-4">{{ __('localization.table.headers.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allLanguages as $lang)
                        <tr>
                            <td class="ps-4 fw-bold">
                                <i class="fi fi-{{ $lang->flag }} me-2 rounded-1 shadow-sm"></i> {{ $lang->name }}
                                @if($lang->is_default) <span class="badge bg-success-subtle text-success ms-2">{{ __('localization.table.badges.default') }}</span> @endif
                            </td>
                            <td><code>{{ $lang->code }}</code></td>
                            <td>{{ $lang->is_rtl ? __('localization.table.badges.yes') : __('localization.table.badges.no') }}</td>
                            <td>
                                @if($lang->is_active_front) 
                                    <span class="badge bg-primary-subtle text-primary">{{ __('localization.table.badges.active') }}</span>
                                @else 
                                    <span class="badge bg-light text-muted border">{{ __('localization.table.badges.hidden') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($lang->is_active_admin) 
                                    <span class="badge bg-info-subtle text-info">{{ __('localization.table.badges.active') }}</span>
                                @else 
                                    <span class="badge bg-light text-muted border">{{ __('localization.table.badges.hidden') }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if(!$lang->is_default)
                                    <form action="{{ route('admin.settings.language.default', $lang->id) }}" method="POST" class="d-inline" id="makeDefaultForm{{ $lang->id }}">
                                        @csrf @method('PATCH')
                                        <button type="button" class="btn btn-sm btn-light border me-1 text-warning confirm-default" 
                                                data-form="makeDefaultForm{{ $lang->id }}"
                                                data-name="{{ $lang->name }}"
                                                title="{{ __('localization.table.tooltips.set_default') }}">
                                            <i class="fa-regular fa-star"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-warning border me-1 text-white" title="{{ __('localization.table.tooltips.curr_default') }}" disabled>
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                @endif

                                <button type="button" class="btn btn-sm btn-light border me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editLangModal{{ $lang->id }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                
                                @if(!$lang->is_default)
                                    <form action="{{ route('admin.settings.language.destroy', $lang->id) }}" method="POST" class="d-inline" id="deleteLangForm{{ $lang->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger confirm-delete-lang"
                                                data-form="deleteLangForm{{ $lang->id }}"
                                                data-name="{{ $lang->name }}"
                                                title="{{ __('localization.table.tooltips.delete') }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="editLangModal{{ $lang->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.settings.language.update', $lang->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">{{ __('localization.modals.edit_title') }}: {{ $lang->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label-premium">{{ __('localization.modals.name_label') }}</label>
                                                <input type="text" name="name" class="form-control-premium" value="{{ $lang->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label-premium">{{ __('localization.modals.flag_label') }}</label>
                                                <input type="text" name="flag" class="form-control-premium" value="{{ $lang->flag }}" required>
                                            </div>
                                            
                                            <div class="form-check form-switch d-flex align-items-center ps-0 gap-3 mb-2">
                                                <input type="hidden" name="is_rtl" value="0">
                                                <input class="form-check-input ms-0 locale-switch-md cursor-pointer" 
                                                       type="checkbox" 
                                                       name="is_rtl" 
                                                       value="1" 
                                                       {{ $lang->is_rtl ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">{{ __('localization.modals.rtl_label') }}</label>
                                            </div>

                                            <hr>

                                            <div class="form-check form-switch d-flex align-items-center ps-0 gap-3 mb-2">
                                                <input type="hidden" name="is_active_front" value="0">
                                                <input class="form-check-input ms-0 locale-switch-md cursor-pointer" 
                                                       type="checkbox" 
                                                       name="is_active_front" 
                                                       value="1" 
                                                       {{ $lang->is_active_front ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">{{ __('localization.modals.front_label') }}</label>
                                            </div>

                                            <div class="form-check form-switch d-flex align-items-center ps-0 gap-3">
                                                <input type="hidden" name="is_active_admin" value="0">
                                                <input class="form-check-input ms-0 locale-switch-md cursor-pointer" 
                                                       type="checkbox" 
                                                       name="is_active_admin" 
                                                       value="1" 
                                                       {{ $lang->is_active_admin ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">{{ __('localization.modals.admin_label') }}</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="submit" class="btn btn-primary">{{ __('localization.modals.save_btn') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="addLanguageModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.settings.language.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ __('localization.modals.add_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('localization.modals.name_label') }}</label>
                            <input type="text" name="name" class="form-control-premium" placeholder="{{ __('localization.modals.name_ph') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">{{ __('localization.modals.code_label') }}</label>
                            <input type="text" name="code" class="form-control-premium" placeholder="{{ __('localization.modals.code_ph') }}" maxlength="2" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-premium">{{ __('localization.modals.flag_label') }}</label>
                            <input type="text" name="flag" class="form-control-premium" placeholder="{{ __('localization.modals.code_ph') }}" required>
                            <div class="form-text text-xs">{{ __('localization.modals.flag_help') }}</div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="form-check form-switch d-flex align-items-center ps-0 gap-3 mb-3">
                        <input type="hidden" name="is_active_front" value="0">
                        <input class="form-check-input ms-0 locale-switch-md cursor-pointer" 
                               type="checkbox" 
                               name="is_active_front" 
                               value="1" 
                               checked>
                        <label class="form-check-label fw-bold">{{ __('localization.modals.front_label') }}</label>
                    </div>

                    <div class="form-check form-switch d-flex align-items-center ps-0 gap-3">
                        <input type="hidden" name="is_active_admin" value="0">
                        <input class="form-check-input ms-0 locale-switch-md cursor-pointer" 
                               type="checkbox" 
                               name="is_active_admin" 
                               value="1" 
                               checked>
                        <label class="form-check-label fw-bold">{{ __('localization.modals.admin_label') }}</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">{{ __('localization.modals.add_btn') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    window.localeAlerts = {
        delete_title: "{{ __('localization.alerts.delete_title') }}",
        delete_text: "{{ __('localization.alerts.delete_text') }}",
        yes_delete: "{{ __('localization.alerts.yes_delete') }}",
        default_title: "{{ __('localization.alerts.default_title') }}",
        default_text: "{{ __('localization.alerts.default_text') }}",
        yes_default: "{{ __('localization.alerts.yes_default') }}",
        cancel: "{{ __('localization.alerts.cancel') }}"
    };
</script>
<script src="{{ asset('assets/js/admin-localization.js') }}"></script>
@endpush