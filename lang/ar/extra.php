<?php

return [
    // Application Page
    'app_title' => 'صحة التطبيق',
    'app_subtitle' => 'نظرة عامة على تكوين التطبيق، وحالة قاعدة البيانات، وصلاحيات نظام الملفات.',
    'core_config' => 'التكوين الأساسي',
    'app_name' => 'اسم التطبيق',
    'app_url' => 'رابط التطبيق (URL)',
    'environment' => 'البيئة',
    'debug_mode' => 'وضع التصحيح (Debug Mode)',
    'timezone' => 'المنطقة الزمنية',
    'locale' => 'اللغة المحلية',
    'db_status' => 'حالة قاعدة البيانات',
    'db_connected' => 'متصل',
    'db_connection' => 'نوع الاتصال',
    'db_size' => 'الحجم الإجمالي',
    'permissions' => 'صلاحيات نظام الملفات',
    'perm_hint' => 'تأكد من أن هذه المجلدات تمتلك تصاريح 775 أو 755 ليعمل التطبيق بشكل صحيح.',
    'enabled' => 'مفعّل',
    'disabled' => 'معطّل',
    'true' => 'صحيح',
    'false' => 'خطأ',

    // Server Page
    'server_title' => 'بيئة الخادم',
    'server_subtitle' => 'تفاصيل تقنية حول الخادم وإعدادات PHP الأساسية.',
    'php_config' => 'إعدادات PHP',
    'memory_limit' => 'حد الذاكرة',
    'max_execution' => 'أقصى وقت للتنفيذ',
    'upload_max' => 'أقصى حجم للرفع',
    'post_max' => 'أقصى حجم لبيانات POST',
    'loaded_ext' => 'الإضافات المحملة',
    'installed' => 'مثبت',
    'host_info' => 'معلومات المستضيف',
    'ip_address' => 'عنوان IP',
    'protocol' => 'البروتوكول',
    'software' => 'برمجيات الخادم',

    // Cache Page
    'cache_title' => 'إدارة التخزين المؤقت (Cache)',
    'cache_subtitle' => 'مسح ملفات البيانات المؤقتة لحل مشاكل الإعدادات أو تحديث الواجهات.',
    'active_drivers' => 'المحركات النشطة',
    'sys_cache' => 'ذاكرة نظام الكاش',
    'session_store' => 'مخزن الجلسات',
    'queue_worker' => 'عامل الطوابير (Queue)',
    'mail_system' => 'نظام البريد',
    'quick_actions' => 'إجراءات سريعة',
    'btn_optimize' => 'تحسين النظام (Optimize)',
    'app_cache' => 'كاش التطبيق',
    'app_cache_desc' => 'يمسح بيانات التطبيق العامة.',
    'route_cache' => 'كاش المسارات',
    'route_cache_desc' => 'يعالج أخطاء 404 والروابط.',
    'config_cache' => 'كاش الإعدادات',
    'config_cache_desc' => 'يعيد تحميل إعدادات ملف .env.',
    'view_cache' => 'كاش الواجهات (Blade)',
    'view_cache_desc' => 'يحدث واجهة المستخدم المجمعة.',

    // Update Page
    'update_title'         => 'مركز التحديثات',
    'update_subtitle'      => 'إدارة تحديثات النظام وعرض سجل التغييرات.',
    'btn_check_update'     => 'التحقق من وجود تحديثات',
    'up_to_date'           => 'نظامك محدث بالكامل!',
    'current_ver'          => 'الإصدار الحالي للنظام',
    'last_checked'         => 'آخر فحص:',

    // Manual Update
    'manual_update_title'  => 'تحديث يدوي',
    'manual_update_desc'   => 'قم برفع حزمة التحديث الأخيرة المقدمة من المطور. سيقوم النظام تلقائياً بفك ضغط الملفات وتشغيل عمليات ترحيل قاعدة البيانات.',
    'supported_file'       => 'الملف المدعوم',
    'max_size'             => 'الحد الأقصى',
    'important_label'      => 'هام',
    'update_warning'       => 'ستؤدي هذه العملية إلى استبدال ملفات النظام الأساسية. يرجى التأكد من أن لديك نسخة احتياطية كاملة لقاعدة البيانات والملفات قبل المتابعة.',
    'btn_upload_update'    => 'رفع وتحديث النظام',
    
    // JS Alerts
    'js_confirm_update'    => 'هل أنت متأكد تماماً من رغبتك في التحديث؟ هذا الإجراء لا يمكن التراجع عنه وسيقوم باستبدال ملفات النظام.',
    'js_processing'        => 'جاري المعالجة...',
];