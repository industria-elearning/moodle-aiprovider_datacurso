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

$string['apikey'] = 'Clave API';
$string['apikey_desc'] = 'Introduce la clave API de tu servicio Datacurso para conectar la IA.';
$string['enableglobalratelimit'] = 'Habilitar límite global';
$string['enableglobalratelimit_desc'] = 'Si está habilitado, se aplicará un límite de peticiones por hora para todos los usuarios.';
$string['enableuserratelimit'] = 'Habilitar límite por usuario';
$string['enableuserratelimit_desc'] = 'Si está habilitado, cada usuario tendrá un límite de peticiones por hora.';
$string['globalratelimit'] = 'Límite global de peticiones';
$string['globalratelimit_desc'] = 'Número máximo de peticiones permitidas por hora para todo el sistema.';
$string['orgid'] = 'ID de organización';
$string['orgid_desc'] = 'Introduce el identificador de tu organización en el servicio Datacurso.';
$string['pluginname'] = 'Datacurso Provider';
$string['userratelimit'] = 'Límite de peticiones por usuario';
$string['userratelimit_desc'] = 'Número máximo de peticiones permitidas por hora para cada usuario individual.';

$string['apiurl'] = 'URL base de la API';
$string['apiurl_desc'] = 'Introduce la URL base del servicio para conectarse a la API de Datacurso.';
$string['licensekey'] = 'Clave de licencia';
$string['licensekey_desc'] = 'Introduce la clave de licencia proporcionada (ejemplo: LICENSE_KEY_CLIENTE).';
$string['tokenthreshold'] = 'Umbral de tokens';
$string['tokenthreshold_desc'] = 'Número de tokens a partir del cual se mostrará una notificación para conseguir más.';


$string['link_consumptionhistory'] = 'Historial de consumo de tokens';
$string['link_generalreport'] = 'Reporte general';

$string['id'] = 'ID';
$string['userid'] = 'Usuario';
$string['action'] = 'Acción';
$string['tokensused'] = 'Tokens usados';
$string['remainingtokens'] = 'Saldo restante';
$string['nodata'] = 'No se encontró alguna información';

$string['link_report_statistic'] = 'Reporte general';
$string['link_listplugings'] = 'Lista de plugins Datacurso';
$string['tokens_available'] = 'Tokens disponibles';
$string['total_consumed'] = 'Total consumido';
$string['chart_tokens_by_day'] = 'Consumo de tokens por día';
$string['chart_actions'] = 'Distribución de tokens por servicio';

$string['workplace'] = '¿Es Moodle Workplace?';
$string['workplace_desc'] = 'Define si se debe enviar el header X-Workplace con valor true (Workplace) o false (Moodle estándar).';
$string['chart_tokens_by_month'] = 'Cantidad de tokens consumidos por mes';