<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Verificación de correo electrónico
    |--------------------------------------------------------------------------
    */
    'email_not_verified' => 'Verifique su correo electrónico',
    'verify_email_desc' => 'Por favor verifique su correo para acceder a todas las funciones.',
    'resend_link' => 'Reenviar enlace de verificación',
    'verification_link_sent' => 'Se ha enviado un nuevo enlace de verificación a su correo.',
    'system_alert' => 'Alerta del sistema',


     // User Result Page (Metrics & Status)
    'overall_score'         => 'PUNTUACIÓN GENERAL',
    'status_passed'         => 'APROBADO',
    'status_failed'         => 'FALLIDO',

    // New Metric Labels (Crucial for the new layout)
    'metric_correct'        => 'RESPUESTAS CORRECTAS',
    'metric_time'           => 'TIEMPO EMPLEADO (MIN)',
    'metric_pass_percentage'=> 'PORCENTAJE DE APROBACIÓN REQUERIDO',
    
    // NEW NEGATIVE MARKING KEYS
    'metric_incorrect'      => 'RESPUESTAS INCORRECTAS',
    'metric_net_score'      => 'PUNTUACIÓN NETA',
    'metric_deducted_marks' => 'PUNTOS DEDUCIDOS',
    
    // Total marks label update
    'metric_total_marks'    => 'PUNTUACIÓN TOTAL', 

    // New Alert Key
    'negative_marking_alert' => 'La calificación negativa está habilitada: :value puntos deducidos por respuesta incorrecta.',
    
    // Additional Text (If needed)
    'mins'                  => 'Min',
    /*
    |--------------------------------------------------------------------------
    | Frontend Dashboard & General (Spanish)
    |--------------------------------------------------------------------------
    */
    'welcome_back' => 'Bienvenido de nuevo',
    'student_default' => 'Estudiante',
    'header_subtitle' => 'Mantente enfocado. Tu próxima evaluación está lista.',
    'assessment' => 'Evaluación',
    'minutes' => 'Minutos',
    'mins' => 'Mins',
    'view' => 'Ver',
    'result' => 'Resultado',
    // Checkout Flow
    'checkout_title' => 'Pago Seguro',
    'step_cart' => 'Carrito',
    'step_details' => 'Detalles',
    'step_payment' => 'Pago',
    
    // Payment Page Titles & Descriptions
    'payment_method' => 'Método de Pago',
    'payment_desc' => 'Las transacciones están cifradas y aseguradas.',
    'no_payment_enabled' => 'No hay pasarelas de pago habilitadas actualmente.',
    
    // Payment Options
    'credit_debit_card' => 'Tarjeta de Crédito/Débito',
    'bank_transfer_offline' => 'Transferencia Bancaria/Offline',
    
    // Stripe Fields
    'card_holder_name' => 'Nombre del Titular',
    'email' => 'Correo Electrónico',
    'card_number' => 'Número de Tarjeta',
    'expiry_date' => 'Fecha de Caducidad',
    'cvc' => 'CVC',
    'securely_processed_by_stripe' => 'Procesado de forma segura por Stripe',
    
    // Bank/Offline Fields
    'account_holder_name' => 'Nombre del Titular de la Cuenta',
    'bank_name' => 'Nombre del Banco',
    'account_number_iban' => 'Número de Cuenta / IBAN',
    'ifsc_swift_code' => 'Código IFSC / SWIFT',
    'additional_instructions' => 'Instrucciones Adicionales',
    'offline_gateway_note' => 'Utilice los detalles a continuación para completar su transferencia. Su pedido se confirmará tras la verificación manual del pago.',
    
    // Payment Prompts
    'select_gateway_prompt' => 'Por favor, seleccione un método de pago arriba para mostrar los campos requeridos.',
    'razorpay_redirect_note' => 'Este método generalmente requiere una redirección para completar el pago en la plataforma Razorpay.',

    // Order Summary
    'order_summary' => 'Resumen del Pedido',
    'subtotal' => 'Subtotal',
    'taxes' => 'Impuestos',
    'total_amount' => 'Importe Total',

    // Buttons & Security
    'pay_with_amount' => 'Pagar {amount} y Acceder',
    'back_details' => 'Volver a Detalles',
    'bank_security' => 'Seguridad de Nivel Bancario',
    
    // Exam Actions
    'continue_exam' => 'Continuar Examen',
    'start_now' => 'Empezar Ahora',
    'view_instructions' => 'Ver Instrucciones',
    'view_all_exams' => 'Ver Todos los Exámenes',
    'retake_exam' => 'Retomar Examen',
    'go_to_exams' => 'Ir a Exámenes',

    // Exam Status
    'ongoing' => 'En Curso',
    'ready' => 'Listo',
    'completed' => 'Completado',
    'pending' => 'Pendiente',
    
    // Hero Section
    'no_active' => 'SIN ACTIVIDAD',
    'no_scheduled_title' => 'No hay evaluaciones programadas',
    'no_scheduled_desc' => 'Consulta tus cursos para ver los exámenes o cuestionarios disponibles.',
    'available_now' => 'Disponible Ahora',

    // Stats Widget
    'scheduled' => 'Programado',
    'avg_score' => 'Promedio',
    
    // Exam List Section
    'your_exams' => 'Tus Exámenes',
    'tab_upcoming' => 'Próximos',
    'tab_history' => 'Historial',
    'no_upcoming_exams' => 'No se encontraron exámenes próximos.',
    'no_history_exams' => 'No se encontraron exámenes completados.',
    'score_label' => 'Puntuación:',
    
    // Performance Widget
    'performance_snapshot' => 'Resumen de Rendimiento',
    'accuracy_rate' => 'Tasa de Precisión',
    'time_management' => 'Gestión del Tiempo',
    'consistency' => 'Consistencia',
    
    // Updates Widget
    'exam_updates' => 'Actualizaciones',
    'schedule_change' => 'Cambio de Horario',
    'schedule_change_msg' => 'El examen parcial de Física se reprogramó para el 28 de octubre.',
    'result_published' => 'Resultado Publicado',
    'result_published_msg' => 'La nota final de Química ya está disponible para ver.',

    // Notificaciones
    'notifications' => 'Notificaciones',
    'no_notifications' => 'No hay nuevas notificaciones.',
    'view_all_notifications' => 'Ver todas las notificaciones',
    'notification_welcome_title' => 'Bienvenido a ZiExam AI',
    'notification_welcome_body' => '¡Estamos emocionados de tenerte a bordo! Comienza explorando los exámenes disponibles.',
    'notification_result_title' => 'Resultado del examen publicado',
    'notification_result_body' => 'Tus resultados para "{exam}" ya están disponibles.',
    'notification_payment_title' => 'Pago exitoso',
    'notification_payment_body' => 'Recibimos tu pago por el "{plan}". ID de transacción: {trx}',
    'notification_profile_title' => 'Perfil incompleto',
    'notification_profile_body' => 'Por favor completa tu información de perfil para generar certificados con precisión.',
    'notification_missed_title' => 'Examen perdido',
    'notification_missed_body' => 'Perdiste el horario programado para "{exam}".',
    // Success / Failure
    'payment_successful' => '¡Pago Exitoso!',
    'payment_pending' => 'Pago Pendiente',
    'exam_access_active' => 'Su acceso al examen está activo ahora.',
    'offline_processing' => 'Su pago fuera de línea está siendo revisado.',
    'purchased_exams' => 'Examen(es) Comprado(s)',
    'access_activated' => 'Acceso Activado',
    'access_pending' => 'Acceso Pendiente',
    'order_id' => 'ID de Pedido',
    'amount_paid' => 'Monto Pagado',
    'go_to_dashboard' => 'Ir al Panel',
    'home' => 'Inicio',
    'not_ready_note' => '¿No está listo para comenzar? Este examen se ha guardado en su cuenta.',
    'buy_again_btn' => 'Comprar de Nuevo',
    'retake_exam_btn' => 'Volver a Tomar',
    'start_exam_btn' => 'Comenzar Examen',
    'buy_exam_btn' => 'Comprar Ahora',
    'start_free_btn' => 'Empezar Gratis',
    'result_published' => 'Resultado Publicado',
    'result_pending' => 'Resultado Pendiente',
    'results_locked' => 'Resultados Bloqueados',
    'score_label_card' => 'Puntuación',
    'progress_label' => 'Progreso',
    'upcoming_exam_title' => 'El examen está próximo',
    'upcoming_exam_msg' => 'Este examen está programado para comenzar el',
    'upcoming_exam_wait' => 'Por favor espere hasta la hora de inicio para participar.',
    'visit_website' => 'Visitar Sitio',
     'no_exams_match' => 'Ningún examen coincide con tus criterios.',
    'no_exams_suggestion' => 'Intenta ajustar tus términos de búsqueda o filtros para encontrar lo que buscas.',
    'clear_all_filters' => 'Borrar todos los filtros',

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */
    'my_certificates' => 'Mis Certificados',
    'certificates_subtitle' => 'Certificados obtenidos tras completar con éxito los exámenes calificados.',
    'earned_section' => 'Certificados Obtenidos',
    'cert_achievement' => 'Certificado de Logro',
    'issued_on' => 'Emitido:',
    'download_pdf' => 'Descargar PDF',
    
    'processing_section' => 'Procesando',
    'passed_on' => 'Aprobado el',
    'waiting_admin' => 'Esperando emisión del administrador.',
    
    'locked_section' => 'Bloqueado',
    'not_earned' => 'No Obtenido',
    'highest_score' => 'Puntaje Más Alto:',
    'required_score' => 'Requerido:',
    
    'no_certs_title' => 'Aún no hay certificados',
    'no_certs_desc' => 'Complete exámenes con una puntuación aprobatoria para obtener certificados oficiales.',

    /* Notifications */
    'notifications_title' => 'Notificaciones',
    'notifications_subtitle' => 'Mantente actualizado con tus últimas actividades y alertas.',
    'mark_all_read' => 'Marcar todo como leído',
    'view_details' => 'Ver Detalles',
    'remove_notification' => 'Eliminar Notificación',
    'no_notifications' => 'Sin Notificaciones',
    'no_notifications_desc' => '¡Estás al día! No hay nuevas alertas para mostrar.',

    /* Profile */
    'profile_title' => 'Mi Perfil',
    'profile_subtitle' => 'Actualiza tu información personal y cambia tu contraseña.',
    'general_info' => 'Información General',
    'change_avatar' => 'Cambiar Avatar',
    'avatar_help' => 'Tam. máx. 2MB (JPG/PNG)',
    'file_selected' => 'Archivo Seleccionado',
    'full_name' => 'Nombre Completo',
    'email_address' => 'Dirección de Correo',
    'save_general' => 'Guardar Información',
    
    'change_password' => 'Cambiar Contraseña',
    'current_password' => 'Contraseña Actual',
    'new_password' => 'Nueva Contraseña',
    'confirm_password' => 'Confirmar Nueva Contraseña',
    'update_password' => 'Actualizar Contraseña',

    /* Transactions */
    'transactions_title' => 'Historial de Transacciones',
    'transactions_subtitle' => 'Revisa todos tus pagos y suscripciones de planes.',
    'filter_btn' => 'Filtrar',
    'reset_btn' => 'Restablecer',
    'txn_id' => 'ID Transacción',
    'plan_item' => 'Plan/Ítem',
    'amount' => 'Monto',
    'gateway' => 'Pasarela',
    'status' => 'Estado',
    'date' => 'Fecha',
    'standalone_purchase' => 'Compra Única',
    'days_subscription' => 'días de suscripción',
    
    'no_txn_found' => 'No se encontraron transacciones',
    'no_txn_desc' => 'Tus filtros no arrojaron pagos que coincidan con los criterios.',
    'browse_plans' => 'Explorar Exámenes y Planes',

    /* Settings */
    'settings_title' => 'Configuración de la Aplicación',
    'settings_subtitle' => 'Configure sus preferencias, notificaciones y opciones de seguridad.',
    'notification_prefs' => 'Preferencias de Notificación',
    'email_notify' => 'Notificaciones por Correo',
    'email_notify_desc' => 'Reciba actualizaciones sobre nuevos resultados e invitaciones a exámenes.',
    'in_app_alerts' => 'Alertas en la App',
    'in_app_alerts_desc' => 'Mostrar notificaciones oportunas dentro del panel.',
    'regional_settings' => 'Configuración Regional y Horaria',
    'timezone' => 'Zona Horaria',
    'language' => 'Idioma',
    'save_settings' => 'Guardar Configuración',

    /* Support Tickets */
    'tickets_title' => 'Tickets de Soporte',
    'tickets_subtitle' => 'Gestione sus solicitudes de soporte y correspondencia.',
    'create_ticket' => 'Crear Nuevo Ticket',
    'my_active_tickets' => 'Mis Tickets Activos',
    'filter_by' => 'Filtrar por:',
    'status_all' => 'Todos',
    'status_open' => 'Abierto',
    'status_replied' => 'Respondido',
    'status_closed' => 'Cerrado',
    
    'th_ticket_id' => 'ID TICKET',
    'th_subject' => 'ASUNTO',
    'th_priority' => 'PRIORIDAD',
    'th_status' => 'ESTADO',
    'th_last_updated' => 'ÚLTIMA ACT.',
    'th_action' => 'ACCIÓN',
    'no_tickets' => 'No se encontraron tickets.',
    
    'back_btn' => 'Atrás',
    'priority_suffix' => 'Prioridad',
    'created_prefix' => 'Creado',
    'me_label' => 'Yo',
    'admin_label' => 'Admin',
    'support_agent' => 'Agente de Soporte',
    'attachments_label' => 'Adjuntos:',
    'view_file' => 'Ver Archivo',
    'no_messages' => 'No se encontraron mensajes en este ticket.',
    'reply_label' => 'Responder',
    'reply_placeholder' => 'Escriba su mensaje aquí...',
    'attachments_optional' => 'Adjuntos (Opcional)',
    'send_reply' => 'Enviar Respuesta',
    'close_ticket' => 'Cerrar Ticket',
    'close_confirm' => '¿Está seguro de querer cerrar este ticket? No podrá responder más.',
    'ticket_closed_msg' => 'Este ticket está cerrado. Si necesita más ayuda, por favor',
    'open_new_link' => 'abra un nuevo ticket',
    
    'modal_title' => 'Enviar Nuevo Ticket de Soporte',
    'subject_label' => 'Asunto',
    'subject_place' => 'Breve resumen del problema',
    'category_label' => 'Categoría',
    'select_cat' => 'Seleccione Categoría...',
    'cat_billing' => 'Facturación / Pago',
    'cat_tech' => 'Problema Técnico',
    'cat_content' => 'Contenido del Curso',
    'cat_general' => 'Consulta General',
    'cat_feature' => 'Solicitud de Función',
    'priority_label' => 'Prioridad',
    'p_low' => 'Baja',
    'p_medium' => 'Media',
    'p_high' => 'Alta',
    'desc_label' => 'Descripción',
    'desc_place' => 'Proporcione pasos detallados o contexto...',
    'supported_formats' => 'Formatos soportados: JPG, PNG, PDF, DOCX',
    'support_notice' => 'Nuestro equipo busca responder tickets urgentes en 24 horas.',
    'cancel_btn' => 'Cancelar',
    'submit_btn' => 'Enviar Ticket',

    /* Exam Results */
    'results_title' => 'Resultados del Examen',
    'results_subtitle' => 'Informes detallados de rendimiento para sus evaluaciones completadas.',
    'no_results_title' => 'Aún no hay resultados',
    'no_results_desc' => 'No has completado ningún examen. Una vez finalizada una evaluación, tu informe aparecerá aquí.',
    'browse_exams_btn' => 'Explorar Exámenes',
    
    'status_passed' => 'Aprobado',
    'status_failed' => 'Reprobado',
    'status_pending' => 'Esperando Resultado',
    'result_available' => 'Resultado Disponible:',
    'completed_on' => 'Completado:',
    'your_score' => 'Tu Puntuación:',
    'passing_mark' => 'Nota de Aprobación:',
    'view_full_report' => 'Ver Informe Completo',
    'view_status' => 'Ver Estado',

    'exam_completed_title' => '¡Examen Completado!',
    'exam_completed_msg' => 'Gracias por completar :title. Tus respuestas se han registrado correctamente.',
    'expected_date_label' => 'Fecha Esperada de Resultados',
    'publish_time_msg' => 'Los resultados se publicarán alrededor de',
    'tba_title' => 'Por Anunciar',
    'tba_msg' => 'El instructor aún no ha establecido una fecha de publicación.',
    'back_to_exams' => 'Volver a Mis Exámenes',

    'report_prefix' => 'Informe:',
    'report_subtitle' => 'Análisis detallado y desglose de preguntas para su intento.',
    'back_to_results' => 'Volver a Resultados',
    'download_pdf_alert' => '¡Descarga en PDF próximamente!',
    'overall_score' => 'Puntuación General',
    'metric_correct' => 'Respuestas Correctas',
    'metric_time' => 'Tiempo (Mins)',
    'metric_total_marks' => 'Puntos Totales',
    'metric_pass_percentage' => '% Requerido',
    
    'analysis_title' => 'Análisis de Preguntas',
    'analysis_subtitle' => 'Revisa tu respuesta contra la solución correcta para cada pregunta.',
    'review_answer_btn' => 'Revisar Respuesta',
    'label_your_answer' => 'Tu Respuesta:',
    'label_skipped' => 'Omitida',
    'label_correct_answer' => 'Respuesta Correcta:',
    'label_explanation' => 'Explicación:',

    /* Exam Cards & Instructions */
    'no_records_found' => 'No se encontraron registros.',
    'starts' => 'Comienza',
    'soon' => 'Pronto',
    'view_report' => 'Ver Informe',
    'questions_count' => 'Preguntas',
    'progress_label' => 'Progreso',
    'score_label_card' => 'Puntuación',
    
    'instructions_header' => 'Instrucciones del Examen',
    'instructions_subtitle' => 'Por favor lea estas pautas cuidadosamente antes de comenzar su desafío :title.',
    'instruction_1_title' => '1. Responder y Guardar',
    'instruction_1_text' => 'Cada pregunta tiene solo una respuesta correcta a menos que se especifique lo contrario. Su selección se guarda automáticamente.',
    'instruction_2_title' => '2. Navegación y Revisión',
    'instruction_2_text' => 'Puede moverse libremente entre todas las preguntas. Use la bandera "Marcar para revisión" para volver a visitarlas más tarde.',
    'instruction_3_title' => '3. Límite de Tiempo y Envío',
    'instruction_3_text' => 'La duración total es de :minutes minutos. El examen se enviará automáticamente cuando expire el tiempo.',
    'instruction_4_title' => '4. Seguridad Técnica',
    'instruction_4_text' => 'Asegúrese de tener una conexión a Internet estable. No actualice la página ni cierre la ventana.',
    
    'agree_terms' => 'He leído y entendido todas las instrucciones anteriores.',
    'back_to_exams' => 'Volver a Mis Exámenes',
    'start_exam_btn' => 'Comenzar Examen',

    /* My Exams Page */
    'my_exams_title' => 'Mis Exámenes',
    'my_exams_subtitle' => 'Administre sus evaluaciones compradas y siga su progreso.',
    'tab_available' => 'Disponible',
    'tab_ongoing' => 'En Curso',
    'tab_completed' => 'Completado',
    'tab_upcoming' => 'Próximo',
    'no_exams_ready' => 'Aún no tiene exámenes listos para comenzar.',
    'browse_exams_btn' => 'Explorar Exámenes',
    'no_exams_progress' => 'No hay exámenes en curso actualmente.',
    'no_exams_completed' => 'Aún no se han completado exámenes.',
    'no_exams_scheduled' => 'No hay exámenes programados próximos.',

    /* Participation Screen */
    'end_exam' => 'Finalizar Examen',
    'question_label' => 'Pregunta',
    'of_label' => 'de',
    'loading' => 'Cargando...',
    'previous_btn' => 'Anterior',
    'next_btn' => 'Siguiente',
    'mark_review' => 'Marcar para Revisión',
    'submit_finish' => 'Enviar y Finalizar',
    'auto_save_msg' => 'Todas las respuestas guardadas automáticamente.',
    'progress_overview' => 'Resumen de Progreso',
    'stat_answered' => 'Respondidas',
    'stat_marked' => 'Marcadas',
    'stat_remaining' => 'Restantes',
    'question_navigator' => 'Navegador de Preguntas',
    'confirm_submission' => 'Confirmar Envío',

    /*
    |--------------------------------------------------------------------------
    | Exam List Page (Spanish)
    |--------------------------------------------------------------------------
    */
    'explore_exams_title' => 'Explorar Exámenes',
    'explore_exams_desc' => 'Examine nuestro catálogo completo de exámenes, pruebas de práctica y certificaciones.',
    'filters_title' => 'Filtros',
    'reset_btn' => 'Restablecer',
    'search_placeholder' => 'Buscar exámenes...',
    'categories_title' => 'Categorías',
    'price_title' => 'Precio',
    'all_prices' => 'Todos los Precios',
    'free_only' => 'Solo Gratis',
    'paid_only' => 'Solo Pagos',
    'apply_filters_btn' => 'Aplicar Filtros',
    
    'showing_results' => 'Mostrando :first a :last de :total exámenes',
    'showing_results_footer' => 'Mostrando :first a :last de :total resultados',
    
    'sort_newest' => 'Ordenar por: Más Nuevo',
    'sort_price_low' => 'Precio: Bajo a Alto',
    'sort_price_high' => 'Precio: Alto a Bajo',
    
    'free_badge' => 'GRATIS',
    'qns_short' => 'Pgs',
    'buy_exam_btn' => 'Comprar Examen',
    'start_free_btn' => 'Empezar Gratis',
    'no_exams_match' => 'Ningún examen coincide con sus criterios.',

    /*
    |--------------------------------------------------------------------------
    | Dynamic Pages (Spanish)
    |--------------------------------------------------------------------------
    */
    'page_not_found' => 'Página no encontrada',
    'back_home' => 'Volver al Inicio',

    /* Checkout & Payment */
    'checkout_title' => 'Pago Seguro',
    'step_cart' => 'Carrito',
    'step_details' => 'Detalles',
    'step_payment' => 'Pago',
    
    // Cart Page
    'review_selection' => 'Revise su Selección',
    'confirm_exams' => 'Confirme sus exámenes antes de continuar.',
    'remove_item' => 'Eliminar',
    'order_summary' => 'Resumen del Pedido',
    'subtotal' => 'Subtotal',
    'taxes' => 'Impuestos',
    'total_amount' => 'Monto Total',
    'continue_checkout' => 'Continuar al Pago',
    'money_back_guarantee' => 'Garantía de devolución de 30 días',
    'cart_empty' => 'Tu carrito está vacío',
    'cart_empty_desc' => 'Explore nuestros exámenes.',
    'browse_exams_btn' => 'Explorar Exámenes',

    // Details Page
    'billing_details' => 'Detalles de Facturación',
    'billing_desc' => 'Usado para su recibo y acceso.',
    'first_name' => 'Nombre',
    'last_name' => 'Apellido',
    'email_address' => 'Correo Electrónico',
    'country' => 'País',
    'your_order' => 'Tu Pedido',
    'total_to_pay' => 'Total a Pagar',
    'continue_payment' => 'Continuar al Pago',
    'return_cart' => 'Volver al Carrito',
    'ssl_secure' => 'Transacción Segura SSL',

    // Payment Page
    'payment_method' => 'Método de Pago',
    'payment_desc' => 'Las transacciones están encriptadas.',
    'credit_card' => 'Tarjeta de Crédito / Débito',
    'pay_with_amount' => 'Pagar :amount y Acceder',
    'back_details' => 'Volver a Detalles',
    'bank_security' => 'Seguridad Bancaria',

    // Success Page
    'payment_success' => '¡Pago Exitoso!',
    'access_active' => 'Su acceso al examen está activo.',
    'purchased_exams' => 'Examen(es) Comprado(s)',
    'access_activated' => 'Acceso Activado',
    'order_id' => 'ID de Pedido',
    'amount_paid' => 'Monto Pagado',
    'go_dashboard' => 'Ir al Panel',
    'home_btn' => 'Inicio',
    'save_note' => '¿No estás listo? Este examen se ha guardado.',

    /* Home Page Sections */
    
    // Hero
    'hero_title_default' => 'Construye, Vende y Gestiona<br><span class="gradient-text">Exámenes Online</span> con<br><span class="ai-highlight">Automatización IA</span>',
    'hero_subtitle_default' => 'ZiExam AI es una plataforma SaaS lista para vender que te permite crear exámenes generados por IA, vender acceso y gestionar suscripciones.',
    'hero_rating_label' => 'Con la confianza de 58,980+ usuarios',
    
    // Categories
    'categories_title_default' => 'Crea Exámenes en Múltiples Categorías',
    'categories_subtitle_default' => 'ZiExam AI soporta una amplia gama de tipos de exámenes, desde pruebas académicas hasta evaluaciones profesionales.',
    'categories_bottom_text_default' => 'Todas las categorías son totalmente personalizables desde el panel de administración.',
    'category_exams_count' => ':count Exámenes',
    'no_categories_found' => 'No se encontraron categorías. Por favor agréguelas desde el Panel de Administración.',
    
    // Audience
    'audience_title_default' => 'Creado para Instituciones, Educadores y Emprendedores SaaS',
    'audience_subtitle_default' => 'ZiExam AI está diseñado para escalar, ya sea que dirijas un instituto o lances tu propio negocio SaaS.',
    'audience_bottom_text_default' => '¿No estás seguro de qué modelo te conviene? ZiExam AI soporta todos los tipos de negocios principales.',

    // Features
    'features_title_default' => 'Todo lo que necesitas para Lanzar y Escalar',
    'features_subtitle_default' => 'Un conjunto completo de herramientas impulsadas por IA para crear exámenes, automatizar evaluaciones y monetizar.',
    
    'feat_p1_title' => 'Creación y Control de Exámenes con IA',
    'feat_p1_desc' => 'Crea exámenes profesionales en minutos usando herramientas asistidas por IA.',
    'feat_p1_hint' => 'Generación impulsada por IA',
    
    'feat_p2_title' => 'Evaluación Automatizada e Insights',
    'feat_p2_desc' => 'Evalúa el rendimiento al instante y obtén información profunda sin esfuerzo manual.',
    'feat_p2_hint' => 'Analítica en tiempo real',
    
    'feat_p3_title' => 'Monetización y Acceso',
    'feat_p3_desc' => 'Convierte tus exámenes en un negocio sostenible con pagos integrados.',
    'feat_p3_hint' => 'Pagos Seguros',
    
    'feat_p4_title' => 'Gestión y Seguridad',
    'feat_p4_desc' => 'Herramientas de nivel empresarial para gestionar usuarios y datos de forma segura.',
    'feat_p4_hint' => 'Controles de Admin',

    // How It Works
    'how_it_works_title_default' => 'Cómo Funciona',
    'how_it_works_subtitle_default' => 'Lanza tu negocio de exámenes en 4 pasos simples.',
    
    'hiw_s1_title' => 'Instalar y Configurar',
    'hiw_s1_desc' => 'Configuración en tu servidor en minutos con nuestro instalador fácil.',
    
    'hiw_s2_title' => 'Crear Exámenes con IA',
    'hiw_s2_desc' => 'Usa IA para generar preguntas y estructurar exámenes al instante.',
    
    'hiw_s3_title' => 'Establecer Precios y Planes',
    'hiw_s3_desc' => 'Define modelos de suscripción o tarifas de compra única.',
    
    'hiw_s4_title' => 'Rastrear y Escalar',
    'hiw_s4_desc' => 'Monitorea el rendimiento de los estudiantes y el crecimiento de los ingresos.',

    /* Pricing Section */
    'pricing_title_default' => 'Precios Simples. Propiedad de por Vida.',
    'pricing_subtitle_default' => 'Elige la licencia que se ajuste a tu modelo de negocio. Compra única. Sin cuotas mensuales.',
    'most_popular' => 'MÁS POPULAR',
    'per_month' => '/ Mes',
    'choose_plan' => 'Elegir :plan',
    'exams_limit_count' => ':count Exámenes',
    'exams_unlimited' => 'Exámenes Ilimitados',
    'pricing_trust_1' => 'Pagos Seguros',
    'pricing_trust_2' => 'Cancela en Cualquier Momento',
    'pricing_trust_3' => 'Sin Cargos Ocultos',
    'pricing_trust_4' => 'Calidad Verificada',
    'no_pricing_plans' => 'No hay planes de precios definidos en el panel de administración.',

    /* Testimonials Section */
    'testimonials_title_default' => 'Con la confianza de Educadores, Equipos y Creadores en todo el mundo',
    'testimonials_subtitle_default' => 'Desde instructores individuales hasta instituciones en rápido crecimiento, los equipos confían en nuestra plataforma.',

    /* Featured Exams Section */
    'exams_title_default' => 'Vende Exámenes y Genera Ingresos Recurrentes',
    'exams_subtitle_default' => 'Monetiza exámenes individuales o agrúpalos en suscripciones, todo gestionado desde tu panel de administración.',
    'buy_exam_btn' => 'Comprar Examen',
    'start_free_btn' => 'Empezar Gratis',
    'no_active_exams' => 'No se encontraron exámenes activos. Por favor crea exámenes en el panel de administración.',
    
    'sub_strip_title_default' => 'Ofrece Acceso Ilimitado',
    'sub_strip_desc_default' => 'Agrupa todos los exámenes en niveles de suscripción mensuales o anuales.',
    'exams_bottom_text_default' => 'Todos los precios, reglas de acceso y disponibilidad se controlan completamente desde el panel de administración.',

    /* CMS Features Section */
    'cms_badge_default' => 'CMS INCLUIDO',
    'cms_title_default' => 'Lanza tu Sitio Web Sin Herramientas Extra',
    'cms_desc_default' => 'ZiExam AI incluye un CMS integrado que te permite crear páginas dinámicas, gestionar menús y editar secciones de inicio.',
    
    'cms_feat_1_title' => 'Páginas Dinámicas',
    'cms_feat_1_desc' => 'Crea páginas ilimitadas usando un sistema visual basado en secciones.',
    
    'cms_feat_2_title' => 'Constructor de Menús',
    'cms_feat_2_desc' => 'Construye y gestiona menús de navegación directamente desde admin.',
    
    'cms_feat_3_title' => 'Listo para SEO',
    'cms_feat_3_desc' => 'Controla meta títulos, descripciones y slugs para un mejor ranking.',
    
    'cms_feat_4_title' => 'Secciones de Inicio',
    'cms_feat_4_desc' => 'Edita bloques de héroe, características, precios y CTA fácilmente.',

    /* Admin Preview Section */
    'admin_preview_title_default' => 'Controla Todo desde un Panel Potente',
    'admin_preview_subtitle_default' => 'Un panel de administración centralizado diseñado para gestionar usuarios, exámenes, suscripciones e ingresos a escala.',
    
    'admin_stat_1_val' => '10,000+',
    'admin_stat_1_lbl' => 'Usuarios Soportados',
    
    'admin_stat_2_val' => '100%',
    'admin_stat_2_lbl' => 'Acceso Basado en Roles',
    
    'admin_stat_3_val' => 'Tiempo Real',
    'admin_stat_3_lbl' => 'Seguimiento de Ingresos',
    
    'admin_stat_4_val' => 'Costo IA',
    'admin_stat_4_lbl' => 'Control de Uso y Costos',
    
    'admin_feat_1_title' => 'Control de Usuarios y Roles',
    'admin_feat_1_desc' => 'Gestiona administradores, instructores y estudiantes con permisos detallados.',
    
    'admin_feat_2_title' => 'Ingresos y Suscripciones',
    'admin_feat_2_desc' => 'Rastrea pagos, planes, renovaciones y crecimiento en tiempo real.',
    
    'admin_feat_3_title' => 'Uso de IA y Límites',
    'admin_feat_3_desc' => 'Monitorea el consumo de IA, establece límites y controla los costos operativos.',
    
    'admin_feat_4_title' => 'Configuración del Sistema',
    'admin_feat_4_desc' => 'Configura pagos, correo electrónico, seguridad y comportamiento de la plataforma centralmente.',
    
    'admin_check_1' => 'No Requiere Código',
    'admin_check_2' => 'Arquitectura Lista para Empresas',
    'admin_check_3' => 'Construido en Laravel 10',

    /* CTA Section */
    'cta_title_default' => 'Comience su Negocio de<br>Exámenes en Línea Hoy',
    'cta_subtitle_default' => 'Obtenga el script de exámenes impulsado por IA más avanzado del mercado.',
    'cta_btn_primary' => 'Empezar',
    'cta_btn_secondary' => 'Ver Demo',

    /* Footer Section */
    'footer_about_text_default' => 'ZiExam AI es una plataforma de exámenes en línea impulsada por IA que le ayuda a crear y gestionar exámenes sin esfuerzo.',
    'useful_links' => 'Enlaces Útiles',
    'legal' => 'Legal',
    'contact_info' => 'Información de Contacto',
    'copyright_text' => 'Derechos de autor © :year ZiExam AI. Todos los derechos reservados.',
    
    'home_link' => 'Inicio',
    'features_link' => 'Características',
    'pricing_link' => 'Precios',
    'faq_link' => 'Preguntas Frecuentes',
    'privacy_policy' => 'Política de Privacidad',
    'terms_service' => 'Términos de Servicio',
    'security_policy' => 'Política de Seguridad',
    'refund_policy' => 'Política de Reembolso',

    /* Header & Navigation */
    'dashboard_btn' => 'Tablero',
    'start_free_btn_header' => 'Empezar Gratis',
    'select_language' => 'Seleccionar Idioma',
    'select_language_caps' => 'SELECCIONAR IDIOMA',

    /* Pagination */
    'previous' => 'Anterior',
    'next' => 'Siguiente',

    /* User Dashboard Sidebar & Topbar */
    'sidebar_main_menu' => 'Menú Principal',
    'sidebar_dashboard' => 'Tablero',
    'sidebar_my_exams' => 'Mis Exámenes',
    'sidebar_results' => 'Resultados',
    'sidebar_certificates' => 'Certificados',
    'sidebar_account' => 'Cuenta',
    'sidebar_profile' => 'Perfil',
    'sidebar_transactions' => 'Historial de Transacciones',
    'sidebar_settings' => 'Configuración',
    'sidebar_support' => 'Soporte',
    'sidebar_tickets' => 'Tickets de Soporte',
    'sidebar_logout' => 'Cerrar Sesión',

    'topbar_search_placeholder' => 'Buscar exámenes, resultados...',
    'topbar_go_website' => 'Ir al Sitio Web',
    'topbar_select_language' => 'Seleccionar Idioma',
    'topbar_select_language_caps' => 'SELECCIONAR IDIOMA',
    'topbar_notifications' => 'Notificaciones',
    'topbar_mark_read' => 'Marcar todo como leído',
    'topbar_no_notifications' => 'No hay notificaciones recientes',
    'topbar_view_activity' => 'Ver Toda la Actividad',
    'topbar_default_student' => 'Estudiante',
    'topbar_my_profile' => 'Mi Perfil',
    'topbar_menu_settings' => 'Configuración',
    'topbar_logout' => 'Cerrar Sesión',

    // resources/lang/es/frontend.php

