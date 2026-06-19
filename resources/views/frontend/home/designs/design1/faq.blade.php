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

    $faqsDefaults = [
        1 => [
            'icon'  => 'fa-server',
            'title' => 'Is this a SaaS ready script?',
            'desc'  => 'Yes, it supports multi-user roles, subscription models, and payment gateways out of the box.'
        ],
        2 => [
            'icon'  => 'fa-money-bill-transfer',
            'title' => 'Can I resell the exams I create?',
            'desc'  => 'Absolutely. You can create paid exams or bundle them into subscription plans to earn revenue.'
        ],
        3 => [
            'icon'  => 'fa-brands fa-stripe',
            'title' => 'Can I add my own payment gateway?',
            'desc'  => 'The system comes with Stripe and PayPal. Being Laravel based, developers can easily extend it.'
        ],
        4 => [
            'icon'  => 'fa-wand-magic-sparkles',
            'title' => 'Is the AI integration optional?',
            'desc'  => 'Yes, you can create questions manually if you prefer not to use the AI generation features.'
        ]
    ];

    // --- Header Fallback Logic ---
    $headerTitleKey = 'frontend.faq_title_default';
    $headerSubtitleKey = 'frontend.faq_subtitle_default';

    // 1. Attempt to get title from settings or translation key
    $faqTitle = get_trans($settings['faq_title'] ?? '') ?: __($headerTitleKey);
    $faqSubtitle = get_trans($settings['faq_subtitle'] ?? '') ?: __($headerSubtitleKey);
    
    // 2. If the translation failed (returned the key name), use a hardcoded English string
    if ($faqTitle === $headerTitleKey) {
        $faqTitle = 'Frequently Asked Questions';
    }
    if ($faqSubtitle === $headerSubtitleKey) {
        $faqSubtitle = 'Find answers to the most common questions about our platform.';
    }
    
@endphp

<section class="section-py bg-light" id="faq">
    <div class="container">
        
        <div class="section-title">
            <h2>{{ $faqTitle }}</h2>
            <p>{{ $faqSubtitle }}</p>
        </div>

        <div class="faq-grid">
            @foreach($faqsDefaults as $key => $faq)
                @php
                    // 1. Get value from custom settings (translated)
                    $cardTitle = get_trans($settings["faq_q{$key}_title"] ?? '');
                    $cardDesc = get_trans($settings["faq_q{$key}_desc"] ?? '');

                    // 2. Fallback to Laravel translation key
                    if (empty($cardTitle)) {
                        $cardTitle = __('frontend.faq_q'.$key.'_title');
                    }
                    if (empty($cardDesc)) {
                        $cardDesc = __('frontend.faq_q'.$key.'_desc');
                    }

                    // 3. Final fallback: Use hardcoded default from $faqsDefaults if translation failed (key returned)
                    if (str_contains($cardTitle, 'frontend.faq_q'.$key)) {
                        $cardTitle = $faq['title'];
                    }
                    if (str_contains($cardDesc, 'frontend.faq_q'.$key)) {
                        $cardDesc = $faq['desc'];
                    }
                @endphp
                
                <div class="faq-card">
                    <div class="faq-icon">
                        <i class="{{ $settings["faq_q{$key}_icon"] ?? ('fa-solid ' . $faq['icon']) }}"></i>
                    </div>
                    <div class="faq-content">
                        <h5>{{ $cardTitle }}</h5>
                        <p>{{ $cardDesc }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>