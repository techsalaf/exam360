@php
    $gdprSettings = \App\Models\SystemSetting::whereIn('key', [
        'security_gdpr_enable', 
        'security_gdpr_message'
    ])->pluck('value', 'key');

    $isGdprEnabled = ($gdprSettings['security_gdpr_enable'] ?? '0') === '1';
    $gdprMessage = $gdprSettings['security_gdpr_message'] ?? 'We use cookies to ensure you get the best experience on our website.';
@endphp

@if($isGdprEnabled)
    <div id="zi-cookie-banner" class="zi-cookie-banner">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="zi-cookie-text">
                    <i class="fa-solid fa-cookie-bite text-primary me-2 fs-5"></i>
                    <span>{{ $gdprMessage }}</span>
                </div>
                <div class="zi-cookie-actions">
                    <button type="button" id="btn-accept-cookies" class="btn btn-primary btn-sm fw-bold rounded-pill px-4">
                        {{ __('Accept') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cookieBanner = document.getElementById('zi-cookie-banner');
            const acceptBtn = document.getElementById('btn-accept-cookies');
            const storageKey = 'ziexam_cookie_consent';

            if (!localStorage.getItem(storageKey)) {
                cookieBanner.style.display = 'block';
            }

            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    localStorage.setItem(storageKey, 'accepted');
                    
                    // JS animation for exit
                    cookieBanner.style.transition = 'transform 0.3s ease-in';
                    cookieBanner.style.transform = 'translateY(100%)';
                    
                    setTimeout(() => {
                        cookieBanner.style.display = 'none';
                    }, 300);
                });
            }
        });
    </script>
@endif