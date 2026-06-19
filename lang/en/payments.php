<?php

return [
    'title' => 'Payments',
    'header_title' => 'Payment History',
    'header_subtitle' => 'Manage and track all system transactions.',

    // Currency Settings - NEW KEYS
    'currency_title' => 'Global Currency Settings',
    'currency_desc' => 'Configure your primary currency, display format, and transaction separators.',
    'currency_global_currency' => 'Global Currency',
    'currency_global_desc' => 'Set the primary currency for all transactions.',
    'currency_primary' => 'PRIMARY CURRENCY',
    'currency_position' => 'SYMBOL POSITION',
    'currency_pos_before' => 'Before Amount', 
    'currency_pos_after' => 'After Amount', 
    'currency_pos_before_space' => 'Before Amount (With Space)', 
    'currency_pos_after_space' => 'After Amount (With Space)',
    'currency_custom_opt' => 'Custom Currency',
    'currency_decimal_sep' => 'DECIMAL SEPARATOR',
    'currency_thousands_sep' => 'THOUSANDS SEPARATOR',
    'currency_decimal_help' => 'Character used for decimals (e.g. 10.00).',
    'currency_thousands_help' => 'Character used for thousands (e.g. 1,000).',
    'custom_code_label' => 'CUSTOM CODE (E.G., :example)',
    'custom_symbol_label' => 'CUSTOM SYMBOL (E.G., :example)',
    'example_code_placeholder' => 'e.g. QAR',
    'example_symbol_placeholder' => 'e.g. ₹',
    'save_settings' => 'Save Settings',
    
    // Buttons & Links
    'btn_filter' => 'Filter',
    'btn_review_all' => 'Review All',
    'btn_export' => 'Export Data',
    'btn_view' => 'View',
    'btn_approve' => 'Approve',
    'btn_reject' => 'Reject',
    'btn_close' => 'Close',
    'btn_clear_filters' => 'Clear Filters',
    
    // Placeholders & Inputs
    'placeholder_search' => 'Search payments...',
    'label_status' => 'Status',
    'opt_all' => 'All',
    'opt_pending' => 'Pending',
    'opt_success' => 'Success',
    'opt_successful' => 'Successful',
    'opt_failed' => 'Failed',
    'opt_rejected' => 'Rejected',
    'opt_initiated' => 'Initiated',
    
    // Alerts
    'alert_pending_count' => ':count Pending Payment|:count Pending Payments',
    
    // Table Headers
    'col_trx' => 'TRANSACTION ID / GATEWAY',
    'col_user' => 'USER',
    'col_amount' => 'AMOUNT (:currency)',
    'col_status' => 'STATUS',
    'col_date' => 'DATE',
    'col_action' => 'ACTION',
    
    // Table Content
    'text_user_deleted' => 'User Deleted',
    'text_user_not_found' => 'User not found',
    'status_success' => 'Success',
    'status_approved' => 'Approved',
    'status_pending' => 'Pending',
    'status_initiated' => 'Initiated',
    'status_failed' => 'Failed',
    'status_rejected' => 'Rejected',
    'empty_title' => 'No payments found',
    
    // Modal Details
    'modal_title' => 'Payment Via :gateway',
    'sect_user_info' => 'User Payment Information',
    'sect_payment_details' => 'Payment Details',
    'label_fname' => 'First Name',
    'label_lname' => 'Last Name',
    'label_bank' => 'Bank Name',
    'label_trx' => 'Transaction Number',
    'label_screenshot' => 'Screenshot',
    'link_attachment' => 'Attachment',
    'text_no_attachment' => 'No attachment provided',
    
    'label_date' => 'Date',
    'label_username' => 'Username',
    'label_method' => 'Method',
    'label_amount' => 'Amount',
    'label_charge' => 'Charge',
    'label_after_charge' => 'After Charge',
    'label_rate' => 'Rate',
    'label_total' => 'Total Payable',
    
    // JS Confirmations
    'confirm_title' => 'Are you sure?',
    'confirm_text' => 'Action cannot be undone.',
    'confirm_yes' => 'Yes, proceed!',
    'confirm_approve_title' => 'Approve Payment?',
    'confirm_approve_text' => 'Approve transaction :trx?',
    'confirm_reject_title' => 'Reject Payment?',
    'confirm_reject_text' => 'Reject transaction :trx? This cannot be undone.',
];