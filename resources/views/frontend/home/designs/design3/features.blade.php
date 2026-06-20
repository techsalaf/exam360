<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-features.css') }}">

<section class="d3-features-section">
    <div class="container">
        <div class="d3-section-header">
            <h2 class="d3-section-title">
                {{ dynamicTransHelper($settings['d3_features_title'] ?? 'Platform Features') }}
            </h2>
            <p class="d3-section-description">
                {{ dynamicTransHelper($settings['d3_features_subtitle'] ?? 'Built with students and educators in mind') }}
            </p>
        </div>

        <div class="d3-features-grid">
            <!-- Secure Environment -->
            <div class="d3-feature-card">
                <div class="d3-feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="d3-feature-title">
                    {{ dynamicTransHelper($settings['d3_feature1_title'] ?? 'Secure CBT Environment') }}
                </h3>
                <p class="d3-feature-description">
                    {{ dynamicTransHelper($settings['d3_feature1_desc'] ?? 'Enterprise-grade security ensures your data and exam integrity are always protected.') }}
                </p>
            </div>

            <!-- Instant Results -->
            <div class="d3-feature-card">
                <div class="d3-feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="d3-feature-title">
                    {{ dynamicTransHelper($settings['d3_feature2_title'] ?? 'Instant Result Processing') }}
                </h3>
                <p class="d3-feature-description">
                    {{ dynamicTransHelper($settings['d3_feature2_desc'] ?? 'Get immediate feedback and detailed performance analysis after every exam.') }}
                </p>
            </div>

            <!-- Time Tracking -->
            <div class="d3-feature-card">
                <div class="d3-feature-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h3 class="d3-feature-title">
                    {{ dynamicTransHelper($settings['d3_feature3_title'] ?? 'Precision Time Tracking') }}
                </h3>
                <p class="d3-feature-description">
                    {{ dynamicTransHelper($settings['d3_feature3_desc'] ?? 'Real-time countdown timer with alerts ensures fair exam management.') }}
                </p>
            </div>

            <!-- Multi-Subject -->
            <div class="d3-feature-card">
                <div class="d3-feature-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="d3-feature-title">
                    {{ dynamicTransHelper($settings['d3_feature4_title'] ?? 'Multi-Subject Assessments') }}
                </h3>
                <p class="d3-feature-description">
                    {{ dynamicTransHelper($settings['d3_feature4_desc'] ?? 'Diverse exams across multiple subjects and difficulty levels.') }}
                </p>
            </div>

            <!-- Transparent Evaluation -->
            <div class="d3-feature-card">
                <div class="d3-feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="d3-feature-title">
                    {{ dynamicTransHelper($settings['d3_feature5_title'] ?? 'Transparent Evaluation') }}
                </h3>
                <p class="d3-feature-description">
                    {{ dynamicTransHelper($settings['d3_feature5_desc'] ?? 'Fair and unbiased grading with detailed performance metrics and analytics.') }}
                </p>
            </div>

            <!-- Mobile Friendly -->
            <div class="d3-feature-card">
                <div class="d3-feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="d3-feature-title">
                    {{ dynamicTransHelper($settings['d3_feature6_title'] ?? 'Mobile Friendly') }}
                </h3>
                <p class="d3-feature-description">
                    {{ dynamicTransHelper($settings['d3_feature6_desc'] ?? 'Access exams anytime, anywhere on desktop, tablet, or mobile device.') }}
                </p>
            </div>
        </div>
    </div>
</section>
