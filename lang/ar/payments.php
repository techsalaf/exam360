<?php

return [
    'title' => 'المدفوعات',
    'header_title' => 'سجل المدفوعات',
    'header_subtitle' => 'إدارة وتتبع جميع المعاملات المالية في النظام.',

    // Currency Settings
    'currency_title' => 'إعدادات العملة العالمية',
    'currency_desc' => 'قم بتكوين عملتك الأساسية، وتنسيق العرض، وفواصل المعاملات.',
    'currency_global_currency' => 'العملة العالمية',
    'currency_global_desc' => 'تعيين العملة الأساسية لجميع المعاملات.',
    'currency_primary' => 'العملة الأساسية',
    'currency_position' => 'موقع الرمز',
    'currency_pos_before' => 'قبل المبلغ', 
    'currency_pos_after' => 'بعد المبلغ', 
    'currency_pos_before_space' => 'قبل المبلغ (مع مسافة)', 
    'currency_pos_after_space' => 'بعد المبلغ (مع مسافة)',
    'currency_custom_opt' => 'عملة مخصصة',
    'currency_decimal_sep' => 'الفاصلة العشرية',
    'currency_thousands_sep' => 'فاصل الآلاف',
    'currency_decimal_help' => 'الرمز المستخدم للأرقام العشرية (مثلاً: 10.00).',
    'currency_thousands_help' => 'الرمز المستخدم للآلاف (مثلاً: 1,000).',
    'custom_code_label' => 'الرمز المخصص (مثلاً: :example)',
    'custom_symbol_label' => 'الرمز المخصص (مثلاً: :example)',
    'example_code_placeholder' => 'مثلاً: QAR',
    'example_symbol_placeholder' => 'مثلاً: ر.ق',
    'save_settings' => 'حفظ الإعدادات',
    
    // Buttons & Links
    'btn_filter' => 'تصفية',
    'btn_review_all' => 'مراجعة الكل',
    'btn_export' => 'تصدير البيانات',
    'btn_view' => 'عرض',
    'btn_approve' => 'موافقة',
    'btn_reject' => 'رفض',
    'btn_close' => 'إغلاق',
    'btn_clear_filters' => 'مسح الفلاتر',
    
    // Placeholders & Inputs
    'placeholder_search' => 'البحث في المدفوعات...',
    'label_status' => 'الحالة',
    'opt_all' => 'الكل',
    'opt_pending' => 'قيد الانتظار',
    'opt_success' => 'ناجح',
    'opt_failed' => 'فاشل',
    
    // Alerts
    'alert_pending_count' => 'لديك :count دفعة معلقة|لديك :count دفعات معلقة',
    
    // Table Headers
    'col_trx' => 'معرف العملية / بوابة الدفع',
    'col_user' => 'المستخدم',
    'col_amount' => 'المبلغ (:currency)',
    'col_status' => 'الحالة',
    'col_date' => 'التاريخ',
    'col_action' => 'الإجراء',
    
    // Table Content
    'text_user_deleted' => 'مستخدم محذوف',
    'text_user_not_found' => 'المستخدم غير موجود',
    'status_success' => 'ناجح',
    'status_approved' => 'معتمد',
    'status_pending' => 'قيد الانتظار',
    'status_initiated' => 'مبدوء',
    'status_failed' => 'فاشل',
    'status_rejected' => 'مرفوض',
    'empty_title' => 'لم يتم العثور على مدفوعات',
    
    // Modal Details
    'modal_title' => 'دفع عبر :gateway',
    'sect_user_info' => 'معلومات دفع المستخدم',
    'sect_payment_details' => 'تفاصيل الدفع',
    'label_fname' => 'الاسم الأول',
    'label_lname' => 'اسم العائلة',
    'label_bank' => 'اسم البنك',
    'label_trx' => 'رقم المعاملة',
    'label_screenshot' => 'لقطة شاشة',
    'link_attachment' => 'المرفق',
    'text_no_attachment' => 'لم يتم تقديم مرفقات',
    
    'label_date' => 'التاريخ',
    'label_username' => 'اسم المستخدم',
    'label_method' => 'الطريقة',
    'label_amount' => 'المبلغ',
    'label_charge' => 'الرسوم',
    'label_after_charge' => 'بعد الرسوم',
    'label_rate' => 'السعر/المعدل',
    'label_total' => 'إجمالي المستحق',
    
    // JS Confirmations
    'confirm_title' => 'هل أنت متأكد؟',
    'confirm_text' => 'لا يمكن التراجع عن هذا الإجراء.',
    'confirm_yes' => 'نعم، استمر!',
    'confirm_approve_title' => 'الموافقة على الدفع؟',
    'confirm_approve_text' => 'الموافقة على المعاملة :trx؟',
    'confirm_reject_title' => 'رفض الدفع؟',
    'confirm_reject_text' => 'رفض المعاملة :trx؟ لا يمكن التراجع عن هذا الإجراء.',
];