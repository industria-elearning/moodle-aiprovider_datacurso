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
$string['all'] = 'Todos';
$string['apikey'] = 'Clave API';
$string['apikey_desc'] = 'Ingrese la clave API de su servicio Datacurso para conectar la IA.';
$string['apiurl'] = 'URL base de la API';
$string['apiurl_desc'] = 'Ingrese la URL base del servicio para conectarse a la API de Datacurso.';
$string['assigned'] = 'Asignado';
$string['chart_actions'] = 'Distribución de tokens por servicio';
$string['chart_tokens_by_day'] = 'Consumo de tokens por día';
$string['chart_tokens_by_month'] = 'Número de tokens consumidos por mes';
$string['configured'] = 'Configurado';
$string['contextwstoken'] = 'Token de servicio web para contexto del curso';
$string['contextwstoken_desc'] = 'Token utilizado por la IA para recuperar información del curso (contexto). Almacenado de forma segura. Crear/gestionar tokens en Administración del sitio > Servidor > Servicios web > Gestionar tokens.';
$string['created'] = 'Creado';
$string['datacurso:manage'] = 'Gestionar configuración del proveedor de IA';
$string['datacurso:use'] = 'Usar servicios de IA Datacurso';
$string['datacurso:viewreports'] = 'Ver informes de uso de IA';
$string['description'] = 'Descripción';
$string['descriptionpagelistplugins'] = 'Aquí encontramos la lista de plugins compatibles con el proveedor Datacurso';
$string['disabled'] = 'Deshabilitado';
$string['enabled'] = 'Habilitado';
$string['enableglobalratelimit'] = 'Habilitar límite global';
$string['enableglobalratelimit_desc'] = 'Si está habilitado, se aplicará un límite global de solicitudes por hora para todos los usuarios.';
$string['enableuserratelimit'] = 'Habilitar límite por usuario';
$string['enableuserratelimit_desc'] = 'Si está habilitado, cada usuario tendrá un límite de solicitudes por hora.';
$string['exists'] = 'Existe';
$string['generate_activitie'] = 'Generar actividad o recurso con IA';
$string['generate_analysis_comments'] = 'Generar análisis del rating de una actividad/recurso con IA';
$string['generate_analysis_course'] = 'Generar análisis del rating del curso con IA';
$string['generate_analysis_general'] = 'Generar análisis del rating general con IA';
$string['generate_assign_answer'] = 'Generar revisión de tarea con IA';
$string['generate_certificate_answer'] = 'Generar mensaje para certificado con IA';
$string['generate_creation_course'] = 'Crear curso completo con IA';
$string['generate_forum_chat'] = 'Generar respuesta de foro con IA';
$string['generate_image'] = 'Generar imagen con IA';
$string['generate_plan_course'] = 'Generar plan de creación del curso con IA';
$string['generate_summary'] = 'Generar resumen con IA';
$string['generate_text'] = 'Generar texto con IA';
$string['globalratelimit'] = 'Límite global de solicitudes';
$string['globalratelimit_desc'] = 'Número máximo de solicitudes permitidas por hora para todo el sistema.';
$string['goto'] = 'Ir al Informe';
$string['gotopage'] = 'Ir a la página';
$string['id'] = 'ID';
$string['installed'] = 'Instalado';
$string['invalidlicensekey'] = 'Clave de licencia inválida';
$string['last_sent'] = 'Último enviado';
$string['licensekey'] = 'Clave de licencia';
$string['licensekey_desc'] = 'Ingrese la clave de licencia proporcionada (ejemplo: LICENSE_KEY_CLIENT).';
$string['link_consumptionhistory'] = 'Historial de consumo de tokens';
$string['link_generalreport'] = 'Informe general';
$string['link_generalreport_datacurso'] = 'Informe general Datacurso IA';
$string['link_listplugings'] = 'Lista de plugins Datacurso';
$string['link_plugin'] = 'Enlace';
$string['link_report_statistic'] = 'Informe de estadísticas generales';
$string['link_webservice_config'] = 'Configuración del servicio web Datacurso';
$string['live_log'] = 'Registro en vivo';
$string['message_no_there_plugins'] = 'No hay plugins disponibles';
$string['missing'] = 'Faltante';
$string['needs_repair'] = 'Necesita reparación';
$string['nodata'] = 'No se encontró información';
$string['not_assigned'] = 'No asignado';
$string['not_configured'] = 'No configurado';
$string['not_created'] = 'No creado';
$string['orgid'] = 'ID de Organización';
$string['orgid_desc'] = 'Ingrese el identificador de su organización en el servicio Datacurso.';
$string['pending'] = 'Pendiente';
$string['plugin'] = 'Plugin';
$string['pluginname'] = 'Proveedor Datacurso';
$string['privacy:metadata'] = 'El plugin Proveedor de IA Datacurso no almacena datos personales localmente. Todos los datos son procesados por servicios externos de IA Datacurso.';
$string['privacy:metadata:datacurso_ai_services'] = 'Datos enviados a los servicios de IA Datacurso para su procesamiento.';
$string['privacy:metadata:datacurso_ai_services:request_data'] = 'Los datos enviados al servicio de IA para su procesamiento.';
$string['privacy:metadata:datacurso_ai_services:response_data'] = 'La respuesta recibida del servicio de IA.';
$string['privacy:metadata:datacurso_ai_services:timestamp'] = 'Cuándo se realizó la solicitud de IA.';
$string['privacy:metadata:datacurso_ai_services:tokens_consumed'] = 'Número de tokens consumidos en la solicitud.';
$string['privacy:metadata:datacurso_ai_services:userid'] = 'El ID del usuario que realiza la solicitud de IA.';
$string['read_context_course'] = 'Leer contexto para la creación del curso con IA';
$string['read_context_course_model'] = 'Subir modelo académico para la creación del curso con IA';
$string['registration_error'] = 'Último error';
$string['registration_last'] = 'Registro';
$string['registration_lastsent'] = 'Último enviado';
$string['registration_notverified'] = 'Registro no verificado';
$string['registration_status'] = 'Último estado';
$string['registration_verified'] = 'Registro verificado';
$string['registrationapibearer'] = 'Token bearer de registro';
$string['registrationapibearer_desc'] = 'Token bearer utilizado para autenticar la solicitud de registro.';
$string['registrationapiurl'] = 'URL del endpoint de registro';
$string['registrationapiurl_desc'] = 'Endpoint para recibir la carga útil de registro del sitio. Predeterminado: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API de Registro';
$string['remainingtokens'] = 'Saldo restante';
$string['rest_enabled'] = 'Protocolo REST habilitado';
$string['service'] = 'Servicio';
$string['showrows'] = 'Mostrar filas';
$string['tokens_available'] = 'Tokens disponibles';
$string['tokensused'] = 'Tokens utilizados';
$string['tokenthreshold'] = 'Umbral de tokens';
$string['tokenthreshold_desc'] = 'Número de tokens a partir del cual se mostrará una notificación para comprar más.';
$string['total_consumed'] = 'Total consumido';
$string['userid'] = 'Usuario';
$string['userratelimit'] = 'Límite de solicitudes por usuario';
$string['userratelimit_desc'] = 'Número máximo de solicitudes permitidas por hora para cada usuario individual.';
$string['verified'] = 'Verificado';
$string['webserviceconfig_current'] = 'Configuración actual';
$string['webserviceconfig_desc'] = 'Configura automáticamente un servicio web dedicado para el servicio de IA Datacurso, permitiéndole extraer de forma segura información de la plataforma como datos básicos de usuarios, cursos y actividades para una mejor contextualización de la IA. Esta configuración crea un usuario de servicio, asigna el rol necesario, configura el servicio externo, genera un token seguro y habilita el protocolo REST en un solo clic. Nota: El valor del token no se muestra por razones de seguridad.';
$string['webserviceconfig_heading'] = 'Configuración automática del servicio web';
$string['webserviceconfig_site'] = 'Información del sitio';
$string['webserviceconfig_status'] = 'Estado';
$string['webserviceconfig_title'] = 'Configuración del servicio web Datacurso';
$string['workplace'] = '¿Es este Moodle Workplace?';
$string['workplace_desc'] = 'Define si el encabezado X-Workplace debe enviarse con valor true (Workplace) o false (Moodle Estándar).';
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
$string['ws_service'] = 'Servicio externo';
$string['ws_step_enableauth'] = 'Habilitando plugin de autenticación de servicios web…';
$string['ws_step_enablerest'] = 'Habilitando protocolo REST…';
$string['ws_step_enablews'] = 'Habilitando servicios web del sitio…';
$string['ws_step_registration_sent'] = 'Solicitud de registro enviada.';
$string['ws_step_role_assign'] = 'Asignando rol al usuario del servicio…';
$string['ws_step_role_caps'] = 'Configurando capacidades requeridas del rol…';
$string['ws_step_role_create'] = 'Creando rol "{$a}"…';
$string['ws_step_role_exists'] = 'El rol ya existe, usando ID {$a}…';
$string['ws_step_service_enable'] = 'Creando/Habilitando servicio externo…';
$string['ws_step_service_functions'] = 'Agregando funciones comunes del núcleo al servicio…';
$string['ws_step_service_user'] = 'Autorizando usuario para el servicio…';
$string['ws_step_setup'] = 'Iniciando configuración…';
$string['ws_step_token_create'] = 'Asegurando que el token exista…';
$string['ws_step_token_generated'] = 'Token generado.';
$string['ws_step_token_regenerated'] = 'Token regenerado.';
$string['ws_step_token_regenerating'] = 'Regenerando token…';
$string['ws_step_token_retry'] = 'Reintentando configuración…';
$string['ws_step_user_check'] = 'Verificando si el usuario "{$a}" existe…';
$string['ws_step_user_create'] = 'Creando usuario del servicio "{$a}"…';
$string['ws_tokenexists'] = 'El token existe';
$string['ws_user'] = 'Usuario del servicio';
$string['ws_userassigned'] = 'Rol asignado al usuario';
