<?php

return [
    'save' => 'Guardar cambios',
    'cancel' => 'Cancelar',
    'remove' => 'Eliminar',
    'none' => 'Ninguno',
    'recommended' => 'Recomendado:',
    'publish' => 'Publicar',
    
    'certificate' => [
        'title' => 'Estudio de Certificados',
        'sub' => 'Diseño y Verificación',
        'reset_tooltip' => 'Restablecer Diseño',
        'layout_tab' => 'Diseño y Estilo',
        'content_tab' => 'Contenido',
        'footer_tab' => 'Pie de página',
        'orientation' => 'Orientación',
        'landscape' => 'Horizontal',
        'portrait' => 'Vertical',
        'typography' => 'Estilo de Tipografía',
        
        // NEU
        'desktop_required' => 'Se requiere escritorio',
        'desktop_desc' => 'El Estudio de Certificados ofrece una experiencia de diseño enriquecida que se utiliza mejor en una pantalla más grande. Por favor, acceda a esta página desde un ordenador de escritorio o portátil.',
        
        'font_pinyon_elegant' => 'Pinyon Script (Elegante)',
        'font_great_vibes_cursive' => 'Great Vibes (Cursiva)',
        'font_cinzel_formal' => 'Cinzel (Formal)',
        'font_lato_modern' => 'Lato (Moderno)',
        
        'var_name' => 'Nombre',
        'var_exam' => 'Examen',
        'var_score' => 'Puntuación',
        'var_date' => 'Fecha',
        'var_qr' => 'Imagen QR',
        'default_body' => "Certifica que\n<strong>@{{full_name}}</strong>\n\nha completado con éxito los requisitos para\n<strong>@{{exam_title}}</strong>",
        
        'bg_image' => 'Imagen de Fondo',
        'bg_help' => 'Subir Marco/Fondo Personalizado',
        'size_help' => 'Tamaño Recomendado (A4 @ 300DPI): Horizontal: 3508x2480 px, Vertical: 2480x3508 px',
        'remove_bg' => 'Eliminar Fondo',
        'heading' => 'Encabezado',
        'alignment' => 'Alineación',
        'body_text' => 'Cuerpo del Texto',
        'insert_var' => 'Insertar Variable',
        'var_help' => 'Eliminar variables directamente en el cuadro de arriba.',
        'date' => 'Fecha',
        'sig_mode' => 'Modo de Firma',
        'text' => 'Texto',
        'image' => 'Imagen',
        'sig_name' => 'Nombre',
        'upload_sig' => 'Subir Firma',
        'remove_sig' => 'Eliminar Imagen',
        'toast_saved' => '¡Configuración guardada!',
        'variables_title' => 'Variables',
        'default_title' => 'Certificado de Finalización',
        'default_sign' => 'Director de Educación',
        'default_date' => 'Fecha: @{{completed_at}}',
    ],

    'frontend' => [
    'title' => 'Visibilidad Frontend',
    'desc' => 'Alternar secciones para construir el diseño de su página de inicio.',
    'sections' => [
        'hero' => ['title' => 'Sección Principal (Hero)', 'desc' => 'Área de banner principal'],
        'features' => ['title' => 'Características', 'desc' => 'Puntos clave de venta'],
        'categories' => ['title' => 'Categorías', 'desc' => 'Agrupaciones temáticas'],
        'exams' => ['title' => 'Exámenes Destacados', 'desc' => 'Lista de los mejores exámenes'],
        'how_it_works' => ['title' => 'Cómo Funciona', 'desc' => 'Explicación del proceso'],
        'audience' => ['title' => 'Público Objetivo', 'desc' => '¿Para quién es esto?'],
        'pricing' => ['title' => 'Tablas de Precios', 'desc' => 'Planes de suscripción'],
        'testimonials' => ['title' => 'Testimonios', 'desc' => 'Opiniones de usuarios'],
        'faq' => ['title' => 'Preguntas Frecuentes', 'desc' => 'Preguntas comunes'],
        'cta' => ['title' => 'Llamada a la Acción', 'desc' => 'Mensaje final de registro'],
        
        // New sections
        'admin_preview' => ['title' => 'Vista Previa del Panel de Admin', 'desc' => 'Métricas y características clave del panel de control'],
        'cms_features' => ['title' => 'CMS y Características', 'desc' => 'Resumen de las capacidades del sistema de gestión de contenido'],
    ],
],

    'logo' => [
        'title' => 'Logo y Favicon',
        'desc' => 'Administrar la identidad visual de la aplicación en modos claro y oscuro.',
        'system_logos' => 'Logos del Sistema',
        'light_mode' => 'Logo (Modo Claro)',
        'light_help' => 'Texto/icono de color oscuro para fondos claros.',
        'dark_mode' => 'Logo (Modo Oscuro)',
        'dark_help' => 'Texto/icono blanco/claro para fondos oscuros.',
        'browser_icon' => 'Icono del Navegador',
        'favicon' => 'Favicon',
        'favicon_help' => 'Formato .ico o .png de 32x32px o 64x64px.',
        'alerts' => [
            'confirm_title' => '¿Está seguro?',
            'confirm_text' => '¿Desea eliminar este logo? Esta acción no se puede deshacer.',
            'yes_remove' => 'Sí, eliminarlo!',
        ]
    ],

    'styling' => [
        'title' => 'Estilo Personalizado',
        'desc' => 'Inyectar CSS o JS personalizado para anular los temas predeterminados.',
        'warning' => 'Solo usuarios avanzados.',
        'css_label' => 'CSS Personalizado',
        'css_sub' => 'Se carga en el <head> de las páginas visibles para el usuario.',
        'css_placeholder' => '/* Ingrese reglas CSS personalizadas aquí */',
        'js_label' => 'JavaScript Personalizado',
        'js_sub' => 'Se carga antes de la etiqueta de cierre </body>.',
        'js_placeholder' => '// Ingrese JS personalizado aquí (sin etiquetas <script>)',
        'save_code' => 'Guardar Código',
    ]
];