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

use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use aiprovider_datacurso\httpclient\datacurso_api;

/**
 * Web service to retrieve token consumption history.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_consumption_history extends \external_api {

    /**
     * Define parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            // All optional parameters must be declared with VALUE_DEFAULT to avoid reordering by Moodle.
            'page' => new external_value(PARAM_INT, 'Page number', VALUE_DEFAULT, 1),
            'limit' => new external_value(PARAM_INT, 'Results per page', VALUE_DEFAULT, 10),
            'userid' => new external_value(PARAM_INT, 'User ID', VALUE_DEFAULT, 0),
            'servicio' => new external_value(PARAM_TEXT, 'Service ID', VALUE_DEFAULT, ''),
            'accion' => new external_value(PARAM_TEXT, 'Action name', VALUE_DEFAULT, ''),
            'fechadesde' => new external_value(PARAM_TEXT, 'Start date', VALUE_DEFAULT, ''),
            'fechahasta' => new external_value(PARAM_TEXT, 'End date', VALUE_DEFAULT, ''),
        ]);
    }

    /**
     * Execute function.
     *
     * @param int $page
     * @param int $limit
     * @param int|null $userid
     * @param string|null $servicio
     * @param string|null $accion
     * @param string|null $fechadesde
     * @param string|null $fechahasta
     * @return array
     */
    public static function execute($page = 1, $limit = 10, $userid = null, $servicio = null,
        $accion = null, $fechadesde = null, $fechahasta = null) {

        global $USER;

        // Ensure page and limit have valid values.
        $page = (empty($page) || $page < 1) ? 1 : (int)$page;
        $limit = (empty($limit) || $limit < 1) ? 10 : (int)$limit;

        $client = new datacurso_api();

        // Base request parameters.
        $params = [
            'page' => $page,
            'limit' => $limit,
        ];

        if (!empty($userid)) {
            $params['userid'] = $userid;
        }

        if (!empty($servicio)) {
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

        try {
            // Call to external API endpoint.
            $response = $client->get('/tokens/historial-consumos', $params);

            if (isset($response['status']) && $response['status'] === 'success') {
                $usuarios = $response['usuarios'] ?? [];
                $consumos = [];

                foreach ($usuarios as $usuario) {
                    if (!empty($usuario['consumos'])) {
                        foreach ($usuario['consumos'] as $consumo) {
                            $consumos[] = [
                                'id_consumption' => $consumo['id_consumo'] ?? 0,
                                'userid' => $consumo['userid'] ?? 0,
                                'action' => $consumo['accion'] ?? '',
                                'id_service' => $consumo['id_servicio'] ?? '',
                                'cant_tokens' => $consumo['cantidad_tokens'] ?? 0,
                                'balance' => $consumo['saldo_restante'] ?? 0,
                                'date' => $consumo['fecha'] ?? '',
                            ];
                        }
                    }
                }

                // Adapt pagination keys according to external API.
                $pagination = $response['pagination'] ?? $response['paginacion'] ?? [];

                return [
                    'status' => 'success',
                    'consumption' => $consumos,
                    'pagination' => [
                        'current_page' => $pagination['current_page'] ?? $pagination['pagina_actual'] ?? $page,
                        'limit' => $pagination['limit'] ?? $pagination['limite'] ?? $limit,
                        'total' => $pagination['total'] ?? count($consumos),
                        'total_pages' => $pagination['total_pages'] ?? $pagination['total_paginas'] ?? 1,
                    ],
                ];
            }

            // No valid results found.
            return [
                'status' => 'error',
                'message' => 'No se encontraron datos de consumo',
                'consumption' => [],
            ];

        } catch (\Exception $e) {
            // Connection or response error.
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'consumption' => [],
            ];
        }
    }

    /**
     * Define return structure.
     *
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Operation status'),
            'message' => new external_value(PARAM_TEXT, 'Message', VALUE_OPTIONAL),
            'consumption' => new external_multiple_structure(
                new external_single_structure([
                    'id_consumption' => new external_value(PARAM_INT, 'Consumption ID'),
                    'userid' => new external_value(PARAM_INT, 'User ID'),
                    'action' => new external_value(PARAM_TEXT, 'Action performed'),
                    'id_service' => new external_value(PARAM_TEXT, 'Service identifier'),
                    'cant_tokens' => new external_value(PARAM_INT, 'Tokens used'),
                    'balance' => new external_value(PARAM_INT, 'Remaining balance'),
                    'date' => new external_value(PARAM_TEXT, 'Consumption date'),
                ])
            ),
            'pagination' => new external_single_structure([
                'current_page' => new external_value(PARAM_INT, 'Current page'),
                'limit' => new external_value(PARAM_INT, 'Limit per page'),
                'total' => new external_value(PARAM_INT, 'Total records'),
                'total_pages' => new external_value(PARAM_INT, 'Total pages'),
            ], 'Pagination information', VALUE_OPTIONAL),
        ]);
    }
}
