<?php

return [
    'mobile_menu' => 'Einstellungsmenü',
    'nav_title'   => 'Einstellungen',
    
    'groups' => [
        'system'        => 'System',
        'appearance'    => 'Erscheinungsbild',
        'communication' => 'Kommunikation',
        'billing'       => 'Abrechnung',
        'regional'      => 'Regional',
        'visibility'    => 'Sichtbarkeit',
        'automation'    => 'Automatisierung',
        'security'      => 'Sicherheit',
    ],

    'links' => [
        'general'     => ['title' => 'Allgemein', 'sub' => 'Identität & Zeitzone'],
        'config'      => ['title' => 'Kernkonfiguration', 'sub' => 'Umgebung & Limits'],
        'roles'       => ['title' => 'Rollen & Rechte', 'sub' => 'Zugriffskontrolle'],
        'maintenance' => ['title' => 'Wartung', 'sub' => 'Ausfallzeiten verwalten'],
        
        'logo'         => ['title' => 'Logo & Favicon', 'sub' => 'Marken-Assets'],
        'certificates' => ['title' => 'Zertifikate', 'sub' => 'Vorlagen & Design'],
        'frontend'     => ['title' => 'Frontend', 'sub' => 'Öffentliche Sichtbarkeit'],
        'css'          => ['title' => 'Benutzerdefiniertes CSS', 'sub' => 'Globale Überschreibungen'],
        
        'alerts' => ['title' => 'Benachrichtigungen', 'sub' => 'Systemmeldungen'],
        'email'  => ['title' => 'E-Mail-Setup', 'sub' => 'SMTP & Treiber'],
        'social' => ['title' => 'Social Login', 'sub' => 'OAuth-Anbieter'],
        
        'gateways' => ['title' => 'Zahlungs-Gateways', 'sub' => 'Stripe, PayPal, usw.'],
        'currency' => ['title' => 'Währung', 'sub' => 'Symbole & Formate'],
        'tax'      => ['title' => 'Steuerregeln', 'sub' => 'MwSt. & Umsatzsteuer'],
        
        'language' => ['title' => 'Lokalisierung', 'sub' => 'Sprache & Land'],
        
        'seo'     => ['title' => 'SEO-Konfiguration', 'sub' => 'Meta-Tags & Analysen'],
        'sitemap' => ['title' => 'Sitemap', 'sub' => 'XML-Generierung'],
        
        'ai'         => ['title' => 'KI-Integration', 'sub' => 'LLM-Konfiguration'],
        'cron'       => ['title' => 'Cron-Jobs', 'sub' => 'Geplante Aufgaben'],
        'extensions' => ['title' => 'Erweiterungen', 'sub' => 'Module & Add-ons'],
        
        'gdpr'   => ['title' => 'DSGVO & Cookies', 'sub' => 'Einwilligungsverwaltung'],
        'policy' => ['title' => 'Rechtliche Seiten', 'sub' => 'AGB & Datenschutz'],
    ],

    'status' => [
        'operational' => 'System Betriebsbereit'
    ]
];