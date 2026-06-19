<?php

return [
    'save' => 'Guardar cambios',
    'save_config' => 'Guardar configuración',
    'generate_now' => 'Generar Sitemap ahora',

    'alerts' => [
        'remove_title' => '¿Eliminar banner?',
        'remove_text' => 'Esto eliminará el banner SEO actual al guardar.',
        'yes_remove' => 'Sí, eliminar',
        'cancel' => 'Cancelar',
        'invalid_group' => 'Grupo de configuración no válido.',
        'updated_success' => 'Configuración SEO actualizada con éxito.',
        'sitemap_generated' => 'Sitemap generado con éxito.',
        'sitemap_failed' => 'Error al generar el sitemap: :error',
        'sitemap_not_found' => 'Archivo sitemap no encontrado. Por favor genérelo primero.',
    ],

    'defaults' => [
        'desc' => 'La mejor plataforma de evaluación y aprendizaje impulsada por IA.',
        'keywords' => 'examen, ia, evaluación, aprendizaje',
    ],
    
    'config' => [
        'title' => 'Configuración SEO',
        'desc' => 'Configure metaetiquetas, visuales para redes sociales y seguimiento analítico.',
        'meta_title' => 'Metaetiquetas y Visuales',
        'meta_desc' => 'Optimice cómo aparece su sitio en los resultados de búsqueda.',
        'meta_title_label' => 'Meta Título (Máx. 60 caracteres)',
        'meta_title_ph' => 'Ej: ZiExam AI - Plataforma de Aprendizaje',
        'meta_desc_label' => 'Meta Descripción (Máx. 160 caracteres)',
        'meta_desc_ph' => 'Un breve resumen del contenido de su sitio.',
        'keywords_label' => 'Palabras clave (separadas por comas)',
        'keywords_ph' => 'palabras, clave, separadas, por, comas',
        
        'analytics_title' => 'Analítica y Verificación',
        'ga_label' => 'ID de seguimiento de Google Analytics',
        'ga_ph' => 'UA-XXXXXXXXX-Y o G-XXXXXXXXX',
        'ga_help' => 'Inserte su ID de medición de Google Analytics/GA4.',
        
        'banner_title' => 'Banner para compartir en redes',
        'banner_help' => 'Tamaño recomendado: 1200x630px. Usado para OpenGraph / Twitter Cards.',
        'delete_banner_title' => 'Eliminar banner actual',
        'no_banner' => 'No se ha subido ningún banner.',
    ],

    'sitemap' => [
        'title' => 'Configuración del Sitemap',
        'desc' => 'Controle el rastreo de motores de búsqueda y administre el archivo XML.',
        'crawling_title' => 'Reglas de rastreo',
        'crawling_desc' => 'Defina cómo interactúan los bots con la estructura de su sitio.',
        'robots_label' => 'Etiqueta Meta Robots',
        'robots_options' => [
            'index_follow' => 'Indexar y seguir (Predeterminado)',
            'noindex_follow' => 'No indexar, pero seguir enlaces',
            'index_nofollow' => 'Indexar, pero no seguir enlaces',
            'noindex_nofollow' => 'No indexar y no seguir',
        ],
        'robots_help' => 'Controla el comportamiento de indexación en todo el sitio.',
        
        'status_title' => 'Estado del Sitemap',
        'file_url' => 'URL del archivo:',
        'last_gen' => 'Última generación:',
        'never' => 'Nunca',
        'download_xml' => 'Descargar XML',
        'info_text' => 'El archivo <strong>sitemap.xml</strong> ayuda a los motores de búsqueda a descubrir sus páginas. Después de generar, envíe la URL completa a Google Search Console.',
    ],
];