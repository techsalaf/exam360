<?php

return [
    'save' => 'حفظ التغييرات',
    'save_config' => 'حفظ التكوين',
    'generate_now' => 'إنشاء خريطة الموقع الآن',
    
    // ALERTS
    'alerts' => [
        'remove_title' => 'إزالة البانر؟',
        'remove_text' => 'سيؤدي هذا إلى إزالة بنر SEO الحالي عند الحفظ.',
        'yes_remove' => 'نعم، قم بالإزالة',
        'cancel' => 'إلغاء',
        'invalid_group' => 'مجموعة إعدادات غير صالحة.',
        'updated_success' => 'تم تحديث إعدادات SEO بنجاح.',
        'sitemap_generated' => 'تم إنشاء خريطة الموقع بنجاح.',
        'sitemap_failed' => 'فشل إنشاء خريطة الموقع: :error',
        'sitemap_not_found' => 'ملف خريطة الموقع غير موجود. يرجى إنشاؤه أولاً.',
    ],

    'defaults' => [
        'desc' => 'أفضل منصة للتعلم والتقييم مدعومة بالذكاء الاصطناعي.',
        'keywords' => 'اختبار، ذكاء اصطناعي، تقييم، تعلم',
    ],

    'config' => [
        'title' => 'إعدادات SEO',
        'desc' => 'تكوين وسوم الميتا، صور المشاركة الاجتماعية، وتتبع التحليلات.',
        'meta_title' => 'وسوم الميتا والمرئيات',
        'meta_desc' => 'تحسين كيفية ظهور موقعك في نتائج البحث وخلاصات التواصل الاجتماعي.',
        'meta_title_label' => 'عنوان الميتا (الحد الأقصى 60 حرفاً)',
        'meta_title_ph' => 'مثلاً: ZiExam AI - منصة تعليمية',
        'meta_desc_label' => 'وصف الميتا (الحد الأقصى 160 حرفاً)',
        'meta_desc_ph' => 'ملخص موجز لمحتوى موقعك.',
        'keywords_label' => 'الكلمات المفتاحية (مفصولة بفواصل)',
        'keywords_ph' => 'كلمات، مفتاحية، مفصولة، بفواصل',
        
        'analytics_title' => 'التحليلات والتحقق',
        'ga_label' => 'معرف تتبع تحليلات جوجل (Google Analytics)',
        'ga_ph' => 'UA-XXXXXXXXX-Y أو G-XXXXXXXXX',
        'ga_help' => 'أدخل معرف القياس الخاص بـ Google Analytics/GA4.',
        
        'banner_title' => 'بنر المشاركة الاجتماعية',
        'banner_help' => 'الحجم الموصى به: 1200x630 بكسل. يستخدم لـ OpenGraph / Twitter Cards.',
        'delete_banner_title' => 'حذف البنر الحالي',
        'no_banner' => 'لم يتم رفع أي بنر.',
    ],

    'sitemap' => [
        'title' => 'إعدادات خريطة الموقع (Sitemap)',
        'desc' => 'التحكم في زحف محركات البحث وإدارة ملف خريطة الموقع XML.',
        'crawling_title' => 'قواعد الزحف',
        'crawling_desc' => 'تحديد كيفية تفاعل برامج الروبوت مع هيكل موقعك.',
        'robots_label' => 'وسم الروبوتات (Robots Meta Tag)',
        'robots_options' => [
            'index_follow' => 'أرشفة ومتابعة (الافتراضي)',
            'noindex_follow' => 'عدم الأرشفة، مع متابعة الروابط',
            'index_nofollow' => 'الأرشفة، مع عدم متابعة الروابط',
            'noindex_nofollow' => 'عدم الأرشفة وعدم المتابعة',
        ],
        'robots_help' => 'يتحكم في سلوك الأرشفة على مستوى الموقع بالكامل.',
        
        'status_title' => 'حالة خريطة الموقع',
        'file_url' => 'رابط الملف:',
        'last_gen' => 'آخر إنشاء:',
        'never' => 'أبداً',
        'download_xml' => 'تحميل ملف XML',
        'info_text' => 'يساعد ملف <strong>sitemap.xml</strong> محركات البحث على اكتشاف صفحاتك. بعد الإنشاء، قم بتقديم الرابط الكامل أعلاه إلى Google Search Console.',
    ],
];