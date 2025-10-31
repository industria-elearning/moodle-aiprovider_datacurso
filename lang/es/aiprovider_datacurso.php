<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     aiprovider_datacurso
 * @category    string
 * @copyright   Josue <josue@datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['action'] = 'Acción';
$string['action:generate_image:endpoint'] = 'Punto final de API';
$string['action:generate_image:endpoint_desc'] = 'El punto final para generar imágenes';
$string['action:generate_text:endpoint'] = 'Punto final de API';
$string['action:generate_text:endpoint_desc'] = 'El punto final para generar texto';
$string['action:generate_text:instruction'] = 'Instrucción del sistema';
$string['action:generate_text:instruction_desc'] = 'Esta instrucción se envía al modelo de IA junto con la solicitud del usuario. No se recomienda editar esta instrucción a menos que sea absolutamente necesario.';
$string['action:summarise_text:endpoint'] = 'Punto final de API';
$string['action:summarise_text:endpoint_desc'] = 'El punto final para generar texto';
$string['action:summarise_text:instruction'] = 'Instrucción del sistema';
$string['action:summarise_text:instruction_desc'] = 'Esta instrucción se envía al modelo de IA junto con la solicitud del usuario. No se recomienda editar esta instrucción a menos que sea absolutamente necesario.';
$string['all'] = 'Todos';
$string['alt_datacurso_icon'] = 'Icono de Datacurso';
$string['apikey'] = 'Clave de API';
$string['apikey_desc'] = 'Ingrese la clave de API de su servicio Datacurso para conectar la IA.';
$string['apiurl'] = 'URL base de la API';
$string['apiurl_desc'] = 'Ingrese la URL base del servicio para conectarse a la API de Datacurso.';
$string['assigned'] = 'Asignado';
$string['chart_actions'] = 'Distribución de créditos por servicio';
$string['chart_tokens_by_day'] = 'Consumo de créditos por día';
$string['chart_tokens_by_month'] = 'Número de créditos consumidos por mes';
$string['configured'] = 'Configurado';
$string['contextwstoken'] = 'Token de servicio web para contexto del curso';
$string['contextwstoken_desc'] = 'Token utilizado por la IA para recuperar información del curso (contexto). Almacenado de forma segura. Crear/gestionar tokens en Administración del sitio > Servidor > Servicios web > Gestionar tokens.';
$string['created'] = 'Creado';
$string['curlerror'] = 'Error cURL de la API de Datacurso: {$a}';
$string['datacurso:manage'] = 'Gestionar configuración del proveedor de IA';
$string['datacurso:use'] = 'Usar servicios de IA de Datacurso';
$string['datacurso:viewreports'] = 'Ver informes de uso de IA';
$string['days'] = 'Días';
$string['description'] = 'Descripción';
$string['descriptionpagelistplugins'] = 'Aquí puede encontrar la lista de plugins compatibles con el proveedor Datacurso';
$string['disabled'] = 'Deshabilitado';
$string['emptyprompt'] = 'Indicación vacía';
$string['emptyresponse'] = 'Sin respuesta de la API de Datacurso.';
$string['enabled'] = 'Habilitado';
$string['enableglobalratelimit'] = 'Habilitar límite global';
$string['enableglobalratelimit_desc'] = 'Si está habilitado, se aplicará un límite de solicitudes global por hora para todos los usuarios.';
$string['enableuserratelimit'] = 'Habilitar límite por usuario';
$string['enableuserratelimit_desc'] = 'Si está habilitado, cada usuario tendrá un límite de solicitudes por hora.';
$string['error_ratelimit_exceeded'] = 'Se ha superado el límite. Inténtalo de nuevo más tarde.';
$string['errorgetbalancecredits'] = 'No se pudo recuperar el saldo de créditos de la API externa';
$string['errorinitinformation'] = 'No se pudo obtener la información inicial.';
$string['exists'] = 'Existe';
$string['forbidden'] = 'No tiene permiso para realizar esta acción con la licencia actual. Por favor, verifique su licencia y créditos disponibles en <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gestionar Créditos</a> en la Tienda Datacurso.';
$string['generate_activitie'] = 'Generar actividad o recurso con IA';
$string['generate_ai_reinforcement_activity'] = 'Crear actividad de refuerzo con IA';
$string['generate_analysis_comments'] = 'Generar análisis de calificación de una actividad/recurso con IA';
$string['generate_analysis_course'] = 'Generar análisis de calificación del curso con IA';
$string['generate_analysis_general'] = 'Generar análisis de calificación general con IA';
$string['generate_analysis_story_student'] = 'Generar análisis de historia del estudiante con IA';
$string['generate_assign_answer'] = 'Generar revisión de tarea con IA';
$string['generate_certificate_answer'] = 'Generar mensaje de certificado con IA';
$string['generate_creation_course'] = 'Crear curso completo con IA';
$string['generate_forum_chat'] = 'Generar respuesta de foro con IA';
$string['generate_image'] = 'Generar imagen con IA';
$string['generate_plan_course'] = 'Generar plan de creación de curso con IA';
$string['generate_summary'] = 'Generar resumen con IA';
$string['generate_text'] = 'Generar texto con IA';
$string['globalratelimit'] = 'Límite de solicitudes global';
$string['globalratelimit_desc'] = 'Número máximo de solicitudes permitidas por hora para todo el sistema.';
$string['goto'] = 'Ir al Informe';
$string['gotopage'] = 'Ir a la página';
$string['hours'] = 'Horas';
$string['minute'] = 'minuto';
$string['minutes'] = 'Minutos';
$string['second'] = 'segundo';
$string['seconds'] = 'Segundos';
$string['httperror'] = 'Error inesperado al procesar su solicitud (HTTP {$a}). Por favor, inténtelo de nuevo más tarde. Si el problema persiste, contacte a su administrador del sitio.';
$string['id'] = 'ID';
$string['installed'] = 'Instalado';
$string['invalidlicensekey'] = 'La clave de licencia ha caducado o no es válida. Por favor, vaya a <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gestionar Créditos</a> en la Tienda Datacurso para renovar o comprar una nueva licencia.';
$string['json_encode_failed'] = 'Falló la codificación JSON';
$string['jsondecodeerror'] = 'Error al procesar la respuesta de la API de Datacurso: {$a}';
$string['last_sent'] = 'Último envío';
$string['license_not_allowed'] = 'Su licencia no permite realizar esta solicitud. Por favor, gestione sus licencias y créditos en <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gestionar Créditos</a> en la Tienda Datacurso.';
$string['licensekey'] = 'Clave de licencia';
$string['licensekey_desc'] = 'Ingrese la clave de licencia del área de clientes de la Tienda Datacurso.';
$string['link_consumptionhistory'] = 'Historial de consumo de créditos';
$string['link_generalreport'] = 'Informe general';
$string['link_generalreport_datacurso'] = 'Informe general Datacurso IA';
$string['link_listplugings'] = 'Lista de plugins Datacurso';
$string['link_plugin'] = 'Enlace';
$string['link_report_statistic'] = 'Informe de estadísticas generales';
$string['link_webservice_config'] = 'Configuración de servicio web Datacurso';
$string['live_log'] = 'Registro en vivo';
$string['message_no_there_plugins'] = 'No hay plugins disponibles';
$string['missing'] = 'Falta';
$string['months'] = 'Meses';
$string['needs_repair'] = 'Necesita reparación';
$string['nodata'] = 'No se encontró información';
$string['not_assigned'] = 'No asignado';
$string['not_configured'] = 'No configurado';
$string['not_created'] = 'No creado';
$string['notenoughtokens'] = 'Créditos de IA insuficientes. Por favor, visite <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gestionar Créditos</a> en la Tienda Datacurso para asignar o comprar más créditos. O contacte a su administrador.';
$string['of'] = 'de';
$string['orgid'] = 'ID de organización';
$string['orgid_desc'] = 'Ingrese el identificador de su organización en el servicio Datacurso.';
$string['pageinfo'] = 'Página {$a->current} de {$a->totalpages} ({$a->total} registros)';
$string['pending'] = 'Pendiente';
$string['plugin'] = 'Plugin';
$string['plugindesc_assign_ai'] = 'Revisar tareas con asistencia de IA.';
$string['plugindesc_coursegen'] = 'Crear cursos completos, actividades y recursos con IA.';
$string['plugindesc_datacurso_ratings'] = 'Permite a los estudiantes calificar actividades y recursos; los profesores y administradores pueden generar análisis de cursos basados en IA.';
$string['plugindesc_dttutor'] = 'Chatear con un tutor de IA dentro del curso.';
$string['plugindesc_forum_ai'] = 'Extender foros con análisis impulsado por IA para generar resúmenes automáticamente.';
$string['plugindesc_lifestory'] = 'Informe y análisis impulsado por IA del progreso académico del estudiante.';
$string['plugindesc_smartrules'] = 'Crear actividades automatizadas basadas en condiciones previas de los estudiantes.';
$string['plugindesc_socialcert'] = 'Generar automáticamente certificados personalizados al completar el curso.';
$string['pluginname'] = 'Proveedor de IA Datacurso';
$string['pluginname_assign_ai'] = 'Tareas IA';
$string['pluginname_coursegen'] = 'Creador de Cursos IA';
$string['pluginname_datacurso_ratings'] = 'Calificación de Actividades IA';
$string['pluginname_dttutor'] = 'Tutor IA';
$string['pluginname_forum_ai'] = 'Foro IA';
$string['pluginname_lifestory'] = 'Historia de Vida del Estudiante IA';
$string['pluginname_smartrules'] = 'SmartRules IA';
$string['pluginname_socialcert'] = 'Compartir Certificado IA';
$string['privacy:metadata'] = 'El plugin Proveedor de IA Datacurso no almacena ningún dato personal localmente. Todos los datos son procesados por los servicios externos de IA de Datacurso.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Cargas útiles de solicitudes de IA de Datacurso enviadas al servicio externo.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Estos datos se envían a Datacurso IA para cumplir con la acción solicitada.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Número total de imágenes solicitadas del servicio de IA.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'El texto de indicación suministrado al servicio de IA.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'El ID de usuario de Moodle que realiza la solicitud de IA.';
$string['ratelimit_enable'] = 'Habilitar límite';
$string['ratelimit_enable_desc'] = 'Si está habilitado, se aplicará el límite de créditos por usuario para este plugin.';
$string['ratelimit_local_coursegen_activitycreators'] = 'Usuarios autorizados para crear actividades';
$string['ratelimit_local_coursegen_activitycreators_desc'] = 'Selecciona a los usuarios que podrán generar actividades o recursos con IA cuando este servicio esté habilitado.';
$string['ratelimit_local_coursegen_coursecreators'] = 'Usuarios autorizados para crear cursos';
$string['ratelimit_local_coursegen_coursecreators_desc'] = 'Selecciona a los usuarios que podrán crear cursos completos con IA cuando este servicio esté habilitado.';
$string['ratelimit_limit'] = 'Créditos por ventana';
$string['ratelimit_limit_desc'] = 'Número máximo de créditos que un usuario puede consumir dentro de la ventana de tiempo seleccionada.';
$string['ratelimit_window'] = 'Ventana de tiempo';
$string['ratelimit_window_desc'] = 'Seleccione la duración y unidad para la ventana del límite.';
$string['ratelimits_heading'] = 'Límites por plugin';
$string['ratelimits_heading_desc'] = 'Configura límites por usuario para cada plugin que use el proveedor Datacurso.';
$string['read_context_course'] = 'Leer contexto para creación de curso con IA';
$string['read_context_course_model'] = 'Cargar modelo académico para creación de curso con IA';
$string['registers'] = 'Registros';
$string['registration_error'] = 'Último error';
$string['registration_last'] = 'Registro';
$string['registration_lastsent'] = 'Último envío';
$string['registration_notverified'] = 'Registro no verificado';
$string['registration_status'] = 'Último estado';
$string['registration_verified'] = 'Registro verificado';
$string['registrationapibearer'] = 'Token bearer de registro';
$string['registrationapibearer_desc'] = 'Token bearer utilizado para autenticar la solicitud de registro.';
$string['registrationapiurl'] = 'URL del punto final de registro';
$string['registrationapiurl_desc'] = 'Punto final para recibir la carga útil de registro del sitio. Predeterminado: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API de registro';
$string['remainingtokens'] = 'Saldo restante';
$string['responseinvalidai'] = 'Respuesta inválida del servicio de IA.';
$string['responseinvalidaimage'] = 'Respuesta inválida del servicio de IA (sin imagen).';
$string['responseinvalidaimagecreate'] = 'No se pudo crear el archivo de imagen.';
$string['rest_enabled'] = 'Protocolo REST habilitado';
$string['service'] = 'Servicio';
$string['showrows'] = 'Mostrar filas';
$string['tokens'] = 'Créditos';
$string['tokens_available'] = 'Créditos disponibles';
$string['tokensconsumed'] = 'Créditos consumidos';
$string['tokensconsumedday'] = 'Créditos consumidos por día';
$string['tokensconsumedmonth'] = 'Créditos consumidos por mes';
$string['tokensnotsufficient'] = 'Créditos de IA insuficientes. Saldo actual: {$a->current}. Mínimo requerido: {$a->required}. Por favor, visite <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gestionar Créditos</a> en la Tienda Datacurso para asignar o comprar más créditos. O contacte a su administrador.';
$string['tokensused'] = 'Créditos utilizados';
$string['tokenthreshold'] = 'Umbral de créditos';
$string['tokenthreshold_desc'] = 'Número de créditos a partir del cual se mostrará una notificación para comprar más.';
$string['total_consumed'] = 'Créditos consumidos';
$string['userid'] = 'Usuario';
$string['userratelimit'] = 'Límite de solicitudes por usuario';
$string['userratelimit_desc'] = 'Número máximo de solicitudes permitidas por hora para cada usuario individual.';
$string['verified'] = 'Verificado';
$string['webserviceconfig_current'] = 'Configuración actual';
$string['webserviceconfig_desc'] = 'Configura automáticamente un servicio web dedicado para el servicio de IA Datacurso, permitiéndole extraer de forma segura información de la plataforma como datos básicos de usuarios, cursos y actividades para una mejor contextualización de la IA. Esta configuración crea un usuario de servicio, asigna el rol necesario, configura el servicio externo, genera un token seguro y habilita el protocolo REST con un clic. Nota: El valor del token no se muestra por razones de seguridad.';
$string['webserviceconfig_heading'] = 'Configuración automática de servicio web';
$string['webserviceconfig_site'] = 'Información del sitio';
$string['webserviceconfig_status'] = 'Estado';
$string['webserviceconfig_title'] = 'Configuración de servicio web Datacurso';
$string['weeks'] = 'Semanas';
$string['workplace'] = '¿Es este Moodle Workplace?';
$string['workplace_desc'] = 'Define si se debe enviar el encabezado X-Workplace con valor true (Workplace) o false (Moodle estándar).';
$string['ws_activity'] = 'Registro de actividad';
$string['ws_btn_regenerate'] = 'Regenerar token';
$string['ws_btn_retry'] = 'Reintentar configuración';
$string['ws_btn_setup'] = 'Configurar servicio web';
$string['ws_enabled'] = 'Servicios web habilitados';
$string['ws_error_missing_setup'] = 'Servicio o usuario no encontrado. Ejecute la configuración primero.';
$string['ws_error_missing_token'] = 'Token no encontrado. Genérelo primero.';
$string['ws_error_regenerate_token'] = 'Error al regenerar el token.';
$string['ws_error_registration'] = 'Error al registrar el token del servicio web.';
$string['ws_error_setup'] = 'Error al configurar el servicio web.';
$string['ws_role'] = 'Rol del servicio';
$string['ws_role_desc'] = 'Rol para el servicio web de Datacurso';
$string['ws_role_name'] = 'Servicio web de Datacurso';
$string['ws_service'] = 'Servicio externo';
$string['ws_service_name'] = 'Servicio web de Datacurso';
$string['ws_step_enableauth'] = 'Habilitando plugin de autenticación de servicios web…';
$string['ws_step_enablerest'] = 'Habilitando protocolo REST…';
$string['ws_step_enablews'] = 'Habilitando servicios web del sitio…';
$string['ws_step_registration_sent'] = 'Solicitud de registro enviada.';
$string['ws_step_role_assign'] = 'Asignando rol al usuario del servicio…';
$string['ws_step_role_caps'] = 'Estableciendo capacidades de rol requeridas…';
$string['ws_step_role_create'] = 'Creando rol "{$a}"…';
$string['ws_step_role_exists'] = 'El rol ya existe, usando ID {$a}…';
$string['ws_step_service_enable'] = 'Creando/Habilitando servicio externo…';
$string['ws_step_service_functions'] = 'Agregando funciones principales comunes al servicio…';
$string['ws_step_service_user'] = 'Autorizando usuario para el servicio…';
$string['ws_step_setup'] = 'Iniciando configuración…';
$string['ws_step_token_create'] = 'Asegurando que el token existe…';
$string['ws_step_token_generated'] = 'Token generado.';
$string['ws_step_token_regenerated'] = 'Token regenerado.';
$string['ws_step_token_regenerating'] = 'Regenerando token…';
$string['ws_step_token_retry'] = 'Reintentando configuración…';
$string['ws_step_user_check'] = 'Verificando si el usuario "{$a}" existe…';
$string['ws_step_user_create'] = 'Creando usuario del servicio "{$a}"…';
$string['ws_token_label'] = 'Token de Datacurso';
$string['ws_tokenexists'] = 'El token existe';
$string['ws_user'] = 'Usuario del servicio';
$string['ws_user_firstname'] = 'Datacurso';
$string['ws_user_lastname'] = 'Servicio';
$string['ws_userassigned'] = 'Rol asignado al usuario';
$string['years'] = 'Años';
