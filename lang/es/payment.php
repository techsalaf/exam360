<?php

return [
    'save_settings' => 'Guardar Ajustes',
    'save_gateway' => 'Guardar Pasarelas',
    'enable' => 'Habilitar',
    // Currency Settings - NEW KEYS
'currency_title' => 'Configuración de Moneda Global',
'currency_desc' => 'Configure su moneda principal, formato de visualización y separadores de transacciones.',
'currency_global_currency' => 'Moneda Global',
'currency_global_desc' => 'Establecer la moneda principal para todas las transacciones.',
'currency_primary' => 'MONEDA PRINCIPAL',
'currency_position' => 'POSICIÓN DEL SÍMBOLO',
'currency_pos_before' => 'Antes de la Cantidad', // Ejemplo: $100
'currency_pos_after' => 'Después de la Cantidad', // Ejemplo: 100$
'currency_pos_before_space' => 'Antes de la Cantidad (con Espacio)', // Ejemplo: $ 100
'currency_pos_after_space' => 'Después de la Cantidad (con Espacio)', // Ejemplo: 100 $
'currency_custom_opt' => 'Moneda Personalizada',
'currency_decimal_sep' => 'SEPARADOR DECIMAL',
'currency_thousands_sep' => 'SEPARADOR DE MILES',
'currency_decimal_help' => 'Carácter utilizado para decimales (p. ej. 10.00).',
'currency_thousands_help' => 'Carácter utilizado para miles (p. ej. 1,000).',
'save_settings' => 'Guardar Configuración',

    'gateways' => [
        'stripe' => [
            'title' => 'Configuración de Stripe',
            'desc' => 'Acepte pagos con tarjeta de crédito vía Stripe.',
            'public_key' => 'Clave Pública de Stripe',
            'secret_key' => 'Clave Secreta de Stripe',
            'webhook_secret' => 'Secreto Webhook de Stripe',
            'webhook_url' => 'URL Webhook:',
        ],
        'paypal' => [
            'title' => 'Configuración de PayPal',
            'desc' => 'Acepte pagos vía PayPal o Crédito PayPal.',
            'client_id' => 'ID de Cliente PayPal',
            'secret_key' => 'Clave Secreta PayPal',
            'env' => 'Entorno',
            'sandbox' => 'Sandbox (Pruebas)',
            'live' => 'En Vivo (Producción)',
        ],
        'razorpay' => [
            'title' => 'Configuración de Razorpay',
            'desc' => 'Pasarela de pago popular para India.',
            'key_id' => 'ID de Clave Razorpay',
            'key_secret' => 'Secreto de Clave Razorpay',
        ],
        'offline' => [
            'title' => 'Transferencia Offline/Bancaria',
            'desc' => 'Permitir a usuarios pagar directamente (requiere confirmación manual).',
            'holder_name' => 'Nombre del Titular',
            'bank_name' => 'Nombre del Banco',
            'acc_number' => 'Número de Cuenta / IBAN',
            'swift_code' => 'Código IFSC / SWIFT',
            'instructions' => 'Instrucciones Adicionales',
            'instructions_help' => 'Use esta área para métodos de pago secundarios o instrucciones regionales.',
            'default_inst' => 'Ejemplo: Para pagos locales use Banco A. Para internacionales, ver detalles arriba.',
        ],
    ],

    'tax' => [
        'title' => 'Configuración de Impuestos',
        'desc' => 'Configure reglas de IVA o Impuesto de Ventas.',
        'global_rules' => 'Reglas Globales de Impuestos',
        'global_rules_desc' => 'Defina cómo se calculan los impuestos en el pago.',
        'name' => 'Nombre del Impuesto',
        'name_help' => 'Etiqueta mostrada en facturas.',
        'rate' => 'Tasa de Impuesto Predeterminada',
        'rate_help' => 'Porcentaje añadido al subtotal.',
        'inclusive' => 'Precios con Impuestos Incluidos',
        'inclusive_help' => 'Si se habilita, los precios de productos incluirán el monto del impuesto.',
    ],
];