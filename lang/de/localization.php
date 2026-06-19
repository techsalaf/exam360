<?php

return [
    'defaults' => [
        'title' => 'Lokalisierungsstandards',
        'desc' => 'Konfigurieren Sie Standardsprache, Zeitzone und Land.',
        'regional_title' => 'Regionale Einstellungen',
        'regional_desc' => 'Basiswerte für neue Besucher festlegen.',
        'language_label' => 'Systemsprache',
        'language_help' => 'Rückfallsprache, wenn keine Auswahl getroffen wurde.',
        'timezone_label' => 'Standard-Zeitzone',
        'country_label' => 'Standardland',
        'countries' => [
            'US' => 'Vereinigte Staaten',
            'GB' => 'Vereinigtes Königreich',
            'IN' => 'Indien',
            'BD' => 'Bangladesch',
        ],
        'save_btn' => 'Standards speichern',
    ],

    'switchers' => [
        'title' => 'Sprachumschalter',
        'desc' => 'Steuern Sie, wo Benutzer die Sprache ändern können.',
        'front_label' => 'Frontend-Umschalter',
        'front_help' => 'Besuchern erlauben, die Sprache auf der öffentlichen Seite zu ändern.',
        'admin_label' => 'Admin-Panel-Umschalter',
        'admin_help' => 'Mitarbeitern erlauben, die Sprache im Dashboard zu ändern.',
        'update_btn' => 'Sichtbarkeit aktualisieren',
    ],

    'table' => [
        'title' => 'Verfügbare Sprachen',
        'desc' => 'Verwalten Sie Systemsprachen und deren Verfügbarkeit.',
        'add_new' => 'Neu hinzufügen',
        'headers' => [
            'name' => 'Name',
            'code' => 'Code',
            'rtl' => 'RTL',
            'front' => 'Frontend',
            'admin' => 'Admin',
            'actions' => 'Aktionen',
        ],
        'badges' => [
            'default' => 'Standard',
            'active' => 'Aktiv',
            'hidden' => 'Versteckt',
            'yes' => 'Ja',
            'no' => 'Nein',
        ],
        'tooltips' => [
            'set_default' => 'Als Standard festlegen',
            'curr_default' => 'Aktueller Standard',
            'delete' => 'Sprache löschen',
        ],
    ],

    'modals' => [
        'add_title' => 'Neue Sprache hinzufügen',
        'edit_title' => 'Sprache bearbeiten',
        'name_label' => 'Name',
        'name_ph' => 'z.B. Französisch',
        'code_label' => 'Code (ISO 2)',
        'code_ph' => 'z.B. fr',
        'flag_label' => 'Flaggen-Code',
        'flag_help' => 'Verwendet für flag-icon-css oder Emoji.',
        'rtl_label' => 'Rechts-nach-Links (RTL)',
        'front_label' => 'Verfügbar im Frontend',
        'admin_label' => 'Verfügbar im Admin-Panel',
        'add_btn' => 'Sprache hinzufügen',
        'save_btn' => 'Änderungen speichern',
    ],

    'alerts' => [
        'delete_title' => 'Sprache löschen?',
        'delete_text' => 'Möchten Sie :name wirklich löschen? Dies entfernt die Übersetzungsdatei unwiderruflich.',
        'default_title' => 'Als Standard?',
        'default_text' => 'Möchten Sie :name zur Hauptsprache machen? Neue Besucher sehen diese Sprache zuerst.',
        'yes_delete' => 'Ja, löschen',
        'yes_default' => 'Ja, als Standard',
        'cancel' => 'Abbrechen',
    ],
];