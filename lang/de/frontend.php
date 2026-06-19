<?php

return [
    /*
    |--------------------------------------------------------------------------
    | E-Mail-Bestätigung
    |--------------------------------------------------------------------------
    */
    'email_not_verified' => 'Bestätigen Sie Ihre E-Mail-Adresse',
    'verify_email_desc' => 'Bitte bestätigen Sie Ihre E-Mail, um alle Funktionen nutzen zu können.',
    'resend_link' => 'Bestätigungslink erneut senden',
    'verification_link_sent' => 'Ein neuer Bestätigungslink wurde an Ihre E-Mail-Adresse gesendet.',
    'system_alert' => 'Systemwarnung',


     // User Result Page (Metrics & Status)
    'overall_score'         => 'GESAMTERGEBNIS',
    'status_passed'         => 'BESTANDEN',
    'status_failed'         => 'NICHT BESTANDEN',

    // New Metric Labels (Crucial for the new layout)
    'metric_correct'        => 'KORREKTE ANTWORTEN',
    'metric_time'           => 'BENÖTIGTE ZEIT (MIN)',
    'metric_pass_percentage'=> 'ERFORDERLICHE BESTEHENSQUOTE %',
    
    // NEW NEGATIVE MARKING KEYS
    'metric_incorrect'      => 'FALSCHE ANTWORTEN',
    'metric_net_score'      => 'NETTO-PUNKTEZAHL',
    'metric_deducted_marks' => 'ABGEZOGENE PUNKTE',
    
    // Total marks label update
    'metric_total_marks'    => 'GESAMTPUNKTE', 

    // New Alert Key
    'negative_marking_alert' => 'Negativbewertung ist aktiviert: :value Punkte Abzug pro falscher Antwort.',
    
    // Additional Text (If needed)
    'mins'                  => 'Min',

    // resources/lang/de/frontend.php

'end_exam' => 'Prüfung beenden',
'question_label' => 'Frage',
'of_label' => 'von',
'loading' => 'Lädt...',
'previous_btn' => 'Zurück',
'next_btn' => 'Weiter',
'mark_review' => 'Zur Überprüfung markieren',
'submit_finish' => 'Absenden & Beenden',
'auto_save_msg' => 'Alle Antworten werden automatisch gespeichert.',
'progress_overview' => 'Fortschrittsübersicht',
'stat_answered' => 'Beantwortet',
'stat_marked' => 'Zur Überprüfung markiert',
'stat_remaining' => 'Verbleibend',
'question_navigator' => 'Fragen-Navigator',
'confirm_submission' => 'Einreichung bestätigen',

// --- JavaScript Strings ---

'error_no_questions' => 'Keine Fragen gefunden. Bitte wenden Sie sich an den Support.',
'question_missing_text' => 'Fragentext fehlt.',
'no_options_available' => 'Keine Optionen verfügbar.',
'status_saving' => 'Speichern läuft...',
'status_saved_success' => 'Alle Antworten wurden automatisch gespeichert.',
'status_save_error' => 'Verbindung verloren. Versuche erneut zu verbinden...',
'timer_time_up_title' => 'Die Zeit ist abgelaufen!',
'timer_time_up_text' => 'Ihre Prüfung wird automatisch eingereicht.',
'validation_action_required_title' => 'Aktion erforderlich',
'validation_answer_or_mark' => 'Bitte wählen Sie eine Antwort ODER markieren Sie diese Frage zur Überprüfung, bevor Sie fortfahren.',
'validation_understood' => 'Verstanden',
'mark_unmarked_warning' => 'Frage demarkiert. Sie müssen sie beantworten, um später fortzufahren.',
'mark_marked_info' => 'Zur Überprüfung markiert.',
'submission_pending_reviews_title' => 'Ausstehende Überprüfungen',
'submission_pending_reviews_text' => 'Sie haben {count} Frage(n) zur Überprüfung markiert.',
'submission_submit_anyway' => 'Trotzdem absenden',
'submission_review_questions' => 'Fragen überprüfen',
'submission_finish_title' => 'Prüfung beenden?',
'submission_finish_text' => 'Sie können Ihre Antworten nach der Einreichung nicht mehr ändern.',
'submission_yes_submit' => 'Ja, absenden!',
    /*
    |--------------------------------------------------------------------------
    | Frontend Dashboard & General (German)
    |--------------------------------------------------------------------------
    */
    'welcome_back' => 'Willkommen zurück',
    'student_default' => 'Student',
    'header_subtitle' => 'Bleib konzentriert. Deine nächste Prüfung steht bereit.',
    'assessment' => 'Prüfung',
    'minutes' => 'Minuten',
    'mins' => 'Min',
    'view' => 'Ansehen',
    'result' => 'Ergebnis',
    // Checkout Flow
    'checkout_title' => 'Sicherer Checkout',
    'step_cart' => 'Warenkorb',
    'step_details' => 'Details',
    'step_payment' => 'Zahlung',
    
    // Payment Page Titles & Descriptions
    'payment_method' => 'Zahlungsmethode',
    'payment_desc' => 'Transaktionen sind verschlüsselt und gesichert.',
    'no_payment_enabled' => 'Derzeit sind keine Zahlungsgateways aktiviert.',
    
    // Payment Options
    'credit_debit_card' => 'Kredit-/Debitkarte',
    'bank_transfer_offline' => 'Banküberweisung/Offline',
    
    // Stripe Fields
    'card_holder_name' => 'Name des Karteninhabers',
    'email' => 'E-Mail',
    'card_number' => 'Kartennummer',
    'expiry_date' => 'Ablaufdatum',
    'cvc' => 'CVC',
    'securely_processed_by_stripe' => 'Sicher verarbeitet durch Stripe',
    
    // Bank/Offline Fields
    'account_holder_name' => 'Name des Kontoinhabers',
    'bank_name' => 'Name der Bank',
    'account_number_iban' => 'Kontonummer / IBAN',
    'ifsc_swift_code' => 'IFSC / SWIFT-Code',
    'additional_instructions' => 'Zusätzliche Anweisungen',
    'offline_gateway_note' => 'Bitte verwenden Sie die folgenden Details, um Ihre Überweisung abzuschließen. Ihre Bestellung wird nach manueller Überprüfung der Zahlung bestätigt.',
    
    // Payment Prompts
    'select_gateway_prompt' => 'Bitte wählen Sie oben eine Zahlungsmethode aus, um die erforderlichen Felder anzuzeigen.',
    'razorpay_redirect_note' => 'Diese Methode erfordert normalerweise eine Weiterleitung zur Vervollständigung der Zahlung auf der Razorpay-Plattform.',

    // Order Summary
    'order_summary' => 'Bestellübersicht',
    'subtotal' => 'Zwischensumme',
    'taxes' => 'Steuern',
    'total_amount' => 'Gesamtbetrag',

    // Buttons & Security
    'pay_with_amount' => 'Bezahlen Sie {amount} & Zugang',
    'back_details' => 'Zurück zu den Details',
    'bank_security' => 'Sicherheit auf Bankniveau',
    
    // Exam Actions
    'continue_exam' => 'Prüfung fortsetzen',
    'start_now' => 'Jetzt starten',
    'view_instructions' => 'Anleitung ansehen',
    'view_all_exams' => 'Alle Prüfungen ansehen',
    'retake_exam' => 'Prüfung wiederholen',
    'go_to_exams' => 'Zu den Prüfungen',

    // Exam Status
    'ongoing' => 'Laufend',
    'ready' => 'Bereit',
    'completed' => 'Abgeschlossen',
    'pending' => 'Ausstehend',
    
    // Hero Section
    'no_active' => 'KEINE AKTIVEN',
    'no_scheduled_title' => 'Keine geplanten Prüfungen',
    'no_scheduled_desc' => 'Überprüfe deine Kurse auf verfügbare Prüfungen oder Quizze.',
    'available_now' => 'Jetzt verfügbar',

    // Stats Widget
    'scheduled' => 'Geplant',
    'avg_score' => 'Durchschn.',
    
    // Exam List Section
    'your_exams' => 'Deine Prüfungen',
    'tab_upcoming' => 'Bevorstehend',
    'tab_history' => 'Verlauf',
    'no_upcoming_exams' => 'Keine bevorstehenden Prüfungen gefunden.',
    'no_history_exams' => 'Keine abgeschlossenen Prüfungen gefunden.',
    'score_label' => 'Punktzahl:',
    
    // Performance Widget
    'performance_snapshot' => 'Leistungsübersicht',
    'accuracy_rate' => 'Genauigkeitsrate',
    'time_management' => 'Zeitmanagement',
    'consistency' => 'Konsistenz',
    
    // Updates Widget
    'exam_updates' => 'Prüfungs-Updates',
    'schedule_change' => 'Terminänderung',
    'schedule_change_msg' => 'Die Physik-Zwischenprüfung wurde auf den 28. Okt verschoben.',
    'result_published' => 'Ergebnis veröffentlicht',
    'result_published_msg' => 'Die Endnote für Chemie ist jetzt einsehbar.',

    // Benachrichtigungen
    'notifications' => 'Benachrichtigungen',
    'no_notifications' => 'Keine neuen Benachrichtigungen.',
    'view_all_notifications' => 'Alle Benachrichtigungen ansehen',
    'notification_welcome_title' => 'Willkommen bei ZiExam AI',
    'notification_welcome_body' => 'Wir freuen uns, Sie an Bord zu haben! Starten Sie, indem Sie verfügbare Prüfungen erkunden.',
    'notification_result_title' => 'Prüfungsergebnis veröffentlicht',
    'notification_result_body' => 'Ihre Ergebnisse für "{exam}" sind jetzt verfügbar.',
    'notification_payment_title' => 'Zahlung erfolgreich',
    'notification_payment_body' => 'Wir haben Ihre Zahlung für den "{plan}" erhalten. Transaktions-ID: {trx}',
    'notification_profile_title' => 'Unvollständiges Profil',
    'notification_profile_body' => 'Bitte vervollständigen Sie Ihre Profilinformationen, um Zertifikate korrekt zu erstellen.',
    'notification_missed_title' => 'Prüfung verpasst',
    'notification_missed_body' => 'Sie haben die geplante Zeit für "{exam}" verpasst.',

    // Success / Failure
    'payment_successful' => 'Zahlung erfolgreich!',
    'payment_pending' => 'Zahlung ausstehend',
    'exam_access_active' => 'Ihr Prüfungszugang ist jetzt aktiv.',
    'offline_processing' => 'Ihre Offline-Zahlung wird derzeit geprüft.',
    'purchased_exams' => 'Gekaufte Prüfung(en)',
    'access_activated' => 'Zugang aktiviert',
    'access_pending' => 'Zugang ausstehend',
    'order_id' => 'Bestell-ID',
    'amount_paid' => 'Gezahlter Betrag',
    'go_to_dashboard' => 'Zum Dashboard',
    'home' => 'Startseite',
    'not_ready_note' => 'Noch nicht bereit zu starten? Diese Prüfung wurde in Ihrem Konto gespeichert.',
    'buy_again_btn' => 'Erneut Kaufen',
    'retake_exam_btn' => 'Wiederholen',
    'start_exam_btn' => 'Prüfung Starten',
    'buy_exam_btn' => 'Jetzt Kaufen',
    'start_free_btn' => 'Kostenlos Starten',
    'result_published' => 'Ergebnis veröffentlicht',
    'result_pending' => 'Ergebnis ausstehend',
    'results_locked' => 'Ergebnisse gesperrt',
    'score_label_card' => 'Punktzahl',
    'progress_label' => 'Fortschritt',
    'upcoming_exam_title' => 'Prüfung steht bevor',
    'upcoming_exam_msg' => 'Diese Prüfung beginnt am',
    'upcoming_exam_wait' => 'Bitte warten Sie bis zur Startzeit, um teilzunehmen.',
    'visit_website' => 'Webseite besuchen',
    'no_exams_match' => 'Keine Prüfungen entsprechen Ihren Kriterien.',
    'no_exams_suggestion' => 'Versuchen Sie, Ihre Suchbegriffe oder Filter anzupassen, um das Gesuchte zu finden.',
    'clear_all_filters' => 'Alle Filter löschen',

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */
    'my_certificates' => 'Meine Zertifikate',
    'certificates_subtitle' => 'Verdiente Zertifikate nach erfolgreichem Abschluss von Prüfungen.',
    'earned_section' => 'Erworbene Zertifikate',
    'cert_achievement' => 'Leistungszertifikat',
    'issued_on' => 'Ausgestellt am:',
    'download_pdf' => 'PDF herunterladen',
    
    'processing_section' => 'In Bearbeitung',
    'passed_on' => 'Bestanden am',
    'waiting_admin' => 'Wartet auf Ausstellung durch Admin.',
    
    'locked_section' => 'Gesperrt',
    'not_earned' => 'Nicht erreicht',
    'highest_score' => 'Höchste Punktzahl:',
    'required_score' => 'Erforderlich:',
    
    'no_certs_title' => 'Noch keine Zertifikate',
    'no_certs_desc' => 'Absolviere Prüfungen mit ausreichender Punktzahl, um offizielle Zertifikate zu erhalten.',

    /* Notifications */
    'notifications_title' => 'Benachrichtigungen',
    'notifications_subtitle' => 'Bleiben Sie über Ihre neuesten Aktivitäten auf dem Laufenden.',
    'mark_all_read' => 'Alle als gelesen markieren',
    'view_details' => 'Details ansehen',
    'remove_notification' => 'Benachrichtigung entfernen',
    'no_notifications' => 'Keine Benachrichtigungen',
    'no_notifications_desc' => 'Alles erledigt! Keine neuen Benachrichtigungen.',

    /* Profile */
    'profile_title' => 'Mein Profil',
    'profile_subtitle' => 'Aktualisieren Sie Ihre persönlichen Daten und ändern Sie Ihr Passwort.',
    'general_info' => 'Allgemeine Informationen',
    'change_avatar' => 'Avatar ändern',
    'avatar_help' => 'Max. Größe 2MB (JPG/PNG)',
    'file_selected' => 'Datei ausgewählt',
    'full_name' => 'Vollständiger Name',
    'email_address' => 'E-Mail-Adresse',
    'save_general' => 'Infos speichern',
    
    'change_password' => 'Passwort ändern',
    'current_password' => 'Aktuelles Passwort',
    'new_password' => 'Neues Passwort',
    'confirm_password' => 'Neues Passwort bestätigen',
    'update_password' => 'Passwort aktualisieren',

    /* Transactions */
    'transactions_title' => 'Transaktionsverlauf',
    'transactions_subtitle' => 'Überprüfen Sie alle Ihre Zahlungen und Abonnements.',
    'filter_btn' => 'Filtern',
    'reset_btn' => 'Zurücksetzen',
    'txn_id' => 'Transaktions-ID',
    'plan_item' => 'Plan/Artikel',
    'amount' => 'Betrag',
    'gateway' => 'Gateway',
    'status' => 'Status',
    'date' => 'Datum',
    'standalone_purchase' => 'Einzelkauf',
    'days_subscription' => 'Tage Abonnement',
    
    'no_txn_found' => 'Keine Transaktionen gefunden',
    'no_txn_desc' => 'Ihre Filter ergaben keine Zahlungen, die den Kriterien entsprechen.',
    'browse_plans' => 'Prüfungen & Pläne durchsuchen',

    /* Settings */
    'settings_title' => 'Anwendungseinstellungen',
    'settings_subtitle' => 'Konfigurieren Sie Ihre Einstellungen, Benachrichtigungen und Sicherheitsoptionen.',
    'notification_prefs' => 'Benachrichtigungseinstellungen',
    'email_notify' => 'E-Mail-Benachrichtigungen',
    'email_notify_desc' => 'Erhalten Sie Updates zu neuen Ergebnissen und Prüfungseinladungen.',
    'in_app_alerts' => 'In-App-Warnungen',
    'in_app_alerts_desc' => 'Zeige zeitnahe Benachrichtigungen im Dashboard an.',
    'regional_settings' => 'Regional- & Zeiteinstellungen',
    'timezone' => 'Zeitzone',
    'language' => 'Sprache',
    'save_settings' => 'Einstellungen speichern',

    /* Support Tickets */
    'tickets_title' => 'Support-Tickets',
    'tickets_subtitle' => 'Verwalten Sie Ihre Support-Anfragen und Korrespondenz.',
    'create_ticket' => 'Neues Ticket erstellen',
    'my_active_tickets' => 'Meine aktiven Tickets',
    'filter_by' => 'Filtern nach:',
    'status_all' => 'Alle',
    'status_open' => 'Offen',
    'status_replied' => 'Beantwortet',
    'status_closed' => 'Geschlossen',
    
    'th_ticket_id' => 'TICKET-ID',
    'th_subject' => 'BETREFF',
    'th_priority' => 'PRIORITÄT',
    'th_status' => 'STATUS',
    'th_last_updated' => 'ZULETZT AKTUALISIERT',
    'th_action' => 'AKTION',
    'no_tickets' => 'Keine Tickets gefunden.',
    
    'back_btn' => 'Zurück',
    'priority_suffix' => 'Priorität',
    'created_prefix' => 'Erstellt',
    'me_label' => 'Ich',
    'admin_label' => 'Admin',
    'support_agent' => 'Support-Mitarbeiter',
    'attachments_label' => 'Anhänge:',
    'view_file' => 'Datei ansehen',
    'no_messages' => 'Keine Nachrichten in diesem Ticket gefunden.',
    'reply_label' => 'Antworten',
    'reply_placeholder' => 'Geben Sie hier Ihre Nachricht ein...',
    'attachments_optional' => 'Anhänge (Optional)',
    'send_reply' => 'Antwort senden',
    'close_ticket' => 'Ticket schließen',
    'close_confirm' => 'Sind Sie sicher, dass Sie dieses Ticket schließen möchten? Sie können nicht mehr antworten.',
    'ticket_closed_msg' => 'Dieses Ticket ist geschlossen. Wenn Sie weitere Hilfe benötigen, bitte',
    'open_new_link' => 'ein neues Ticket eröffnen',
    
    'modal_title' => 'Neues Support-Ticket einreichen',
    'subject_label' => 'Betreff',
    'subject_place' => 'Kurze Zusammenfassung des Problems',
    'category_label' => 'Kategorie',
    'select_cat' => 'Kategorie wählen...',
    'cat_billing' => 'Abrechnung / Zahlung',
    'cat_tech' => 'Technisches Problem',
    'cat_content' => 'Kursinhalt',
    'cat_general' => 'Allgemeine Anfrage',
    'cat_feature' => 'Funktionswunsch',
    'priority_label' => 'Priorität',
    'p_low' => 'Niedrig',
    'p_medium' => 'Mittel',
    'p_high' => 'Hoch',
    'desc_label' => 'Beschreibung',
    'desc_place' => 'Bitte geben Sie detaillierte Schritte oder Kontext an...',
    'supported_formats' => 'Unterstützte Formate: JPG, PNG, PDF, DOCX',
    'support_notice' => 'Unser Support-Team bemüht sich, auf dringende Tickets innerhalb von 24 Stunden zu antworten.',
    'cancel_btn' => 'Abbrechen',
    'submit_btn' => 'Ticket einreichen',

    /* Exam Results */
    'results_title' => 'Prüfungsergebnisse',
    'results_subtitle' => 'Detaillierte Leistungsberichte für Ihre abgeschlossenen Prüfungen.',
    'no_results_title' => 'Noch keine Ergebnisse',
    'no_results_desc' => 'Sie haben noch keine Prüfungen abgeschlossen. Sobald Sie eine Bewertung beenden, erscheint hier Ihr Bericht.',
    'browse_exams_btn' => 'Prüfungen durchsuchen',
    
    'status_passed' => 'Bestanden',
    'status_failed' => 'Nicht bestanden',
    'status_pending' => 'Wartet auf Ergebnis',
    'result_available' => 'Ergebnis verfügbar:',
    'completed_on' => 'Abgeschlossen am:',
    'your_score' => 'Ihre Punktzahl:',
    'passing_mark' => 'Bestehensgrenze:',
    'view_full_report' => 'Vollständigen Bericht ansehen',
    'view_status' => 'Status ansehen',

    'exam_completed_title' => 'Prüfung abgeschlossen!',
    'exam_completed_msg' => 'Danke, dass Sie :title abgeschlossen haben. Ihre Antworten wurden erfolgreich gespeichert.',
    'expected_date_label' => 'Erwartetes Ergebnisdatum',
    'publish_time_msg' => 'Ergebnisse werden veröffentlicht gegen',
    'tba_title' => 'Wird noch bekannt gegeben',
    'tba_msg' => 'Der Dozent hat noch kein Veröffentlichungsdatum festgelegt.',
    'back_to_exams' => 'Zurück zu meinen Prüfungen',

    'report_prefix' => 'Bericht:',
    'report_subtitle' => 'Detaillierte Analyse und Fragenaufschlüsselung Ihres Versuchs.',
    'back_to_results' => 'Zurück zu den Ergebnissen',
    'download_pdf_alert' => 'PDF-Download folgt bald!',
    'overall_score' => 'Gesamtpunktzahl',
    'metric_correct' => 'Richtige Antworten',
    'metric_time' => 'Benötigte Zeit (Min)',
    'metric_total_marks' => 'Gesamtpunkte',
    'metric_pass_percentage' => 'Erforderliche %',
    
    'analysis_title' => 'Fragenanalyse',
    'analysis_subtitle' => 'Überprüfen Sie Ihre Antwort im Vergleich zur richtigen Lösung.',
    'review_answer_btn' => 'Antwort überprüfen',
    'label_your_answer' => 'Ihre Antwort:',
    'label_skipped' => 'Übersprungen',
    'label_correct_answer' => 'Richtige Antwort:',
    'label_explanation' => 'Erklärung:',

    /* Exam Cards & Instructions */
    'no_records_found' => 'Keine Datensätze gefunden.',
    'starts' => 'Beginnt',
    'soon' => 'Bald',
    'view_report' => 'Bericht ansehen',
    'questions_count' => 'Fragen',
    'progress_label' => 'Fortschritt',
    'score_label_card' => 'Punktzahl',
    
    'instructions_header' => 'Prüfungsanweisungen',
    'instructions_subtitle' => 'Bitte lesen Sie diese Richtlinien sorgfältig durch, bevor Sie Ihre Herausforderung :title beginnen.',
    'instruction_1_title' => '1. Beantworten & Speichern',
    'instruction_1_text' => 'Jede Frage hat nur eine richtige Antwort, sofern nicht anders angegeben. Ihre Auswahl wird automatisch gespeichert.',
    'instruction_2_title' => '2. Navigation & Überprüfung',
    'instruction_2_text' => 'Sie können sich frei zwischen allen Fragen bewegen. Nutzen Sie die Markierung zur Überprüfung, um Fragen später erneut anzusehen.',
    'instruction_3_title' => '3. Zeitlimit & Einreichung',
    'instruction_3_text' => 'Die Gesamtdauer beträgt :minutes Minuten. Die Prüfung wird automatisch eingereicht, wenn die Zeit abläuft.',
    'instruction_4_title' => '4. Technische Sicherheit',
    'instruction_4_text' => 'Stellen Sie eine stabile Internetverbindung sicher. Aktualisieren Sie die Seite nicht und schließen Sie das Fenster nicht.',
    
    'agree_terms' => 'Ich habe alle oben genannten Anweisungen gelesen und verstanden.',
    'back_to_exams' => 'Zurück zu meinen Prüfungen',
    'start_exam_btn' => 'Prüfung starten',

    /* My Exams Page */
    'my_exams_title' => 'Meine Prüfungen',
    'my_exams_subtitle' => 'Verwalten Sie Ihre gekauften Bewertungen und verfolgen Sie Ihren Fortschritt.',
    'tab_available' => 'Verfügbar',
    'tab_ongoing' => 'Laufend',
    'tab_completed' => 'Abgeschlossen',
    'tab_upcoming' => 'Bevorstehend',
    'no_exams_ready' => 'Sie haben noch keine startbereiten Prüfungen.',
    'browse_exams_btn' => 'Prüfungen durchsuchen',
    'no_exams_progress' => 'Derzeit keine Prüfungen im Gange.',
    'no_exams_completed' => 'Noch keine Prüfungen abgeschlossen.',
    'no_exams_scheduled' => 'Keine bevorstehenden Prüfungen geplant.',

    /* Participation Screen */
    'end_exam' => 'Prüfung beenden',
    'question_label' => 'Frage',
    'of_label' => 'von',
    'loading' => 'Laden...',
    'previous_btn' => 'Zurück',
    'next_btn' => 'Weiter',
    'mark_review' => 'Zur Überprüfung markieren',
    'submit_finish' => 'Absenden & Beenden',
    'auto_save_msg' => 'Alle Antworten automatisch gespeichert.',
    'progress_overview' => 'Fortschrittsübersicht',
    'stat_answered' => 'Beantwortet',
    'stat_marked' => 'Markiert',
    'stat_remaining' => 'Verbleibend',
    'question_navigator' => 'Fragen-Navigator',
    'confirm_submission' => 'Einreichung bestätigen',

    /*
    |--------------------------------------------------------------------------
    | Exam List Page (German)
    |--------------------------------------------------------------------------
    */
    'explore_exams_title' => 'Alle Prüfungen entdecken',
    'explore_exams_desc' => 'Durchsuchen Sie unseren kompletten Katalog an Prüfungen, Übungstests und Zertifizierungen.',
    'filters_title' => 'Filter',
    'reset_btn' => 'Zurücksetzen',
    'search_placeholder' => 'Prüfungen suchen...',
    'categories_title' => 'Kategorien',
    'price_title' => 'Preis',
    'all_prices' => 'Alle Preise',
    'free_only' => 'Nur Kostenlos',
    'paid_only' => 'Nur Kostenpflichtig',
    'apply_filters_btn' => 'Filter anwenden',
    
    'showing_results' => 'Zeige :first bis :last von :total Prüfungen',
    'showing_results_footer' => 'Zeige :first bis :last von :total Ergebnissen',
    
    'sort_newest' => 'Sortierung: Neueste',
    'sort_price_low' => 'Preis: Niedrig bis Hoch',
    'sort_price_high' => 'Preis: Hoch bis Niedrig',
    
    'free_badge' => 'GRATIS',
    'qns_short' => 'Frg',
    'buy_exam_btn' => 'Prüfung kaufen',
    'start_free_btn' => 'Kostenlos starten',
    'no_exams_match' => 'Keine Prüfungen entsprechen Ihren Kriterien.',

    /*
    |--------------------------------------------------------------------------
    | Dynamic Pages (German)
    |--------------------------------------------------------------------------
    */
    'page_not_found' => 'Seite nicht gefunden',
    'back_home' => 'Zurück zur Startseite',

    /* Checkout & Payment */
    'checkout_title' => 'Sichere Kasse',
    'step_cart' => 'Warenkorb',
    'step_details' => 'Details',
    'step_payment' => 'Zahlung',
    
    // Cart Page
    'review_selection' => 'Auswahl überprüfen',
    'confirm_exams' => 'Bestätigen Sie Ihre Prüfungen.',
    'remove_item' => 'Entfernen',
    'order_summary' => 'Bestellübersicht',
    'subtotal' => 'Zwischensumme',
    'taxes' => 'Steuern',
    'total_amount' => 'Gesamtsumme',
    'continue_checkout' => 'Zur Kasse gehen',
    'money_back_guarantee' => '30-Tage Geld-zurück-Garantie',
    'cart_empty' => 'Ihr Warenkorb ist leer',
    'cart_empty_desc' => 'Durchsuchen Sie unsere Prüfungen.',
    'browse_exams_btn' => 'Prüfungen durchsuchen',

    // Details Page
    'billing_details' => 'Rechnungsdetails',
    'billing_desc' => 'Für Quittung und Prüfungszugang.',
    'first_name' => 'Vorname',
    'last_name' => 'Nachname',
    'email_address' => 'E-Mail-Adresse',
    'country' => 'Land',
    'your_order' => 'Ihre Bestellung',
    'total_to_pay' => 'Zu zahlen',
    'continue_payment' => 'Weiter zur Zahlung',
    'return_cart' => 'Zurück zum Warenkorb',
    'ssl_secure' => 'SSL-gesicherte Transaktion',

    // Payment Page
    'payment_method' => 'Zahlungsmethode',
    'payment_desc' => 'Transaktionen sind verschlüsselt.',
    'credit_card' => 'Kredit- / Debitkarte',
    'pay_with_amount' => ':amount zahlen & Zugang',
    'back_details' => 'Zurück zu Details',
    'bank_security' => 'Sicherheit auf Bankniveau',

    // Success Page
    'payment_success' => 'Zahlung erfolgreich!',
    'access_active' => 'Ihr Prüfungszugang ist jetzt aktiv.',
    'purchased_exams' => 'Gekaufte Prüfung(en)',
    'access_activated' => 'Zugang aktiviert',
    'order_id' => 'Bestell-ID',
    'amount_paid' => 'Gezahlter Betrag',
    'go_dashboard' => 'Zum Dashboard',
    'home_btn' => 'Startseite',
    'save_note' => 'Nicht bereit? Diese Prüfung wurde gespeichert.',

    /* Home Page Sections */
    
    // Hero
    'hero_title_default' => 'Erstellen, Verkaufen & Verwalten<br><span class="gradient-text">Online-Prüfungen</span> mit<br><span class="ai-highlight">KI-Automatisierung</span>',
    'hero_subtitle_default' => 'ZiExam AI ist eine SaaS-Plattform, mit der Sie KI-generierte Prüfungen erstellen, Zugänge verkaufen, Abonnements verwalten und Ergebnisse verfolgen können.',
    'hero_rating_label' => 'Vertraut von 58.980+ Nutzern',
    
    // Categories
    'categories_title_default' => 'Prüfungen über mehrere Kategorien erstellen',
    'categories_subtitle_default' => 'ZiExam AI unterstützt eine breite Palette von Prüfungstypen – von akademischen Tests bis hin zu beruflichen Bewertungen.',
    'categories_bottom_text_default' => 'Alle Kategorien sind über das Admin-Panel vollständig anpassbar.',
    'category_exams_count' => ':count Prüfungen',
    'no_categories_found' => 'Keine Kategorien gefunden. Bitte im Admin-Panel hinzufügen.',
    
    // Audience
    'audience_title_default' => 'Für Institutionen, Pädagogen & SaaS-Unternehmer',
    'audience_subtitle_default' => 'ZiExam AI ist skalierbar – egal ob Sie ein Institut leiten, Kurse verkaufen oder Ihr eigenes Prüfungs-SaaS-Geschäft starten.',
    'audience_bottom_text_default' => 'Unsicher, welches Modell passt? ZiExam AI unterstützt alle wichtigen Geschäftsmodelle.',

    // Features
    'features_title_default' => 'Alles, was Sie zum Starten & Skalieren benötigen',
    'features_subtitle_default' => 'Eine komplette Suite KI-gestützter Tools zum Erstellen von Prüfungen, zur automatisierten Bewertung und zur Monetarisierung.',
    
    'feat_p1_title' => 'KI-Prüfungserstellung & Kontrolle',
    'feat_p1_desc' => 'Erstellen Sie professionelle Prüfungen in Minuten mit KI-unterstützten Tools.',
    'feat_p1_hint' => 'KI-gestützte Generierung',
    
    'feat_p2_title' => 'Automatisierte Bewertung & Einblicke',
    'feat_p2_desc' => 'Bewerten Sie Leistungen sofort und gewinnen Sie tiefe Einblicke ohne manuellen Aufwand.',
    'feat_p2_hint' => 'Echtzeit-Analytik',
    
    'feat_p3_title' => 'Monetarisierung & Zugang',
    'feat_p3_desc' => 'Verwandeln Sie Ihre Prüfungen in ein nachhaltiges Geschäft mit integrierten Zahlungen.',
    'feat_p3_hint' => 'Sichere Zahlungen',
    
    'feat_p4_title' => 'Verwaltung & Sicherheit',
    'feat_p4_desc' => 'Enterprise-Tools zur sicheren Verwaltung von Benutzern und Daten.',
    'feat_p4_hint' => 'Admin-Kontrollen',

    // How It Works
    'how_it_works_title_default' => 'So funktioniert es',
    'how_it_works_subtitle_default' => 'Starten Sie Ihr Prüfungsgeschäft in 4 einfachen Schritten.',
    
    'hiw_s1_title' => 'Installieren & Konfigurieren',
    'hiw_s1_desc' => 'In wenigen Minuten auf Ihrem Server eingerichtet.',
    
    'hiw_s2_title' => 'Prüfungen mit KI erstellen',
    'hiw_s2_desc' => 'Nutzen Sie KI, um Fragen und Strukturen sofort zu generieren.',
    
    'hiw_s3_title' => 'Preise & Pläne festlegen',
    'hiw_s3_desc' => 'Definieren Sie Abonnements oder Einmalzahlungen.',
    
    'hiw_s4_title' => 'Verfolgen & Skalieren',
    'hiw_s4_desc' => 'Überwachen Sie Schülerleistungen und Umsatzwachstum.',

    /* Pricing Section */
    'pricing_title_default' => 'Einfache Preise. Lebenslanges Eigentum.',
    'pricing_subtitle_default' => 'Wählen Sie die Lizenz, die zu Ihrem Geschäftsmodell passt. Einmaliger Kauf. Keine monatlichen Gebühren.',
    'most_popular' => 'BELIEBTESTE',
    'per_month' => '/ Monat',
    'choose_plan' => 'Wähle :plan',
    'exams_limit_count' => ':count Prüfungen',
    'exams_unlimited' => 'Unbegrenzte Prüfungen',
    'pricing_trust_1' => 'Sichere Zahlungen',
    'pricing_trust_2' => 'Jederzeit kündbar',
    'pricing_trust_3' => 'Keine versteckten Gebühren',
    'pricing_trust_4' => 'Geprüfte Qualität',
    'no_pricing_plans' => 'Keine Preispläne im Admin-Panel definiert.',

    /* Testimonials Section */
    'testimonials_title_default' => 'Vertraut von Pädagogen, Teams & Entwicklern weltweit',
    'testimonials_subtitle_default' => 'Von einzelnen Dozenten bis hin zu schnell wachsenden Institutionen verlassen sich Teams auf unsere Plattform.',

    /* Featured Exams Section */
    'exams_title_default' => 'Prüfungen verkaufen & wiederkehrende Einnahmen aufbauen',
    'exams_subtitle_default' => 'Monetarisieren Sie einzelne Prüfungen oder bündeln Sie sie in Abonnements – vollständig verwaltet über Ihr Admin-Dashboard.',
    'buy_exam_btn' => 'Prüfung kaufen',
    'start_free_btn' => 'Kostenlos starten',
    'no_active_exams' => 'Keine aktiven Prüfungen gefunden. Bitte im Admin-Panel erstellen.',
    
    'sub_strip_title_default' => 'Unbegrenzten Zugang anbieten',
    'sub_strip_desc_default' => 'Bündeln Sie alle Prüfungen in monatlichen oder jährlichen Abonnementstufen.',
    'exams_bottom_text_default' => 'Alle Preise, Zugangsregeln und Verfügbarkeiten werden vollständig über das Admin-Panel gesteuert.',

    /* CMS Features Section */
    'cms_badge_default' => 'CMS ENTHALTEN',
    'cms_title_default' => 'Starten Sie Ihre Website ohne zusätzliche Tools',
    'cms_desc_default' => 'ZiExam AI enthält ein integriertes CMS, mit dem Sie dynamische Seiten erstellen, Navigationsmenüs verwalten und Homepage-Abschnitte bearbeiten können.',
    
    'cms_feat_1_title' => 'Dynamische Seiten',
    'cms_feat_1_desc' => 'Erstellen Sie unbegrenzte Seiten mit einem visuellen abschnittsbasierten System.',
    
    'cms_feat_2_title' => 'Menü-Builder',
    'cms_feat_2_desc' => 'Erstellen und verwalten Sie Navigationsmenüs direkt im Admin-Bereich.',
    
    'cms_feat_3_title' => 'SEO-Bereit',
    'cms_feat_3_desc' => 'Steuern Sie Meta-Titel, Beschreibungen und Slugs für ein besseres Ranking.',
    
    'cms_feat_4_title' => 'Homepage-Abschnitte',
    'cms_feat_4_desc' => 'Bearbeiten Sie Helden-, Feature-, Preis- und CTA-Blöcke ganz einfach.',

    /* Admin Preview Section */
    'admin_preview_title_default' => 'Alles von einem leistungsstarken Dashboard aus steuern',
    'admin_preview_subtitle_default' => 'Ein zentrales Admin-Panel zur Verwaltung von Benutzern, Prüfungen, Abonnements, Einnahmen und KI-Nutzung.',
    
    'admin_stat_1_val' => '10.000+',
    'admin_stat_1_lbl' => 'Unterstützte Benutzer',
    
    'admin_stat_2_val' => '100%',
    'admin_stat_2_lbl' => 'Rollenbasierter Zugriff',
    
    'admin_stat_3_val' => 'Echtzeit',
    'admin_stat_3_lbl' => 'Umsatzverfolgung',
    
    'admin_stat_4_val' => 'KI-Kosten',
    'admin_stat_4_lbl' => 'Nutzungs- & Kostenkontrolle',
    
    'admin_feat_1_title' => 'Benutzer- & Rollenkontrolle',
    'admin_feat_1_desc' => 'Verwalten Sie Admins, Dozenten und Studenten mit feinkörnigen Berechtigungen.',
    
    'admin_feat_2_title' => 'Umsatz & Abonnements',
    'admin_feat_2_desc' => 'Verfolgen Sie Zahlungen, Pläne, Verlängerungen und Wachstum in Echtzeit.',
    
    'admin_feat_3_title' => 'KI-Nutzung & Limits',
    'admin_feat_3_desc' => 'Überwachen Sie den KI-Verbrauch, legen Sie Limits fest und kontrollieren Sie die Betriebskosten.',
    
    'admin_feat_4_title' => 'Systemkonfiguration',
    'admin_feat_4_desc' => 'Konfigurieren Sie Zahlungen, E-Mails, Sicherheit und Plattformverhalten zentral.',
    
    'admin_check_1' => 'Keine Programmierung erforderlich',
    'admin_check_2' => 'Unternehmensgerechte Architektur',
    'admin_check_3' => 'Gebaut auf Laravel 10',

    /* CTA Section */
    'cta_title_default' => 'Starten Sie Ihr Online-Prüfungs<br>Geschäft noch heute',
    'cta_subtitle_default' => 'Holen Sie sich das fortschrittlichste KI-gestützte Prüfungsskript auf dem Markt.',
    'cta_btn_primary' => 'Loslegen',
    'cta_btn_secondary' => 'Demo ansehen',

    /* Footer Section */
    'footer_about_text_default' => 'ZiExam AI ist eine KI-gestützte Online-Prüfungsplattform, mit der Sie Prüfungen mühelos und sicher erstellen und durchführen können.',
    'useful_links' => 'Nützliche Links',
    'legal' => 'Rechtliches',
    'contact_info' => 'Kontaktinfo',
    'copyright_text' => 'Urheberrecht © :year ZiExam AI. Alle Rechte vorbehalten.',
    
    'home_link' => 'Startseite',
    'features_link' => 'Funktionen',
    'pricing_link' => 'Preise',
    'faq_link' => 'FAQ',
    'privacy_policy' => 'Datenschutzrichtlinie',
    'terms_service' => 'Nutzungsbedingungen',
    'security_policy' => 'Sicherheitsrichtlinie',
    'refund_policy' => 'Rückerstattungsrichtlinie',

    /* Header & Navigation */
    'dashboard_btn' => 'Dashboard',
    'start_free_btn_header' => 'Kostenlos starten',
    'select_language' => 'Sprache wählen',
    'select_language_caps' => 'SPRACHE WÄHLEN',

    /* Pagination */
    'previous' => 'Zurück',
    'next' => 'Weiter',

    /* User Dashboard Sidebar & Topbar */
    'sidebar_main_menu' => 'Hauptmenü',
    'sidebar_dashboard' => 'Dashboard',
    'sidebar_my_exams' => 'Meine Prüfungen',
    'sidebar_results' => 'Ergebnisse',
    'sidebar_certificates' => 'Zertifikate',
    'sidebar_account' => 'Konto',
    'sidebar_profile' => 'Profil',
    'sidebar_transactions' => 'Transaktionsverlauf',
    'sidebar_settings' => 'Einstellungen',
    'sidebar_support' => 'Support',
    'sidebar_tickets' => 'Support-Tickets',
    'sidebar_logout' => 'Abmelden',

    'topbar_search_placeholder' => 'Prüfungen, Ergebnisse suchen...',
    'topbar_go_website' => 'Zur Website',
    'topbar_select_language' => 'Sprache wählen',
    'topbar_select_language_caps' => 'SPRACHE WÄHLEN',
    'topbar_notifications' => 'Benachrichtigungen',
    'topbar_mark_read' => 'Alle als gelesen markieren',
    'topbar_no_notifications' => 'Keine neuen Benachrichtigungen',
    'topbar_view_activity' => 'Alle Aktivitäten anzeigen',
    'topbar_default_student' => 'Student',
    'topbar_my_profile' => 'Mein Profil',
    'topbar_menu_settings' => 'Einstellungen',
    'topbar_logout' => 'Abmelden',
];