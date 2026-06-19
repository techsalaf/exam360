<?php

return [
    'mobile_menu' => 'Menú de Ajustes',
    'nav_title'   => 'Navegación',
    
    'groups' => [
        'system'        => 'Sistema',
        'appearance'    => 'Apariencia',
        'communication' => 'Comunicación',
        'billing'       => 'Facturación',
        'regional'      => 'Regional',
        'visibility'    => 'Visibilidad',
        'automation'    => 'Automatización',
        'security'      => 'Seguridad',
    ],

    'links' => [
        'general'     => ['title' => 'Ajustes Generales', 'sub' => 'Identidad y Zona Horaria'],
        'config'      => ['title' => 'Configuración Núcleo', 'sub' => 'Entorno y Límites'],
        'roles'       => ['title' => 'Roles y Permisos', 'sub' => 'Control de Acceso'],
        'maintenance' => ['title' => 'Mantenimiento', 'sub' => 'Gestión de Inactividad'],
        
        'logo'         => ['title' => 'Logo y Favicon', 'sub' => 'Activos de Marca'],
        'certificates' => ['title' => 'Certificados', 'sub' => 'Plantillas y Diseño'],
        'frontend'     => ['title' => 'Interfaz Pública', 'sub' => 'Visibilidad Pública'],
        'css'          => ['title' => 'CSS Personalizado', 'sub' => 'Anulaciones Globales'],
        
        'alerts' => ['title' => 'Alertas', 'sub' => 'Notificaciones del Sistema'],
        'email'  => ['title' => 'Configuración Email', 'sub' => 'SMTP y Controladores'],
        'social' => ['title' => 'Acceso Social', 'sub' => 'Proveedores OAuth'],
        
        'gateways' => ['title' => 'Pasarelas de Pago', 'sub' => 'Stripe, PayPal, etc.'],
        'currency' => ['title' => 'Moneda', 'sub' => 'Símbolos y Formatos'],
        'tax'      => ['title' => 'Reglas de Impuestos', 'sub' => 'IVA e Impuestos de Venta'],
        
        'language' => ['title' => 'Localización', 'sub' => 'Idioma y País'],
        
        'seo'     => ['title' => 'Configuración SEO', 'sub' => 'Metaetiquetas y Análisis'],
        'sitemap' => ['title' => 'Mapa del Sitio', 'sub' => 'Generación XML'],
        
        'ai'         => ['title' => 'Integración IA', 'sub' => 'Configuración LLM'],
        'cron'       => ['title' => 'Cron Jobs', 'sub' => 'Tareas Programadas'],
        'extensions' => ['title' => 'Extensiones', 'sub' => 'Módulos y Complementos'],
        
        'gdpr'   => ['title' => 'RGPD y Cookies', 'sub' => 'Gestión de Consentimiento'],
        'policy' => ['title' => 'Páginas Legales', 'sub' => 'Términos y Privacidad'],
    ],

    'status' => [
        'operational' => 'Sistema Operativo'
    ]
];