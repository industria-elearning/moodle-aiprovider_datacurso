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
 * Web service para obtener el historial de consumos de tokens.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_consumption_history extends external_api {

    /**
     * Parámetros de entrada (ninguno en este caso).
     */
    public static function execute_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * Lógica del WS: llama al cliente API y retorna la respuesta formateada.
     */
    public static function execute() {
        $client = new datacurso_api();

        $response = $client->get('/tokens/historial-consumos');

        if (empty($response) || !isset($response['status'])) {
            return [
                'status' => 'error',
                'message' => 'No se pudo obtener el historial de consumos desde la API externa',
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
     * Estructura de salida.
     */
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Estado de la petición (success/error)'),
            'message' => new external_value(PARAM_RAW, 'Mensaje adicional de la API o error'),
            'consumos' => new external_multiple_structure(
                new external_single_structure([
                    'id_consumo'      => new external_value(PARAM_INT, 'Identificador único de consumo'),
                    'id_usuario'      => new external_value(PARAM_INT, 'ID del usuario'),
                    'id_key'          => new external_value(PARAM_INT, 'ID de la licencia usada'),
                    'accion'          => new external_value(PARAM_TEXT, 'Acción realizada'),
                    'servicio'          => new external_value(PARAM_TEXT, 'Servicio utilizado'),
                    'cantidad_tokens' => new external_value(PARAM_INT, 'Cantidad de tokens consumidos'),
                    'saldo_restante'  => new external_value(PARAM_INT, 'Saldo restante después del consumo'),
                    'fecha'           => new external_value(PARAM_RAW, 'Fecha del consumo en formato ISO 8601'),
                    'created_at'      => new external_value(PARAM_RAW, 'Fecha de creación en formato ISO 8601'),
                    'updated_at'      => new external_value(PARAM_RAW, 'Fecha de actualización en formato ISO 8601'),
                ])
            ),
        ]);
    }
}
