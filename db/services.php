<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External functions and service declaration for Datacurso Provider
 *
 * Documentation: {@link https://moodledev.io/docs/apis/subsystems/external/description}
 *
 * @package    aiprovider_datacurso
 * @category   webservice
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'aiprovider_datacurso_get_tokens_saldo' => [
        'classname'   => 'aiprovider_datacurso\external\get_tokens_saldo',
        'methodname'  => 'execute',
        'classpath'   => '',
        'description' => 'Obtiene el saldo actual de tokens desde el API externo',
        'type'        => 'read',
        'ajax'        => true,
    ],
    'aiprovider_datacurso_get_consumption_history' => [
        'classname'   => 'aiprovider_datacurso\external\get_consumption_history',
        'methodname'  => 'execute',
        'classpath'   => '',
        'description' => 'Obtiene el historial de consumos de tokens desde la API externa',
        'type'        => 'read',
        'ajax'        => true,
    ],
    'aiprovider_datacurso_webservice_get_status' => [
        'classname'   => 'aiprovider_datacurso\\external\\webservice_config_api',
        'methodname'  => 'get_status',
        'classpath'   => '',
        'description' => 'Get current automatic webservice configuration status',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'moodle/site:config',
    ],
    'aiprovider_datacurso_webservice_setup' => [
        'classname'   => 'aiprovider_datacurso\\external\\webservice_config_api',
        'methodname'  => 'setup',
        'classpath'   => '',
        'description' => 'Run automatic setup: enable WS, user/role, service, token',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'moodle/site:config',
    ],
    'aiprovider_datacurso_webservice_regenerate_token' => [
        'classname'   => 'aiprovider_datacurso\\external\\webservice_config_api',
        'methodname'  => 'regenerate_token',
        'classpath'   => '',
        'description' => 'Regenerate the permanent token for the service user',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'moodle/site:config',
    ],
    'aiprovider_datacurso_webservice_send_registration' => [
        'classname'   => 'aiprovider_datacurso\\external\\webservice_config_api',
        'methodname'  => 'send_registration',
        'classpath'   => '',
        'description' => 'Send registration payload to external endpoint using bearer',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'moodle/site:config',
    ],
];
