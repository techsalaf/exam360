@php
    /**
     * Helper to clean JSON translatable strings for the sidebar inputs
     */
    if (!function_exists('cleanAdminSidebarJson')) {
        function cleanAdminSidebarJson($value) {
            if (empty($value)) return '';

            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return $value;
            }

            // Get English as default
            $englishValue = $decoded['en'] ?? '';

            if (empty($englishValue)) {
                return '';
            }

            // Check if it's double encoded
            $secondDecode = json_decode($englishValue, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($secondDecode)) {
                return $secondDecode['en'] ?? '';
            }

            return $englishValue;
        }
    }
@endphp

<!-- Hero Image Card -->
<div class="cms-card h-auto mb-4">
    <div class="cms-header">
        <h5 class="cms-title">{{ __('cms.hero_section') }} {{ __('cms.image') }}</h5>
    </div>
    <div class="cms-body">
        <div class="image-upload-container">
            <label class="image-upload-label hero-image-upload {{ !empty($settings['home_hero_image']) ? 'has-image' : '' }}" id="hero_label">
                
                <input type="file" 
                       name="hero_image_file" 
                       class="file-input generic-image-input" 
                       accept="image/*"
                       data-preview-img="heroPreview"
                       data-preview-label="hero_label"
                       data-delete-input="deleteHeroInput">
                
                <div class="upload-content text-center">
                    <div class="upload-icon-wrapper"><i class="fa-regular fa-image"></i></div>
                    <div class="upload-text"><span>{{ __('cms.click_to_upload') }}</span></div>
                </div>

                @php
                    $heroImageUrl = !empty($settings['home_hero_image']) ? asset('storage/' . $settings['home_hero_image']) : '';
                @endphp

                <img id="heroPreview" 
                     src="{{ $heroImageUrl }}" 
                     class="image-preview hero-image-preview {{ !empty($settings['home_hero_image']) ? 'show' : '' }}"
                     style="{{ empty($settings['home_hero_image']) ? 'display:none;' : '' }}">
                
                <div class="btn-remove-image generic-image-remove"
                     data-preview-img="heroPreview"
                     data-preview-label="hero_label"
                     data-delete-input="deleteHeroInput"
                     style="{{ empty($settings['home_hero_image']) ? 'display:none;' : '' }}">
                    <i class="fa-solid fa-trash"></i>
                </div>
            </label>
            <input type="hidden" name="home_hero_image" value="{{ $settings['home_hero_image'] ?? '' }}">
            <input type="hidden" name="delete_hero_image" id="deleteHeroInput" value="0">
        </div>
        <div class="upload-help-text">
            <i class="fa-solid fa-circle-info"></i> {{ __('cms.hero_image_recommended') }}
        </div>
    </div>
</div>