'end_exam' => 'Finalizar Examen',
'question_label' => 'Pregunta',
'of_label' => 'de',
'loading' => 'Cargando...',
'previous_btn' => 'Anterior',
'next_btn' => 'Siguiente',
'mark_review' => 'Marcar para Revisión',
'submit_finish' => 'Enviar y Finalizar',
'auto_save_msg' => 'Todas las respuestas guardadas automáticamente.',
'progress_overview' => 'Resumen del Progreso',
'stat_answered' => 'Respondidas',
'stat_marked' => 'Marcadas para Revisión',
'stat_remaining' => 'Restantes',
'question_navigator' => 'Navegador de Preguntas',
'confirm_submission' => 'Confirmar Envío',

// --- JavaScript Strings ---

'error_no_questions' => 'No se encontraron preguntas. Por favor, contacte a soporte.',
'question_missing_text' => 'Texto de la pregunta faltante.',
'no_options_available' => 'No hay opciones disponibles.',
'status_saving' => 'Guardando...',
'status_saved_success' => 'Todas las respuestas guardadas automáticamente.',
'status_save_error' => 'Conexión perdida. Intentando reconectar...',
'timer_time_up_title' => '¡Se acabó el tiempo!',
'timer_time_up_text' => 'Su examen se enviará automáticamente.',
'validation_action_required_title' => 'Acción Requerida',
'validation_answer_or_mark' => 'Por favor, seleccione una respuesta O marque esta pregunta para revisión antes de continuar.',
'validation_understood' => 'Entendido',
'mark_unmarked_warning' => 'Pregunta desmarcada. Debe responderla para continuar más tarde.',
'mark_marked_info' => 'Marcada para revisión.',
'submission_pending_reviews_title' => 'Revisiones Pendientes',
'submission_pending_reviews_text' => 'Tiene {count} pregunta(s) marcada(s) para revisión.',
'submission_submit_anyway' => 'Enviar de Todos Modos',
'submission_review_questions' => 'Revisar Preguntas',
'submission_finish_title' => '¿Finalizar Examen?',
'submission_finish_text' => 'No podrá cambiar sus respuestas después del envío.',
'submission_yes_submit' => 'Sí, ¡Enviar!',
];