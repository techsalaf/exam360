<?php

return [
    'defaults' => [
        'title' => 'Valores Predeterminados',
        'desc' => 'Configure el idioma, la zona horaria y el país por defecto.',
        'regional_title' => 'Configuración Regional',
        'regional_desc' => 'Establezca los valores base para nuevos visitantes.',
        'language_label' => 'Idioma del Sistema',
        'language_help' => 'Idioma de respaldo si falta la selección del usuario.',
        'timezone_label' => 'Zona Horaria Predeterminada',
        'country_label' => 'País Predeterminado',
        'countries' => [
            'US' => 'Estados Unidos',
            'GB' => 'Reino Unido',
            'IN' => 'India',
            'BD' => 'Bangladesh',
        ],
        'save_btn' => 'Guardar Valores',
    ],

    'switchers' => [
        'title' => 'Selectores de Idioma',
        'desc' => 'Controle dónde los usuarios pueden cambiar de idioma.',
        'front_label' => 'Selector Frontend',
        'front_help' => 'Permitir a visitantes cambiar idioma en el sitio público.',
        'admin_label' => 'Selector Panel Admin',
        'admin_help' => 'Permitir al personal cambiar idioma en el tablero.',
        'update_btn' => 'Actualizar Visibilidad',
    ],

    'table' => [
        'title' => 'Idiomas Disponibles',
        'desc' => 'Gestione los idiomas del sistema y su disponibilidad.',
        'add_new' => 'Agregar Nuevo',
        'headers' => [
            'name' => 'Nombre',
            'code' => 'Código',
            'rtl' => 'RTL',
            'front' => 'Frontend',
            'admin' => 'Admin',
            'actions' => 'Acciones',
        ],
        'badges' => [
            'default' => 'Defecto',
            'active' => 'Activo',
            'hidden' => 'Oculto',
            'yes' => 'Sí',
            'no' => 'No',
        ],
        'tooltips' => [
            'set_default' => 'Establecer como Predeterminado',
            'curr_default' => 'Predeterminado Actual',
            'delete' => 'Eliminar Idioma',
        ],
    ],

    'modals' => [
        'add_title' => 'Agregar Nuevo Idioma',
        'edit_title' => 'Editar Idioma',
        'name_label' => 'Nombre',
        'name_ph' => 'ej. Francés',
        'code_label' => 'Código (ISO 2)',
        'code_ph' => 'ej. fr',
        'flag_label' => 'Código de Bandera',
        'flag_help' => 'Usado para flag-icon-css o emoji.',
        'rtl_label' => 'Derecha a Izquierda (RTL)',
        'front_label' => 'Disponible en Frontend',
        'admin_label' => 'Disponible en Panel Admin',
        'add_btn' => 'Agregar Idioma',
        'save_btn' => 'Guardar Cambios',
    ],

    'alerts' => [
        'delete_title' => '¿Eliminar Idioma?',
        'delete_text' => '¿Está seguro de eliminar :name? Esto borrará el archivo de traducción y no se puede deshacer.',
        'default_title' => '¿Hacer Predeterminado?',
        'default_text' => '¿Hacer de :name el idioma principal? Los nuevos visitantes verán este idioma primero.',
        'yes_delete' => 'Sí, Eliminar',
        'yes_default' => 'Sí, Hacer Predeterminado',
        'cancel' => 'Cancelar',
    ],
];