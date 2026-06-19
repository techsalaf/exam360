<!-- Load fixed premium CTA CSS with Low Rounded Corners -->
<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-cta.css') }}">

<section class="d2-cta-premium-section">
    <div class="container">
        
        <!-- Premium CTA Card -->
        <div class="d2-cta-card">
            
            <!-- Visual Blobs for Depth -->
            <div class="d2-cta-shape d2-cta-shape-1"></div>
            <div class="d2-cta-shape d2-cta-shape-2"></div>

            <!-- Content Area -->
            <div class="d2-cta-content">
                
                <h2 class="d2-cta-title">
                    {{ get_trans($settings['cta_title'] ?? 'Ready to Modernize Your Exams?') }}
                </h2>
                
                <p class="d2-cta-desc">
                    {{ get_trans($settings['cta_subtitle'] ?? 'Start creating smarter assessments today with the power of ZiExam AI.') }}
                </p>

                <div class="d2-cta-actions">
                    <a href="{{ route('register') }}" class="d2-cta-btn">
                        {{ get_trans($settings['cta_btn_text'] ?? 'Get Started Now') }}
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>