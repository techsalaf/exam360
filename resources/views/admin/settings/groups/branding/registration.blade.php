<link rel="stylesheet" href="{{ asset('assets/css/components/settings-notifications.css') }}">

<div class="settings-content">
    <form action="{{ route('admin.settings.branding.update') }}" method="POST">
        @csrf
        <input type="hidden" name="setting_group_key" value="branding_registration">
        <input type="hidden" name="registration_custom_fields" id="fields_json_input">

        <div class="setting-card">
            <div class="setting-header">
                <h3 class="setting-title">{{ __('branding.registration.title') }}</h3>
                <p class="setting-desc">{{ __('branding.registration.desc') }}</p>
            </div>

            <div class="sn-card-body">
                <!-- Text Customization Section -->
                <h6 class="fw-bold text-main-color mb-3">{{ __('branding.registration.text_customization') }}</h6>
                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <label class="sn-label">{{ __('branding.registration.label_headline') }}</label>
                        <input type="text" name="auth_register_headline" class="sn-input" value="{{ $settings['auth_register_headline'] ?? '' }}" placeholder="{{ __('auth.register.headline') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="sn-label">{{ __('branding.registration.label_title') }}</label>
                        <input type="text" name="auth_register_title" class="sn-input" value="{{ $settings['auth_register_title'] ?? '' }}" placeholder="{{ __('auth.register.title') }}">
                    </div>
                    <div class="col-12">
                        <label class="sn-label">{{ __('branding.registration.label_desc') }}</label>
                        <textarea name="auth_register_brand_desc" class="sn-input" rows="2" placeholder="{{ __('auth.register.brand_desc') }}">{{ $settings['auth_register_brand_desc'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="sn-label">{{ __('branding.registration.feature_1') }}</label>
                        <input type="text" name="auth_register_feature_1" class="sn-input" value="{{ $settings['auth_register_feature_1'] ?? '' }}" placeholder="{{ __('auth.register.features.ai_tests') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="sn-label">{{ __('branding.registration.feature_2') }}</label>
                        <input type="text" name="auth_register_feature_2" class="sn-input" value="{{ $settings['auth_register_feature_2'] ?? '' }}" placeholder="{{ __('auth.register.features.global_cert') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="sn-label">{{ __('branding.registration.feature_3') }}</label>
                        <input type="text" name="auth_register_feature_3" class="sn-input" value="{{ $settings['auth_register_feature_3'] ?? '' }}" placeholder="{{ __('auth.register.features.auto_results') }}">
                    </div>
                </div>

                <!-- Dynamic Fields Section -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-main-color m-0">{{ __('branding.registration.dynamic_fields') }}</h6>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold" onclick="addRegistrationField()">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('branding.registration.add_field') }}
                    </button>
                </div>

                <div class="zi-logic-card p-0">
                    <table class="zi-logic-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">{{ __('branding.registration.col_label') }}</th>
                                <th style="width: 20%;">{{ __('branding.registration.col_type') }}</th>
                                <th style="width: 15%;">{{ __('branding.registration.col_required') }}</th>
                                <th style="width: 30%;">{{ __('branding.registration.col_options') }}</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="fields_tbody"></tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-success text-white px-5 py-2 fw-bold shadow-sm rounded-pill" onclick="syncFieldsJson()">
                    <i class="fa-solid fa-save me-2"></i> {{ __('branding.save') }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let fields = {!! $settings['registration_custom_fields'] ?? '[]' !!};

    function addRegistrationField() {
        fields.push({ id: Date.now(), label: '', type: 'text', required: '0', options: [] });
        renderFields();
    }

    function removeField(id) {
        fields = fields.filter(f => f.id !== id);
        renderFields();
    }

    function updateField(id, key, value) {
        let field = fields.find(f => f.id === id);
        if (field) {
            field[key] = value;
            if(key === 'type') {
                field.options = (value === 'select') ? ['Option 1'] : '';
                renderFields();
            }
        }
    }

    function addOption(fieldId) {
        let field = fields.find(f => f.id === fieldId);
        if (field && Array.isArray(field.options)) {
            field.options.push('');
            renderFields();
        }
    }

    function updateOptionValue(fieldId, index, value) {
        let field = fields.find(f => f.id === fieldId);
        if (field && Array.isArray(field.options)) {
            field.options[index] = value;
        }
    }

    function removeOption(fieldId, index) {
        let field = fields.find(f => f.id === fieldId);
        if (field && Array.isArray(field.options)) {
            field.options.splice(index, 1);
            renderFields();
        }
    }

    function renderFields() {
        const tbody = document.getElementById('fields_tbody');
        tbody.innerHTML = '';
        fields.forEach(field => {
            const tr = document.createElement('tr');
            
            let optionsHtml = '';
            if(field.type === 'select') {
                optionsHtml = `<div class="d-flex flex-column gap-2">`;
                if(!Array.isArray(field.options)) field.options = [];
                field.options.forEach((opt, idx) => {
                    optionsHtml += `
                        <div class="d-flex gap-1 align-items-center">
                            <input type="text" class="sn-input py-1 fs-sm" style="height:32px" value="${opt}" onchange="updateOptionValue(${field.id}, ${idx}, this.value)" placeholder="Enter Value">
                            <button type="button" class="btn btn-link text-danger p-0 px-1" onclick="removeOption(${field.id}, ${idx})"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    `;
                });
                optionsHtml += `
                    <button type="button" class="btn btn-link text-primary fw-bold fs-xs p-0 text-start" onclick="addOption(${field.id})">
                        <i class="fa-solid fa-plus-circle me-1"></i> Add Dropdown Item
                    </button>
                </div>`;
            } else {
                optionsHtml = `<input type="text" class="sn-input" value="${field.options || ''}" onchange="updateField(${field.id}, 'options', this.value)" placeholder="Help instructions...">`;
            }

            tr.innerHTML = `
                <td><input type="text" class="sn-input" value="${field.label}" onchange="updateField(${field.id}, 'label', this.value)" placeholder="e.g. University"></td>
                <td>
                    <select class="sn-input" onchange="updateField(${field.id}, 'type', this.value)">
                        <option value="text" ${field.type === 'text' ? 'selected' : ''}>Text Input</option>
                        <option value="select" ${field.type === 'select' ? 'selected' : ''}>Select Dropdown</option>
                        <option value="attachment" ${field.type === 'attachment' ? 'selected' : ''}>File Attachment</option>
                    </select>
                </td>
                <td>
                    <select class="sn-input" onchange="updateField(${field.id}, 'required', this.value)">
                        <option value="0" ${field.required === '0' ? 'selected' : ''}>Optional</option>
                        <option value="1" ${field.required === '1' ? 'selected' : ''}>Required</option>
                    </select>
                </td>
                <td>${optionsHtml}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="removeField(${field.id})"><i class="fa-solid fa-trash-can"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
        });
        syncFieldsJson();
    }

    function syncFieldsJson() {
        document.getElementById('fields_json_input').value = JSON.stringify(fields);
    }

    renderFields();
</script>