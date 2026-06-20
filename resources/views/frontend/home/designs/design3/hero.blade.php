<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-hero.css') }}">

<section class="d3-hero-section d3-hero-alqurra">
    <div class="d3-hero-background">
        <div class="d3-hero-gradient-alqurra"></div>
        <div class="d3-hero-grid"></div>
        <div class="d3-hero-glow glow-1"></div>
        <div class="d3-hero-glow glow-2"></div>
    </div>

    <div class="container position-relative" style="z-index: 10;">
        <div class="d3-hero-content">
            <div class="d3-hero-badge d3-hero-badge-alqurra">
                <i class="fas fa-award"></i>
                <span>Al-Qurraa' College Champions</span>
            </div>

            <h1 class="d3-hero-title d3-hero-title-alqurra">
                {{ dynamicTransHelper($settings['d3_hero_title'] ?? 'Champions Academic Challenge 2026') }}
            </h1>

            <p class="d3-hero-subtitle d3-hero-subtitle-alqurra">
                {{ dynamicTransHelper($settings['d3_hero_subtitle'] ?? 'Read & Ascend: Prove Your Excellence') }}
            </p>

            <p class="d3-hero-description d3-hero-description-alqurra">
                {{ dynamicTransHelper($settings['d3_hero_description'] ?? 'Select the exam for your class and showcase your academic mastery. Become a Champion, Scholar, or Rising Star in the Al-Qurraa\' College Champions Academic Challenge.') }}
            </p>

            <div class="d3-hero-buttons">
                <button class="d3-btn d3-btn-primary d3-btn-primary-alqurra" id="browseExamsBtn">
                    <i class="fas fa-book-open"></i>
                    <span>Select Your Class Exam</span>
                </button>
                @if(!Auth::check())
                    <a href="{{ route('login') }}" class="d3-btn d3-btn-secondary d3-btn-secondary-alqurra">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login to Your Account</span>
                    </a>
                @endif
            </div>

            <!-- Class-based Trust Info -->
            <div class="d3-trust-badges d3-trust-badges-alqurra">
                <div class="d3-trust-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ $allExams->count() ?? 0 }} Class Exams</span>
                </div>
                <div class="d3-trust-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure CBT Platform</span>
                </div>
                <div class="d3-trust-item">
                    <i class="fas fa-bolt"></i>
                    <span>Instant Results</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const browseBtn = document.getElementById('browseExamsBtn');
        if (browseBtn) {
            browseBtn.addEventListener('click', function() {
                const examsSection = document.getElementById('d3-exams-section');
                if (examsSection) {
                    examsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
    });
</script>
