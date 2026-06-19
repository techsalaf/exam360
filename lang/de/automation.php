<?php

return [
    'save' => 'Änderungen speichern',
    'recommended' => 'Empfohlen:',
    'or' => 'oder',
    'copy_url' => 'URL kopieren',
    
    'ai' => [
        'title' => 'KI-Konfiguration',
        'desc' => 'Verbinden Sie LLM-Anbieter für automatische Inhaltserstellung und Bewertung.',
        'primary_driver' => 'Primärer KI-Treiber',
        'driver_desc' => 'Wählen Sie die Engine für Generierungsaufgaben.',
        'disabled' => 'Deaktiviert',
        'driver_gemini' => 'Google Gemini (Empfohlen)',
        'driver_openai' => 'OpenAI (ChatGPT)',
        
        'gemini' => [
            'title' => 'Gemini-Einstellungen',
            'desc' => 'Konfigurieren Sie den Zugriff auf die generativen KI-Modelle von Google.',
            'api_key' => 'Gemini API-Schlüssel',
            'model' => 'Modellversion',
        ],
        
        'openai' => [
            'title' => 'OpenAI-Einstellungen',
            'desc' => 'Konfigurieren Sie den Zugriff auf ChatGPT-Modelle.',
            'api_key' => 'OpenAI API-Schlüssel',
            'model' => 'Modellversion',
        ],
    ],

    'cron' => [
        'title' => 'Cron-Job-Planer',
        'desc' => 'Verwalten Sie geplante Hintergrundaufgaben für Benachrichtigungen und Wartung.',
        'info_title' => 'Warum ist das erforderlich?',
        'info_desc' => 'Cron-Jobs erledigen kritische wiederkehrende Aufgaben wie die Verarbeitung von Prüfungsergebnissen, Abonnementverlängerungen und E-Mail-Benachrichtigungen.',
        'server_cmd' => 'Server-Befehl',
        'server_cmd_desc' => 'Fügen Sie diesen Eintrag zu Ihrer Server-Crontab hinzu (z. B. cPanel).',
        'entry_label' => 'Cron-Eintrag (Jede Minute ausführen)',
        'token_label' => 'Sicherheitstoken',
        'token_ph' => 'Eindeutiges Sicherheitstoken',
        'token_help' => 'Sichert die URL für externe Cron-Dienste.',
        'enable_label' => 'Planer aktivieren',
        'enable_desc' => 'Hintergrundaufgaben verarbeiten.',
        'copied' => 'Befehl in die Zwischenablage kopiert',
        'copy_fail' => 'Befehl konnte nicht kopiert werden',
    ],

    'ext' => [
        'title' => 'Erweiterungskonfigurationen',
        'desc' => 'Verwalten Sie Integrationen von Drittanbietern, Widgets und Sicherheitstools.',
        
        'google' => [
            'title' => 'Google Login',
            'desc' => 'Benutzern die Anmeldung mit Google ermöglichen.',
            'client_id' => 'Client-ID',
            'client_secret' => 'Client-Schlüssel',
            'callback' => 'Callback-URL',
            'help' => 'Fügen Sie diese URL in Ihre Google Developer Console ein.',
        ],

        'facebook' => [
            'title' => 'Facebook Login',
            'desc' => 'Benutzern die Anmeldung mit Facebook ermöglichen.',
            'app_id' => 'App-ID',
            'app_secret' => 'App-Schlüssel',
            'callback' => 'Callback-URL',
            'help' => 'Fügen Sie diese URL in Ihre Facebook Developer-Einstellungen ein.',
        ],

        'captcha' => [
            'title' => 'Benutzerdefiniertes Captcha',
            'desc' => 'Interne Überprüfung.',
            'length' => 'Code-Länge',
            'chars' => 'Zeichen',
        ],

        'recaptcha' => [
            'title' => 'Google Recaptcha v2',
            'desc' => 'Spamschutz.',
            'site_key' => 'Websiteschlüssel',
            'secret_key' => 'Geheimer Schlüssel',
        ],

        'tawk' => [
            'title' => 'Tawk.to Live-Chat',
            'desc' => 'Kundensupport.',
            'link_label' => 'Direkter Chat-Link',
            'link_help' => 'Fügen Sie Ihren direkten Chat-Link aus dem Tawk.to-Dashboard ein.',
        ],
    ],
];