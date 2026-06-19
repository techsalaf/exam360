<?php

return [
    'save' => 'Änderungen speichern',
    'configure' => 'Konfigurieren',
    'active' => 'Aktiv',
    'variables' => 'Variablen:',
    'test_mail' => 'Test-E-Mail',
    'copy_url' => 'URL kopieren',
    
    'general' => [
        'title' => 'Benachrichtigungszentrum',
        'subtitle' => 'Verwalten Sie Auslöser, Logik und Drittanbieter-Gateways.',
        'tabs' => [
            'logic' => 'Logik & Auslöser',
            'sms' => 'SMS-Gateway',
            'push' => 'Push-Konfig',
            'templates' => 'Vorlagen',
        ],
        'kpi' => [
            'email' => 'E-Mail-System',
            'sms' => 'SMS-System',
            'push' => 'Push-Nachr.',
            'global_switch' => 'Globaler Schalter',
        ],
        'triggers' => [
            'title' => 'Ereignisauslöser & Routing',
            'col_event' => 'Ereignis',
            'signup' => 'Neue Benutzerregistrierung',
            'signup_desc' => 'Wird sofort nach der Anmeldung ausgelöst.',
            'exam' => 'Prüfungsabschluss',
            'exam_desc' => 'Ergebnis- und Punktzahlverarbeitung.',
            'payment' => 'Zahlung erfolgreich',
            'payment_desc' => 'Rechnungen und Planaktivierung.',
        ],
        'sms_gateways' => [
            'provider' => 'SMS-Anbieter',
            'twilio' => 'Twilio',
            'vonage' => 'Vonage',
            'standard' => 'Standard',
            'international' => 'International',
            'api_creds' => 'API-Anmeldeinformationen',
            'account_sid' => 'Account SID',
            'api_key' => 'API Key',
            'auth_token' => 'Auth Token',
            'api_secret' => 'API Secret',
            'from' => 'Absendernummer',
            'from_desc' => 'Absendernummer (E.164)',
            'sender_id' => 'Sender ID (Markenname)',
            'env' => 'Umgebung',
            'sandbox' => 'Sandbox-Modus aktivieren',
        ],
        'firebase' => [
            'title' => 'Firebase Cloud Messaging (FCM)',
            'server_key' => 'Serverschlüssel (Legacy)',
            'project_id' => 'Project ID',
            'app_id' => 'App ID',
            'sender_id' => 'Sender ID',
            'bucket' => 'Storage Bucket',
        ],
        'template_links' => [
            'email' => 'E-Mail-Vorlagen',
            'sms' => 'SMS-Vorlagen',
            'push' => 'Push-Vorlagen',
        ],
    ],

    'social' => [
        'title' => 'Social Login',
        'subtitle' => 'Konfigurieren Sie OAuth-Anbieter für die Ein-Klick-Anmeldung.',
        'google' => 'Google Login',
        'google_desc' => 'Anmeldung über Google-Konto aktivieren.',
        'facebook' => 'Facebook Login',
        'facebook_desc' => 'Anmeldung über Facebook-Konto aktivieren.',
        'client_id' => 'Client ID',
        'client_secret' => 'Client Secret',
        'app_id' => 'App ID',
        'app_secret' => 'App Secret',
        'callback' => 'Callback URL',
        'google_help' => 'Fügen Sie diese URL in Ihre Google Developer Console ein.',
        'facebook_help' => 'Fügen Sie diese URL in Ihre Facebook Developer-Einstellungen ein.',
    ],

    'email' => [
        'title' => 'E-Mail-Konfiguration',
        'subtitle' => 'Konfigurieren Sie Postausgangstreiber und Absenderidentität.',
        'driver_label' => 'Mail-Treiber',
        'drivers' => [
            'smtp' => 'SMTP',
            'smtp_desc' => 'Empfohlen',
            'php' => 'PHP Mail',
            'php_desc' => 'Server-Standard',
            'mailgun' => 'Mailgun',
            'mailgun_desc' => 'API-basiert',
        ],
        'smtp' => [
            'title' => 'SMTP-Verbindungsdetails',
            'host' => 'Mail-Host',
            'port' => 'Port',
            'username' => 'Benutzername',
            'password' => 'Passwort',
            'encryption' => 'Verschlüsselung',
            'none' => 'Keine',
        ],
        'mailgun' => [
            'title' => 'Mailgun API-Konfiguration',
            'domain' => 'Mailgun-Domain',
            'secret' => 'Mailgun Secret (API Key)',
            'endpoint' => 'Mailgun Endpoint',
            'help' => 'Verwenden Sie api.eu.mailgun.net für EU-Regionen.',
        ],
        'identity' => [
            'title' => 'Absenderidentität',
            'name' => 'Absendername',
            'name_desc' => 'Wird im Posteingang des Empfängers angezeigt.',
            'address' => 'Absender-E-Mail',
            'address_desc' => 'Muss von Ihrem Anbieter autorisiert sein.',
        ],
        'alerts' => [
            'confirm_test' => 'Test-E-Mail an den aktuell angemeldeten Benutzer senden? Stellen Sie sicher, dass Sie zuerst gespeichert haben.',
            'sending' => 'Senden...',
            'success' => 'Erfolg',
            'failed' => 'Verbindung fehlgeschlagen',
            'error' => 'Ein unerwarteter Fehler ist aufgetreten.',
        ]
    ],

    'templates' => [
        'email_title' => 'E-Mail-Vorlagen',
        'email_subtitle' => 'Passen Sie HTML-Betreff und Text für System-E-Mails an.',
        'sms_title' => 'SMS-Vorlagen',
        'sms_subtitle' => 'Konfigurieren Sie 160-Zeichen-Textnachrichten.',
        'push_title' => 'Push-Benachrichtigungen',
        'push_subtitle' => 'Verwalten Sie App-Warnungen und Inhalte.',
        
        'tabs' => [
            'signup' => 'Willkommen (Registrierung)',
            'exam' => 'Prüfungsergebnis',
            'payment' => 'Zahlungsbeleg',
        ],
        
        'fields' => [
            'subject' => 'Betreffzeile',
            'html_body' => 'HTML-Inhalt',
            'content' => 'Nachrichteninhalt',
            'alert_title' => 'Titel der Warnung',
            'alert_body' => 'Inhalt der Warnung',
        ],

        'defaults' => [
            'signup_sub' => 'Willkommen auf unserer Plattform!',
            'signup_body' => '<p>Hallo {{name}},</p><p>Willkommen!</p>',
            'exam_sub' => 'Prüfungsergebnisse verfügbar',
            'exam_body' => '<p>Hallo {{name}},</p><p>Sie haben <strong>{{score}}%</strong> erreicht.</p>',
            'pay_sub' => 'Zahlungsbeleg',
            'pay_body' => '<p>Wir haben Ihre Zahlung von <strong>{{amount}}</strong> erhalten.</p>',
            
            'push_signup_t' => 'Willkommen!',
            'push_signup_b' => 'Danke, dass Sie beigetreten sind.',
            'push_exam_t' => 'Prüfungsergebnisse',
            'push_exam_b' => 'Sie haben {{score}}% in {{exam}} erreicht.',
            'push_pay_t' => 'Zahlung erhalten',
            'push_pay_b' => 'Ihr Abonnement für {{plan}} ist aktiv.',
            
            'sms_signup' => 'Willkommen bei Ziexam, {{name}}! Verifizieren: {{link}}',
            'sms_exam' => 'Glückwunsch {{name}}! {{exam}} bestanden mit {{score}}%.',
            'sms_pay' => 'Zahlung von {{amount}} für {{plan}} erhalten. Danke!',
        ],

        'preview' => [
            'label' => 'Vorschau',
            'app_name' => 'App Name',
            'now' => 'jetzt',
        ]
    ]
];