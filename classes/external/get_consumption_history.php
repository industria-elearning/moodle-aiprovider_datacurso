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

namespace aiprovider_datacurso\external;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use aiprovider_datacurso\httpclient\datacurso_api;

/**
 * Web service to get the token consumption history.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_consumption_history extends external_api {

    /**
     * Input parameters (none in this case).
     */
    public static function execute_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * WS logic: calls the API client and returns the formatted response.
     */
    public static function execute() {
        $client = new datacurso_api();

        $response = $client->get('/tokens/historial-consumos');

        if (empty($response) || !isset($response['status'])) {
            return [
                'status' => 'error',
                'message' => 'Could not retrieve the consumption history from the external API',
                'consumos' => [],
            ];
        }

        return [
            'status' => $response['status'] ?? 'error',
            'message' => $response['message'] ?? '',
            'consumos' => $response['consumos'] ?? [],
        ];
    }

    /**
     * Output structure.
     */
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Request status (success/error)'),
            'message' => new external_value(PARAM_RAW, 'Additional API message or error'),
            'consumos' => new external_multiple_structure(
                new external_single_structure([
                    'id_consumo'      => new external_value(PARAM_INT, 'Unique consumption identifier'),
                    'id_usuario'      => new external_value(PARAM_INT, 'User ID'),
                    'id_key'          => new external_value(PARAM_INT, 'ID of the license used'),
                    'accion'          => new external_value(PARAM_TEXT, 'Action performed'),
                    'servicio'        => new external_value(PARAM_TEXT, 'Service used'),
                    'cantidad_tokens' => new external_value(PARAM_INT, 'Number of tokens consumed'),
                    'saldo_restante'  => new external_value(PARAM_INT, 'Remaining balance after consumption'),
                    'fecha'           => new external_value(PARAM_RAW, 'Consumption date in ISO 8601 format'),
                    'created_at'      => new external_value(PARAM_RAW, 'Creation date in ISO 8601 format'),
                    'updated_at'      => new external_value(PARAM_RAW, 'Update date in ISO 8601 format'),
                ])
            ),
        ]);
    }
}
