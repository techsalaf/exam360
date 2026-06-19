@php
    // Smart Translation Helper
    if (!function_exists('get_trans')) {
        function get_trans($jsonString) {
            if (empty($jsonString)) return '';
            $decoded = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return $jsonString; 
            }
            $locale = app()->getLocale();
            return $decoded[$locale] ?? $decoded['en'] ?? reset($decoded) ?? '';
        }
    }

    // Helper to decode JSON translations safely for external data (testimonials)
    $decode = function($val) {
        if (empty($val)) return '';
        // If the value is an object (Eloquent Model Attribute with json casting), use data_get
        if (is_object($val) || is_array($val)) {
            $val = json_encode($val); // Re-encode to string to use get_trans helper
        }
        return get_trans($val);
    };

    // Helper to determine image URL
    $getAvatarUrl = function($path) {
        if (empty($path)) return asset('assets/frontend/images/avatars/default.jpg'); 
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return Storage::url($path); // Assuming user-uploaded avatars go through Storage::url
    };

    // Fallback data
    $fallbackTestimonials = [
        // ... (your fallback data remains the same) ...
        [
            'name' => 'Ananya Rao', 
            'role' => 'Academic Coordinator', 
            'avatar' => 'assets/frontend/images/avatars/user-1.jpg',
            'review' => 'We went from manual grading to instant evaluation overnight. The platform completely transformed how we conduct assessments.'
        ],
        [
            'name' => 'Daniel Moore', 
            'role' => 'Training Lead', 
            'avatar' => 'assets/frontend/images/avatars/user-2.jpg',
            'review' => 'Creating exams used to take days. Now we generate structured, high-quality tests in minutes without compromising standards.'
        ],
        [
            'name' => 'Meera Patel', 
            'role' => 'Program Manager', 
            'avatar' => 'assets/frontend/images/avatars/user-3.jpg',
            'review' => 'The analytics alone are worth it. We finally understand how students are performing across every exam.'
        ],
        [
            'name' => 'Lucas Bennett', 
            'role' => 'EdTech Founder', 
            'avatar' => 'assets/frontend/images/avatars/user-4.jpg',
            'review' => 'Launching paid exams was seamless. Subscriptions, access control, and reporting—all in one place.'
        ]
    ];

    $items = (isset($testimonials) && $testimonials->count() > 0) ? $testimonials : collect($fallbackTestimonials);
    $chunkSize = ceil($items->count() / 2);
    $row1 = $items->take($chunkSize); 
    $row2 = $items->skip($chunkSize);
@endphp

<section class="testimonial-section">
    <div class="container text-center mb-5">
        <h2 class="testimonial-title">
            {{ get_trans($settings['testimonials_title'] ?? '') ?: __('frontend.testimonials_title_default') }}
        </h2>
        <p class="testimonial-desc">
            {{ get_trans($settings['testimonials_subtitle'] ?? '') ?: __('frontend.testimonials_subtitle_default') }}
        </p>
    </div>

    <!-- Marquee Wrapper -->
    <div class="marquee-wrapper">
        
        <!-- Row 1 (Scroll Left) -->
        <div class="marquee-track scroll-left">
            @foreach($row1 as $t)
                <div class="testimonial-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    {{-- Assuming 'review' and 'role' from the Testimonial model might be JSON translated --}}
                    <p class="review-text">“{{ $decode(data_get($t, 'review')) }}”</p>
                    <div class="user-info">
                        <div class="user-avatar" style="background-image: url('{{ $getAvatarUrl(data_get($t, 'avatar')) }}');"></div>
                        <div>
                            <h6 class="user-name">{{ data_get($t, 'name', 'User') }}</h6>
                            <span class="user-role">{{ $decode(data_get($t, 'role')) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Duplicates -->
            @foreach($row1 as $t)
                <div class="testimonial-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <p class="review-text">“{{ $decode(data_get($t, 'review')) }}”</p>
                    <div class="user-info">
                        <div class="user-avatar" style="background-image: url('{{ $getAvatarUrl(data_get($t, 'avatar')) }}');"></div>
                        <div>
                            <h6 class="user-name">{{ data_get($t, 'name', 'User') }}</h6>
                            <span class="user-role">{{ $decode(data_get($t, 'role')) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Row 2 (Scroll Right) -->
        <div class="marquee-track scroll-right">
            @foreach($row2 as $t)
                <div class="testimonial-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <p class="review-text">“{{ $decode(data_get($t, 'review')) }}”</p>
                    <div class="user-info">
                        <div class="user-avatar" style="background-image: url('{{ $getAvatarUrl(data_get($t, 'avatar')) }}');"></div>
                        <div>
                            <h6 class="user-name">{{ data_get($t, 'name', 'User') }}</h6>
                            <span class="user-role">{{ $decode(data_get($t, 'role')) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Duplicates -->
            @foreach($row2 as $t)
                <div class="testimonial-card">
                    <i class="fa-solid fa-quote-left quote-icon"></i>
                    <p class="review-text">“{{ $decode(data_get($t, 'review')) }}”</p>
                    <div class="user-info">
                        <div class="user-avatar" style="background-image: url('{{ $getAvatarUrl(data_get($t, 'avatar')) }}');"></div>
                        <div>
                            <h6 class="user-name">{{ data_get($t, 'name', 'User') }}</h6>
                            <span class="user-role">{{ $decode(data_get($t, 'role')) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>