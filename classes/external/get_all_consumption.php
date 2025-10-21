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
use external_single_structure;
use external_multiple_structure;
use external_value;
use aiprovider_datacurso\httpclient\datacurso_api;

/**
 * Web service to get all consumption history from the Datacurso API.
 *
 * @package    aiprovider_datacurso
 * @category   external
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_all_consumption extends external_api {
    /**
     * Parameters definition.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'servicio' => new external_value(PARAM_TEXT, 'Service filter', VALUE_OPTIONAL),
            'accion' => new external_value(PARAM_TEXT, 'Action filter', VALUE_OPTIONAL),
            'fechadesde' => new external_value(PARAM_RAW, 'Start date (YYYY-MM-DD)', VALUE_OPTIONAL),
            'fechahasta' => new external_value(PARAM_RAW, 'End date (YYYY-MM-DD)', VALUE_OPTIONAL),
        ]);
    }

    /**
     * Execute the WS to fetch all consumptions.
     *
     * @param string|null $servicio Service filter.
     * @param string|null $accion Action filter.
     * @param string|null $fechadesde Start date (YYYY-MM-DD).
     * @param string|null $fechahasta End date (YYYY-MM-DD).
     * @return array The result including total and consumptions list.
     */
    public static function execute($servicio = null, $accion = null, $fechadesde = null, $fechahasta = null) {
        $client = new datacurso_api();

        $params = [
            'page' => 1,
            'limit' => 1,
        ];

        if (!empty($servicio) && $servicio !== 'all') {
            $params['servicio'] = $servicio;
        }
        if (!empty($accion) && $accion !== 'all') {
            $params['accion'] = $accion;
        }
        if (!empty($fechadesde)) {
            $params['fecha_desde'] = $fechadesde;
        }
        if (!empty($fechahasta)) {
            $params['fecha_hasta'] = $fechahasta;
        }

        $firstresponse = $client->get('/tokens/historial-consumos', $params);

        if (empty($firstresponse) || $firstresponse['status'] !== 'success') {
            return [
                'status' => 'error',
                'message' => 'No se pudo obtener la información inicial.',
            ];
        }

        // Step 2. Get total records and setup pagination.
        $pagination = $firstresponse['paginacion'] ?? [];
        $totalrecords = (int)($pagination['total'] ?? 0);
        $limitperpage = 50; // Tamaño por página.
        $totalpages = ceil($totalrecords / $limitperpage);

        $allconsumptions = [];

        // Step 3. Fetch all pages.
        for ($page = 1; $page <= $totalpages; $page++) {
            $params['page'] = $page;
            $params['limit'] = $limitperpage;

            $response = $client->get('/tokens/historial-consumos', $params);

            if (empty($response) || $response['status'] !== 'success') {
                continue;
            }

            $userdata = $response['usuarios'][0] ?? null;
            $consumptions = $userdata['consumos'] ?? [];

            $actions = \aiprovider_datacurso\provider::get_actions();
            $actionmap = [];
            foreach ($actions as $a) {
                    $actionmap[$a['id']] = $a['name'];
            }

            foreach ($consumptions as $item) {
                $rawaction = (string)($item['accion'] ?? '');
                $translatedaction = $actionmap[$rawaction] ?? $rawaction;
                $allconsumptions[] = [
                    'id_consumption' => (int)($item['id_consumo'] ?? 0),
                    'action' => $translatedaction,
                    'id_service' => (string)($item['id_servicio'] ?? ''),
                    'userid' => isset($item['userid']) ? (int)$item['userid'] : null,
                    'cant_tokens' => (int)($item['cantidad_tokens'] ?? 0),
                    'balance' => (int)($item['saldo_restante'] ?? 0),
                    'date' => (string)($item['fecha'] ?? ''),
                    'created_at' => (string)($item['created_at'] ?? ''),
                ];
            }
        }

        return [
            'status' => 'success',
            'total' => count($allconsumptions),
            'consumption' => $allconsumptions,
        ];
    }

    /**
     * Returns structure.
     *
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Estado de la petición (success/error)'),
            'total' => new external_value(PARAM_INT, 'Total de consumos encontrados'),
            'consumption' => new external_multiple_structure(
                new external_single_structure([
                    'id_consumption' => new external_value(PARAM_INT, 'ID del consumo'),
                    'action' => new external_value(PARAM_TEXT, 'Acción realizada'),
                    'id_service' => new external_value(PARAM_TEXT, 'Servicio usado'),
                    'userid' => new external_value(PARAM_INT, 'ID del usuario en Moodle', VALUE_OPTIONAL),
                    'cant_tokens' => new external_value(PARAM_INT, 'Cantidad de tokens consumidos'),
                    'balance' => new external_value(PARAM_INT, 'Saldo restante del usuario'),
                    'date' => new external_value(PARAM_RAW, 'Fecha del consumo (YYYY-MM-DD)'),
                    'created_at' => new external_value(PARAM_RAW, 'Fecha de creación del registro en la API'),
                ])
            ),
        ]);
    }
}
