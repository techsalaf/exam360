@php
    $menuCol1 = $footerCol1 ?? \App\Models\Menu::where('location', 'footer_column_1')->first();
    $menuCol2 = $footerCol2 ?? \App\Models\Menu::where('location', 'footer_column_2')->first();

    $settings = $settings ?? [];
    $showPolicy = ($settings['security_policy_show_footer'] ?? '0') === '1';
    $termsUrl = $settings['security_policy_terms_url'] ?? '#';
    $privacyUrl = $settings['security_policy_privacy_url'] ?? '#';
@endphp

<footer class="footer-section">
    <div class="container">
        <div class="footer-top">
            
            <div class="footer-widget brand-widget">
                <a href="{{ route('frontend.home') }}" class="footer-logo">
                    @if(!empty($settings['footer_logo']))
                        <img src="{{ Storage::url($settings['footer_logo']) }}" alt="{{ config('app.name') }}" class="footer-brand-img" style="max-height: 45px;">
                    @elseif(!empty($settings['app_logo_light']))
                        <img src="{{ Storage::url($settings['app_logo_light']) }}" alt="{{ config('app.name') }}" class="footer-brand-img" style="max-height: 45px;">
                    @else
                        <div class="footer-brand-text">
                            <i class="fa-solid fa-layer-group text-primary me-2"></i>
                            <span class="text-white">Zi</span><span class="text-primary">Exam</span><span class="text-white">AI</span>
                        </div>
                    @endif
                </a>
                <p class="footer-desc mt-3">
                    @dynamicTrans('footer_about_text', $settings)
                </p>
            </div>

            <div class="footer-widget">
                <h5 class="widget-title">
                    @if(isset($menuCol1->name)) 
                        @jsonLang($menuCol1->name) 
                    @else 
                        {{ __('frontend.useful_links') }} 
                    @endif
                </h5>
                <ul class="footer-links">
                    @if($menuCol1 && !empty($menuCol1->items))
                        @foreach($menuCol1->items as $item)
                            <li>
                                <a href="{{ $item['url'] }}" target="{{ str_starts_with($item['url'], 'http') ? '_blank' : '_self' }}">
                                    @jsonLang($item['label'])
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li><a href="{{ route('frontend.home') }}">{{ __('frontend.home_link') }}</a></li>
                        <li><a href="{{ route('frontend.home') }}#features">{{ __('frontend.features_link') }}</a></li>
                        <li><a href="{{ route('frontend.home') }}#pricing">{{ __('frontend.pricing_link') }}</a></li>
                        <li><a href="{{ route('frontend.home') }}#faq">{{ __('frontend.faq_link') }}</a></li>
                    @endif
                </ul>
            </div>

            <div class="footer-widget">
                <h5 class="widget-title">
                    @if(isset($menuCol2->name)) 
                        @jsonLang($menuCol2->name) 
                    @else 
                        {{ __('frontend.legal') }} 
                    @endif
                </h5>
                <ul class="footer-links">
                    @if($menuCol2 && !empty($menuCol2->items))
                        @foreach($menuCol2->items as $item)
                            <li>
                                <a href="{{ $item['url'] }}" target="{{ str_starts_with($item['url'], 'http') ? '_blank' : '_self' }}">
                                    @jsonLang($item['label'])
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li><a href="{{ url('/privacy-policy') }}">{{ __('frontend.privacy_policy') }}</a></li>
                        <li><a href="{{ url('/terms-of-service') }}">{{ __('frontend.terms_service') }}</a></li>
                        <li><a href="{{ url('/security') }}">{{ __('frontend.security_policy') }}</a></li>
                        <li><a href="{{ url('/refund-policy') }}">{{ __('frontend.refund_policy') }}</a></li>
                    @endif
                </ul>
            </div>

            <div class="footer-widget">
                <h5 class="widget-title">{{ __('frontend.contact_info') }}</h5>
                <ul class="contact-list">
                    @if(!empty($settings['contact_address']))
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>@dynamicTrans('contact_address', $settings)</span>
                        </li>
                    @endif
                    
                    @if(!empty($settings['contact_email']))
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
                        </li>
                    @endif
                    
                    @if(!empty($settings['contact_phone']))
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:{{ $settings['contact_phone'] }}">{{ $settings['contact_phone'] }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            
            <div class="social-icons">
                @foreach(['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'] as $social)
                    @if(!empty($settings["social_{$social}"]))
                        <a href="{{ $settings["social_{$social}"] }}" target="_blank" class="social-link" rel="noopener noreferrer">
                            <i class="fa-brands fa-{{ $social === 'facebook' ? 'facebook-f' : ($social === 'linkedin' ? 'linkedin-in' : $social) }}"></i>
                        </a>
                    @endif
                @endforeach
            </div>
            
            @if($showPolicy)
                <div class="footer-policy-links d-flex gap-3 text-sm">
                    @if(!empty($settings['security_policy_terms_url']))
                        <a href="{{ $termsUrl }}" class="text-muted text-decoration-none small hover-white">{{ __('Terms') }}</a>
                    @endif
                    
                    @if(!empty($settings['security_policy_terms_url']) && !empty($settings['security_policy_privacy_url']))
                        <span class="text-muted small">•</span>
                    @endif

                    @if(!empty($settings['security_policy_privacy_url']))
                        <a href="{{ $privacyUrl }}" class="text-muted text-decoration-none small hover-white">{{ __('Privacy') }}</a>
                    @endif
                </div>
            @endif

            <p class="copyright mb-0 text-muted small text-center text-md-end">
                @dynamicTrans('footer_copyright', $settings)
            </p>
        </div>
    </div>
</footer>