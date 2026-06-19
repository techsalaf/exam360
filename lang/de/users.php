<?php

return [
    // Page Titles & Headers
    'title_list' => 'Alle Benutzer',
    'title_active' => 'Aktive Benutzer',
    'title_banned' => 'Gesperrte Benutzer',
    'title_unverified_email' => 'E-Mail nicht verifiziert',
    'title_unverified_mobile' => 'Mobilnummer nicht verifiziert',
    'subtitle_list' => 'Verwalten und überwachen Sie alle registrierten Benutzer im System.',
    'title_show' => 'Konsole: :name',
    'title_notifications' => 'Benachrichtigungen senden',
    'subtitle_notifications' => 'Erstellen und versenden Sie Nachrichten per E-Mail oder SMS an Ihre Benutzer.',

    // KPI Labels
    'kpi_total_users' => 'Gesamtbenutzer',
    'kpi_unverified_emails' => 'Unverifizierte E-Mails',
    'kpi_banned_users' => 'Gesperrte Benutzer',
    'kpi_total_transactions' => 'Gesamtumsatz',
    'kpi_exams_taken' => 'Abgelegte Prüfungen',
    'kpi_payment_count' => 'Anzahl Zahlungen',

    // Buttons
    'btn_filter' => 'Filtern & Suchen',
    'btn_add_new' => 'Neu hinzufügen',
    'btn_view_details' => 'Details anzeigen',
    'btn_edit' => 'Bearbeiten',
    'btn_back' => 'Zurück zur Liste',
    'btn_logins' => 'Logins',
    'btn_login_as' => 'Anmelden als',
    'btn_save' => 'Änderungen speichern',
    'btn_clear_all' => 'Alle löschen',
    'btn_send_now' => 'Jetzt senden',
    'btn_cancel' => 'Abbrechen',
    'btn_apply_filters' => 'Filter anwenden',
    
    // Table Headers
    'col_user' => 'BENUTZER',
    'col_contact' => 'E-MAIL / MOBIL',
    'col_country' => 'LAND',
    'col_joined' => 'BEIGETRETEN AM',
    'col_action' => 'AKTION',
    'col_date' => 'DATUM',
    'col_plan' => 'PLAN / ARTIKEL',
    'col_amount' => 'BETRAG',
    'col_status' => 'STATUS',

    // Form Labels
    'label_name' => 'Vollständiger Name',
    'label_email' => 'E-Mail-Adresse',
    'label_mobile' => 'Mobilnummer',
    'label_country' => 'Land',
    'label_address' => 'Straße',
    'label_city' => 'Stadt',
    'label_state' => 'Bundesland',
    'label_zip' => 'Postleitzahl',
    'label_role' => 'Systemrolle',
    'label_password' => 'Passwort',
    'label_password_optional' => 'Neues Passwort (optional)',
    'label_password_placeholder' => 'Leer lassen, um aktuelles zu behalten',
    'label_search' => 'Suche nach Name oder E-Mail...',
    'label_search_users' => 'BENUTZER SUCHEN',
    'label_subject' => 'Betreff',
    'label_message' => 'Nachricht',

    // Statuses
    'status_verified' => 'Verifiziert',
    'status_unverified' => 'Nicht verifiziert',
    'status_banned' => 'GESPERRT',
    'status_active' => 'AKTIVER BENUTZER',
    'status_paid' => 'Bezahlt',
    'status_pending' => 'Ausstehend',
    'status_failed' => 'Fehlgeschlagen',
    'status_open_tickets' => ':count offene Tickets',

    // Sections (Show Page)
    'sect_profile' => 'Profilinformationen',
    'sect_transactions' => 'Letzte Transaktionen',
    'sect_notifications' => 'Benachrichtigungen',
    'sect_support' => 'Support-Verlauf',
    'sect_actions' => 'Aktionen',
    'sect_login_history' => 'Login-Verlauf',
    'sect_email_status' => 'E-MAIL-STATUS',
    'sect_mobile_status' => 'MOBIL-STATUS',
    'sect_current_plan' => 'AKTUELLER PLAN',

    // Alerts & Confirmations
    'confirm_title' => 'Sind Sie sicher?',
    'confirm_text' => 'Diese Aktion kann nicht rückgängig gemacht werden.',
    'confirm_yes' => 'Ja, fortfahren!',
    'confirm_delete_title' => 'Benachrichtigung löschen?',
    'confirm_delete_text' => "Dies kann nicht wiederhergestellt werden!",
    'confirm_clear_title' => 'Alle Benachrichtigungen löschen?',
    'confirm_clear_text' => 'Dies wird alle Benachrichtigungen für diesen Benutzer dauerhaft entfernen.',
    'confirm_ban_title' => 'Benutzer sperren?',
    'confirm_lift_ban_title' => 'Sperre aufheben?',
    'confirm_ban_text' => 'Möchten Sie den Status dieses Benutzers wirklich ändern?',
    'confirm_send_title' => 'Senden bestätigen',
    'confirm_send_text' => 'Sie sind dabei, diese Kampagne zu starten.',
    'action_ban' => 'Benutzer sperren',
    'action_unban' => 'Sperre aufheben',

    // Messages
    'msg_user_created' => 'Benutzer erfolgreich erstellt.',
    'msg_user_updated' => 'Benutzer erfolgreich aktualisiert.',
    'msg_user_banned' => 'Benutzer wurde gesperrt.',
    'msg_user_activated' => 'Benutzer wurde aktiviert.',
    'msg_plan_assigned' => 'Plan erfolgreich zugewiesen.',
    'msg_unauthorized_role' => 'Unbefugte Rollenzuweisung.',
    'msg_unauthorized_action' => 'Unbefugte Aktion.',
    'msg_cannot_ban' => 'Dieses Konto kann nicht gesperrt werden.',
    'msg_cannot_impersonate' => 'Super Admin kann nicht imitiert werden.',

    // Empty States
    'empty_title' => 'Keine Benutzer gefunden',
    'empty_subtitle' => 'Versuchen Sie, Ihre Suche oder Filter anzupassen.',
    'empty_transactions' => 'Keine Transaktionshistorie verfügbar.',
    'empty_notifications' => 'Keine neuen Benachrichtigungen.',
    'empty_support' => 'Keine Support-Tickets gefunden.',
    'empty_logins' => 'Kein Login-Verlauf verfügbar.',

    // Modals
    'modal_create_title' => 'Neuen Systembenutzer erstellen',
    'modal_create_btn' => 'Benutzer erstellen',
    'modal_edit_title' => 'Benutzer bearbeiten',
    'modal_edit_btn' => 'Benutzer aktualisieren',
];