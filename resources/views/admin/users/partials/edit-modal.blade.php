@php
    use Spatie\Permission\Models\Role as SpatieRole;
    $allRoles = SpatieRole::all(); 
@endphp

<div style="display:none;" id="editModalBaseTitle" data-title="{{ __('users.modal_edit_title') }}"></div>

<x-modal 
    id="editUserModal" 
    :title="__('users.modal_edit_title')" 
    action="/admin/users/1" 
    method="PUT"
    is-edit="true"
    :submit-text="__('users.modal_edit_btn')"
>
    <span class="d-none" data-base-title="{{ __('users.modal_edit_title') }}"></span>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="edit_name" class="form-label-premium required">{{ __('users.label_name') }}</label>
            <input type="text" id="edit_name" name="name" class="form-control-premium" required>
        </div>
        <div class="col-md-6">
            <label for="edit_email" class="form-label-premium required">{{ __('users.label_email') }}</label>
            <input type="email" id="edit_email" name="email" class="form-control-premium" required>
        </div>

        <div class="col-md-6">
            <label for="edit_password" class="form-label-premium">{{ __('users.label_password_optional') }}</label>
            <input type="password" id="edit_password" name="password" class="form-control-premium" placeholder="{{ __('users.label_password_placeholder') }}">
        </div>
        <div class="col-md-6">
            <label for="edit_role" class="form-label-premium required">{{ __('users.label_role') }}</label>
            <select id="edit_role" name="role_name" class="form-control-premium" required>
                @foreach($allRoles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="edit_mobile" class="form-label-premium">{{ __('users.label_mobile') }}</label>
            <input type="text" id="edit_mobile" name="mobile" class="form-control-premium">
        </div>
        <div class="col-md-6">
            <label for="edit_country" class="form-label-premium">{{ __('users.label_country') }}</label>
            <input type="text" id="edit_country" name="country" class="form-control-premium">
        </div>

        <div class="col-12">
            <label for="edit_address" class="form-label-premium">{{ __('users.label_address') }}</label>
            <input type="text" id="edit_address" name="address" class="form-control-premium">
        </div>
        
        <div class="col-md-4">
            <label for="edit_city" class="form-label-premium">{{ __('users.label_city') }}</label>
            <input type="text" id="edit_city" name="city" class="form-control-premium">
        </div>
        <div class="col-md-4">
            <label for="edit_state" class="form-label-premium">{{ __('users.label_state') }}</label>
            <input type="text" id="edit_state" name="state" class="form-control-premium">
        </div>
        <div class="col-md-4">
            <label for="edit_zip" class="form-label-premium">{{ __('users.label_zip') }}</label>
            <input type="text" id="edit_zip" name="zip" class="form-control-premium">
        </div>
    </div>
</x-modal>