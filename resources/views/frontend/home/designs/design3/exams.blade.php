<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-exams.css') }}">

<section class="d3-exams-section" id="d3-exams-section">
    <div class="container">
        <!-- Header -->
        <div class="d3-section-header">
            <h2 class="d3-section-title">
                {{ dynamicTransHelper($settings['d3_exams_title'] ?? 'Available Examinations') }}
            </h2>
            <p class="d3-section-description">
                {{ dynamicTransHelper($settings['d3_exams_subtitle'] ?? 'Discover and take exams tailored for your academic journey') }}
            </p>
        </div>

        <!-- Search and Filter Bar -->
        <div class="d3-exams-controls">
            <!-- Search Box -->
            <div class="d3-search-box-wrapper">
                <input 
                    type="text" 
                    class="d3-search-box" 
                    id="d3ExamSearch" 
                    placeholder="Search exams..."
                >
                <i class="fas fa-search"></i>
            </div>

            <!-- Filter Tabs -->
            <div class="d3-filter-tabs">
                <button class="d3-filter-tab active" data-filter="all">
                    All
                </button>
                <button class="d3-filter-tab" data-filter="active">
                    <i class="fas fa-circle-play"></i> Active
                </button>
                <button class="d3-filter-tab" data-filter="upcoming">
                    <i class="fas fa-clock"></i> Upcoming
                </button>
                <button class="d3-filter-tab" data-filter="closed">
                    <i class="fas fa-lock"></i> Closed
                </button>
            </div>
        </div>

        <!-- Loading Skeleton -->
        <div class="d3-exams-skeleton" style="display: none;">
            @for($i = 0; $i < 6; $i++)
                <div class="d3-exam-card-skeleton">
                    <div class="d3-skeleton-banner"></div>
                    <div class="d3-skeleton-title"></div>
                    <div class="d3-skeleton-meta"></div>
                </div>
            @endfor
        </div>

        <!-- Exams Grid -->
        <div class="d3-exams-grid" id="d3ExamsGrid">
            @forelse($allExams as $exam)
                @php
                    $now = \Carbon\Carbon::now();
                    $status = 'active';
                    $statusLabel = 'Active';
                    $statusIcon = 'fa-circle-play';
                    $statusColor = 'status-active';

                    if ($exam->start_date && $exam->start_date > $now) {
                        $status = 'upcoming';
                        $statusLabel = 'Upcoming';
                        $statusIcon = 'fa-clock';
                        $statusColor = 'status-upcoming';
                    } elseif ($exam->end_date && $exam->end_date < $now) {
                        $status = 'closed';
                        $statusLabel = 'Closed';
                        $statusIcon = 'fa-lock';
                        $statusColor = 'status-closed';
                    }
                @endphp

                <div class="d3-exam-card" data-status="{{ $status }}" data-title="{{ strtolower($exam->title) }}">
                    <!-- Card Header with Banner -->
                    <div class="d3-exam-card-banner">
                        @if($exam->banner)
                            <img src="{{ asset('storage/' . $exam->banner) }}" alt="{{ $exam->title }}" class="d3-banner-img">
                        @else
                            <div class="d3-banner-placeholder">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="d3-status-badge {{ $statusColor }}">
                            <i class="fas {{ $statusIcon }}"></i>
                            <span>{{ $statusLabel }}</span>
                        </div>

                        <!-- Category Badge -->
                        @if($exam->category)
                            <div class="d3-category-badge">
                                {{ $exam->category->name }}
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="d3-exam-card-body">
                        <h3 class="d3-exam-card-title">
                            {{ dynamicTransHelper($exam->title) }}
                        </h3>

                        @if($exam->description)
                            <p class="d3-exam-card-description">
                                {{ Str::limit(dynamicTransHelper($exam->description), 100) }}
                            </p>
                        @endif

                        <!-- Meta Information -->
                        <div class="d3-exam-meta">
                            <div class="d3-meta-item">
                                <i class="fas fa-list-check"></i>
                                <span>{{ $exam->questions_count ?? 0 }} Questions</span>
                            </div>
                            <div class="d3-meta-item">
                                <i class="fas fa-hourglass-end"></i>
                                <span>{{ $exam->duration_minutes }} mins</span>
                            </div>
                            @if($exam->total_marks)
                                <div class="d3-meta-item">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $exam->total_marks }} Marks</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="d3-exam-card-footer">
                        @if($exam->is_paid)
                            <div class="d3-exam-price">
                                {{ ($settings['currency_symbol'] ?? '$') }}{{ number_format($exam->price, 2) }}
                            </div>
                        @else
                            <div class="d3-exam-price d3-price-free">
                                FREE
                            </div>
                        @endif

                        @if(\Auth::check())
                            <a href="{{ route('exam.participate', $exam->slug) }}" class="d3-btn-take-exam">
                                <i class="fas fa-play-circle"></i>
                                <span>Take Exam</span>
                            </a>
                        @else
                            <a href="{{ route('login', ['redirect' => route('exam.participate', $exam->slug)]) }}" class="d3-btn-take-exam">
                                <i class="fas fa-play-circle"></i>
                                <span>Login to Take</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="d3-empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>No Exams Available</h4>
                    <p>Check back soon for new exams!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('d3ExamSearch');
        const filterTabs = document.querySelectorAll('.d3-filter-tab');
        const examCards = document.querySelectorAll('.d3-exam-card');
        let currentFilter = 'all';

        function filterExams() {
            const searchTerm = (searchInput?.value || '').toLowerCase();
            let visibleCount = 0;

            examCards.forEach(card => {
                const matchesStatus = currentFilter === 'all' || card.dataset.status === currentFilter;
                const matchesSearch = card.dataset.title.includes(searchTerm);

                if (matchesStatus && matchesSearch) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            const emptyState = document.querySelector('.d3-empty-state');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
            }
        }

        searchInput?.addEventListener('input', filterExams);

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                filterExams();
            });
        });

        filterExams();
    });
</script>
