<?php

return [
    'save' => 'Guardar Cambios',
    'recommended' => 'Recomendado:',
    'or' => 'o',
    'copy_url' => 'Copiar URL',
    
    'ai' => [
        'title' => 'Configuración de IA',
        'desc' => 'Conecte proveedores LLM para generación automática de contenido y calificación.',
        'primary_driver' => 'Motor de IA Principal',
        'driver_desc' => 'Seleccione el motor utilizado para tareas de generación.',
        'disabled' => 'Deshabilitado',
        'driver_gemini' => 'Google Gemini (Recomendado)',
        'driver_openai' => 'OpenAI (ChatGPT)',
        
        'gemini' => [
            'title' => 'Ajustes de Gemini',
            'desc' => 'Configure el acceso a los modelos generativos de Google.',
            'api_key' => 'Clave API Gemini',
            'model' => 'Versión del Modelo',
        ],
        
        'openai' => [
            'title' => 'Ajustes de OpenAI',
            'desc' => 'Configure el acceso a los modelos ChatGPT.',
            'api_key' => 'Clave API OpenAI',
            'model' => 'Versión del Modelo',
        ],
    ],

    'cron' => [
        'title' => 'Programador de Tareas (Cron)',
        'desc' => 'Gestione tareas programadas en segundo plano para notificaciones y mantenimiento.',
        'info_title' => '¿Por qué es necesario?',
        'info_desc' => 'Los trabajos cron manejan tareas recurrentes críticas como el procesamiento de resultados de exámenes, renovaciones de suscripciones y notificaciones por correo.',
        'server_cmd' => 'Comando del Servidor',
        'server_cmd_desc' => 'Agregue esta entrada al crontab de su servidor (ej. cPanel).',
        'entry_label' => 'Entrada Cron (Ejecutar cada minuto)',
        'token_label' => 'Token de Seguridad',
        'token_ph' => 'Token de seguridad único',
        'token_help' => 'Protege la URL para servicios cron externos.',
        'enable_label' => 'Habilitar Programador',
        'enable_desc' => 'Procesar tareas en segundo plano.',
        'copied' => 'Comando copiado al portapapeles',
        'copy_fail' => 'Error al copiar comando',
    ],

    'ext' => [
        'title' => 'Configuraciones de Extensiones',
        'desc' => 'Gestione integraciones de terceros, widgets y herramientas de seguridad.',
        
        'google' => [
            'title' => 'Inicio de Sesión con Google',
            'desc' => 'Permitir a los usuarios iniciar sesión con Google.',
            'client_id' => 'ID de Cliente',
            'client_secret' => 'Secreto de Cliente',
            'callback' => 'URL de Callback',
            'help' => 'Pegue esta URL en la configuración de Google Developer Console.',
        ],

        'facebook' => [
            'title' => 'Inicio de Sesión con Facebook',
            'desc' => 'Permitir a los usuarios iniciar sesión con Facebook.',
            'app_id' => 'ID de App',
            'app_secret' => 'Secreto de App',
            'callback' => 'URL de Callback',
            'help' => 'Pegue esta URL en la configuración de desarrolladores de Facebook.',
        ],

        'captcha' => [
            'title' => 'Captcha Personalizado',
            'desc' => 'Verificación interna.',
            'length' => 'Longitud del Código',
            'chars' => 'Caracteres',
        ],

        'recaptcha' => [
            'title' => 'Google Recaptcha v2',
            'desc' => 'Protección contra spam.',
            'site_key' => 'Clave del Sitio',
            'secret_key' => 'Clave Secreta',
        ],

        'tawk' => [
            'title' => 'Chat en Vivo Tawk.to',
            'desc' => 'Soporte al cliente.',
            'link_label' => 'Enlace Directo de Chat',
            'link_help' => 'Pegue su enlace directo de chat desde el panel de Tawk.to.',
        ],
    ],
];