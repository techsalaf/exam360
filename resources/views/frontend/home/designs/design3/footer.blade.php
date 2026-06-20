<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-footer.css') }}">

<footer class="d3-footer">
    <div class="d3-footer-content">
        <div class="container">
            <div class="d3-footer-grid">
                <!-- About Section -->
                <div class="d3-footer-col">
                    <div class="d3-footer-logo">
                        @if(!empty($settings['app_logo_light']))
                            <img src="{{ Storage::url($settings['app_logo_light']) }}" alt="{{ $settings['app_name'] ?? 'Logo' }}" class="d3-footer-logo-img">
                        @else
                            <span>{{ $settings['app_name'] ?? 'Exam360' }}</span>
                        @endif
                    </div>
                    <p class="d3-footer-about">
                        {{ dynamicTransHelper($settings['d3_footer_about'] ?? 'The Champions Academic Challenge CBT Platform provides secure, fair, and transparent online examinations for academic excellence.') }}
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="d3-footer-col">
                    <h4 class="d3-footer-title">Quick Links</h4>
                    <ul class="d3-footer-links">
                        <li><a href="#d3-exams-section">Available Exams</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        @if(\Auth::check())
                            <li><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Support -->
                <div class="d3-footer-col">
                    <h4 class="d3-footer-title">Support</h4>
                    <ul class="d3-footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                <!-- Organization Info -->
                <div class="d3-footer-col">
                    <h4 class="d3-footer-title">Organization</h4>
                    <p class="d3-footer-text">
                        {{ dynamicTransHelper($settings['d3_footer_organization'] ?? 'Powered by Al-Qurraa\' College') }}
                    </p>
                    <div class="d3-footer-quote">
                        <em>"{{ dynamicTransHelper($settings['d3_footer_quote'] ?? 'Read in the name of your Lord who created.' ) }}"</em>
                        <span>— Qur\'an 96:1</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="d3-footer-bottom">
                <p class="d3-footer-copyright">
                    © {{ date('Y') }} {{ dynamicTransHelper($settings['app_name'] ?? 'Champions Academic Challenge') }}. All rights reserved.
                </p>
                <div class="d3-footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>
