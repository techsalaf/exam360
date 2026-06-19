<link rel="stylesheet" href="{{ asset('assets/css/frontend/design2/design2-faq.css') }}">
<section class="d2-faq-premium-section">
    <div class="container">
        <div class="d2-faq-header text-center mb-5">
            <h2 class="d2-faq-main-title">
                {{ get_trans($settings['faq_title'] ?? 'Frequently Asked Questions') }}
            </h2>
            <p class="d2-faq-main-desc">
                {{ get_trans($settings['faq_subtitle'] ?? 'Everything you need to know about the product and billing.') }}
            </p>
        </div>
        <div class="d2-faq-wrapper">
            <div class="accordion" id="fixedDesign2Faq">
                @php
                    $themes = ['theme-blue', 'theme-green', 'theme-purple', 'theme-orange'];
                @endphp
                @for($i=1; $i<=4; $i++)
                    @php
                        $q = get_trans($settings["faq_q{$i}_title"] ?? '');
                        if(empty($q)) continue;
                        $currentTheme = $themes[($i-1) % count($themes)];
                    @endphp
                    <div class="d2-faq-card {{ $currentTheme }}">
                        <h3 class="m-0">
                            <button class="d2-faq-trigger collapsed" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#faqCollapseItem{{$i}}" 
                                    aria-expanded="false"
                                    aria-controls="faqCollapseItem{{$i}}">
                                {{ $q }}
                            </button>
                        </h3>
                        <div id="faqCollapseItem{{$i}}" 
                             class="collapse d2-faq-pane" 
                             data-bs-parent="#fixedDesign2Faq">
                            <div class="d2-faq-content">
                                {{ get_trans($settings["faq_q{$i}_desc"] ?? '') }}
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const triggers = document.querySelectorAll('.d2-faq-trigger');
        triggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                if (typeof bootstrap === 'undefined') {
                    const targetId = this.getAttribute('data-bs-target');
                    const pane = document.querySelector(targetId);
                    const isOpen = pane.classList.contains('show');
                    document.querySelectorAll('.d2-faq-pane').forEach(el => el.classList.remove('show'));
                    document.querySelectorAll('.d2-faq-trigger').forEach(el => el.classList.add('collapsed'));
                    if (!isOpen) {
                        pane.classList.add('show');
                        this.classList.remove('collapsed');
                    }
                }
            });
        });
    });
</script>