<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-categories.css') }}">
<section class="d2-categories-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="d2-badge-container">
                <span class="d2-popular-badge">
                    Browse through our diverse range of assessment categories designed for every skill level.
                </span>
            </div>
            <h2 class="d2-section-title">Explore Exams by Category</h2>
            <p class="d2-section-desc">Browse top-rated subjects and discover trending courses designed to boost your skills</p>
        </div>

        <div class="d2-category-grid">
            @php
                $colors = ['#eff6ff', '#f0fdf4', '#fff1f2', '#f5f3ff', '#fffbeb', '#fafaf9', '#f0fdfa', '#fdf2f8', '#f0f9ff', '#fff7ed'];
                $accentColors = ['#3b82f6', '#10b981', '#f43f5e', '#8b5cf6', '#f59e0b', '#78716c', '#14b8a6', '#db2777', '#0ea5e9', '#ea580c'];
            @endphp
            @foreach($categories as $index => $category)
                @php
                    $catImage = $category->image ? asset('storage/' . $category->image) : null;
                    $fallbackColor = $colors[$index % count($colors)];
                    $accentColor = $accentColors[$index % count($accentColors)];
                @endphp
                <a href="{{ route('exams.list', ['category[]' => $category->slug]) }}" class="d2-cat-card-wrapper">
                    <div class="d2-cat-card-box {{ $catImage ? 'has-banner' : '' }}" 
                         style="{{ $catImage ? 'background-image: url('.$catImage.');' : 'background-color: '.$fallbackColor.'; border: 1px solid '.$accentColor.'20;' }}">
                        
                        <div class="d2-cat-icon-white" style="border: 1px solid {{ $accentColor }}40;">
                            <i class="fa-solid fa-microchip" style="color: {{ $accentColor }};"></i>
                        </div>
                    </div>
                    <h6 class="d2-cat-name">{{ $category->name }}</h6>
                </a>
            @endforeach
        </div>
    </div>
</section>