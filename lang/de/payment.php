<?php

return [
    'save_settings' => 'Einstellungen speichern',
    'save_gateway' => 'Gateway-Einstellungen speichern',
    'enable' => 'Aktivieren',
    'custom_code_label' => 'BENUTZERDEFINIERTER CODE (Z.B., :example)',
    'custom_symbol_label' => 'BENUTZERDEFINIERTES SYMBOL (Z.B., :example)',
    'example_code_placeholder' => 'z.B. QAR',
    'example_symbol_placeholder' => 'z.B. ₹',
    
    // Currency Settings - NEW KEYS
'currency_title' => 'Globale Währungseinstellungen',
'currency_desc' => 'Konfigurieren Sie Ihre primäre Währung, das Anzeigeformat und die Transaktionstrennzeichen.',
'currency_global_currency' => 'Globale Währung',
'currency_global_desc' => 'Legen Sie die primäre Währung für alle Transaktionen fest.',
'currency_primary' => 'PRIMÄRE WÄHRUNG',
'currency_position' => 'SYMBOLPOSITION',
'currency_pos_before' => 'Vor dem Betrag', // Beispiel: $100
'currency_pos_after' => 'Nach dem Betrag', // Beispiel: 100$
'currency_pos_before_space' => 'Vor dem Betrag (mit Leerzeichen)', // Beispiel: $ 100
'currency_pos_after_space' => 'Nach dem Betrag (mit Leerzeichen)', // Beispiel: 100 $
'currency_custom_opt' => 'Benutzerdefinierte Währung',
'currency_decimal_sep' => 'DEZIMALTRENNZEICHEN',
'currency_thousands_sep' => 'TAUSENDERTRENNZEICHEN',
'currency_decimal_help' => 'Zeichen für Dezimalstellen (z.B. 10.00).',
'currency_thousands_help' => 'Zeichen für Tausender (z.B. 1.000).',
'save_settings' => 'Einstellungen speichern',

    'gateways' => [
        'stripe' => [
            'title' => 'Stripe-Konfiguration',
            'desc' => 'Akzeptieren Sie Kreditkartenzahlungen über Stripe.',
            'public_key' => 'Öffentlicher Stripe-Schlüssel',
            'secret_key' => 'Geheimer Stripe-Schlüssel',
            'webhook_secret' => 'Stripe Webhook-Geheimnis',
            'webhook_url' => 'Webhook-URL:',
        ],
        'paypal' => [
            'title' => 'PayPal-Konfiguration',
            'desc' => 'Akzeptieren Sie Zahlungen über PayPal.',
            'client_id' => 'PayPal Client-ID',
            'secret_key' => 'Geheimer PayPal-Schlüssel',
            'env' => 'Umgebung',
            'sandbox' => 'Sandbox (Testen)',
            'live' => 'Live (Produktion)',
        ],
        'razorpay' => [
            'title' => 'Razorpay-Konfiguration',
            'desc' => 'Beliebtes Zahlungsgateway für Indien.',
            'key_id' => 'Razorpay Key ID',
            'key_secret' => 'Razorpay Key Secret',
        ],
        'offline' => [
            'title' => 'Offline-/Banküberweisung',
            'desc' => 'Erlauben Sie Benutzern direkt zu bezahlen (manuelle Bestätigung erforderlich).',
            'holder_name' => 'Kontoinhaber Name',
            'bank_name' => 'Bankname',
            'acc_number' => 'Kontonummer / IBAN',
            'swift_code' => 'IFSC / SWIFT-Code',
            'instructions' => 'Zusätzliche Anweisungen',
            'instructions_help' => 'Nutzen Sie diesen Bereich für sekundäre Zahlungsmethoden.',
            'default_inst' => 'Beispiel: Für Zahlungen innerhalb der EU verwenden Sie Bank A.',
        ],
    ],

    'tax' => [
        'title' => 'Steuerkonfiguration',
        'desc' => 'Konfigurieren Sie MwSt.- oder Umsatzsteuerregeln.',
        'global_rules' => 'Globale Steuerregeln',
        'global_rules_desc' => 'Definieren Sie, wie die Steuer an der Kasse berechnet wird.',
        'name' => 'Steuername',
        'name_help' => 'Auf Rechnungen angezeigte Bezeichnung.',
        'rate' => 'Standardsteuersatz',
        'rate_help' => 'Prozentsatz, der zur Zwischensumme addiert wird.',
        'inclusive' => 'Preise inklusive Steuern',
        'inclusive_help' => 'Wenn aktiviert, enthalten die Produktpreise den Steuerbetrag.',
    ],
];