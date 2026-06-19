@extends('layouts.user')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/account.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('frontend.profile_title') }}</h1>
        <p class="page-subtitle">{{ __('frontend.profile_subtitle') }}</p>
    </div>
</div>

<form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="account-card">
        <div class="account-card-header">
            <h3 class="account-card-title">{{ __('frontend.general_info') }}</h3>
            @if ($user->is_subscribed)
                <div class="plan-badge">
                    <i class="fa-solid fa-crown me-1"></i> 
                    {{ $user->plan->name }} 
                    ({{ $user->currentSubscription->end_date->format('M d, Y') }} expiry)
                </div>
            @endif
        </div>
        
        <div class="profile-avatar-upload">
            <div class="avatar-wrapper js-avatar-trigger">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ __('frontend.avatar') }}" class="profile-avatar-img">
                @else
                    <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>
                @endif
                
                <div class="avatar-camera-overlay">
                    <i class="fa-solid fa-camera"></i>
                </div>
            </div>
            
            <div>
                <input type="file" id="avatar-input" name="avatar" hidden accept="image/jpeg, image/png">
                <button type="button" class="btn-avatar-action js-avatar-trigger">{{ __('frontend.change_avatar') }}</button>
                <p class="text-muted small mt-2">{{ __('frontend.avatar_help') }}</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('frontend.full_name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('auth.email_label') }}</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save mt-3">{{ __('frontend.save_general') }}</button>
    </div>
</form>

<form action="{{ route('user.profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="account-card mt-4">
        <div class="account-card-header">
            <h3 class="account-card-title">{{ __('frontend.change_password') }}</h3>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="current_password" class="form-label">{{ __('frontend.current_password') }}</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password">
                        <i class="fa-solid fa-eye-slash password-toggle" data-target="current_password"></i> 
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="new_password" class="form-label">{{ __('frontend.new_password') }}</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control" id="new_password" name="new_password" required autocomplete="new-password">
                        <i class="fa-solid fa-eye-slash password-toggle" data-target="new_password"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">{{ __('frontend.confirm_password') }}</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                        <i class="fa-solid fa-eye-slash password-toggle" data-target="new_password_confirmation"></i>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save mt-3">{{ __('frontend.update_password') }}</button>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const avatarAltText = @json(__('frontend.avatar'));
    const btnAvatarAction = document.querySelector('.btn-avatar-action');
    const avatarInput = document.getElementById('avatar-input');
    const fileSelectedText = @json(__('frontend.file_selected'));

    document.querySelectorAll('.js-avatar-trigger').forEach(el => {
        el.addEventListener('click', function () {
            avatarInput.click();
        });
    });

    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    if (avatarInput) {
        avatarInput.addEventListener('change', function () {
            if (this.files.length) {
                if (btnAvatarAction) {
                    btnAvatarAction.textContent = fileSelectedText;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.querySelector('.avatar-wrapper');
                    let img = wrapper.querySelector('.profile-avatar-img');

                    if (!img) {
                        // Create img element if only initials were present
                        img = document.createElement('img');
                        img.className = 'profile-avatar-img';
                        img.alt = avatarAltText;
                        
                        // Remove existing initials div if present
                        const initialsDiv = wrapper.querySelector('.profile-avatar');
                        if (initialsDiv) {
                            initialsDiv.remove();
                        }
                        
                        // Prepend the new image
                        wrapper.prepend(img);
                    }
                    img.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
</script>
@endpush