<!-- Rating & Trust Stats Card -->
<div class="cms-card h-auto">
    <div class="cms-header">
        <h5 class="cms-title">{{ __('cms.rating_trust_badges') }}</h5>
    </div>
    <div class="cms-body">
        
        <!-- Avatar Group Uploads -->
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">{{ __('cms.user_photo') }} ({{ __('cms.rating_proof') }})</h6>
        <div class="row g-2 mb-4">
            @for($i = 1; $i <= 4; $i++)
                <div class="col-6">
                    <div class="image-upload-container mb-0">
                        <label class="image-upload-label avatar-upload {{ !empty($settings["avatar_{$i}"]) ? 'has-image' : '' }}" id="avatar_label_{{ $i }}">
                            
                            <input type="file" 
                                   name="avatar_{{$i}}_file" 
                                   class="file-input generic-image-input" 
                                   accept="image/*"
                                   data-preview-img="avatarPreview{{$i}}"
                                   data-preview-label="avatar_label_{{ $i }}"
                                   data-delete-input="deleteAvatarInput{{$i}}">
                            
                            <div class="upload-content text-center">
                                <div class="upload-icon-wrapper avatar-icon-wrapper">
                                    <i class="fa-solid fa-user small"></i>
                                </div>
                                <div class="upload-text avatar-text">{{ __('cms.user_label') }} {{$i}}</div>
                            </div>

                            @php
                                $avatarUrl = !empty($settings["avatar_{$i}"]) ? asset('storage/' . $settings["avatar_{$i}"]) : '';
                            @endphp

                            <img id="avatarPreview{{$i}}" 
                                 src="{{ $avatarUrl }}" 
                                 class="image-preview avatar-preview {{ !empty($settings["avatar_{$i}"]) ? 'show' : '' }}"
                                 style="{{ empty($settings["avatar_{$i}"]) ? 'display:none;' : '' }}">
                            
                            <div class="btn-remove-image generic-image-remove"
                                 data-preview-img="avatarPreview{{$i}}"
                                 data-preview-label="avatar_label_{{ $i }}"
                                 data-delete-input="deleteAvatarInput{{$i}}"
                                 style="{{ empty($settings["avatar_{$i}"]) ? 'display:none;' : '' }}">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                        </label>
                        <input type="hidden" name="avatar_{{$i}}" value="{{ $settings["avatar_{$i}"] ?? '' }}">
                        <input type="hidden" name="delete_avatar_{{$i}}" id="deleteAvatarInput{{$i}}" value="0">
                    </div>
                </div>
            @endfor
        </div>

        <!-- Star Rating Section -->
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">{{ __('cms.rating') }}</h6>
        <div class="mb-3">
            <label class="form-label-premium">{{ __('cms.score_out_of_5') }}</label>
            <div class="input-group">
                <span class="input-group-text bg-warning text-white border-warning"><i class="fa-solid fa-star"></i></span>
                <input type="text" name="home_stat_3_count" class="form-control form-control-cms" placeholder="4.8" value="{{ $settings['home_stat_3_count'] ?? '' }}">
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label-premium">{{ __('cms.hero_rating_label') }}</label>
            <input type="text" name="home_stat_1_label" class="form-control-cms" placeholder="{{ __('cms.trusted_by_placeholder') }}" value="{{ cleanAdminSidebarJson($settings['home_stat_1_label'] ?? '') }}">
        </div>

        <!-- Trust Badges Section -->
        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2 pt-2">{{ __('cms.trust_badges') }}</h6>
        
        <div class="mb-3">
            <label class="form-label-premium">{{ __('cms.badge_1') }}</label>
            <input type="text" name="trust_badge_1" class="form-control-cms mb-1" placeholder="Laravel 10" value="{{ cleanAdminSidebarJson($settings['trust_badge_1'] ?? '') }}">
            <input type="text" name="trust_icon_1" class="form-control-cms text-muted small" placeholder="fa-brands fa-laravel" value="{{ $settings['trust_icon_1'] ?? '' }}">
        </div>

        <div class="mb-3">
            <label class="form-label-premium">{{ __('cms.badge_2') }}</label>
            <input type="text" name="trust_badge_2" class="form-control-cms mb-1" placeholder="SaaS Ready" value="{{ cleanAdminSidebarJson($settings['trust_badge_2'] ?? '') }}">
            <input type="text" name="trust_icon_2" class="form-control-cms text-muted small" placeholder="fa-solid fa-cloud" value="{{ $settings['trust_icon_2'] ?? '' }}">
        </div>

        <div class="mb-0">
            <label class="form-label-premium">{{ __('cms.badge_3') }}</label>
            <input type="text" name="trust_badge_3" class="form-control-cms mb-1" placeholder="CodeCanyon Approved" value="{{ cleanAdminSidebarJson($settings['trust_badge_3'] ?? '') }}">
            <input type="text" name="trust_icon_3" class="form-control-cms text-muted small" placeholder="fa-solid fa-check" value="{{ $settings['trust_icon_3'] ?? '' }}">
        </div>

    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/admin-cms-images.js') }}"></script>
@endpush