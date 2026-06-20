<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-navbar.css') }}">

<nav class="d3-navbar">
    <div class="container">
        <div class="d3-navbar-content">
            <!-- Logo -->
            <div class="d3-navbar-logo">
                @if(!empty($settings['app_logo_light']))
                    <img src="{{ Storage::url($settings['app_logo_light']) }}" alt="{{ $settings['app_name'] ?? 'Logo' }}" class="d3-logo">
                @else
                    <span class="d3-logo-text">{{ $settings['app_name'] ?? 'Exam360' }}</span>
                @endif
            </div>

            <!-- Navigation Links -->
            <div class="d3-navbar-menu">
                <a href="#" class="d3-nav-link active">Home</a>
                <a href="#d3-exams-section" class="d3-nav-link">Exams</a>
                @if(\Auth::check())
                    <a href="{{ route('user.dashboard') }}" class="d3-nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="d3-nav-link">Login</a>
                @endif
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="d3-menu-toggle" id="d3MobileMenuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('d3MobileMenuToggle');
        const navbar = document.querySelector('.d3-navbar');
        
        if (toggle) {
            toggle.addEventListener('click', function() {
                navbar.classList.toggle('d3-navbar-open');
            });
        }

        // Sticky navbar on scroll
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > 100) {
                navbar.classList.add('d3-navbar-sticky');
            } else {
                navbar.classList.remove('d3-navbar-sticky');
            }
            lastScrollTop = scrollTop;
        });
    });
</script>
