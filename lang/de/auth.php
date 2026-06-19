<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    */

    'failed' => 'Diese Anmeldedaten stimmen nicht mit unseren Aufzeichnungen überein.',
    'password' => 'Das angegebene Passwort ist falsch.',
    'throttle' => 'Zu viele Anmeldeversuche. Bitte versuchen Sie es in :seconds Sekunden erneut.',

    /*
    |--------------------------------------------------------------------------
    | Custom UI Language Lines
    |--------------------------------------------------------------------------
    */

    // General Fields
    'name' => 'Vollständiger Name',
    'email_label' => 'E-Mail-Adresse', // FIX: Renamed from 'email'
    'password_label' => 'Passwort', // FIX: Added for UI input label
    'confirm_password' => 'Passwort bestätigen',
    'remember_me' => 'Angemeldet bleiben',
    'forgot_password' => 'Passwort vergessen?',

    // Login Page
    'login' => [
        'title' => 'Anmelden',
        'tagline' => 'KI-gestützte Bewertungsplattform',
        'headline' => 'Intelligente Prüfungen mit KI.',
        'brand_sub' => 'Sichere, automatisierte Prüfungen mit sofortiger KI-Auswertung und tiefgehenden Leistungsanalysen.',
        
        'features' => [
            'instant' => 'Sofortige KI-Auswertung',
            'security' => 'Sicherheit auf Unternehmensniveau',
            'analytics' => 'Erweiterte Leistungsanalysen',
        ],

        'welcome' => 'Willkommen zurück',
        'welcome_sub' => 'Melden Sie sich an, um auf Ihre Prüfungen, Ergebnisse und KI-Analysen zuzugreifen.',
        
        'captcha_label' => 'Sicherheitsüberprüfung',
        'captcha_placeholder' => 'Code eingeben',
        'captcha_help' => 'Klicken Sie auf das Bild, um den Code zu aktualisieren.',
        
        'submit' => 'Anmelden',
        'or_continue' => 'oder weiter mit',
        
        'no_account' => "Noch kein Konto?",
        'create_account' => 'Konto erstellen',
        'secure_badge' => 'Sichere 256-Bit-verschlüsselte Verbindung',
    ],

    // Register Page
    'register' => [
        'title' => 'Erstellen Sie Ihr Konto',
        'subtitle' => 'Starten Sie in wenigen Minuten und greifen Sie sofort auf KI-gestützte Prüfungen zu.',
        'headline' => 'Werden Sie Teil der Zukunft der KI-Bewertung.',
        'tagline' => 'KI-gestützte Bewertungsplattform',
        'brand_desc' => 'Erstellen, verwalten und skalieren Sie Ihre Bewertungen in wenigen Minuten mit unserer intelligenten Engine.',

        'features' => [
            'ai_tests' => 'Unbegrenzte KI-Übungstests',
            'global_cert' => 'Globale Zertifizierungsstandards',
            'auto_results' => 'Automatisierte Ergebnisgenerierung',
        ],

        'password_hint' => 'Mindestens 8 Zeichen mit Buchstaben und Zahlen.',
        
        'captcha_label' => 'Sicherheitsüberprüfung',
        'captcha_placeholder' => 'Code eingeben',
        'captcha_help' => 'Klicken Sie auf das Bild, um den Code zu aktualisieren.',

        'or_signup' => 'oder registrieren mit',

        'terms_text' => 'Mit der Erstellung eines Kontos stimmen Sie unseren',
        'terms' => 'Nutzungsbedingungen zu',
        'privacy' => 'Datenschutzrichtlinie',

        'already_account' => 'Haben Sie bereits ein Konto?',
        'signin' => 'Anmelden',
        'submit' => 'Konto erstellen',
    ],

    // Forgot / Reset Password
    'forgot_title' => 'Passwort vergessen',
    'forgot_subtitle' => 'Geben Sie Ihre E-Mail-Adresse ein und wir senden Ihnen einen Link zum Zurücksetzen.',
    'send_reset_link' => 'Link zum Zurücksetzen senden',
    'reset_link_sent' => 'Ein Link zum Zurücksetzen des Passworts wurde an Ihre E-Mail-Adresse gesendet.',
    'email_not_found' => 'Wir konnten keinen Benutzer mit dieser E-Mail-Adresse finden.',

    'reset_title' => 'Passwort zurücksetzen',
    'reset_subtitle' => 'Erstellen Sie ein neues Passwort für Ihr Konto',
    'reset_button' => 'Passwort zurücksetzen',
    'password_updated' => 'Ihr Passwort wurde erfolgreich zurückgesetzt.',
    'token_invalid' => 'Dieser Token zum Zurücksetzen des Passworts ist ungültig oder abgelaufen.',

    // Email Verification
    'verify' => [
        'title' => 'E-Mail verifizieren - :app',
        'heading' => 'Verifizieren Sie Ihre E-Mail',
        'subheading' => 'Bitte verifizieren Sie Ihre E-Mail-Adresse, um alle Funktionen nutzen zu können.',
        'check_inbox' => 'Prüfen Sie Ihren Posteingang',
        'sent_to' => 'Wir haben einen Bestätigungslink an<br>:email gesendet',
        'resent' => 'Ein neuer Bestätigungslink wurde an Ihre E-Mail-Adresse gesendet.',
        'help' => "E-Mail nicht erhalten? Prüfen Sie Ihren Spam-Ordner oder senden Sie sie unten erneut.",
        'resend' => 'Bestätigungs-E-Mail erneut senden',
        'sign_out' => 'Abmelden',
        'verified_success' => 'Ihre E-Mail wurde erfolgreich verifiziert.',
        'already_verified' => 'Ihre E-Mail ist bereits verifiziert.',
    ],

    // Common UI Messages
    'security_check' => 'Sicherheitsüberprüfung',
    'confirm_identity' => 'Bestätigen Sie Ihre Identität',
    'continue' => 'Weiter',
    'success' => 'Erfolg',
    'error' => 'Fehler',
    'warning' => 'Warnung',
    'info' => 'Information',
    'something_wrong' => 'Etwas ist schief gelaufen. Bitte versuchen Sie es erneut.',
    'session_expired' => 'Ihre Sitzung ist abgelaufen. Bitte melden Sie sich erneut an.',
    'account_disabled' => 'Ihr Konto wurde deaktiviert. Bitte kontaktieren Sie den Support.',

    'reset' => [
        'title' => 'Neues Passwort festlegen',
        'subtitle' => 'Geben Sie unten Ihr neues, starkes Passwort ein und bestätigen Sie es.',
        'headline' => 'Schließen Sie Ihre Sicherheitsüberprüfung ab.',
        'brand_sub' => 'Wählen Sie ein starkes, neues Passwort, um sicherzustellen, dass Ihr Konto geschützt bleibt.',
        'features' => [
            'secure_token' => 'Sichere Token-Verifizierung',
            'strong_pw' => 'Erzwingung starker Passwörter',
        ],
        'email_label' => 'E-Mail-Adresse',
        'password_label' => 'Neues Passwort',
        'confirm_label' => 'Neues Passwort bestätigen',
        'submit' => 'Passwort zurücksetzen',
        'secure_badge' => 'Geschützt durch Industriestandards',
    ],

    'email' => [
        'title' => 'Passwort vergessen',
        'subtitle' => 'Setzen Sie Ihr Passwort sicher zurück und kehren Sie sofort zu Ihren Prüfungen zurück.',
        'headline' => 'Zugriff benötigt? Wir helfen Ihnen.',
        'page_title' => 'Probleme beim Anmelden?',
        'page_sub' => 'Geben Sie Ihre E-Mail-Adresse ein, um einen sicheren Link zum Zurücksetzen des Passworts zu erhalten.',
        'features' => [
            'secure_process' => 'Sicherer Rücksetzprozess',
            'instant_delivery' => 'Sofortige E-Mail-Zustellung',
        ],
        'submit' => 'Link senden',
        'return' => 'Zurück zur Anmeldung',
        'secure_badge' => 'Sichere Anfrage geschützt durch SSL',
    ],

    'sent' => [
        'title' => 'Prüfen Sie Ihren Posteingang',
        'subtitle' => 'Wir haben einen sicheren Link zum Zurücksetzen des Passworts an Ihre E-Mail-Adresse gesendet.',
        'help' => 'Nicht erhalten? Prüfen Sie Ihren Spam-Ordner oder',
        'try_again' => 'versuchen Sie es erneut',
        'return' => 'Zurück zur Anmeldung',
        'secure_badge' => 'Sicherer Prozess',
    ],

    'success' => [
        'title' => 'Erfolg!',
        'subtitle' => 'Ihr Passwort wurde erfolgreich aktualisiert. Sie können sich jetzt mit Ihren neuen Zugangsdaten anmelden.',
        'signin' => 'Jetzt anmelden',
        'secure_badge' => 'Konto gesichert',
    ],

];