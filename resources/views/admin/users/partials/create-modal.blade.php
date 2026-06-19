@php
    use Spatie\Permission\Models\Role as SpatieRole;
    $allRoles = SpatieRole::all(); 
@endphp

<x-modal 
    id="createUserModal" 
    :title="__('users.modal_create_title')"
    action="{{ route('admin.users.store') }}" 
    :submit-text="__('users.modal_create_btn')"
>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="create_name" class="form-label-premium required">{{ __('users.label_name') }}</label>
            <input type="text" id="create_name" name="name" class="form-control-premium" value="{{ old('name') }}" required placeholder="John Doe">
        </div>
        <div class="col-md-6">
            <label for="create_email" class="form-label-premium required">{{ __('users.label_email') }}</label>
            <input type="email" id="create_email" name="email" class="form-control-premium" value="{{ old('email') }}" required placeholder="john@example.com">
        </div>
        
        <div class="col-md-6">
            <label for="create_password" class="form-label-premium required">{{ __('users.label_password') }}</label>
            <input type="password" id="create_password" name="password" class="form-control-premium" required placeholder="••••••••">
        </div>
        <div class="col-md-6">
            <label for="create_role" class="form-label-premium required">{{ __('users.label_role') }}</label>
            <select id="create_role" name="role_name" class="form-control-premium" required>
                <option value="" disabled>{{ __('Select a Role') }}</option>
                @foreach($allRoles as $role)
                    <option value="{{ $role->name }}" {{ ($role->name === 'Student') ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="create_mobile" class="form-label-premium">{{ __('users.label_mobile') }}</label>
            <input type="text" id="create_mobile" name="mobile" class="form-control-premium" value="{{ old('mobile') }}" placeholder="+1 234 567 890">
        </div>
        <div class="col-md-6">
            <label for="create_country" class="form-label-premium">{{ __('users.label_country') }}</label>
            <input type="text" id="create_country" name="country" class="form-control-premium" value="{{ old('country') }}" placeholder="United States">
        </div>

        <div class="col-12">
            <label for="create_address" class="form-label-premium">{{ __('users.label_address') }}</label>
            <input type="text" id="create_address" name="address" class="form-control-premium" value="{{ old('address') }}" placeholder="123 Main St">
        </div>
        
        <div class="col-md-4">
            <label for="create_city" class="form-label-premium">{{ __('users.label_city') }}</label>
            <input type="text" id="create_city" name="city" class="form-control-premium" value="{{ old('city') }}" placeholder="New York">
        </div>
        <div class="col-md-4">
            <label for="create_state" class="form-label-premium">{{ __('users.label_state') }}</label>
            <input type="text" id="create_state" name="state" class="form-control-premium" value="{{ old('state') }}" placeholder="NY">
        </div>
        <div class="col-md-4">
            <label for="create_zip" class="form-label-premium">{{ __('users.label_zip') }}</label>
            <input type="text" id="create_zip" name="zip" class="form-control-premium" value="{{ old('zip') }}" placeholder="10001">
        </div>
    </div>
</x-modal>