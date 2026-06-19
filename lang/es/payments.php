<?php

return [
    'title' => 'Pagos',
    'header_title' => 'Historial de Pagos',
    'header_subtitle' => 'Administre y rastree todas las transacciones del sistema.',
    
    // Buttons & Links
    'btn_filter' => 'Filtrar',
    'btn_review_all' => 'Revisar Todo',
    'btn_export' => 'Exportar Datos',
    'btn_view' => 'Ver',
    'btn_approve' => 'Aprobar',
    'btn_reject' => 'Rechazar',
    'btn_close' => 'Cerrar',
    'btn_clear_filters' => 'Borrar Filtros',
    
    // Placeholders & Inputs
    'placeholder_search' => 'Buscar pagos...',
    'label_status' => 'Estado',
    'opt_all' => 'Todos',
    'opt_pending' => 'Pendiente',
    'opt_success' => 'Exitoso',
    'opt_failed' => 'Fallido',
    
    // Alerts
    'alert_pending_count' => ':count Pago Pendiente|:count Pagos Pendientes',
    
    // Table Headers
    'col_trx' => 'ID TRANSACCIÓN / PASARELA',
    'col_user' => 'USUARIO',
    'col_amount' => 'MONTO (:currency)',
    'col_status' => 'ESTADO',
    'col_date' => 'FECHA',
    'col_action' => 'ACCIÓN',
    
    // Table Content
    'text_user_deleted' => 'Usuario Eliminado',
    'text_user_not_found' => 'Usuario no encontrado',
    'status_success' => 'Exitoso',
    'status_approved' => 'Aprobado',
    'status_pending' => 'Pendiente',
    'status_initiated' => 'Iniciado',
    'status_failed' => 'Fallido',
    'status_rejected' => 'Rechazado',
    'empty_title' => 'No se encontraron pagos',
    
    // Modal Details
    'modal_title' => 'Pago vía :gateway',
    'sect_user_info' => 'Información de Pago del Usuario',
    'sect_payment_details' => 'Detalles del Pago',
    'label_fname' => 'Nombre',
    'label_lname' => 'Apellido',
    'label_bank' => 'Nombre del Banco',
    'label_trx' => 'Número de Transacción',
    'label_screenshot' => 'Captura de Pantalla',
    'link_attachment' => 'Adjunto',
    'text_no_attachment' => 'No se proporcionó adjunto',
    
    'label_date' => 'Fecha',
    'label_username' => 'Nombre de Usuario',
    'label_method' => 'Método',
    'label_amount' => 'Monto',
    'label_charge' => 'Cargo',
    'label_after_charge' => 'Después del Cargo',
    'label_rate' => 'Tasa',
    'label_total' => 'Total a Pagar',
    
    // JS Confirmations
    'confirm_title' => '¿Estás seguro?',
    'confirm_text' => 'La acción no se puede deshacer.',
    'confirm_yes' => '¡Sí, proceder!',
    'confirm_approve_title' => '¿Aprobar Pago?',
    'confirm_approve_text' => '¿Aprobar transacción :trx?',
    'confirm_reject_title' => '¿Rechazar Pago?',
    'confirm_reject_text' => '¿Rechazar transacción :trx? Esto no se puede deshacer.',
];