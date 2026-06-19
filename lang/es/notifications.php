<?php

return [
    'save' => 'Guardar Cambios',
    'configure' => 'Configurar',
    'active' => 'Activo',
    'variables' => 'Variables:',
    'test_mail' => 'Probar Correo',
    'copy_url' => 'Copiar URL',
    
    'general' => [
        'title' => 'Centro de Notificaciones',
        'subtitle' => 'Gestione disparadores, lógica y pasarelas de terceros para todos los canales.',
        'tabs' => [
            'logic' => 'Lógica y Disparadores',
            'sms' => 'Pasarela SMS',
            'push' => 'Config Push',
            'templates' => 'Plantillas',
        ],
        'kpi' => [
            'email' => 'Sistema Email',
            'sms' => 'Sistema SMS',
            'push' => 'Notif. Push',
            'global_switch' => 'Interruptor Global',
        ],
        'triggers' => [
            'title' => 'Disparadores de Eventos',
            'col_event' => 'Evento Disparador',
            'signup' => 'Nuevo Registro de Usuario',
            'signup_desc' => 'Se activa inmediatamente después del registro.',
            'exam' => 'Finalización del Examen',
            'exam_desc' => 'Procesamiento de resultados y puntuación.',
            'payment' => 'Pago Exitoso',
            'payment_desc' => 'Facturas y activación de planes.',
        ],
        'sms_gateways' => [
            'provider' => 'Proveedor SMS',
            'twilio' => 'Twilio',
            'vonage' => 'Vonage',
            'standard' => 'Estándar',
            'international' => 'Internacional',
            'api_creds' => 'Credenciales API',
            'account_sid' => 'Account SID',
            'api_key' => 'API Key',
            'auth_token' => 'Auth Token',
            'api_secret' => 'API Secret',
            'from' => 'Número de Envío',
            'from_desc' => 'Número de Envío (E.164)',
            'sender_id' => 'Sender ID (Nombre de Marca)',
            'env' => 'Entorno',
            'sandbox' => 'Activar Modo Sandbox',
        ],
        'firebase' => [
            'title' => 'Firebase Cloud Messaging (FCM)',
            'server_key' => 'Clave del Servidor (Legacy)',
            'project_id' => 'Project ID',
            'app_id' => 'App ID',
            'sender_id' => 'Sender ID',
            'bucket' => 'Storage Bucket',
        ],
        'template_links' => [
            'email' => 'Plantillas de Email',
            'sms' => 'Plantillas SMS',
            'push' => 'Plantillas Push',
        ],
    ],

    'social' => [
        'title' => 'Inicio de Sesión Social',
        'subtitle' => 'Configure proveedores OAuth para registro e inicio de sesión con un clic.',
        'google' => 'Login con Google',
        'google_desc' => 'Habilitar inicio de sesión vía cuenta Google.',
        'facebook' => 'Login con Facebook',
        'facebook_desc' => 'Habilitar inicio de sesión vía cuenta Facebook.',
        'client_id' => 'Client ID',
        'client_secret' => 'Client Secret',
        'app_id' => 'App ID',
        'app_secret' => 'App Secret',
        'callback' => 'URL de Callback',
        'google_help' => 'Pegue esta URL en la configuración de Google Developer Console.',
        'facebook_help' => 'Pegue esta URL en la configuración de desarrolladores de Facebook.',
    ],

    'email' => [
        'title' => 'Configuración de Email',
        'subtitle' => 'Configure controladores de correo saliente e identidad del remitente.',
        'driver_label' => 'Controlador de Correo',
        'drivers' => [
            'smtp' => 'SMTP',
            'smtp_desc' => 'Recomendado',
            'php' => 'PHP Mail',
            'php_desc' => 'Predeterminado del Servidor',
            'mailgun' => 'Mailgun',
            'mailgun_desc' => 'Basado en API',
        ],
        'smtp' => [
            'title' => 'Detalles de Conexión SMTP',
            'host' => 'Servidor de Correo',
            'port' => 'Puerto',
            'username' => 'Usuario',
            'password' => 'Contraseña',
            'encryption' => 'Cifrado',
            'none' => 'Ninguno',
        ],
        'mailgun' => [
            'title' => 'Configuración API Mailgun',
            'domain' => 'Dominio Mailgun',
            'secret' => 'Secreto Mailgun (API Key)',
            'endpoint' => 'Endpoint Mailgun',
            'help' => 'Use api.eu.mailgun.net para regiones de la UE.',
        ],
        'identity' => [
            'title' => 'Identidad del Remitente',
            'name' => 'Nombre del Remitente',
            'name_desc' => 'Mostrado en la bandeja de entrada del destinatario.',
            'address' => 'Correo del Remitente',
            'address_desc' => 'Debe estar autorizado por su proveedor.',
        ],
        'alerts' => [
            'confirm_test' => '¿Enviar un correo de prueba al usuario actual? Asegúrese de haber guardado los cambios primero.',
            'sending' => 'Enviando...',
            'success' => 'Éxito',
            'failed' => 'Conexión Fallida',
            'error' => 'Ocurrió un error inesperado.',
        ]
    ],

    'templates' => [
        'email_title' => 'Plantillas de Email',
        'email_subtitle' => 'Personalice el asunto y cuerpo HTML para correos del sistema.',
        'sms_title' => 'Plantillas SMS',
        'sms_subtitle' => 'Configure mensajes de texto de 160 caracteres.',
        'push_title' => 'Notificaciones Push',
        'push_subtitle' => 'Gestione el estilo y contenido de alertas móviles.',
        
        'tabs' => [
            'signup' => 'Bienvenida Registro',
            'exam' => 'Resultado Examen',
            'payment' => 'Recibo de Pago',
        ],
        
        'fields' => [
            'subject' => 'Asunto',
            'html_body' => 'Cuerpo HTML',
            'content' => 'Contenido del Mensaje',
            'alert_title' => 'Título de Alerta',
            'alert_body' => 'Cuerpo de Alerta',
        ],

        'defaults' => [
            'signup_sub' => '¡Bienvenido a nuestra plataforma!',
            'signup_body' => '<p>Hola {{name}},</p><p>¡Bienvenido a nuestra plataforma!</p>',
            'exam_sub' => 'Resultados de Examen Disponibles',
            'exam_body' => '<p>Hola {{name}},</p><p>Obtuviste <strong>{{score}}%</strong>.</p>',
            'pay_sub' => 'Recibo de Pago',
            'pay_body' => '<p>Recibimos tu pago de <strong>{{amount}}</strong>.</p>',
            
            'push_signup_t' => '¡Bienvenido!',
            'push_signup_b' => 'Gracias por unirte a nuestra plataforma.',
            'push_exam_t' => 'Resultados de Examen',
            'push_exam_b' => 'Obtuviste {{score}}% en {{exam}}.',
            'push_pay_t' => 'Pago Recibido',
            'push_pay_b' => 'Tu suscripción para {{plan}} está activa.',
            
            'sms_signup' => '¡Bienvenido a Ziexam, {{name}}! Verifica aquí: {{link}}',
            'sms_exam' => '¡Felicidades {{name}}! Pasaste {{exam}} con {{score}}%.',
            'sms_pay' => 'Pago de {{amount}} recibido por {{plan}}. ¡Gracias!',
        ],

        'preview' => [
            'label' => 'Vista Previa',
            'app_name' => 'Nombre App',
            'now' => 'ahora',
        ]
    ]
];