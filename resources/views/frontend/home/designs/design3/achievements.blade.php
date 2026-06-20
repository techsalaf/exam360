<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-achievements.css') }}">

<section class="d3-achievements-section">
    <div class="container">
        <div class="d3-section-header">
            <h2 class="d3-section-title">
                {{ dynamicTransHelper($settings['d3_achievement_title'] ?? 'Achievement Levels') }}
            </h2>
            <p class="d3-section-description">
                {{ dynamicTransHelper($settings['d3_achievement_subtitle'] ?? 'Students are recognized for their academic excellence across three achievement tiers') }}
            </p>
        </div>

        <div class="d3-achievements-grid">
            <!-- Champion -->
            <div class="d3-achievement-card d3-achievement-champion">
                <div class="d3-achievement-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="d3-achievement-title">Champion</h3>
                <p class="d3-achievement-description">
                    {{ dynamicTransHelper($settings['d3_champion_desc'] ?? 'Students who attain the highest level of academic excellence and mastery.') }}
                </p>
                <div class="d3-achievement-stats">
                    <span class="d3-achievement-badge">Top 10%</span>
                </div>
            </div>

            <!-- Scholar -->
            <div class="d3-achievement-card d3-achievement-scholar">
                <div class="d3-achievement-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="d3-achievement-title">Scholar</h3>
                <p class="d3-achievement-description">
                    {{ dynamicTransHelper($settings['d3_scholar_desc'] ?? 'Students who score within the excellent academic range.') }}
                </p>
                <div class="d3-achievement-stats">
                    <span class="d3-achievement-badge">Top 30%</span>
                </div>
            </div>

            <!-- Rising Star -->
            <div class="d3-achievement-card d3-achievement-rising-star">
                <div class="d3-achievement-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="d3-achievement-title">Rising Star</h3>
                <p class="d3-achievement-description">
                    {{ dynamicTransHelper($settings['d3_rising_star_desc'] ?? 'Students who meet or exceed their Academic Performance Target.') }}
                </p>
                <div class="d3-achievement-stats">
                    <span class="d3-achievement-badge">70%+</span>
                </div>
            </div>
        </div>
    </div>
</section>
