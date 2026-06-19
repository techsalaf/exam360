<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */
    'email_not_verified' => 'تحقق من عنوان بريدك الإلكتروني',
    'verify_email_desc' => 'يرجى تفعيل بريدك الإلكتروني للوصول إلى كافة الميزات واستلام الإشعارات.',
    'resend_link' => 'إعادة إرسال رابط التحقق',
    'verification_link_sent' => 'تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.',
    'system_alert' => 'تنبيه النظام',
    /*
    |--------------------------------------------------------------------------
    | Frontend Dashboard & General
    |--------------------------------------------------------------------------
    */
    'welcome_back' => 'مرحباً بك مجدداً',
    'student_default' => 'طالب',
    'header_subtitle' => 'ابقَ مركزاً. تقييمك القادم جاهز.',
    'assessment' => 'تقييم',
    'minutes' => 'دقيقة',
    'mins' => 'دقيقة',
    'view' => 'عرض',
    'result' => 'النتيجة',
    'sidebar_administration' => 'الإدارة',
    'manage_exams' => 'إدارة الاختبارات',
    'manage_users' => 'إدارة المستخدمين',
    'system_settings' => 'إعدادات النظام',
    'admin_dashboard' => 'لوحة تحكم المسؤول',
    // Checkout Flow
    'checkout_title' => 'إتمام الدفع الآمن',
    'step_cart' => 'السلة',
    'step_details' => 'التفاصيل',
    'step_payment' => 'الدفع',

    'upgrade_plan' => 'ترقية الخطة',
    
    // Payment Page Titles & Descriptions
    'payment_method' => 'طريقة الدفع',
    'payment_desc' => 'جميع المعاملات مشفرة وآمنة.',
    'no_payment_enabled' => 'لا توجد بوابات دفع مفعلة حالياً.',
    
    // Payment Options
    'credit_debit_card' => 'بطاقة ائتمان / خصم',
    'bank_transfer_offline' => 'تحويل بنكي / دفع يدوي', 
    
    // Stripe Fields
    'card_holder_name' => 'اسم صاحب البطاقة',
    'email' => 'البريد الإلكتروني',
    'card_number' => 'رقم البطاقة',
    'expiry_date' => 'تاريخ الانتهاء',
    'cvc' => 'رمز التحقق (CVC)',
    'securely_processed_by_stripe' => 'تتم معالجة الدفع بأمان عبر Stripe',
    
    // Bank/Offline Fields
    'account_holder_name' => 'اسم صاحب الحساب',
    'bank_name' => 'اسم البنك',
    'account_number_iban' => 'رقم الحساب / IBAN',
    'ifsc_swift_code' => 'كود IFSC / SWIFT',
    'additional_instructions' => 'تعليمات إضافية',
    'offline_gateway_note' => 'يرجى استخدام التفاصيل أدناه لإتمام التحويل. سيتم تأكيد طلبك بعد التحقق اليدوي من الدفع.',
    
    // Payment Prompts
    'select_gateway_prompt' => 'يرجى اختيار طريقة الدفع أعلاه لعرض الحقول المطلوبة.',
    'razorpay_redirect_note' => 'تتطلب هذه الطريقة عادةً إعادة توجيه لإتمام الدفع على منصة Razorpay.',

    // Order Summary
    'order_summary' => 'ملخص الطلب',
    'subtotal' => 'المجموع الفرعي',
    'taxes' => 'الضرائب',
    'total_amount' => 'المبلغ الإجمالي',

    // Buttons & Security
    'pay_with_amount' => 'ادفع {amount} واحصل على الوصول',
    'back_details' => 'العودة للتفاصيل',
    'bank_security' => 'أمان بمستوى بنكي',
    // Exam Actions
    'continue_exam' => 'متابعة الاختبار',
    'start_now' => 'ابدأ الآن',
    'view_instructions' => 'عرض التعليمات',
    'view_all_exams' => 'عرض كافة الاختبارات',
    'retake_exam' => 'إعادة الاختبار',
    'go_to_exams' => 'الذهاب للاختبارات',

    // Exam Status
    'ongoing' => 'جارٍ',
    'ready' => 'جاهز',
    'completed' => 'مكتمل',
    'pending' => 'معلق',
    
    // Hero Section - Empty State
    'no_active' => 'لا يوجد نشاط',
    'no_scheduled_title' => 'لا توجد تقييمات مجدولة',
    'no_scheduled_desc' => 'تحقق من دوراتك للاطلاع على الاختبارات المتاحة.',
    'available_now' => 'متاح الآن',

    // Stats Widget
    'scheduled' => 'مجدول',
    'avg_score' => 'متوسط الدرجة',
    
    // Exam List Section
    'your_exams' => 'اختباراتك',
    'tab_upcoming' => 'القادمة',
    'tab_history' => 'السجل',
    'no_upcoming_exams' => 'لم يتم العثور على اختبارات قادمة.',
    'no_history_exams' => 'لم يتم العثور على اختبارات مكتملة.',
    'score_label' => 'الدرجة:',
    
    // Performance Widget
    'performance_snapshot' => 'لمحة عن الأداء',
    'accuracy_rate' => 'معدل الدقة',
    'time_management' => 'إدارة الوقت',
    'consistency' => 'الاستمرارية',
    
    // Updates Widget
    'exam_updates' => 'تحديثات الاختبارات',
    'schedule_change' => 'تغيير الجدول',
    'schedule_change_msg' => 'تم تأجيل اختبار الفيزياء النصفي إلى 28 أكتوبر.',
    'result_published' => 'نشرت النتيجة',
    'result_published_msg' => 'نتيجة الكيمياء النهائية متاحة الآن للعرض.',

    // Notifications Widget
    'notifications' => 'الإشعارات',
    'no_notifications' => 'لا توجد إشعارات جديدة.',
    'view_all_notifications' => 'عرض كافة الإشعارات',
    'notification_welcome_title' => 'مرحباً بك في ZiExam AI',
    'notification_welcome_body' => 'يسعدنا انضمامك إلينا! ابدأ باستكشاف الاختبارات المتاحة.',
    'notification_result_title' => 'تم نشر نتيجة الاختبار',
    'notification_result_body' => 'نتائجك لاختبار "{exam}" متاحة الآن.',
    'notification_payment_title' => 'نجاح عملية الدفع',
    'notification_payment_body' => 'لقد استلمنا دفعتك لخطة "{plan}". معرف العملية: {trx}',
    'notification_profile_title' => 'ملف شخصي غير مكتمل',
    'notification_profile_body' => 'يرجى إكمال معلومات ملفك الشخصي لإصدار الشهادات بدقة.',
    'notification_missed_title' => 'فاتك الاختبار',
    'notification_missed_body' => 'لقد فاتك الوقت المحدد لاختبار "{exam}".',

    /*
    |--------------------------------------------------------------------------
    | Success / Failure Page
    |--------------------------------------------------------------------------
    */
    'payment_successful' => 'تم الدفع بنجاح!',
    'payment_pending' => 'الدفع قيد الانتظار',
    'exam_access_active' => 'أصبح وصولك للاختبار نشطاً الآن.',
    'offline_processing' => 'دفعتك اليدوية قيد المراجعة حالياً.',
    'purchased_exams' => 'الاختبارات المشتراة',
    'access_activated' => 'تم تفعيل الوصول',
    'access_pending' => 'الوصول معلق',
    'order_id' => 'معرف الطلب',
    'amount_paid' => 'المبلغ المدفوع',
    'go_to_dashboard' => 'الانتقال للوحة التحكم',
    'home' => 'الرئيسية',
    'not_ready_note' => 'لست مستعداً للبدء؟ تم حفظ هذا الاختبار في حسابك.',
    'buy_again_btn' => 'شراء مرة أخرى',
    'retake_exam_btn' => 'إعادة الاختبار',
    'start_exam_btn' => 'بدء الاختبار',
    'buy_exam_btn' => 'اشترِ الآن',
    'start_free_btn' => 'ابدأ مجاناً',
    'result_pending' => 'النتيجة معلقة',
    'results_locked' => 'النتائج مغلقة',
    'score_label_card' => 'الدرجة',
    'progress_label' => 'التقدم',

    // Upcoming Exam Alert
    'upcoming_exam_title' => 'الاختبار قادم قريباً',
    'upcoming_exam_msg' => 'من المقرر أن يبدأ هذا الاختبار في',
    'upcoming_exam_wait' => 'يرجى الانتظار حتى وقت البدء للمشاركة.',
    'visit_website' => 'زيارة الموقع',
    'no_exams_match' => 'لا توجد اختبارات تطابق معاييرك.',
    'no_exams_suggestion' => 'حاول تعديل كلمات البحث أو الفلاتر للعثور على ما تبحث عنه.',
    'clear_all_filters' => 'مسح كافة الفلاتر',

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */
    'my_certificates' => 'شهاداتي',
    'certificates_subtitle' => 'الشهادات المكتسبة عند إكمال الاختبارات المؤهلة بنجاح.',
    'earned_section' => 'الشهادات المكتسبة',
    'cert_achievement' => 'شهادة إنجاز',
    'issued_on' => 'صدرت في:',
    'download_pdf' => 'تحميل PDF',
    
    'processing_section' => 'قيد المعالجة',
    'passed_on' => 'اجتاز في',
    'waiting_admin' => 'بانتظار إصدار المسؤول.',
    
    'locked_section' => 'مغلقة',
    'not_earned' => 'لم تُكتسب بعد',
    'highest_score' => 'أعلى درجة:',
    'required_score' => 'المطلوب:',
    
    'no_certs_title' => 'لا توجد شهادات بعد',
    'no_certs_desc' => 'أكمل الاختبارات بدرجة نجاح للحصول على شهادات رسمية.',

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications_title' => 'الإشعارات',
    'notifications_subtitle' => 'ابقَ على اطلاع بآخر الأنشطة والتنبيهات.',
    'mark_all_read' => 'تحديد الكل كمقروء',
    'view_details' => 'عرض التفاصيل',
    'remove_notification' => 'إزالة الإشعار',
    'no_notifications_desc' => 'أنت مطلع على كل شيء! لا توجد تنبيهات جديدة.',

    /*
    |--------------------------------------------------------------------------
    | Profile & Account
    |--------------------------------------------------------------------------
    */
    'profile_title' => 'ملفي الشخصي',
    'profile_subtitle' => 'تحديث معلوماتك الشخصية وتغيير كلمة المرور.',
    'general_info' => 'معلومات عامة',
    'change_avatar' => 'تغيير الصورة الشخصية',
    'avatar_help' => 'الحد الأقصى 2 ميجابايت (JPG/PNG)',
    'file_selected' => 'تم اختيار الملف',
    'full_name' => 'الاسم الكامل',
    'email_address' => 'عنوان البريد الإلكتروني',
    'save_general' => 'حفظ المعلومات العامة',
    
    'change_password' => 'تغيير كلمة المرور',
    'current_password' => 'كلمة المرور الحالية',
    'new_password' => 'كلمة المرور الجديدة',
    'confirm_password' => 'تأكيد كلمة المرور الجديدة',
    'update_password' => 'تحديث كلمة المرور',

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */
    'transactions_title' => 'سجل المعاملات',
    'transactions_subtitle' => 'مراجعة كافة مدفوعاتك واشتراكاتك.',
    'filter_btn' => 'تصفية',
    'reset_btn' => 'إعادة تعيين',
    'txn_id' => 'معرف العملية',
    'plan_item' => 'الخطة / البند',
    'amount' => 'المبلغ',
    'gateway' => 'البوابة',
    'status' => 'الحالة',
    'date' => 'التاريخ',
    'standalone_purchase' => 'شراء مستقل',
    'days_subscription' => 'يوم اشتراك',
    'approved' => 'معتمد',
    'success' => 'ناجح',
    'successful' => 'ناجح',
    'active' => 'نشط',
    'pending' => 'معلق',
    'initiated' => 'مبدوء',
    'rejected' => 'مرفوض',
    'failed' => 'فاشل',
    'info' => 'معلومات',
    
    'no_txn_found' => 'لم يتم العثور على معاملات',
    'no_txn_desc' => 'الفلاتر المستخدمة لم ترجع أي مدفوعات تطابق المعايير.',
    'browse_plans' => 'تصفح الاختبارات والخطط',

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    'settings_title' => 'إعدادات التطبيق',
    'settings_subtitle' => 'تكوين تفضيلاتك، الإشعارات، وخيارات الأمان.',
    'notification_prefs' => 'تفضيلات الإشعارات',
    'email_notify' => 'إشعارات البريد الإلكتروني',
    'email_notify_desc' => 'استلام تحديثات بخصوص النتائج الجديدة ودعوات الاختبار.',
    'in_app_alerts' => 'تنبيهات داخل التطبيق',
    'in_app_alerts_desc' => 'إظهار إشعارات فورية داخل لوحة التحكم.',
    'regional_settings' => 'إعدادات المنطقة والوقت',
    'timezone' => 'المنطقة الزمنية',
    'language' => 'اللغة',
    'save_settings' => 'حفظ الإعدادات',

    /*
    |--------------------------------------------------------------------------
    | Support Tickets
    |--------------------------------------------------------------------------
    */
    'tickets_title' => 'تذاكر الدعم',
    'tickets_subtitle' => 'إدارة طلبات الدعم والمراسلات الخاصة بك.',
    'create_ticket' => 'إنشاء تذكرة جديدة',
    'my_active_tickets' => 'تذاكري النشطة',
    'filter_by' => 'تصفية حسب:',
    'admin_short' => 'إدارة',
    
    // Table Headers
    'th_ticket_id' => 'معرف التذكرة',
    'th_subject' => 'الموضوع',
    'th_priority' => 'الأولوية',
    'th_status' => 'الحالة',
    'th_last_updated' => 'آخر تحديث',
    'th_action' => 'الإجراء',
    'no_tickets' => 'لم يتم العثور على تذاكر.',
    
    // Ticket Details
    'back_btn' => 'رجوع',
    'priority_suffix' => 'الأولوية',
    'created_prefix' => 'أنشئت في',
    'me_label' => 'أنا',
    'admin_label' => 'المسؤول',
    'support_agent' => 'وكيل الدعم',
    'attachments_label' => 'المرفقات:',
    'view_file' => 'عرض الملف',
    'no_messages' => 'لا توجد رسائل في هذه التذكرة.',
    'reply_label' => 'رد',
    'reply_placeholder' => 'اكتب رسالتك هنا...',
    'attachments_optional' => 'مرفقات (اختياري)',
    'send_reply' => 'إرسال الرد',
    'close_ticket' => 'إغلاق التذكرة',
    'close_confirm' => 'هل أنت متأكد من إغلاق هذه التذكرة؟ لن تتمكن من الرد عليها لاحقاً.',
    'ticket_closed_msg' => 'هذه التذكرة مغلقة. إذا كنت بحاجة لمساعدة إضافية، يرجى',
    'open_new_link' => 'فتح تذكرة جديدة',
    
    // Create Modal
    'modal_title' => 'تقديم تذكرة دعم جديدة',
    'subject_label' => 'الموضوع',
    'subject_place' => 'ملخص موجز للمشكلة (مثلاً: الاختبار لا يفتح)',
    'category_label' => 'الفئة',
    'select_cat' => 'اختر الفئة...',
    'cat_billing' => 'الفواتير / الدفع',
    'cat_tech' => 'مشكلة تقنية',
    'cat_content' => 'محتوى الاختبار',
    'cat_general' => 'استفسار عام',
    'cat_feature' => 'طلب ميزة جديدة',
    'priority_label' => 'الأولوية',
    'p_low' => 'منخفضة',
    'p_medium' => 'متوسطة',
    'p_high' => 'عالية',
    'desc_label' => 'الوصف',
    'desc_place' => 'يرجى تقديم تفاصيل الخطوات أو السياق...',
    'supported_formats' => 'الصيغ المدعومة: JPG, PNG, PDF, DOCX',
    'support_notice' => 'يهدف فريق الدعم لدينا للرد على التذاكر العاجلة خلال 24 ساعة.',
    'cancel_btn' => 'إلغاء',
    'submit_btn' => 'إرسال التذكرة',

    /*
    |--------------------------------------------------------------------------
    | Exam Results
    |--------------------------------------------------------------------------
    */
    'results_title' => 'نتائج الاختبارات',
    'results_subtitle' => 'تقارير أداء مفصلة لاختباراتك المكتملة.',
    'no_results_title' => 'لا توجد نتائج بعد',
    'no_results_desc' => 'لم تكمل أي اختبارات بعد. بمجرد إنهاء التقييم، سيظهر تقرير أدائك المفصل هنا.',
    'browse_exams_btn' => 'تصفح الاختبارات',
    
    // Status & Cards
    'status_failed' => 'راسب',
    'status_pending' => 'بانتظار النتيجة',
    'result_available' => 'النتيجة متاحة:',
    'completed_on' => 'اكتمل في:',
    'your_score' => 'درجتك:',
    'passing_mark' => 'درجة النجاح:',
    'view_full_report' => 'عرض التقرير الكامل',
    'view_status' => 'عرض الحالة',

    // Pending Page
    'exam_completed_title' => 'اكتمل الاختبار!',
    'exam_completed_msg' => 'شكراً لإكمالك اختبار :title. تم تسجيل ردودك بنجاح.',
    'expected_date_label' => 'تاريخ النتيجة المتوقع',
    'publish_time_msg' => 'سيتم نشر النتائج في حوالي الساعة',
    'tba_title' => 'سيتم الإعلان عنه لاحقاً',
    'tba_msg' => 'لم يحدد المحاضر تاريخ النشر بعد.',
    'back_to_exams' => 'العودة لاختباراتي',

    // Report / Show Page
    'report_prefix' => 'تقرير:',
    'report_subtitle' => 'تحليل مفصل وتوزيع الأسئلة لمحاولتك.',
    'back_to_results' => 'العودة للنتائج',
    'download_pdf_alert' => 'تحميل PDF سيتوفر قريباً!',
    'overall_score' => 'الدرجة الإجمالية',
    'metric_correct' => 'الإجابات الصحيحة',
    'metric_time' => 'الوقت المستغرق (دقائق)',
    'metric_total_marks' => 'إجمالي الدرجات',
    'metric_pass_percentage' => 'نسبة النجاح المطلوبة',
    
    // Question Analysis
    'analysis_title' => 'تحليل الأسئلة',
    'analysis_subtitle' => 'راجع إجابتك مقابل الحل الصحيح لكل سؤال.',
    'review_answer_btn' => 'مراجعة الإجابة',
    'label_your_answer' => 'إجابتك:',
    'label_skipped' => 'تخطيت',
    'label_correct_answer' => 'الإجابة الصحيحة:',
    'label_explanation' => 'التوضيح:',

    /*
    |--------------------------------------------------------------------------
    | Exam Cards & Instructions
    |--------------------------------------------------------------------------
    */
    'no_records_found' => 'لم يتم العثور على سجلات.',
    'starts' => 'يبدأ',
    'soon' => 'قريباً',
    'view_report' => 'عرض التقرير',
    'questions_count' => 'سؤال',
    
    'instructions_header' => 'تعليمات الاختبار',
    'instructions_subtitle' => 'يرجى قراءة هذه الإرشادات بعناية قبل بدء تحدي :title.',
    'instruction_1_title' => '1. الإجابة والحفظ',
    'instruction_1_text' => 'لكل سؤال إجابة صحيحة واحدة فقط ما لم ينص على غير ذلك. يتم حفظ اختيارك تلقائياً.',
    'instruction_2_title' => '2. التنقل والمراجعة',
    'instruction_2_text' => 'يمكنك التنقل بحرية بين الأسئلة عبر الشريط الجانبي. استخدم علامة "المراجعة" للعودة للسؤال لاحقاً.',
    'instruction_3_title' => '3. الوقت المحدد والتسليم',
    'instruction_3_text' => 'المدة الإجمالية هي :minutes دقيقة. سيتم تسليم الاختبار تلقائياً عند انتهاء الوقت.',
    'instruction_4_title' => '4. الأمان التقني',
    'instruction_4_text' => 'تأكد من استقرار اتصال الإنترنت. لا تقم بتحديث الصفحة أو إغلاق نافذة المتصفح.',
    
    'agree_terms' => 'لقد قرأت وفهمت جميع التعليمات المذكورة أعلاه.',
    'start_exam_btn' => 'بدء الاختبار',

    /*
    |--------------------------------------------------------------------------
    | My Exams Page
    |--------------------------------------------------------------------------
    */
    'my_exams_title' => 'اختباراتي',
    'my_exams_subtitle' => 'إدارة اختباراتك المشتراة وتتبع تقدمك.',
    'tab_available' => 'المتاحة',
    'tab_ongoing' => 'الجارية',
    'tab_completed' => 'المكتملة',
    'tab_upcoming' => 'القادمة',
    'no_exams_ready' => 'ليس لديك اختبارات جاهزة للبدء بعد.',
    'no_exams_progress' => 'لا توجد اختبارات قيد التنفيذ حالياً.',
    'no_exams_completed' => 'لم تكمل أي اختبارات بعد.',
    'no_exams_scheduled' => 'لا توجد اختبارات مجدولة قادمة.',

    /*
    |--------------------------------------------------------------------------
    | Participation Screen
    |--------------------------------------------------------------------------
    */
    'end_exam' => 'إنهاء الاختبار',
    'question_label' => 'السؤال',
    'of_label' => 'من أصل',
    'loading' => 'جاري التحميل...',
    'previous_btn' => 'السابق',
    'next_btn' => 'التالي',
    'mark_review' => 'تحديد للمراجعة',
    'submit_finish' => 'تسليم وإنهاء',
    'auto_save_msg' => 'تم حفظ جميع الإجابات تلقائياً.',
    'progress_overview' => 'نظرة عامة على التقدم',
    'stat_answered' => 'تمت الإجابة',
    'stat_marked' => 'حدد للمراجعة',
    'stat_remaining' => 'المتبقي',
    'question_navigator' => 'منظم الأسئلة',
    'confirm_submission' => 'تأكيد التسليم',

    /*
    |--------------------------------------------------------------------------
    | Exam List Page
    |--------------------------------------------------------------------------
    */
    'explore_exams_title' => 'استكشف كافة الاختبارات',
    'explore_exams_desc' => 'تصفح كتالوجنا الكامل من الاختبارات، الاختبارات التجريبية، والشهادات.',
    'filters_title' => 'الفلاتر',
    'search_placeholder' => 'البحث في الاختبارات...',
    'categories_title' => 'الفئات',
    'price_title' => 'السعر',
    'all_prices' => 'كافة الأسعار',
    'free_only' => 'المجانية فقط',
    'paid_only' => 'المدفوعة فقط',
    'apply_filters_btn' => 'تطبيق الفلاتر',
    
    'showing_results' => 'عرض :first إلى :last من أصل :total اختبار',
    'showing_results_footer' => 'عرض :first إلى :last من أصل :total نتيجة',
    
    'sort_newest' => 'ترتيب حسب: الأحدث',
    'sort_price_low' => 'السعر: من الأقل للأعلى',
    'sort_price_high' => 'السعر: من الأعلى للأقل',
    
    'free_badge' => 'مجاني',
    'qns_short' => 'سؤال',
    
    /*
    |--------------------------------------------------------------------------
    | Dynamic Pages
    |--------------------------------------------------------------------------
    */
    'page_not_found' => 'الصفحة غير موجودة',
    'back_home' => 'العودة للرئيسية',

    /*
    |--------------------------------------------------------------------------
    | Checkout & Payment
    |--------------------------------------------------------------------------
    */
    // Cart Page
    'review_selection' => 'راجع اختياراتك',
    'confirm_exams' => 'تأكد من اختباراتك قبل المتابعة.',
    'remove_item' => 'إزالة',
    'continue_checkout' => 'المتابعة للدفع',
    'money_back_guarantee' => 'ضمان استرداد الأموال لمدة 30 يوماً',
    'cart_empty' => 'سلة المشتريات فارغة',
    'cart_empty_desc' => 'تصفح اختباراتنا وجد الاختبار المناسب لك.',

    // Details Page
    'billing_details' => 'تفاصيل الفواتير',
    'billing_desc' => 'تستخدم لإيصالك والوصول للاختبار.',
    'first_name' => 'الاسم الأول',
    'last_name' => 'اسم العائلة',
    'country' => 'الدولة',
    'your_order' => 'طلبك',
    'total_to_pay' => 'المبلغ الإجمالي للدفع',
    'continue_payment' => 'المتابعة للدفع',
    'return_cart' => 'العودة للسلة',
    'ssl_secure' => 'عملية مشفرة بـ SSL',

    // Payment Page
    'credit_card' => 'بطاقة ائتمان / خصم',
    'pay_with_amount' => 'ادفع :amount واحصل على الوصول',

    // Success Page
    'payment_success' => 'تم الدفع بنجاح!',
    'access_active' => 'أصبح وصولك للاختبار نشطاً الآن.',
    'go_dashboard' => 'الانتقال للوحة التحكم',
    'home_btn' => 'الرئيسية',
    'save_note' => 'لست مستعداً للبدء؟ تم حفظ الاختبار في حسابك.',

    /*
    |--------------------------------------------------------------------------
    | Home Page Sections
    |--------------------------------------------------------------------------
    */
    
    // Hero Section
    'hero_title_default' => 'أنشئ، بع، وأدر <br><span class="gradient-text">الاختبارات عبر الإنترنت</span> مع<br><span class="ai-highlight">أتمتة الذكاء الاصطناعي</span>',
    'hero_subtitle_default' => 'ZiExam AI هي منصة SaaS جاهزة تتيح لك إنشاء اختبارات بالذكاء الاصطناعي، وبيع الوصول إليها، وإدارة الاشتراكات، وتتبع النتائج - كل ذلك من لوحة إدارة قوية.',
    'hero_rating_label' => 'موثوق من قبل +58,980 مستخدم',
    
    // Category Section
    'categories_title_default' => 'أنشئ اختبارات في فئات متعددة',
    'categories_subtitle_default' => 'يدعم ZiExam AI مجموعة واسعة من أنواع الاختبارات - من الاختبارات الأكاديمية إلى التقييمات التنافسية والمهنية.',
    'categories_bottom_text_default' => 'جميع الفئات قابلة للتخصيص بالكامل من لوحة الإدارة.',
    'category_exams_count' => ':count اختبار',
    'no_categories_found' => 'لم يتم العثور على فئات. يرجى إضافتها من لوحة الإدارة.',
    
    // Audience Section
    'audience_title_default' => 'مصمم للمؤسسات، المعلمين ورواد أعمال SaaS',
    'audience_subtitle_default' => 'صُمم ZiExam AI للتوسع - سواء كنت تدير معهداً، تبيع دورات، أو تطلق مشروعك الخاص في مجال الاختبارات.',
    'audience_bottom_text_default' => 'لست متأكداً أي نموذج يناسبك؟ يدعم ZiExam AI جميع أنواع نماذج أعمال الاختبارات الرئيسية.',
    'audience_card_1_title' => 'العنوان 1',
    'audience_card_1_highlight' => 'نص تمييزي هنا',
    'audience_card_1_desc' => 'محتوى الوصف يوضع هنا...',

    // Features Section
    'features_title_default' => 'كل ما تحتاجه للإطلاق والتوسع',
    'features_subtitle_default' => 'مجموعة كاملة من الأدوات المدعومة بالذكاء الاصطناعي لإنشاء الاختبارات، وأتمتة التقييم، وتحقيق الأرباح من منصتك منذ اليوم الأول.',
    
    // Features Panels Defaults
    'feat_p1_title' => 'إنشاء وتحكم بالذكاء الاصطناعي',
    'feat_p1_desc' => 'أنشئ اختبارات احترافية في دقائق باستخدام أدوات الذكاء الاصطناعي المصممة للسرعة والدقة.',
    'feat_p1_hint' => 'توليد مدعوم بالذكاء الاصطناعي',
    
    'feat_p2_title' => 'تقييم تلقائي ورؤى',
    'feat_p2_desc' => 'قيم الأداء فوراً، وانشر النتائج، واحصل على رؤى عميقة في مخرجات التعلم دون جهد يدوي.',
    'feat_p2_hint' => 'تحليلات في الوقت الفعلي',
    
    'feat_p3_title' => 'تحقيق الأرباح والوصول',
    'feat_p3_desc' => 'حول اختباراتك إلى عمل مستدام مع أنظمة دفع مدمجة، واشتراكات، وتحكم في الوصول القائم على الأدوار.',
    'feat_p3_hint' => 'مدفوعات آمنة',
    
    'feat_p4_title' => 'الإدارة والأمان',
    'feat_p4_desc' => 'أدوات بمستوى المؤسسات لإدارة المستخدمين والأدوار والبيانات بأمان.',
    'feat_p4_hint' => 'تحكم المسؤول',

    // How It Works Section
    'how_it_works_title_default' => 'كيف يعمل النظام',
    'how_it_works_subtitle_default' => 'أطلق مشروع الاختبارات الخاص بك في 4 خطوات بسيطة.',
    
    'hiw_s1_title' => 'التثبيت والتهيئة',
    'hiw_s1_desc' => 'الإعداد على خادمك في دقائق باستخدام المثبت السهل.',
    
    'hiw_s2_title' => 'إنشاء الاختبارات بالذكاء الاصطناعي',
    'hiw_s2_desc' => 'استخدم الذكاء الاصطناعي لتوليد الأسئلة وهيكلة الاختبارات فوراً.',
    
    'hiw_s3_title' => 'ضبط الأسعار والخطط',
    'hiw_s3_desc' => 'حدد نماذج الاشتراك أو رسوم الشراء لمرة واحدة.',
    
    'hiw_s4_title' => 'التتبع والتوسع',
    'hiw_s4_desc' => 'راقب أداء الطلاب ونمو الإيرادات.',

    /*
    |--------------------------------------------------------------------------
    | Pricing Section
    |--------------------------------------------------------------------------
    */
    'pricing_title_default' => 'تسعير بسيط. ملكية مدى الحياة.',
    'pricing_subtitle_default' => 'اختر الترخيص الذي يناسب نموذج عملك. شراء لمرة واحدة. بدون رسوم شهرية.',
    'most_popular' => 'الأكثر رواجاً',
    'per_month' => '/ شهرياً',
    'choose_plan' => 'اختر :plan',
    'exams_limit_count' => ':count اختبار',
    'exams_unlimited' => 'اختبارات غير محدودة',
    'pricing_trust_1' => 'مدفوعات آمنة',
    'pricing_trust_2' => 'إلغاء في أي وقت',
    'pricing_trust_3' => 'لا توجد رسوم مخفية',
    'pricing_trust_4' => 'جودة معتمدة',
    'no_pricing_plans' => 'لم يتم تحديد خطط أسعار في لوحة الإدارة.',

    /*
    |--------------------------------------------------------------------------
    | Testimonials Section
    |--------------------------------------------------------------------------
    */
    'testimonials_title_default' => 'موثوق من قبل المعلمين والفرق والمبدعين حول العالم',
    'testimonials_subtitle_default' => 'من المحاضرين الأفراد إلى المؤسسات سريعة النمو، تعتمد الفرق على منصتنا لبناء وتقييم وتوسيع الاختبارات بثقة.',

    /*
    |--------------------------------------------------------------------------
    | Featured Exams Section
    |--------------------------------------------------------------------------
    */
    'exams_title_default' => 'بع الاختبارات وحقق عائداً مستمراً',
    'exams_subtitle_default' => 'حقق أرباحاً من الاختبارات الفردية أو اجمعها في اشتراكات - تدار بالكامل من لوحة الإدارة.',
    'no_active_exams' => 'لم يتم العثور على اختبارات نشطة. يرجى إنشاء اختبارات في لوحة الإدارة.',
    
    'sub_strip_title_default' => 'قدم وصولاً غير محدود',
    'sub_strip_desc_default' => 'اجمع كافة الاختبارات في باقات اشتراك شهرية أو سنوية.',
    'exams_bottom_text_default' => 'يتم التحكم في جميع الأسعار وقواعد الوصول والتوفر بالكامل من لوحة الإدارة.',

    /*
    |--------------------------------------------------------------------------
    | CMS Features Section
    |--------------------------------------------------------------------------
    */
    'cms_badge_default' => 'نظام إدارة محتوى مدمج',
    'cms_title_default' => 'أطلق موقعك الإلكتروني دون الحاجة لأدوات إضافية',
    'cms_desc_default' => 'يتضمن ZiExam AI نظام إدارة محتوى يتيح لك إنشاء صفحات ديناميكية، وإدارة قوائم التنقل، وتحرير أقسام الصفحة الرئيسية.',
    
    'cms_feat_1_title' => 'صفحات ديناميكية',
    'cms_feat_1_desc' => 'أنشئ صفحات غير محدودة باستخدام نظام مرئي يعتمد على الأقسام.',
    
    'cms_feat_2_title' => 'منشئ القوائم',
    'cms_feat_2_desc' => 'بناء وإدارة قوائم التنقل مباشرة من لوحة الإدارة.',
    
    'cms_feat_3_title' => 'جاهز لمحركات البحث (SEO)',
    'cms_feat_3_desc' => 'التحكم في عناوين الميتا والأوصاف لتحسين الترتيب في محركات البحث.',
    
    'cms_feat_4_title' => 'أقسام الصفحة الرئيسية',
    'cms_feat_4_desc' => 'تحرير الهيرو والمميزات والأسعار وكتل الدعوة للإجراء بسهولة.',

    /*
    |--------------------------------------------------------------------------
    | Admin Preview Section
    |--------------------------------------------------------------------------
    */
    'admin_preview_title_default' => 'تحكم في كل شيء من لوحة تحكم واحدة قوية',
    'admin_preview_subtitle_default' => 'لوحة إدارة مركزية مصممة لإدارة المستخدمين، الاختبارات، الاشتراكات، الإيرادات، واستخدام الذكاء الاصطناعي.',
    
    'admin_stat_1_val' => '10,000+',
    'admin_stat_1_lbl' => 'مستخدم مدعوم',
    
    'admin_stat_2_val' => '100%',
    'admin_stat_2_lbl' => 'وصول قائم على الأدوار',
    
    'admin_stat_3_val' => 'وقت فعلي',
    'admin_stat_3_lbl' => 'تتبع الإيرادات',
    
    'admin_stat_4_val' => 'تكلفة الـ AI',
    'admin_stat_4_lbl' => 'التحكم في الاستخدام والتكلفة',
    
    'admin_feat_1_title' => 'التحكم في المستخدمين والأدوار',
    'admin_feat_1_desc' => 'إدارة المسؤولين والمحاضرين والطلاب بصلاحيات دقيقة.',
    
    'admin_feat_2_title' => 'الإيرادات والاشتراكات',
    'admin_feat_2_desc' => 'تتبع المدفوعات والخطط والتجديدات والنمو في الوقت الفعلي.',
    
    'admin_feat_3_title' => 'استخدام الذكاء الاصطناعي وحدوده',
    'admin_feat_3_desc' => 'مراقبة استهلاك الذكاء الاصطناعي ووضع الحدود والتحكم في التكاليف التشغيلية.',
    
    'admin_feat_4_title' => 'تكوين النظام',
    'admin_feat_4_desc' => 'تكوين المدفوعات، البريد الإلكتروني، الأمان، وسلوك المنصة مركزياً.',
    
    'admin_check_1' => 'لا يتطلب برمجة',
    'admin_check_2' => 'بنية جاهزة للمؤسسات',
    'admin_check_3' => 'مبني على Laravel 10',

    /*
    |--------------------------------------------------------------------------
    | CTA Section
    |--------------------------------------------------------------------------
    */
    'cta_title_default' => 'ابدأ مشروعك في الاختبارات<br>عبر الإنترنت اليوم',
    'cta_subtitle_default' => 'احصل على أكثر سكربت اختبارات تقدماً ومدعوماً بالذكاء الاصطناعي في السوق. أطلق منصتك في دقائق.',
    'cta_btn_primary' => 'ابدأ الآن',
    'cta_btn_secondary' => 'شاهد العرض المباشر',

    /*
    |--------------------------------------------------------------------------
    | Footer Section
    |--------------------------------------------------------------------------
    */
    'footer_about_text_default' => 'ZiExam AI هي منصة اختبارات عبر الإنترنت مدعومة بالذكاء الاصطناعي تساعدك على إنشاء وإدارة وإجراء الاختبارات بسهولة وأمان.',
    'useful_links' => 'روابط مفيدة',
    'legal' => 'قانوني',
    'contact_info' => 'معلومات الاتصال',
    'copyright_text' => 'حقوق النشر © :year ZiExam AI. جميع الحقوق محفوظة.',
    
    // Footer Links
    'home_link' => 'الرئيسية',
    'features_link' => 'المميزات',
    'pricing_link' => 'الأسعار',
    'faq_link' => 'الأسئلة الشائعة',
    'privacy_policy' => 'سياسة الخصوصية',
    'terms_service' => 'شروط الخدمة',
    'security_policy' => 'سياسة الأمان',
    'refund_policy' => 'سياسة الاسترداد',

    /*
    |--------------------------------------------------------------------------
    | Header & Navigation
    |--------------------------------------------------------------------------
    */
    'dashboard_btn' => 'لوحة التحكم',
    'start_free_btn_header' => 'ابدأ مجاناً',
    'select_language' => 'اختر اللغة',
    'select_language_caps' => 'اختر اللغة',
    
    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'previous' => 'السابق',
    'next' => 'التالي',

    /*
    |--------------------------------------------------------------------------
    | User Dashboard Sidebar & Topbar
    |--------------------------------------------------------------------------
    */
    'sidebar_main_menu' => 'القائمة الرئيسية',
    'sidebar_dashboard' => 'لوحة التحكم',
    'sidebar_my_exams' => 'اختباراتي',
    'sidebar_results' => 'النتائج',
    'sidebar_certificates' => 'الشهادات',
    'sidebar_account' => 'الحساب',
    'sidebar_profile' => 'الملف الشخصي',
    'sidebar_transactions' => 'سجل المعاملات',
    'sidebar_settings' => 'الإعدادات',
    'sidebar_support' => 'الدعم',
    'sidebar_tickets' => 'تذاكر الدعم',
    'sidebar_logout' => 'تسجيل الخروج',

    'topbar_search_placeholder' => 'البحث في الاختبارات، النتائج...',
    'topbar_go_website' => 'اذهب للموقع',
    'topbar_select_language' => 'اختر اللغة',
    'topbar_select_language_caps' => 'اختر اللغة',
    'topbar_notifications' => 'الإشعارات',
    'topbar_mark_read' => 'تحديد الكل كمقروء',
    'topbar_no_notifications' => 'لا توجد إشعارات حديثة',
    'topbar_view_activity' => 'عرض كافة الأنشطة',
    'topbar_default_student' => 'طالب',
    'topbar_my_profile' => 'ملفي الشخصي',
    'topbar_menu_settings' => 'الإعدادات',
    'topbar_logout' => 'تسجيل الخروج',
    'sidebar_modules' => 'المديولات',
    'results' => 'النتائج',

    /*
    |--------------------------------------------------------------------------
    | Participation Screen (FRONTEND.PHP)
    |--------------------------------------------------------------------------
    */
    
    // JavaScript Strings
    'error_no_questions' => 'لم يتم العثور على أسئلة. يرجى الاتصال بالدعم.',
    'question_missing_text' => 'نص السؤال مفقود.',
    'no_options_available' => 'لا توجد خيارات متاحة.',
    
    // Saving Status
    'status_saving' => 'جاري الحفظ...',
    'status_saved_success' => 'تم حفظ جميع الإجابات تلقائياً.',
    'status_save_error' => 'انقطع الاتصال. جاري محاولة إعادة الاتصال...',

    // Timer Expiration
    'timer_time_up_title' => 'انتهى الوقت!',
    'timer_time_up_text' => 'يتم تسليم اختبارك تلقائياً الآن.',
    
    // Question Validation
    'validation_action_required_title' => 'إجراء مطلوب',
    'validation_answer_or_mark' => 'يرجى اختيار إجابة أو تحديد السؤال للمراجعة قبل المتابعة.',
    'validation_understood' => 'مفهوم',

    // Marking Messages
    'mark_unmarked_warning' => 'تم إلغاء تحديد السؤال. يجب الإجابة عليه للمتابعة لاحقاً.',
    'mark_marked_info' => 'تم التحديد للمراجعة.',

    // Submission Checks
    'submission_pending_reviews_title' => 'مراجعات معلقة',
    'submission_pending_reviews_text' => 'لديك {count} سؤال محدد للمراجعة.',
    'submission_submit_anyway' => 'تسليم على أي حال',
    'submission_review_questions' => 'مراجعة الأسئلة',
    
    // Final Submission
    'submission_finish_title' => 'إنهاء الاختبار؟',
    'submission_finish_text' => "لن تتمكن من تغيير إجاباتك بعد التسليم.",
    'submission_yes_submit' => 'نعم، قم بالتسليم!',

    // User Result Page
    'overall_score'         => 'الدرجة الإجمالية',
    'status_passed'         => 'ناجح',
    'status_failed'         => 'راسب',

    // Metric Labels
    'metric_correct'        => 'الإجابات الصحيحة',
    'metric_time'           => 'الوقت المستغرق (دقائق)',
    'metric_pass_percentage'=> 'نسبة النجاح المطلوبة',

    // NEGATIVE MARKING KEYS
    'metric_incorrect'      => 'الإجابات الخاطئة',
    'metric_net_score'      => 'صافي الدرجة',
    'metric_deducted_marks' => 'الدرجات المخصومة',
    
    'metric_total_marks'    => 'إجمالي الدرجات',

    'negative_marking_alert' => 'تم تفعيل الخصم (العلامات السلبية): سيتم خصم :value درجة لكل إجابة خاطئة.',
    
    'mins'                  => 'دقيقة',
    
];