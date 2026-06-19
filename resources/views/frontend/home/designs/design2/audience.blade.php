<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-audience.css') }}">
<section class="d2-audience-section">
    <div class="container">
        <div class="text-center mb-5 pb-lg-2">
            <h2 class="d2-audience-title">
                {{ dynamicTransHelper($settings['audience_title'] ?? 'Who is ZiExam AI For?') }}
            </h2>
            <p class="d2-audience-desc">
                {{ dynamicTransHelper($settings['audience_subtitle'] ?? 'Our platform is designed to cater to a wide range of educational and professional needs.') }}
            </p>
        </div>
        <div class="d2-audience-grid">
            @php
                $img1 = !empty($settings['aud_c1_image']) ? asset('storage/'.$settings['aud_c1_image']) : asset('assets/img/audience/aud1.jpg');
            @endphp
            <div class="audience-item large-card theme-blue">
                <div class="audience-card-inner" style="background-image: url('{{ $img1 }}');">
                    <div class="audience-overlay">
                        <div class="audience-content">
                            <h4 class="audience-item-title">
                                {{ dynamicTransHelper($settings['aud_c1_title'] ?? 'Coaching Institutes') }}
                            </h4>
                            <div class="audience-user-info">
                                <div class="user-avatar"></div>
                                <div>
                                    <p class="user-name">ZiExam Member</p>
                                    <p class="user-meta">in Solutions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="audience-side-grid">
                @php
                    $themes = ['theme-green', 'theme-purple', 'theme-orange', 'theme-blue'];
                @endphp
                @for($i=2; $i<=5; $i++)
                @php 
                    $currentTheme = $themes[($i-2) % count($themes)]; 
                    $img = !empty($settings['aud_c'.$i.'_image']) ? asset('storage/'.$settings['aud_c'.$i.'_image']) : asset('assets/img/audience/aud'.$i.'.jpg');
                @endphp
                <div class="audience-item small-card {{ $currentTheme }}">
                    <div class="audience-card-inner" style="background-image: url('{{ $img }}');">
                        <div class="audience-overlay">
                            <div class="audience-content">
                                <h5 class="audience-item-title small">
                                    {{ dynamicTransHelper($settings['aud_c'.$i.'_title'] ?? 'Education Sector') }}
                                </h5>
                                <div class="audience-user-info">
                                    <div class="user-avatar"></div>
                                    <div>
                                        <p class="user-name">ZiExam Member</p>
                                        <p class="user-meta">in Solutions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>