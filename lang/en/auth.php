<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    /*
    |--------------------------------------------------------------------------
    | Custom UI Language Lines
    |--------------------------------------------------------------------------
    */

    // General Field Labels
    'name' => 'Full Name',
    'email_label' => 'Email Address',
    'phone_label' => 'Phone Number',
    'password_label' => 'Password',
    'confirm_password' => 'Confirm Password',
    'remember_me' => 'Remember Me',
    'forgot_password' => 'Forgot your password?',
    'select_option' => '-- Select Option --',

    // Form Placeholders
    'placeholder_name' => 'John Doe',
    'placeholder_email' => 'name@company.com',
    'placeholder_phone' => '+1 234 567 890',
    'placeholder_captcha' => 'Enter security code',

    // Login Page
    'login' => [
        'title' => 'Login',
        'tagline' => 'AI-Powered Assessment Platform',
        'headline' => 'Powering Smart Exams with AI.',
        'brand_sub' => 'Secure, automated exams with instant AI evaluation and deep performance insights.',
        
        'features' => [
            'instant' => 'Instant AI Evaluation',
            'security' => 'Enterprise-Grade Security',
            'analytics' => 'Advanced Performance Analytics',
        ],

        'welcome' => 'Welcome Back',
        'welcome_sub' => 'Sign in to access your exams, results, and AI analytics.',
        
        'captcha_label' => 'Security Check',
        'captcha_placeholder' => 'Enter code',
        'captcha_help' => 'Click the image to refresh code.',
        
        'submit' => 'Sign In',
        'or_continue' => 'or continue with',
        
        'no_account' => "Don't have an account?",
        'create_account' => 'Create an account',
        'secure_badge' => 'Secure 256-bit encrypted connection',
    ],

    // Register Page
    'register' => [
        'title' => 'Create Your Account',
        'subtitle' => 'Get started in minutes and access AI-powered exams instantly.',
        'headline' => 'Join the Future of AI Assessment.',
        'tagline' => 'AI-Powered Assessment Platform',
        'brand_desc' => 'Create, manage, and scale your assessments in minutes with our intelligent engine.',

        'features' => [
            'ai_tests' => 'Unlimited AI Practice Tests',
            'global_cert' => 'Global Certification Standards',
            'auto_results' => 'Automated Result Generation',
        ],

        'password_hint' => 'Minimum 8 characters with letters and numbers.',
        
        'captcha_label' => 'Security Check',
        'captcha_placeholder' => 'Enter code',
        'captcha_help' => 'Click the image to refresh code.',

        'or_signup' => 'or sign up with',

        'terms_text' => 'By creating an account, you agree to our',
        'terms' => 'Terms of Service',
        'privacy' => 'Privacy Policy',

        'already_account' => 'Already have an account?',
        'signin' => 'Sign In',
        'submit' => 'Create Account',
    ],

    // Forgot / Reset Password
    'forgot_title' => 'Forgot Password',
    'forgot_subtitle' => 'Enter your email and we will send you a reset link',
    'send_reset_link' => 'Send Password Reset Link',
    'reset_link_sent' => 'A password reset link has been sent to your email address.',
    'email_not_found' => 'We could not find a user with that email address.',

    'reset_title' => 'Reset Password',
    'reset_subtitle' => 'Create a new password for your account',
    'reset_button' => 'Reset Password',
    'password_updated' => 'Your password has been reset successfully.',
    'token_invalid' => 'This password reset token is invalid or has expired.',

    // Email Verification
    'verify' => [
        'title' => 'Verify Email - :app',
        'heading' => 'Verify Your Email',
        'subheading' => 'Please verify your email address to access full features.',
        'check_inbox' => 'Check your inbox',
        'sent_to' => 'We have sent a verification link to<br>:email',
        'resent' => 'A new verification link has been sent to your email address.',
        'help' => "Didn't receive the email? Check your spam folder or resend below.",
        'resend' => 'Resend Verification Email',
        'sign_out' => 'Sign Out',
        'verified_success' => 'Your email has been verified successfully.',
        'already_verified' => 'Your email is already verified.',
    ],

    // Common UI Messages
    'security_check' => 'Security Check',
    'confirm_identity' => 'Confirm your identity',
    'continue' => 'Continue',
    'success' => 'Success',
    'error' => 'Error',
    'warning' => 'Warning',
    'info' => 'Information',
    'something_wrong' => 'Something went wrong. Please try again.',
    'session_expired' => 'Your session has expired. Please login again.',
    'account_disabled' => 'Your account has been disabled. Please contact support.',

    'reset' => [
        'title' => 'Set New Password',
        'subtitle' => 'Enter and confirm your new strong password below.',
        'headline' => 'Complete Your Security Check.',
        'brand_sub' => 'Choose a strong, new password to ensure your account remains protected.',
        'features' => [
            'secure_token' => 'Secure Token Verification',
            'strong_pw' => 'Strong Password Enforcement',
        ],
        'email_label' => 'Email Address',
        'password_label' => 'New Password',
        'confirm_label' => 'Confirm New Password',
        'submit' => 'Reset Password',
        'secure_badge' => 'Protected by industry standards',
    ],

    'email' => [
        'title' => 'Forgot Password',
        'subtitle' => 'Securely reset your password and get back to your exams in no time.',
        'headline' => 'Need Access? We\'ve Got You.',
        'page_title' => 'Trouble Signing In?',
        'page_sub' => 'Enter your email address to receive a secure password reset link.',
        'features' => [
            'secure_process' => 'Secure Reset Process',
            'instant_delivery' => 'Instant Email Delivery',
        ],
        'submit' => 'Send Reset Link',
        'return' => 'Return to Sign In',
        'secure_badge' => 'Secure request protected by SSL',
    ],

    'sent' => [
        'title' => 'Check Your Inbox',
        'subtitle' => 'We have sent a secure password reset link to your email address.',
        'help' => 'Didn\'t receive it? Check your spam folder or',
        'try_again' => 'try again',
        'return' => 'Return to Sign In',
        'secure_badge' => 'Secure process',
    ],

    'success' => [
        'title' => 'Success!',
        'subtitle' => 'Your password has been successfully updated. You can now sign in with your new credentials.',
        'signin' => 'Sign In Now',
        'secure_badge' => 'Account Secured',
    ],

];