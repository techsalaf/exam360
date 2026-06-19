<?php

return [
    'save' => 'Änderungen speichern',
    'cancel' => 'Abbrechen',
    'remove' => 'Entfernen',
    'none' => 'Keine',
    'recommended' => 'Empfohlen:',
    'publish' => 'Veröffentlichen',
    
    'certificate' => [
        'title' => 'Zertifikat-Studio',
        'sub' => 'Design & Verifikation',
        'reset_tooltip' => 'Design zurücksetzen',
        'layout_tab' => 'Layout & Stil',
        'content_tab' => 'Inhalt',
        'footer_tab' => 'Fußzeile',
        'orientation' => 'Ausrichtung',
        'landscape' => 'Querformat',
        'portrait' => 'Hochformat',
        'typography' => 'Typografie-Stil',

        // NEU
        'desktop_required' => 'Desktop erforderlich',
        'desktop_desc' => 'Das Zertifikat-Studio bietet eine umfangreiche Designerfahrung, die am besten auf einem größeren Bildschirm genutzt wird. Bitte greifen Sie über einen Desktop- oder Laptop-Computer auf diese Seite zu.',
        
        'font_pinyon_elegant' => 'Pinyon Script (Elegant)',
        'font_great_vibes_cursive' => 'Great Vibes (Kursiv)',
        'font_cinzel_formal' => 'Cinzel (Formal)',
        'font_lato_modern' => 'Lato (Modern)',
        
        'var_name' => 'Name',
        'var_exam' => 'Prüfung',
        'var_score' => 'Ergebnis',
        'var_date' => 'Datum',
        'var_qr' => 'QR-Bild',
        'default_body' => "Dies bestätigt, dass\n<strong>@{{full_name}}</strong>\n\ndie Anforderungen für\n<strong>@{{exam_title}}</strong>\nerfolgreich abgeschlossen hat.",
        
        'bg_image' => 'Hintergrundbild',
        'bg_help' => 'Benutzerdefinierten Rahmen/Hintergrund hochladen',
        'size_help' => 'Empfohlene Größe (A4 @ 300DPI): Querformat: 3508x2480 px, Hochformat: 2480x3508 px',
        'remove_bg' => 'Hintergrund entfernen',
        'heading' => 'Überschrift',
        'alignment' => 'Ausrichtung',
        'body_text' => 'Haupttext',
        'insert_var' => 'Variable einfügen',
        'var_help' => 'Variablen direkt in der Box oben löschen.',
        'date' => 'Datum',
        'sig_mode' => 'Signaturmodus',
        'text' => 'Text',
        'image' => 'Bild',
        'sig_name' => 'Name',
        'upload_sig' => 'Signatur hochladen',
        'remove_sig' => 'Bild entfernen',
        'toast_saved' => 'Einstellungen gespeichert!',
        'variables_title' => 'Variablen',
        'default_title' => 'Abschlusszertifikat',
        'default_sign' => 'Bildungsdirektor',
        'default_date' => 'Datum: @{{completed_at}}',
    ],

    'frontend' => [
    'title' => 'Frontend Sichtbarkeit',
    'desc' => 'Umschalten Sie die Abschnitte, um Ihr Homepage-Layout zu erstellen.',
    'sections' => [
        'hero' => ['title' => 'Hero-Abschnitt', 'desc' => 'Hauptbannerbereich'],
        'features' => ['title' => 'Funktionen', 'desc' => 'Wichtige Verkaufsargumente'],
        'categories' => ['title' => 'Kategorien', 'desc' => 'Themengruppen'],
        'exams' => ['title' => 'Hervorgehobene Prüfungen', 'desc' => 'Liste der Top-Prüfungen'],
        'how_it_works' => ['title' => 'So Funktioniert Es', 'desc' => 'Prozesserklärung'],
        'audience' => ['title' => 'Zielgruppe', 'desc' => 'Für wen ist das?'],
        'pricing' => ['title' => 'Preistabellen', 'desc' => 'Abonnementpläne'],
        'testimonials' => ['title' => 'Testimonials', 'desc' => 'Benutzerbewertungen'],
        'faq' => ['title' => 'Häufig gestellte Fragen', 'desc' => 'Häufige Fragen'],
        'cta' => ['title' => 'Handlungsaufforderung', 'desc' => 'Aufforderung zur abschließenden Anmeldung'],
        
        // New sections
        'admin_preview' => ['title' => 'Admin Dashboard Vorschau', 'desc' => 'Wichtige Metriken und Funktionen des Backend-Panels'],
        'cms_features' => ['title' => 'CMS & Funktionen', 'desc' => 'Übersicht über die Funktionen des Content Management Systems'],
    ],
],

    'logo' => [
        'title' => 'Logo & Favicon',
        'desc' => 'Verwalten Sie die visuelle Identität der Anwendung in hellen und dunklen Modi.',
        'system_logos' => 'System-Logos',
        'light_mode' => 'Logo (Heller Modus)',
        'light_help' => 'Dunkler Text/Symbol für helle Hintergründe.',
        'dark_mode' => 'Logo (Dunkler Modus)',
        'dark_help' => 'Weißer/Heller Text/Symbol für dunkle Hintergründe.',
        'browser_icon' => 'Browser-Symbol',
        'favicon' => 'Favicon',
        'favicon_help' => '32x32px oder 64x64px .ico oder .png Format.',
        'alerts' => [
            'confirm_title' => 'Sind Sie sicher?',
            'confirm_text' => 'Möchten Sie dieses Logo wirklich entfernen? Diese Aktion kann nicht rückgängig gemacht werden.',
            'yes_remove' => 'Ja, entfernen!',
        ]
    ],

    'styling' => [
        'title' => 'Benutzerdefiniertes Styling',
        'desc' => 'Fügen Sie benutzerdefiniertes CSS oder JS ein, um Standard-Themes zu überschreiben.',
        'warning' => 'Nur für fortgeschrittene Benutzer.',
        'css_label' => 'Benutzerdefiniertes CSS',
        'css_sub' => 'Wird im <head> der Benutzerseiten geladen.',
        'css_placeholder' => '/* Geben Sie hier benutzerdefinierte CSS-Regeln ein */',
        'js_label' => 'Benutzerdefiniertes JavaScript',
        'js_sub' => 'Wird vor dem schließenden </body> Tag geladen.',
        'js_placeholder' => '// Geben Sie hier benutzerdefiniertes JS ein (ohne <script>-Tags)',
        'save_code' => 'Code speichern',
    ]
];