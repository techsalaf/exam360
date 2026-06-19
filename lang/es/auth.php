<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    */

    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña proporcionada es incorrecta.',
    'throttle' => 'Demasiados intentos de acceso. Por favor inténtelo de nuevo en :seconds segundos.',

    /*
    |--------------------------------------------------------------------------
    | Custom UI Language Lines
    |--------------------------------------------------------------------------
    */

    // General Fields
    'name' => 'Nombre Completo',
    'email_label' => 'Dirección de Correo', // FIX: Renamed from 'email'
    'password_label' => 'Contraseña', // FIX: Added for UI input label
    'confirm_password' => 'Confirmar Contraseña',
    'remember_me' => 'Recuérdame',
    'forgot_password' => '¿Olvidaste tu contraseña?',

    // Login Page
    'login' => [
        'title' => 'Iniciar Sesión',
        'tagline' => 'Plataforma de Evaluación Impulsada por IA',
        'headline' => 'Potenciando Exámenes Inteligentes con IA.',
        'brand_sub' => 'Exámenes seguros y automatizados con evaluación instantánea por IA y análisis profundo de rendimiento.',
        
        'features' => [
            'instant' => 'Evaluación Instantánea por IA',
            'security' => 'Seguridad de Grado Empresarial',
            'analytics' => 'Análisis de Rendimiento Avanzado',
        ],

        'welcome' => 'Bienvenido de Nuevo',
        'welcome_sub' => 'Inicia sesión para acceder a tus exámenes, resultados y análisis de IA.',
        
        'captcha_label' => 'Control de Seguridad',
        'captcha_placeholder' => 'Ingresa el código',
        'captcha_help' => 'Haz clic en la imagen para refrescar el código.',
        
        'submit' => 'Ingresar',
        'or_continue' => 'o continúa con',
        
        'no_account' => "¿No tienes una cuenta?",
        'create_account' => 'Crear una cuenta',
        'secure_badge' => 'Conexión segura encriptada de 256 bits',
    ],

    // Register Page
    'register' => [
        'title' => 'Crea Tu Cuenta',
        'subtitle' => 'Comienza en minutos y accede a exámenes impulsados por IA al instante.',
        'headline' => 'Únete al Futuro de la Evaluación con IA.',
        'tagline' => 'Plataforma de Evaluación Impulsada por IA',
        'brand_desc' => 'Crea, gestiona y escala tus evaluaciones en minutos con nuestro motor inteligente.',

        'features' => [
            'ai_tests' => 'Pruebas de Práctica con IA Ilimitadas',
            'global_cert' => 'Estándares de Certificación Global',
            'auto_results' => 'Generación Automatizada de Resultados',
        ],

        'password_hint' => 'Mínimo 8 caracteres con letras y números.',
        
        'captcha_label' => 'Control de Seguridad',
        'captcha_placeholder' => 'Ingresa el código',
        'captcha_help' => 'Haz clic en la imagen para refrescar el código.',

        'or_signup' => 'o regístrate con',

        'terms_text' => 'Al crear una cuenta, aceptas nuestros',
        'terms' => 'Términos de Servicio',
        'privacy' => 'Política de Privacidad',

        'already_account' => '¿Ya tienes una cuenta?',
        'signin' => 'Iniciar Sesión',
        'submit' => 'Crear Cuenta',
    ],

    // Forgot / Reset Password
    'forgot_title' => 'Contraseña Olvidada',
    'forgot_subtitle' => 'Ingresa tu correo y te enviaremos un enlace de restablecimiento',
    'send_reset_link' => 'Enviar Enlace de Restablecimiento',
    'reset_link_sent' => 'Se ha enviado un enlace de restablecimiento de contraseña a tu correo.',
    'email_not_found' => 'No pudimos encontrar un usuario con esa dirección de correo.',

    'reset_title' => 'Restablecer Contraseña',
    'reset_subtitle' => 'Crea una nueva contraseña para tu cuenta',
    'reset_button' => 'Restablecer Contraseña',
    'password_updated' => 'Tu contraseña ha sido restablecida exitosamente.',
    'token_invalid' => 'Este token de restablecimiento de contraseña es inválido o ha expirado.',

    // Email Verification
    'verify' => [
        'title' => 'Verificar Correo - :app',
        'heading' => 'Verifica Tu Correo',
        'subheading' => 'Por favor verifica tu dirección de correo para acceder a todas las funciones.',
        'check_inbox' => 'Revisa tu bandeja de entrada',
        'sent_to' => 'Hemos enviado un enlace de verificación a<br>:email',
        'resent' => 'Se ha enviado un nuevo enlace de verificación a tu dirección de correo.',
        'help' => "¿No recibiste el correo? Revisa tu carpeta de spam o reenvíalo abajo.",
        'resend' => 'Reenviar Correo de Verificación',
        'sign_out' => 'Cerrar Sesión',
        'verified_success' => 'Tu correo ha sido verificado exitosamente.',
        'already_verified' => 'Tu correo ya está verificado.',
    ],

    // Common UI Messages
    'security_check' => 'Control de Seguridad',
    'confirm_identity' => 'Confirma tu identidad',
    'continue' => 'Continuar',
    'success' => 'Éxito',
    'error' => 'Error',
    'warning' => 'Advertencia',
    'info' => 'Información',
    'something_wrong' => 'Algo salió mal. Por favor inténtalo de nuevo.',
    'session_expired' => 'Tu sesión ha expirado. Por favor inicia sesión nuevamente.',
    'account_disabled' => 'Tu cuenta ha sido deshabilitada. Por favor contacta a soporte.',

    'reset' => [
        'title' => 'Establecer Nueva Contraseña',
        'subtitle' => 'Ingresa y confirma tu nueva contraseña segura abajo.',
        'headline' => 'Completa Tu Control de Seguridad.',
        'brand_sub' => 'Elige una nueva contraseña segura para asegurar que tu cuenta permanezca protegida.',
        'features' => [
            'secure_token' => 'Verificación de Token Seguro',
            'strong_pw' => 'Cumplimiento de Contraseña Segura',
        ],
        'email_label' => 'Dirección de Correo',
        'password_label' => 'Nueva Contraseña',
        'confirm_label' => 'Confirmar Nueva Contraseña',
        'submit' => 'Restablecer Contraseña',
        'secure_badge' => 'Protegido por estándares de la industria',
    ],

    'email' => [
        'title' => 'Contraseña Olvidada',
        'subtitle' => 'Restablece tu contraseña de forma segura y vuelve a tus exámenes rápidamente.',
        'headline' => '¿Necesitas Acceso? Te Cubrimos.',
        'page_title' => '¿Problemas para Iniciar Sesión?',
        'page_sub' => 'Ingresa tu dirección de correo para recibir un enlace seguro de restablecimiento.',
        'features' => [
            'secure_process' => 'Proceso de Restablecimiento Seguro',
            'instant_delivery' => 'Entrega de Correo Instantánea',
        ],
        'submit' => 'Enviar Enlace',
        'return' => 'Volver a Iniciar Sesión',
        'secure_badge' => 'Solicitud segura protegida por SSL',
    ],

    'sent' => [
        'title' => 'Revisa Tu Bandeja de Entrada',
        'subtitle' => 'Hemos enviado un enlace seguro de restablecimiento de contraseña a tu correo.',
        'help' => '¿No lo recibiste? Revisa tu carpeta de spam o',
        'try_again' => 'inténtalo de nuevo',
        'return' => 'Volver a Iniciar Sesión',
        'secure_badge' => 'Proceso seguro',
    ],

    'success' => [
        'title' => '¡Éxito!',
        'subtitle' => 'Tu contraseña ha sido actualizada exitosamente. Ahora puedes iniciar sesión con tus nuevas credenciales.',
        'signin' => 'Iniciar Sesión Ahora',
        'secure_badge' => 'Cuenta Asegurada',
    ],

];