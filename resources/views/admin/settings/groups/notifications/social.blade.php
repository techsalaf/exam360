<form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="sn-wrapper">
    @csrf

    <div class="sn-card">
        
        <div class="sn-card-header">
            <div>
                <h3 class="zi-page-title zi-title-sm">{{ __('notifications.social.title') }}</h3>
                <p class="zi-subtitle mb-0">{{ __('notifications.social.subtitle') }}</p>
            </div>
        </div>

        <div class="sn-card-body">

            <!-- GOOGLE SETTINGS -->
            <div class="sn-card border mb-4">
                <div class="sn-card-header d-flex align-items-center justify-content-between bg-transparent border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="zi-icon-box zi-icon-square bg-white border shadow-sm rounded-3 me-3">
                            <i class="fa-brands fa-google text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold zi-text-main m-0">{{ __('notifications.social.google') }}</h6>
                            <span class="zi-text-muted small">{{ __('notifications.social.google_desc') }}</span>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="social_google_enable" value="1" {{ ($settings['social_google_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6"> 
                            <label class="sn-label">{{ __('notifications.social.client_id') }}</label>
                            <input type="text" 
                                   name="social_google_client_id" 
                                   class="form-control-premium" 
                                   value="{{ $settings['social_google_client_id'] ?? '' }}" 
                                   placeholder="{{ __('notifications.social.google_client_placeholder') }}">
                        </div>
                        
                        <div class="col-md-6"> 
                            <label class="sn-label">{{ __('notifications.social.client_secret') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="password" name="social_google_secret" class="form-control-premium pe-5" value="{{ $settings['social_google_secret'] ?? '' }}">
                                
                                <button type="button" class="zi-input-toggle" data-toggle="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="sn-label">{{ __('notifications.social.callback') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="text" class="form-control-premium callback-url-input pe-5 sn-font-mono" value="{{ url('auth/google/callback') }}" readonly>
                                
                                <button type="button" class="zi-input-toggle" data-copy title="{{ __('notifications.copy_url') }}">
                                    <i class="fa-regular fa-clipboard"></i>
                                </button>
                            </div>
                            <small class="zi-text-muted mt-2 d-block">{{ __('notifications.social.google_help') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FACEBOOK SETTINGS -->
            <div class="sn-card border mb-0">
                <div class="sn-card-header d-flex align-items-center justify-content-between bg-transparent border-bottom">
                    <div class="d-flex align-items-center">
                       
                        <div class="zi-icon-box zi-icon-square bg-white border shadow-sm rounded-3 me-3">
                           
                            <i class="fa-brands fa-facebook text-primary fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold zi-text-main m-0">{{ __('notifications.social.facebook') }}</h6>
                            <span class="zi-text-muted small">{{ __('notifications.social.facebook_desc') }}</span>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="social_facebook_enable" value="1" {{ ($settings['social_facebook_enable'] ?? '0') == '1' ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6"> 
                            <label class="sn-label">{{ __('notifications.social.app_id') }}</label>
                            <input type="text" name="social_facebook_client_id" class="form-control-premium" value="{{ $settings['social_facebook_client_id'] ?? '' }}">
                        </div>
                        
                        <div class="col-md-6"> 
                            <label class="sn-label">{{ __('notifications.social.app_secret') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="password" name="social_facebook_secret" class="form-control-premium pe-5" value="{{ $settings['social_facebook_secret'] ?? '' }}">
                                {{-- Envato Fix: Removed onclick, used data attribute --}}
                                <button type="button" class="zi-input-toggle" data-toggle="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="sn-label">{{ __('notifications.social.callback') }}</label>
                            <div class="position-relative zi-input-group">
                                <input type="text" class="form-control-premium callback-url-input pe-5 sn-font-mono" value="{{ url('auth/facebook/callback') }}" readonly>
                                {{-- Envato Fix: Removed onclick, used data attribute --}}
                                <button type="button" class="zi-input-toggle" data-copy title="{{ __('notifications.copy_url') }}">
                                    <i class="fa-regular fa-clipboard"></i>
                                </button>
                            </div>
                            <small class="zi-text-muted mt-2 d-block">{{ __('notifications.social.facebook_help') }}</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer / Save Button -->
        <div class="sn-card-foot text-end">
            <button type="submit" class="btn btn-success text-white px-4 py-2 fw-bold shadow-sm rounded-pill">
                <i class="fa-solid fa-check me-2"></i> {{ __('notifications.save') }}
            </button>
        </div>

    </div>
</form>

@push('scripts')
{{-- Envato Fix: External JS only --}}
<script src="{{ asset('assets/js/notifications-social-settings.js') }}"></script>
@endpush