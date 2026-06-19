<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-testimonials.css') }}">
<section class="d2-testi-section" id="testimonials">
    <div class="container">
        <div class="d2-testi-header">
            <h2 class="d2-testi-title">
                {{ dynamicTransHelper($settings['testimonials_title'] ?? 'Trusted by Professionals') }}
            </h2>
            <p class="d2-testi-desc">
                {{ dynamicTransHelper($settings['testimonials_subtitle'] ?? 'See how ZiExam AI is transforming assessment workflows for users around the globe.') }}
            </p>
        </div>
        <div class="swiper d2-testi-slider-container">
            <div class="swiper-wrapper">
                @php
                    $themes = ['theme-blue', 'theme-green', 'theme-purple', 'theme-orange'];
                @endphp
                @foreach($testimonials as $index => $t)
                @php $currentTheme = $themes[$index % count($themes)]; @endphp
                <div class="swiper-slide h-auto">
                    <div class="d2-testi-card {{ $currentTheme }}">
                        <div class="d2-testi-quote">
                            <i class="fa-solid fa-quote-right"></i>
                        </div>
                        <div class="d2-testi-stars">
                            @for($i=1; $i<=$t->rating; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @for($i=$t->rating + 1; $i<=5; $i++)
                                <i class="fa-regular fa-star opacity-25"></i>
                            @endfor
                        </div>
                        <div class="d2-testi-review">
                            "{{ dynamicTransHelper($t->review) }}"
                        </div>
                        <div class="d2-testi-author">
                            <div class="d2-testi-avatar">
                                @if($t->avatar)
                                    <img src="{{ asset('storage/'.$t->avatar) }}" alt="{{ $t->name }}">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="d2-testi-name">{{ $t->name }}</h6>
                                <span class="d2-testi-role">{{ dynamicTransHelper($t->role) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.d2-testi-slider-container', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    });
</script>