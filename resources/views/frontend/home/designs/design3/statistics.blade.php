<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-statistics.css') }}">

<section class="d3-statistics-section">
    <div class="container">
        <div class="d3-stats-grid">
            <!-- Total Exams -->
            <div class="d3-stat-card">
                <div class="d3-stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="d3-stat-content">
                    <div class="d3-stat-number" data-target="{{ $totalExams }}">
                        0
                    </div>
                    <div class="d3-stat-label">Total Exams</div>
                </div>
            </div>

            <!-- Total Questions -->
            <div class="d3-stat-card">
                <div class="d3-stat-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="d3-stat-content">
                    <div class="d3-stat-number" data-target="{{ $totalQuestions }}">
                        0
                    </div>
                    <div class="d3-stat-label">Total Questions</div>
                </div>
            </div>

            <!-- Active Exams -->
            <div class="d3-stat-card">
                <div class="d3-stat-icon">
                    <i class="fas fa-circle-play"></i>
                </div>
                <div class="d3-stat-content">
                    <div class="d3-stat-number" data-target="{{ $activeExams }}">
                        0
                    </div>
                    <div class="d3-stat-label">Active Exams</div>
                </div>
            </div>

            <!-- Registered Students -->
            <div class="d3-stat-card">
                <div class="d3-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="d3-stat-content">
                    <div class="d3-stat-number" data-target="{{ $registeredStudents }}">
                        0
                    </div>
                    <div class="d3-stat-label">Active Students</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    
                    const numbers = entry.target.querySelectorAll('[data-target]');
                    numbers.forEach(num => {
                        const target = parseInt(num.dataset.target);
                        const duration = 2000;
                        const increment = target / (duration / 16);
                        let current = 0;

                        const counter = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                num.textContent = target.toLocaleString();
                                clearInterval(counter);
                            } else {
                                num.textContent = Math.floor(current).toLocaleString();
                            }
                        }, 16);
                    });

                    observer.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.d3-statistics-section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    });
</script>
