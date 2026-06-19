<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */
    'email_not_verified' => 'Verify Your Email Address',
    'verify_email_desc' => 'Please verify your email to access all features and receive notifications.',
    'resend_link' => 'Resend Verification Link',
    'verification_link_sent' => 'A new verification link has been sent to your email address.',
    'system_alert' => 'System Alert',
    /*
    |--------------------------------------------------------------------------
    | Frontend Dashboard & General
    |--------------------------------------------------------------------------
    */
    'welcome_back' => 'Welcome back',
    'student_default' => 'Student',
    'header_subtitle' => 'Stay focused. Your next assessment is ready.',
    'assessment' => 'Assessment',
    'minutes' => 'Minutes',
    'mins' => 'Mins',
    'view' => 'View',
    'result' => 'Result',
    'sidebar_administration' => 'Management',
    'manage_exams' => 'Manage Exams',
    'manage_users' => 'Manage Users',
    'system_settings' => 'System Settings',
    'admin_dashboard' => 'Admin Dashboard',
    // Checkout Flow
    'checkout_title' => 'Secure Checkout',
    'step_cart' => 'Cart',
    'step_details' => 'Details',
    'step_payment' => 'Payment',

    'upgrade_plan' => 'Upgrade Plan',
    
    // Payment Page Titles & Descriptions
    'payment_method' => 'Payment Method',
    'payment_desc' => 'Transactions are encrypted and secured.',
    'no_payment_enabled' => 'No payment gateways are currently enabled.',
    
    // Payment Options
    'credit_debit_card' => 'Credit/Debit Card',
    'bank_transfer_offline' => 'Bank Transfer/Offline', 
    
    // Stripe Fields
    'card_holder_name' => 'Card Holder Name',
    'email' => 'Email',
    'card_number' => 'Card Number',
    'expiry_date' => 'Expiry Date',
    'cvc' => 'CVC',
    'securely_processed_by_stripe' => 'Securely processed by Stripe',
    
    // Bank/Offline Fields
    'account_holder_name' => 'Account Holder Name',
    'bank_name' => 'Bank Name',
    'account_number_iban' => 'Account Number / IBAN',
    'ifsc_swift_code' => 'IFSC / SWIFT Code',
    'additional_instructions' => 'Additional Instructions',
    'offline_gateway_note' => 'Please use the details below to complete your transfer. Your order will be confirmed upon manual verification of payment.',
    
    // Payment Prompts
    'select_gateway_prompt' => 'Please select a payment method above to show the required fields.',
    'razorpay_redirect_note' => 'This method typically requires redirection to complete the payment on the Razorpay platform.',

    // Order Summary
    'order_summary' => 'Order Summary',
    'subtotal' => 'Subtotal',
    'taxes' => 'Taxes',
    'total_amount' => 'Total Amount',

    // Buttons & Security
    'pay_with_amount' => 'Pay {amount} & Access',
    'back_details' => 'Back to Details',
    'bank_security' => 'Bank Level Security',
    // Exam Actions
    'continue_exam' => 'Continue Exam',
    'start_now' => 'Start Now',
    'view_instructions' => 'View Instructions',
    'view_all_exams' => 'View All Exams',
    'retake_exam' => 'Retake Exam',
    'go_to_exams' => 'Go to Exams',

    // Exam Status (Dynamic usage)
    'ongoing' => 'Ongoing',
    'ready' => 'Ready',
    'completed' => 'Completed',
    'pending' => 'Pending',
    
    // Hero Section - Empty State
    'no_active' => 'NO ACTIVE',
    'no_scheduled_title' => 'No Scheduled Assessments',
    'no_scheduled_desc' => 'Check your courses for available exams or quizzes.',
    'available_now' => 'Available Now',

    // Stats Widget
    'scheduled' => 'Scheduled',
    'avg_score' => 'Avg. Score',
    
    // Exam List Section
    'your_exams' => 'Your Exams',
    'tab_upcoming' => 'Upcoming',
    'tab_history' => 'History',
    'no_upcoming_exams' => 'No upcoming exams found.',
    'no_history_exams' => 'No completed exams found.',
    'score_label' => 'Score:',
    
    // Performance Widget
    'performance_snapshot' => 'Performance Snapshot',
    'accuracy_rate' => 'Accuracy Rate',
    'time_management' => 'Time Management',
    'consistency' => 'Consistency',
    
    // Updates Widget
    'exam_updates' => 'Exam Updates',
    'schedule_change' => 'Schedule Change',
    'schedule_change_msg' => 'Physics Midterm has been rescheduled to Oct 28.',
    'result_published' => 'Result Published',
    'result_published_msg' => 'Chemistry Final score is now available for view.',

    // Notifications Widget
    'notifications' => 'Notifications',
    'no_notifications' => 'No new notifications.',
    'view_all_notifications' => 'View All Notifications',
    'notification_welcome_title' => 'Welcome to ZiExam AI',
    'notification_welcome_body' => 'We are excited to have you on board! Start by exploring available exams.',
    'notification_result_title' => 'Exam Result Published',
    'notification_result_body' => 'Your results for "{exam}" are now available.',
    'notification_payment_title' => 'Payment Successful',
    'notification_payment_body' => 'We received your payment for the "{plan}". Transaction ID: {trx}',
    'notification_profile_title' => 'Incomplete Profile',
    'notification_profile_body' => 'Please complete your profile information to generate certificates accurately.',
    'notification_missed_title' => 'Exam Missed',
    'notification_missed_body' => 'You missed the scheduled time for "{exam}".',

    /*
    |--------------------------------------------------------------------------
    | Success / Failure Page
    |--------------------------------------------------------------------------
    */
    'payment_successful' => 'Payment Successful!',
    'payment_pending' => 'Payment Pending',
    'exam_access_active' => 'Your exam access is now active.',
    'offline_processing' => 'Your offline payment is currently under review.',
    'purchased_exams' => 'Purchased Exam(s)',
    'access_activated' => 'Access Activated',
    'access_pending' => 'Access Pending',
    'order_id' => 'Order ID',
    'amount_paid' => 'Amount Paid',
    'go_to_dashboard' => 'Go to Dashboard',
    'home' => 'Home',
    'not_ready_note' => 'Not ready to start? This exam has been saved to your account.',
    'buy_again_btn' => 'Buy Again',
    'retake_exam_btn' => 'Retake Exam',
    'start_exam_btn' => 'Start Exam',
    'buy_exam_btn' => 'Buy Now',
    'start_free_btn' => 'Start Free',
    'result_published' => 'Result Published',
    'result_pending' => 'Result Pending',
    'results_locked' => 'Results Locked',
    'score_label_card' => 'Score',
    'progress_label' => 'Progress',

    // Upcoming Exam Alert
    'upcoming_exam_title' => 'Exam is Upcoming',
    'upcoming_exam_msg' => 'This exam is scheduled to start on',
    'upcoming_exam_wait' => 'Please wait until the start time to participate.',
    'visit_website' => 'Visit Website',
    'no_exams_match' => 'No exams match your criteria.',
    'no_exams_suggestion' => 'Try adjusting your search terms or filters to find what you are looking for.',
    'clear_all_filters' => 'Clear All Filters',

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */
    'my_certificates' => 'My Certificates',
    'certificates_subtitle' => 'Certificates earned upon successful completion of qualifying exams.',
    'earned_section' => 'Earned Certificates',
    'cert_achievement' => 'Certificate of Achievement',
    'issued_on' => 'Issued:',
    'download_pdf' => 'Download PDF',
    
    'processing_section' => 'Processing',
    'passed_on' => 'Passed on',
    'waiting_admin' => 'Waiting for admin issuance.',
    
    'locked_section' => 'Locked',
    'not_earned' => 'Not Earned',
    'highest_score' => 'Highest Score:',
    'required_score' => 'Required:',
    
    'no_certs_title' => 'No Certificates Yet',
    'no_certs_desc' => 'Complete exams with a passing score to earn official certificates.',

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications_title' => 'Notifications',
    'notifications_subtitle' => 'Stay updated with your latest activities and alerts.',
    'mark_all_read' => 'Mark All Read',
    'view_details' => 'View Details',
    'remove_notification' => 'Remove Notification',
    'no_notifications' => 'No Notifications',
    'no_notifications_desc' => 'You\'re all caught up! No new alerts to display.',

    /*
    |--------------------------------------------------------------------------
    | Profile & Account
    |--------------------------------------------------------------------------
    */
    'profile_title' => 'My Profile',
    'profile_subtitle' => 'Update your personal information and change your password.',
    'general_info' => 'General Information',
    'change_avatar' => 'Change Avatar',
    'avatar_help' => 'Max size 2MB (JPG/PNG)',
    'file_selected' => 'File Selected',
    'full_name' => 'Full Name',
    'email_address' => 'Email Address',
    'save_general' => 'Save General Info',
    
    'change_password' => 'Change Password',
    'current_password' => 'Current Password',
    'new_password' => 'New Password',
    'confirm_password' => 'Confirm New Password',
    'update_password' => 'Update Password',

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */
    'transactions_title' => 'Transaction History',
    'transactions_subtitle' => 'Review all your payments and plan subscriptions.',
    'filter_btn' => 'Filter',
    'reset_btn' => 'Reset',
    'txn_id' => 'Transaction ID',
    'plan_item' => 'Plan/Item',
    'amount' => 'Amount',
    'gateway' => 'Gateway',
    'status' => 'Status',
    'date' => 'Date',
    'standalone_purchase' => 'Standalone Purchase',
    'days_subscription' => 'days subscription',
    'approved' => 'Approved',
    'success' => 'Success',
    'successful' => 'Successful',
    'active' => 'Active',
    'pending' => 'Pending',
    'initiated' => 'Initiated',
    'rejected' => 'Rejected',
    'failed' => 'Failed',
    'info' => 'Info',
    
    'no_txn_found' => 'No Transactions Found',
    'no_txn_desc' => 'Your filters returned no payments matching the criteria.',
    'browse_plans' => 'Browse Exams & Plans',

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    'settings_title' => 'Application Settings',
    'settings_subtitle' => 'Configure your preferences, notifications, and security options.',
    'notification_prefs' => 'Notification Preferences',
    'email_notify' => 'Email Notifications',
    'email_notify_desc' => 'Receive updates regarding new results and exam invitations.',
    'in_app_alerts' => 'In-App Alerts',
    'in_app_alerts_desc' => 'Show timely notifications inside the dashboard.',
    'regional_settings' => 'Regional & Time Settings',
    'timezone' => 'Time Zone',
    'language' => 'Language',
    'save_settings' => 'Save Settings',

    /*
    |--------------------------------------------------------------------------
    | Support Tickets
    |--------------------------------------------------------------------------
    */
    'tickets_title' => 'Support Tickets',
    'tickets_subtitle' => 'Manage your support requests and correspondence.',
    'create_ticket' => 'Create New Ticket',
    'my_active_tickets' => 'My Active Tickets',
    'filter_by' => 'Filter by:',
    'status_all' => 'All',
    'status_open' => 'Open',
    'status_replied' => 'Replied',
    'status_closed' => 'Closed',
    'admin_short' => 'AD',
    
    // Table Headers
    'th_ticket_id' => 'TICKET ID',
    'th_subject' => 'SUBJECT',
    'th_priority' => 'PRIORITY',
    'th_status' => 'STATUS',
    'th_last_updated' => 'LAST UPDATED',
    'th_action' => 'ACTION',
    'no_tickets' => 'No tickets found.',
    
    // Ticket Details
    'back_btn' => 'Back',
    'priority_suffix' => 'Priority',
    'created_prefix' => 'Created',
    'me_label' => 'Me',
    'admin_label' => 'Admin',
    'support_agent' => 'Support Agent',
    'attachments_label' => 'Attachments:',
    'view_file' => 'View File',
    'no_messages' => 'No messages found in this ticket.',
    'reply_label' => 'Reply',
    'reply_placeholder' => 'Type your message here...',
    'attachments_optional' => 'Attachments (Optional)',
    'send_reply' => 'Send Reply',
    'close_ticket' => 'Close Ticket',
    'close_confirm' => 'Are you sure you want to close this ticket? You will not be able to reply anymore.',
    'ticket_closed_msg' => 'This ticket is closed. If you need further assistance, please',
    'open_new_link' => 'open a new ticket',
    
    // Create Modal
    'modal_title' => 'Submit a New Support Ticket',
    'subject_label' => 'Subject',
    'subject_place' => 'Brief summary of the issue (e.g., Exam not loading)',
    'category_label' => 'Category',
    'select_cat' => 'Select Category...',
    'cat_billing' => 'Billing / Payment',
    'cat_tech' => 'Technical Issue',
    'cat_content' => 'Course Content',
    'cat_general' => 'General Inquiry',
    'cat_feature' => 'Feature Request',
    'priority_label' => 'Priority',
    'p_low' => 'Low',
    'p_medium' => 'Medium',
    'p_high' => 'High',
    'desc_label' => 'Description',
    'desc_place' => 'Please provide detailed steps or context...',
    'supported_formats' => 'Supported formats: JPG, PNG, PDF, DOCX',
    'support_notice' => 'Our support team aims to respond to urgent tickets within 24 hours.',
    'cancel_btn' => 'Cancel',
    'submit_btn' => 'Submit Ticket',

    /*
    |--------------------------------------------------------------------------
    | Exam Results
    |--------------------------------------------------------------------------
    */
    'results_title' => 'Exam Results',
    'results_subtitle' => 'Detailed performance reports for your completed assessments.',
    'no_results_title' => 'No Results Yet',
    'no_results_desc' => 'You haven\'t completed any exams. Once you finish an assessment, your detailed performance report will appear here.',
    'browse_exams_btn' => 'Browse Exams',
    
    // Status & Cards
    'status_passed' => 'Passed',
    'status_failed' => 'Failed',
    'status_pending' => 'Awaiting Result',
    'result_available' => 'Result Available:',
    'completed_on' => 'Completed:',
    'your_score' => 'Your Score:',
    'passing_mark' => 'Passing Mark:',
    'view_full_report' => 'View Full Report',
    'view_status' => 'View Status',

    // Pending Page
    'exam_completed_title' => 'Exam Completed!',
    'exam_completed_msg' => 'Thank you for completing :title. Your responses have been recorded successfully.',
    'expected_date_label' => 'Expected Result Date',
    'publish_time_msg' => 'Results will be published around',
    'tba_title' => 'To Be Announced',
    'tba_msg' => 'The instructor has not set a publication date yet.',
    'back_to_exams' => 'Back to My Exams',

    // Report / Show Page
    'report_prefix' => 'Report:',
    'report_subtitle' => 'Detailed analysis and question breakdown for your attempt.',
    'back_to_results' => 'Back to Results',
    'download_pdf_alert' => 'PDF Download coming soon!',
    'overall_score' => 'Overall Score',
    'metric_correct' => 'Correct Answers',
    'metric_time' => 'Time Taken (Mins)',
    'metric_total_marks' => 'Total Marks',
    'metric_pass_percentage' => 'Required Pass %',
    
    // Question Analysis
    'analysis_title' => 'Question Analysis',
    'analysis_subtitle' => 'Review your answer against the correct solution for each question.',
    'review_answer_btn' => 'Review Answer',
    'label_your_answer' => 'Your Answer:',
    'label_skipped' => 'Skipped',
    'label_correct_answer' => 'Correct Answer:',
    'label_explanation' => 'Explanation:',

    /*
    |--------------------------------------------------------------------------
    | Exam Cards & Instructions
    |--------------------------------------------------------------------------
    */
    'no_records_found' => 'No records found.',
    'starts' => 'Starts',
    'soon' => 'Soon',
    'view_report' => 'View Report',
    'questions_count' => 'Questions',
    'progress_label' => 'Progress',
    'score_label_card' => 'Score',
    
    'instructions_header' => 'Exam Instructions',
    'instructions_subtitle' => 'Please read these guidelines carefully before starting your :title challenge.',
    'instruction_1_title' => '1. Answering & Saving',
    'instruction_1_text' => 'Each question has only one correct answer unless specified otherwise. Your selection is saved automatically.',
    'instruction_2_title' => '2. Navigation & Review',
    'instruction_2_text' => 'You can move freely between all questions using the sidebar navigator. Use the Mark for Review flag to revisit later.',
    'instruction_3_title' => '3. Time Limit & Submission',
    'instruction_3_text' => 'The total duration is :minutes minutes. The exam will auto-submit when the time expires.',
    'instruction_4_title' => '4. Technical Safety',
    'instruction_4_text' => 'Ensure you have a stable internet connection. Do not refresh the page or close the browser window.',
    
    'agree_terms' => 'I have read and understood all the instructions above.',
    'back_to_exams' => 'Back to My Exams',
    'start_exam_btn' => 'Start Exam',

    /*
    |--------------------------------------------------------------------------
    | My Exams Page
    |--------------------------------------------------------------------------
    */
    'my_exams_title' => 'My Exams',
    'my_exams_subtitle' => 'Manage your purchased assessments and track your progress.',
    'tab_available' => 'Available',
    'tab_ongoing' => 'Ongoing',
    'tab_completed' => 'Completed',
    'tab_upcoming' => 'Upcoming',
    'no_exams_ready' => 'You have no exams ready to start yet.',
    'browse_exams_btn' => 'Browse Exams',
    'no_exams_progress' => 'No exams currently in progress.',
    'no_exams_completed' => 'No exams completed yet.',
    'no_exams_scheduled' => 'No upcoming scheduled exams.',

    /*
    |--------------------------------------------------------------------------
    | Participation Screen
    |--------------------------------------------------------------------------
    */
    'end_exam' => 'End Exam',
    'question_label' => 'Question',
    'of_label' => 'of',
    'loading' => 'Loading...',
    'previous_btn' => 'Previous',
    'next_btn' => 'Next',
    'mark_review' => 'Mark for Review',
    'submit_finish' => 'Submit & Finish',
    'auto_save_msg' => 'All answers automatically saved.',
    'progress_overview' => 'Progress Overview',
    'stat_answered' => 'Answered',
    'stat_marked' => 'Marked for Review',
    'stat_remaining' => 'Remaining',
    'question_navigator' => 'Question Navigator',
    'confirm_submission' => 'Confirm Submission',

    /*
    |--------------------------------------------------------------------------
    | Exam List Page
    |--------------------------------------------------------------------------
    */
    'explore_exams_title' => 'Explore All Exams',
    'explore_exams_desc' => 'Browse our complete catalog of exams, practice tests, and certifications.',
    'filters_title' => 'Filters',
    'reset_btn' => 'Reset',
    'search_placeholder' => 'Search exams...',
    'categories_title' => 'Categories',
    'price_title' => 'Price',
    'all_prices' => 'All Prices',
    'free_only' => 'Free Only',
    'paid_only' => 'Paid Only',
    'apply_filters_btn' => 'Apply Filters',
    
    'showing_results' => 'Showing :first to :last of :total exams',
    'showing_results_footer' => 'Showing :first to :last of :total results',
    
    'sort_newest' => 'Sort by: Newest',
    'sort_price_low' => 'Price: Low to High',
    'sort_price_high' => 'Price: High to Low',
    
    'free_badge' => 'FREE',
    'qns_short' => 'Qns', // Short for Questions
    'buy_exam_btn' => 'Buy Exam',
    'start_free_btn' => 'Start Free',
    'no_exams_match' => 'No exams match your criteria.',

    /*
    |--------------------------------------------------------------------------
    | Dynamic Pages
    |--------------------------------------------------------------------------
    */
    // Note: Most dynamic page content comes from the database.
    // Add specific static labels here if you customize the template later.
    'page_not_found' => 'Page not found',
    'back_home' => 'Back to Home',

    /*
    |--------------------------------------------------------------------------
    | Exam List Page
    |--------------------------------------------------------------------------
    */
    'explore_exams_title' => 'Explore All Exams',
    'explore_exams_desc' => 'Browse our complete catalog of exams, practice tests, and certifications.',
    'filters_title' => 'Filters',
    'reset_btn' => 'Reset',
    'search_placeholder' => 'Search exams...',
    'categories_title' => 'Categories',
    'price_title' => 'Price',
    'all_prices' => 'All Prices',
    'free_only' => 'Free Only',
    'paid_only' => 'Paid Only',
    'apply_filters_btn' => 'Apply Filters',
    
    'showing_results' => 'Showing :first to :last of :total exams',
    'showing_results_footer' => 'Showing :first to :last of :total results',
    
    'sort_newest' => 'Sort by: Newest',
    'sort_price_low' => 'Price: Low to High',
    'sort_price_high' => 'Price: High to Low',
    
    'free_badge' => 'FREE',
    'qns_short' => 'Qns',
    'buy_exam_btn' => 'Buy Exam',
    'start_free_btn' => 'Start Free',
    'no_exams_match' => 'No exams match your criteria.',

    /*
    |--------------------------------------------------------------------------
    | Dynamic Pages
    |--------------------------------------------------------------------------
    */
    // Note: Most dynamic page content comes from the database.
    // Add specific static labels here if you customize the template later.
    'page_not_found' => 'Page not found',
    'back_home' => 'Back to Home',

    /*
    |--------------------------------------------------------------------------
    | Checkout & Payment
    |--------------------------------------------------------------------------
    */
    'checkout_title' => 'Secure Checkout',
    'step_cart' => 'Cart',
    'step_details' => 'Details',
    'step_payment' => 'Payment',
    
    // Cart Page
    'review_selection' => 'Review Your Selection',
    'confirm_exams' => 'Confirm your exams before continuing.',
    'remove_item' => 'Remove',
    'order_summary' => 'Order Summary',
    'subtotal' => 'Subtotal',
    'taxes' => 'Taxes',
    'total_amount' => 'Total Amount',
    'continue_checkout' => 'Continue to Checkout',
    'money_back_guarantee' => '30-Day Money Back Guarantee',
    'cart_empty' => 'Your cart is empty',
    'cart_empty_desc' => 'Browse our exams and find the perfect one for you.',
    'browse_exams_btn' => 'Browse Exams',

    // Details Page
    'billing_details' => 'Billing Details',
    'billing_desc' => 'Used for your receipt and exam access.',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'email_address' => 'Email Address',
    'country' => 'Country',
    'your_order' => 'Your Order',
    'total_to_pay' => 'Total to Pay',
    'continue_payment' => 'Continue to Payment',
    'return_cart' => 'Return to Cart',
    'ssl_secure' => 'SSL Secured Transaction',

    // Payment Page
    'payment_method' => 'Payment Method',
    'payment_desc' => 'Transactions are encrypted and secured.',
    'credit_card' => 'Credit / Debit Card',
    'pay_with_amount' => 'Pay :amount & Access',
    'back_details' => 'Back to Details',
    'bank_security' => 'Bank Level Security',

    // Success Page
    'payment_success' => 'Payment Successful!',
    'access_active' => 'Your exam access is now active.',
    'purchased_exams' => 'Purchased Exam(s)',
    'access_activated' => 'Access Activated',
    'order_id' => 'Order ID',
    'amount_paid' => 'Amount Paid',
    'go_dashboard' => 'Go to Dashboard',
    'home_btn' => 'Home',
    'save_note' => 'Not ready to start? This exam has been saved to your account.',

    /*
    |--------------------------------------------------------------------------
    | Home Page Sections
    |--------------------------------------------------------------------------
    */
    
    // Hero Section
    'hero_title_default' => 'Build, Sell & Manage<br><span class="gradient-text">Online Exams</span> with<br><span class="ai-highlight">AI Automation</span>',
    'hero_subtitle_default' => 'ZiExam AI is a ready-to-sell SaaS platform that lets you create AI-generated exams, sell access, manage subscriptions, and track results — all from a powerful admin dashboard.',
    'hero_rating_label' => 'Trusted by 58,980+ users',
    
    // Category Section
    'categories_title_default' => 'Create Exams Across Multiple Categories',
    'categories_subtitle_default' => 'ZiExam AI supports a wide range of exam types — from academic tests to competitive and professional assessments.',
    'categories_bottom_text_default' => 'All categories are fully customizable from the admin panel.',
    'category_exams_count' => ':count Exams',
    'no_categories_found' => 'No categories found. Please add them from the Admin Panel.',
    
    // Audience Section
    'audience_title_default' => 'Built for Institutions, Educators & SaaS Entrepreneurs',
    'audience_subtitle_default' => 'ZiExam AI is designed to scale — whether you are running an institute, selling courses, or launching your own exam SaaS business.',
    'audience_bottom_text_default' => 'Not sure which model fits you? ZiExam AI supports all major exam business types.',
    'audience_card_1_title' => 'Title 1',
    'audience_card_1_highlight' => 'Highlight Text Here',
    'audience_card_1_desc' => 'Description content goes here...',
    // (Repeat pattern for other cards if needed, though they usually come from DB settings)

    // Features Section
    'features_title_default' => 'Everything You Need to Launch & Scale',
    'features_subtitle_default' => 'A complete suite of AI-powered tools to create exams, automate evaluation, and monetize your platform from day one.',
    
    // Features Panels Defaults (Fallbacks if DB is empty)
    'feat_p1_title' => 'AI Exam Creation & Control',
    'feat_p1_desc' => 'Create professional exams in minutes using AI-assisted tools designed for speed, accuracy, and scale.',
    'feat_p1_hint' => 'AI-Powered Generation',
    
    'feat_p2_title' => 'Automated Evaluation & Insights',
    'feat_p2_desc' => 'Instantly evaluate performance, publish results, and gain deep insight into learner outcomes without manual effort.',
    'feat_p2_hint' => 'Real-time Analytics',
    
    'feat_p3_title' => 'Monetization & Access',
    'feat_p3_desc' => 'Turn your exams into a sustainable business with built-in payments, subscriptions, and role-based access control.',
    'feat_p3_hint' => 'Secure Payments',
    
    'feat_p4_title' => 'Management & Security',
    'feat_p4_desc' => 'Enterprise-grade tools to manage users, roles, and data securely at scale.',
    'feat_p4_hint' => 'Admin Controls',

    // How It Works Section
    'how_it_works_title_default' => 'How It Works',
    'how_it_works_subtitle_default' => 'Launch your exam business in 4 simple steps.',
    
    'hiw_s1_title' => 'Install & Configure',
    'hiw_s1_desc' => 'Setup on your server in minutes with our easy installer.',
    
    'hiw_s2_title' => 'Create Exams with AI',
    'hiw_s2_desc' => 'Use AI to generate questions and structure exams instantly.',
    
    'hiw_s3_title' => 'Set Pricing & Plans',
    'hiw_s3_desc' => 'Define subscription models or one-time purchase fees.',
    
    'hiw_s4_title' => 'Track & Scale',
    'hiw_s4_desc' => 'Monitor student performance and revenue growth.',

    /*
    |--------------------------------------------------------------------------
    | Pricing Section
    |--------------------------------------------------------------------------
    */
    'pricing_title_default' => 'Simple Pricing. Lifetime Ownership.',
    'pricing_subtitle_default' => 'Choose the license that fits your business model. One-time purchase. No monthly fees.',
    'most_popular' => 'MOST POPULAR',
    'per_month' => '/ Month',
    'choose_plan' => 'Choose :plan',
    'exams_limit_count' => ':count Exams',
    'exams_unlimited' => 'Unlimited Exams',
    'pricing_trust_1' => 'Secure Payments',
    'pricing_trust_2' => 'Cancel Anytime',
    'pricing_trust_3' => 'No Hidden Fees',
    'pricing_trust_4' => 'Verified Quality',
    'no_pricing_plans' => 'No pricing plans defined in the admin panel.',

    /*
    |--------------------------------------------------------------------------
    | Testimonials Section
    |--------------------------------------------------------------------------
    */
    'testimonials_title_default' => 'Trusted by Educators, Teams & Creators Worldwide',
    'testimonials_subtitle_default' => 'From solo instructors to fast-growing institutions, teams rely on our platform to build, evaluate, and scale online exams with confidence.',

    /*
    |--------------------------------------------------------------------------
    | Featured Exams Section
    |--------------------------------------------------------------------------
    */
    'exams_title_default' => 'Sell Exams & Build Recurring Revenue',
    'exams_subtitle_default' => 'Monetize individual exams or bundle them into subscriptions — fully managed from your admin dashboard.',
    'buy_exam_btn' => 'Buy Exam',
    'start_free_btn' => 'Start Free',
    'no_active_exams' => 'No active exams found. Please create exams in the admin panel.',
    
    'sub_strip_title_default' => 'Offer Unlimited Access',
    'sub_strip_desc_default' => 'Bundle all exams into monthly or yearly subscription tiers.',
    'exams_bottom_text_default' => 'All pricing, access rules, and availability are fully controlled from the admin panel.',

    /*
    |--------------------------------------------------------------------------
    | CMS Features Section
    |--------------------------------------------------------------------------
    */
    'cms_badge_default' => 'CMS INCLUDED',
    'cms_title_default' => 'Launch Your Website Without Any Extra Tools',
    'cms_desc_default' => 'ZiExam AI includes a built-in CMS that lets you create dynamic pages, manage navigation menus, and edit homepage sections directly from the admin panel.',
    
    'cms_feat_1_title' => 'Dynamic Pages',
    'cms_feat_1_desc' => 'Create unlimited pages using a visual section-based system.',
    
    'cms_feat_2_title' => 'Menu Builder',
    'cms_feat_2_desc' => 'Build and manage navigation menus directly from admin.',
    
    'cms_feat_3_title' => 'SEO Ready',
    'cms_feat_3_desc' => 'Control meta titles, descriptions, and slugs for better ranking.',
    
    'cms_feat_4_title' => 'Homepage Sections',
    'cms_feat_4_desc' => 'Edit hero, features, pricing, and CTA blocks easily.',

    /*
    |--------------------------------------------------------------------------
    | Admin Preview Section
    |--------------------------------------------------------------------------
    */
    'admin_preview_title_default' => 'Control Everything from One Powerful Dashboard',
    'admin_preview_subtitle_default' => 'A centralized admin panel designed to manage users, exams, subscriptions, revenue, and AI usage at scale.',
    
    'admin_stat_1_val' => '10,000+',
    'admin_stat_1_lbl' => 'Users Supported',
    
    'admin_stat_2_val' => '100%',
    'admin_stat_2_lbl' => 'Role-Based Access',
    
    'admin_stat_3_val' => 'Real-Time',
    'admin_stat_3_lbl' => 'Revenue Tracking',
    
    'admin_stat_4_val' => 'AI Cost',
    'admin_stat_4_lbl' => 'Usage & Cost Control',
    
    'admin_feat_1_title' => 'User & Role Control',
    'admin_feat_1_desc' => 'Manage admins, instructors, and students with fine-grained permissions.',
    
    'admin_feat_2_title' => 'Revenue & Subscriptions',
    'admin_feat_2_desc' => 'Track payments, plans, renewals, and growth in real time.',
    
    'admin_feat_3_title' => 'AI Usage & Limits',
    'admin_feat_3_desc' => 'Monitor AI consumption, set limits, and control operational costs.',
    
    'admin_feat_4_title' => 'System Configuration',
    'admin_feat_4_desc' => 'Configure payments, email, security, and platform behavior centrally.',
    
    'admin_check_1' => 'No Coding Required',
    'admin_check_2' => 'Enterprise-Ready Architecture',
    'admin_check_3' => 'Built on Laravel 10',

    /*
    |--------------------------------------------------------------------------
    | CTA Section
    |--------------------------------------------------------------------------
    */
    'cta_title_default' => 'Start Your Online Exam<br>Business Today',
    'cta_subtitle_default' => 'Get the most advanced AI-powered examination script on the market. Launch your platform in minutes, not months.',
    'cta_btn_primary' => 'Get Started',
    'cta_btn_secondary' => 'Watch Demo',

    /*
    |--------------------------------------------------------------------------
    | Footer Section
    |--------------------------------------------------------------------------
    */
    'footer_about_text_default' => 'ZiExam AI is an AI-powered online exam platform that helps you create, manage, and conduct exams effortlessly and securely.',
    'useful_links' => 'Useful Links',
    'legal' => 'Legal',
    'contact_info' => 'Contact Info',
    'copyright_text' => 'Copyright © :year ZiExam AI. All Rights Reserved.',
    
    // Default Footer Links
    'home_link' => 'Home',
    'features_link' => 'Features',
    'pricing_link' => 'Pricing',
    'faq_link' => 'FAQ',
    'privacy_policy' => 'Privacy Policy',
    'terms_service' => 'Terms of Service',
    'security_policy' => 'Security Policy',
    'refund_policy' => 'Refund Policy',

    /*
    |--------------------------------------------------------------------------
    | Header & Navigation
    |--------------------------------------------------------------------------
    */
    'dashboard_btn' => 'Dashboard',
    'start_free_btn_header' => 'Start Free',
    'select_language' => 'Select Language',
    'select_language_caps' => 'SELECT LANGUAGE',
    
    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'previous' => 'Previous',
    'next' => 'Next',

    /*
    |--------------------------------------------------------------------------
    | User Dashboard Sidebar & Topbar
    |--------------------------------------------------------------------------
    */
    'sidebar_main_menu' => 'Main Menu',
    'sidebar_dashboard' => 'Dashboard',
    'sidebar_my_exams' => 'My Exams',
    'sidebar_results' => 'Results',
    'sidebar_certificates' => 'Certificates',
    'sidebar_account' => 'Account',
    'sidebar_profile' => 'Profile',
    'sidebar_transactions' => 'Transaction History',
    'sidebar_settings' => 'Settings',
    'sidebar_support' => 'Support',
    'sidebar_tickets' => 'Support Tickets',
    'sidebar_logout' => 'Log Out',

    'topbar_search_placeholder' => 'Search exams, results...',
    'topbar_go_website' => 'Go to Website',
    'topbar_select_language' => 'Select Language',
    'topbar_select_language_caps' => 'SELECT LANGUAGE',
    'topbar_notifications' => 'Notifications',
    'topbar_mark_read' => 'Mark all read',
    'topbar_no_notifications' => 'No recent notifications',
    'topbar_view_activity' => 'View All Activity',
    'topbar_default_student' => 'Student',
    'topbar_my_profile' => 'My Profile',
    'topbar_menu_settings' => 'Settings',
    'topbar_logout' => 'Log Out',
    'sidebar_modules' => 'Modules',
    'results' => 'Results',

    /*
    |--------------------------------------------------------------------------
    | Participation Screen (FRONTEND.PHP)
    |--------------------------------------------------------------------------
    | Includes all static labels and dynamic strings passed to JavaScript
    */
    
    // Static Labels (Visible in Blade)
    'end_exam' => 'End Exam',
    'question_label' => 'Question',
    'of_label' => 'of',
    'loading' => 'Loading...',
    'previous_btn' => 'Previous',
    'next_btn' => 'Next',
    'mark_review' => 'Mark for Review',
    'submit_finish' => 'Submit & Finish',
    'auto_save_msg' => 'All answers automatically saved.',
    'progress_overview' => 'Progress Overview',
    'stat_answered' => 'Answered',
    'stat_marked' => 'Marked for Review',
    'stat_remaining' => 'Remaining',
    'question_navigator' => 'Question Navigator',
    'confirm_submission' => 'Confirm Submission',

    // --- JavaScript Strings (Required to fix errors shown in image) ---

    // Core UI & Errors
    'error_no_questions' => 'No questions found. Please contact support.',
    'question_missing_text' => 'Question missing text.',
    'no_options_available' => 'No options available.', // FIX for the text seen in the image
    
    // Saving Status
    'status_saving' => 'Saving...',
    'status_saved_success' => 'All answers automatically saved.',
    'status_save_error' => 'Connection lost. Trying to reconnect...',

    // Timer Expiration
    'timer_time_up_title' => 'Time is up!',
    'timer_time_up_text' => 'Your exam is being submitted automatically.',
    
    // Question Validation (Next Button Click)
    'validation_action_required_title' => 'Action Required',
    'validation_answer_or_mark' => 'Please select an answer OR mark this question for review before proceeding.',
    'validation_understood' => 'Understood',

    // Marking Messages
    'mark_unmarked_warning' => 'Question unmarked. You must answer it to proceed later.',
    'mark_marked_info' => 'Marked for review.',

    // Submission Checks (Review Check)
    'submission_pending_reviews_title' => 'Pending Reviews',
    'submission_pending_reviews_text' => 'You have {count} question(s) marked for review.', // Use {count} placeholder
    'submission_submit_anyway' => 'Submit Anyway',
    'submission_review_questions' => 'Review Questions',
    
    // Final Submission (FIXES for the modal seen in the image)
    'submission_finish_title' => 'Finish Exam?',
    'submission_finish_text' => "You won't be able to change your answers after submission.",
    'submission_yes_submit' => 'Yes, Submit it!',

    // User Result Page (Metrics & Status)
    'overall_score'         => 'OVERALL SCORE',
    'status_passed'         => 'PASSED',
    'status_failed'         => 'FAILED',

    // New Metric Labels (From the image)
    'metric_correct'        => 'CORRECT ANSWERS',
    'metric_time'           => 'TIME TAKEN (MINS)',
    'metric_pass_percentage'=> 'REQUIRED PASS %',

    // NEW NEGATIVE MARKING KEYS (Crucial for the new layout)
    'metric_incorrect'      => 'INCORRECT ANSWERS',
    'metric_net_score'      => 'NET SCORE',
    'metric_deducted_marks' => 'DEDUCTED MARKS',
    
    // Total marks label update
    'metric_total_marks'    => 'TOTAL MARKS', // Used for the score_obtained / total_marks line

    // New Alert Key
    'negative_marking_alert' => 'Negative Marking is enabled: :value marks deducted per incorrect answer.',
    
    // Additional Text (If needed)
    'mins'                  => 'Mins',
    
];