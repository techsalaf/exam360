<?php

return [
    'save' => 'حفظ التغييرات',
    'configure' => 'تهيئة',
    'active' => 'نشط',
    'variables' => 'المتغيرات:',
    'test_mail' => 'بريد تجريبي',
    'copy_url' => 'نسخ الرابط',
    
    'general' => [
        'title' => 'مركز الإشعارات',
        'subtitle' => 'إدارة المشغلات، المنطق، وبوابات الطرف الثالث لجميع قنوات الاتصال.',
        'tabs' => [
            'logic' => 'المنطق والمشغلات',
            'sms' => 'بوابة SMS',
            'push' => 'إعدادات الـ Push',
            'templates' => 'القوالب',
        ],
        'kpi' => [
            'email' => 'نظام البريد',
            'sms' => 'نظام SMS',
            'push' => 'إشعارات الـ Push',
            'global_switch' => 'المفتاح العام',
        ],
        'triggers' => [
            'title' => 'مشغلات الأحداث والتوجيه',
            'col_event' => 'مشغل الحدث',
            'signup' => 'تسجيل مستخدم جديد',
            'signup_desc' => 'يتم تفعيله فوراً بعد التسجيل.',
            'exam' => 'إكمال الاختبار',
            'exam_desc' => 'معالجة النتائج والدرجات.',
            'payment' => 'نجاح عملية الدفع',
            'payment_desc' => 'الفواتير وتفعيل الخطط.',
        ],
        'sms_gateways' => [
            'provider' => 'مزود خدمة SMS',
            'twilio' => 'Twilio',
            'vonage' => 'Vonage',
            'standard' => 'قياسي',
            'international' => 'دولي',
            'api_creds' => 'بيانات اعتماد الـ API',
            'account_sid' => 'معرف الحساب (Account SID)',
            'api_key' => 'مفتاح الـ API',
            'auth_token' => 'رمز المصادقة (Auth Token)',
            'api_secret' => 'سر الـ API (Secret)',
            'from' => 'رقم المرسل',
            'from_desc' => 'رقم المرسل (صيغة E.164)',
            'sender_id' => 'معرف المرسل (اسم العلامة التجارية)',
            'env' => 'البيئة',
            'sandbox' => 'تفعيل وضع الاختبار (Sandbox)',
        ],
        'firebase' => [
            'title' => 'مراسلة Firebase السحابية (FCM)',
            'server_key' => 'مفتاح الخادم (Legacy)',
            'project_id' => 'معرف المشروع',
            'app_id' => 'معرف التطبيق',
            'sender_id' => 'معرف المرسل',
            'bucket' => 'وعاء التخزين (Storage Bucket)',
        ],
        'template_links' => [
            'email' => 'قوالب البريد الإلكتروني',
            'sms' => 'قوالب SMS',
            'push' => 'قوالب الـ Push',
        ],
    ],

    'social' => [
        'title' => 'تسجيل الدخول الاجتماعي',
        'subtitle' => 'تهيئة مزودي OAuth للتسجيل وتسجيل الدخول بنقرة واحدة.',
        'google' => 'تسجيل الدخول عبر جوجل',
        'google_desc' => 'تفعيل الدخول عبر حساب جوجل.',
        'facebook' => 'تسجيل الدخول عبر فيسبوك',
        'facebook_desc' => 'تفعيل الدخول عبر حساب فيسبوك.',
        'client_id' => 'معرف العميل (Client ID)',
        'client_secret' => 'سر العميل (Client Secret)',
        'app_id' => 'معرف التطبيق (App ID)',
        'app_secret' => 'سر التطبيق (App Secret)',
        'callback' => 'رابط العودة (Callback URL)',
        'google_help' => 'انسخ هذا الرابط والصقه في إعدادات Google Developer Console.',
        'facebook_help' => 'انسخ هذا الرابط والصقه في إعدادات مطوري فيسبوك (OAuth redirect URIs).',
    ],

    'email' => [
        'title' => 'إعدادات البريد الإلكتروني',
        'subtitle' => 'تهيئة محركات البريد الصادر وهوية المرسل لرسائل النظام.',
        'driver_label' => 'محرك البريد',
        'drivers' => [
            'smtp' => 'SMTP',
            'smtp_desc' => 'موصى به',
            'php' => 'PHP Mail',
            'php_desc' => 'افتراضي الخادم',
            'mailgun' => 'Mailgun',
            'mailgun_desc' => 'يعتمد على API',
        ],
        'smtp' => [
            'title' => 'تفاصيل اتصال SMTP',
            'host' => 'مستضيف البريد',
            'port' => 'المنفذ',
            'username' => 'اسم المستخدم',
            'password' => 'كلمة المرور',
            'encryption' => 'التشفير',
            'none' => 'بدون',
        ],
        'mailgun' => [
            'title' => 'إعدادات Mailgun API',
            'domain' => 'نطاق Mailgun',
            'secret' => 'سر Mailgun (مفتاح API)',
            'endpoint' => 'نقطة نهاية Mailgun',
            'help' => 'استخدم api.eu.mailgun.net لمناطق الاتحاد الأوروبي.',
        ],
        'identity' => [
            'title' => 'هوية المرسل',
            'name' => 'اسم المرسل',
            'name_desc' => 'يظهر في صندوق الوارد لدى المستلم.',
            'address' => 'بريد المرسل',
            'address_desc' => 'يجب أن يكون مصرحاً به من قبل مزود الخدمة الخاص بك.',
        ],
        'alerts' => [
            'confirm_test' => 'إرسال بريد تجريبي للمستخدم المسجل حالياً؟ تأكد من حفظ التغييرات أولاً.',
            'sending' => 'جاري الإرسال...',
            'success' => 'نجاح',
            'failed' => 'فشل الاتصال',
            'error' => 'حدث خطأ غير متوقع.',
        ]
    ],

    'templates' => [
        'email_title' => 'قوالب البريد الإلكتروني',
        'email_subtitle' => 'تخصيص موضوع ومحتوى HTML لرسائل النظام.',
        'sms_title' => 'قوالب الرسائل القصيرة (SMS)',
        'sms_subtitle' => 'تكوين رسائل نصية بحد أقصى 160 حرفاً يتم إرسالها للمستخدمين.',
        'push_title' => 'إشعارات الـ Push',
        'push_subtitle' => 'إدارة مظهر ومحتوى تنبيهات تطبيق الجوال.',
        
        'tabs' => [
            'signup' => 'ترحيب التسجيل',
            'exam' => 'نتيجة الاختبار',
            'payment' => 'إيصال الدفع',
        ],
        
        'fields' => [
            'subject' => 'عنوان الموضوع',
            'html_body' => 'محتوى HTML',
            'content' => 'محتوى الرسالة',
            'alert_title' => 'عنوان التنبيه',
            'alert_body' => 'محتوى التنبيه',
        ],

        'defaults' => [
            'signup_sub' => 'مرحباً بك في منصتنا!',
            'signup_body' => '<p>أهلاً {{name}}،</p><p>مرحباً بك في منصتنا!</p>',
            'exam_sub' => 'نتائج الاختبار متاحة الآن',
            'exam_body' => '<p>مرحباً {{name}}،</p><p>لقد حصلت على درجة <strong>{{score}}%</strong>.</p>',
            'pay_sub' => 'إيصال الدفع',
            'pay_body' => '<p>لقد استلمنا دفعتك بقيمة <strong>{{amount}}</strong>.</p>',
            
            'push_signup_t' => 'مرحباً بك!',
            'push_signup_b' => 'شكراً لانضمامك إلى منصتنا.',
            'push_exam_t' => 'نتائج الاختبار',
            'push_exam_b' => 'لقد حصلت على {{score}}% في اختبار {{exam}}.',
            'push_pay_t' => 'تم استلام الدفع',
            'push_pay_b' => 'اشتراكك في خطة {{plan}} نشط الآن.',
            
            'sms_signup' => 'مرحباً بك في Ziexam يا {{name}}! قم بالتحقق من هنا: {{link}}',
            'sms_exam' => 'مبارك يا {{name}}! لقد اجتزت اختبار {{exam}} بنسبة {{score}}%.',
            'sms_pay' => 'تم استلام مبلغ {{amount}} لخطة {{plan}}. شكراً لك!',
        ],

        'preview' => [
            'label' => 'معاينة',
            'app_name' => 'اسم التطبيق',
            'now' => 'الآن',
        ]
    ]
];