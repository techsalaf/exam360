<?php

return [
    'save' => 'Änderungen speichern',
    'save_config' => 'Konfiguration speichern',
    'generate_now' => 'Sitemap jetzt generieren',

    'alerts' => [
        'remove_title' => 'Banner entfernen?',
        'remove_text' => 'Dadurch wird das aktuelle SEO-Banner beim Speichern entfernt.',
        'yes_remove' => 'Ja, entfernen',
        'cancel' => 'Abbrechen',
        'invalid_group' => 'Ungültige Einstellungsgruppe.',
        'updated_success' => 'SEO-Einstellungen erfolgreich aktualisiert.',
        'sitemap_generated' => 'Sitemap erfolgreich generiert.',
        'sitemap_failed' => 'Fehler beim Generieren der Sitemap: :error',
        'sitemap_not_found' => 'Sitemap-Datei nicht gefunden. Bitte zuerst generieren.',
    ],

    'defaults' => [
        'desc' => 'Die beste KI-gestützte Bewertungs- und Lernplattform.',
        'keywords' => 'Prüfung, KI, Bewertung, Lernen',
    ],
    
    'config' => [
        'title' => 'SEO-Konfiguration',
        'desc' => 'Konfigurieren Sie Meta-Tags, visuelle Elemente für soziale Medien und Analysetools.',
        'meta_title' => 'Meta-Tags & Visuelles',
        'meta_desc' => 'Optimieren Sie die Darstellung Ihrer Website in Suchergebnissen.',
        'meta_title_label' => 'Meta-Titel (Max. 60 Zeichen)',
        'meta_title_ph' => 'Bsp: ZiExam AI - Lernplattform',
        'meta_desc_label' => 'Meta-Beschreibung (Max. 160 Zeichen)',
        'meta_desc_ph' => 'Eine kurze Zusammenfassung des Inhalts Ihrer Website.',
        'keywords_label' => 'Schlüsselwörter (durch Komma getrennt)',
        'keywords_ph' => 'schlüsselwörter, getrennt, durch, kommas',
        
        'analytics_title' => 'Analytik & Verifizierung',
        'ga_label' => 'Google Analytics Tracking-ID',
        'ga_ph' => 'UA-XXXXXXXXX-Y oder G-XXXXXXXXX',
        'ga_help' => 'Geben Sie Ihre Google Analytics/GA4 Mess-ID ein.',
        
        'banner_title' => 'Banner für soziale Medien',
        'banner_help' => 'Empfohlene Größe: 1200x630px. Wird für OpenGraph / Twitter Cards verwendet.',
        'delete_banner_title' => 'Aktuelles Banner löschen',
        'no_banner' => 'Kein Banner hochgeladen.',
    ],

    'sitemap' => [
        'title' => 'Sitemap-Konfiguration',
        'desc' => 'Steuern Sie das Crawling durch Suchmaschinen und verwalten Sie die XML-Sitemap.',
        'crawling_title' => 'Crawling-Regeln',
        'crawling_desc' => 'Definieren Sie, wie Bots mit Ihrer Website interagieren.',
        'robots_label' => 'Robots-Meta-Tag',
        'robots_options' => [
            'index_follow' => 'Indexieren und Folgen (Standard)',
            'noindex_follow' => 'Nicht indexieren, aber Links folgen',
            'index_nofollow' => 'Indexieren, aber Links nicht folgen',
            'noindex_nofollow' => 'Nicht indexieren und nicht folgen',
        ],
        'robots_help' => 'Steuert das webweite Indexierungsverhalten.',
        
        'status_title' => 'Sitemap-Status',
        'file_url' => 'Datei-URL:',
        'last_gen' => 'Zuletzt generiert:',
        'never' => 'Niemals',
        'download_xml' => 'XML herunterladen',
        'info_text' => 'Die Datei <strong>sitemap.xml</strong> hilft Suchmaschinen, Ihre Seiten zu entdecken. Senden Sie nach der Generierung die vollständige URL an die Google Search Console.',
    ],
];