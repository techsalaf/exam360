<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-exams.css') }}">
<section class="d2-exams-premium-section" id="featured-exams">
    <div class="container">
        <div class="d2-exams-header">
            <h2 class="d2-exams-main-title">
                {{ dynamicTransHelper($settings['exams_title'] ?? 'Explore Featured Exams') }}
            </h2>
            <p class="d2-exams-main-desc">
                {{ dynamicTransHelper($settings['exams_subtitle'] ?? 'Test your knowledge with our premium, curated examination sets.') }}
            </p>
        </div>
        <div class="d2-exams-grid-row">
            @php
                $badgeColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
            @endphp
            @foreach($featuredExams as $index => $exam)
            @php
                $currentColor = $badgeColors[$index % count($badgeColors)];
            @endphp
            <div class="d2-exams-grid-col">
                <div class="d2-exam-card">
                    <div class="d2-exam-banner-wrap">
                        <div class="d2-exam-cat-pill {{ $currentColor }}">
                            {{ $exam->category->name ?? 'General' }}
                        </div>
                        @if($exam->banner)
                            <img src="{{ asset('storage/' . $exam->banner) }}" class="d2-exam-banner-img" alt="{{ $exam->title }}">
                        @else
                            <div class="d2-exam-banner-img d-flex align-items-center justify-content-center bg-light">
                                <i class="fa-solid fa-graduation-cap fa-4x opacity-10"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="d2-exam-card-title">
                        {{ dynamicTransHelper($exam->title) }}
                    </h4>
                    <div class="d2-exam-meta-row">
                        <div class="d2-exam-meta-item">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ $exam->duration_minutes }}m</span>
                        </div>
                        <div class="d2-exam-meta-item">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>{{ $exam->questions_count }} Qs</span>
                        </div>
                    </div>
                    <div class="d2-exam-card-footer">
                        <div class="d2-exam-price-tag {{ $exam->is_paid ? '' : 'is-free' }}">
                            @if($exam->is_paid)
                                {{ ($rawSettings['currency_symbol'] ?? '$') }}{{ number_format($exam->price, 2) }}
                            @else
                                FREE
                            @endif
                        </div>
                        <a href="{{ route('exams.list') }}" class="d2-exam-btn">
                            Enroll Now
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>