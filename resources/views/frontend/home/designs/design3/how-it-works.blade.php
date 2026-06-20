<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-how-it-works.css') }}">

<section class="d3-how-it-works-section d3-how-it-works-alqurra">
    <div class="container">
        <div class="d3-section-header">
            <h2 class="d3-section-title d3-section-title-alqurra">
                {{ dynamicTransHelper($settings['d3_how_it_works_title'] ?? 'How to Begin Your Challenge') }}
            </h2>
            <p class="d3-section-description d3-section-description-alqurra">
                {{ dynamicTransHelper($settings['d3_how_it_works_subtitle'] ?? 'Three simple steps to take the Champions Academic Challenge') }}
            </p>
        </div>

        <div class="d3-steps-container">
            <!-- Step 1: Select Class -->
            <div class="d3-step-card d3-step-card-alqurra">
                <div class="d3-step-number d3-step-number-alqurra">
                    <span>1</span>
                </div>
                <div class="d3-step-icon d3-step-icon-alqurra-1">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="d3-step-title">
                    {{ dynamicTransHelper($settings['d3_step1_title'] ?? 'Select Your Class Exam') }}
                </h3>
                <p class="d3-step-description">
                    {{ dynamicTransHelper($settings['d3_step1_desc'] ?? 'Find and select the exam designed specifically for your class. Each exam is tailored to your class\'s curriculum and level.') }}
                </p>
            </div>

            <!-- Arrow -->
            <div class="d3-step-arrow d3-step-arrow-alqurra">
                <i class="fas fa-arrow-right"></i>
            </div>

            <!-- Step 2: Login -->
            <div class="d3-step-card d3-step-card-alqurra">
                <div class="d3-step-number d3-step-number-alqurra">
                    <span>2</span>
                </div>
                <div class="d3-step-icon d3-step-icon-alqurra-2">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h3 class="d3-step-title">
                    {{ dynamicTransHelper($settings['d3_step2_title'] ?? 'Authenticate') }}
                </h3>
                <p class="d3-step-description">
                    {{ dynamicTransHelper($settings['d3_step2_desc'] ?? 'Log in with your student account to access the secure exam portal. Your APT (Academic Performance Target) will be pre-loaded based on your profile.') }}
                </p>
            </div>

            <!-- Arrow -->
            <div class="d3-step-arrow d3-step-arrow-alqurra">
                <i class="fas fa-arrow-right"></i>
            </div>

            <!-- Step 3: Attempt -->
            <div class="d3-step-card d3-step-card-alqurra">
                <div class="d3-step-number d3-step-number-alqurra">
                    <span>3</span>
                </div>
                <div class="d3-step-icon d3-step-icon-alqurra-3">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="d3-step-title">
                    {{ dynamicTransHelper($settings['d3_step3_title'] ?? 'Prove Your Excellence') }}
                </h3>
                <p class="d3-step-description">
                    {{ dynamicTransHelper($settings['d3_step3_desc'] ?? 'Attempt the exam, complete all questions, and submit. Receive instant feedback and discover your achievement level: Champion, Scholar, or Rising Star.') }}
                </p>
            </div>
        </div>
    </div>
</section>
