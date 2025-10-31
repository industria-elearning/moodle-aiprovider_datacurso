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
 * External web service to fetch all Datacurso API consumption history.
 *
 * @package    aiprovider_datacurso
 * @category   external
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_all_consumption extends external_api {
    /**
     * Defines input parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'service' => new external_value(PARAM_TEXT, 'Service filter', VALUE_OPTIONAL),
            'action' => new external_value(PARAM_TEXT, 'Action filter', VALUE_OPTIONAL),
            'fromdate' => new external_value(PARAM_RAW, 'Start date (YYYY-MM-DD)', VALUE_OPTIONAL),
            'todate' => new external_value(PARAM_RAW, 'End date (YYYY-MM-DD)', VALUE_OPTIONAL),
        ]);
    }

    /**
     * Executes the web service to retrieve all consumption records.
     *
     * @param string|null $service Service filter.
     * @param string|null $action Action filter.
     * @param string|null $fromdate Start date (YYYY-MM-DD).
     * @param string|null $todate End date (YYYY-MM-DD).
     * @return array Returns the status, total, and list of consumption records.
     */
    public static function execute(
        ?string $service = null,
        ?string $action = null,
        ?string $fromdate = null,
        ?string $todate = null
    ): array {
        $params = self::validate_parameters(self::execute_parameters(), [
            'service' => $service,
            'action' => $action,
            'fromdate' => $fromdate,
            'todate' => $todate,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('aiprovider_datacurso/datacurso:viewreports', $context);

        $client = new datacurso_api();

        // Step 1. Lightweight request to get pagination info only.
        $queryparams = [
            'page' => 1,
            'limit' => 1, // Only to retrieve total count, not full dataset.
        ];

        // Apply filters only if needed.
        if (!empty($params['service']) && $params['service'] !== 'all') {
            $queryparams['servicio'] = $params['service'];
        }
        if (!empty($params['action']) && $params['action'] !== 'all') {
            $queryparams['accion'] = $params['action'];
        }
        if (!empty($params['fromdate'])) {
            $queryparams['fecha_desde'] = $params['fromdate'];
        }
        if (!empty($params['todate'])) {
            $queryparams['fecha_hasta'] = $params['todate'];
        }

        $firstresponse = $client->get('/tokens/historial-consumos', $queryparams);

        if (empty($firstresponse) || $firstresponse['status'] !== 'success') {
            return [
                'status' => 'error',
                'message' => get_string('errorinitinformation', 'aiprovider_datacurso'),
            ];
        }

        // Step 2. Get total records and calculate total pages.
        $pagination = $firstresponse['paginacion'] ?? [];
        $totalrecords = (int)($pagination['total'] ?? 0);
        $limitperpage = 50;
        $totalpages = ceil($totalrecords / $limitperpage);

        $allconsumptions = [];

        // Step 3. Fetch all pages sequentially.
        for ($page = 1; $page <= $totalpages; $page++) {
            $queryparams['page'] = $page;
            $queryparams['limit'] = $limitperpage;

            $response = $client->get('/tokens/historial-consumos', $queryparams);

            if (empty($response) || $response['status'] !== 'success') {
                continue;
            }

            $userdata = $response['usuarios'][0] ?? null;
            $consumptions = $userdata['consumos'] ?? [];

            // Build a map of action names for translation.
            $actions = \aiprovider_datacurso\provider::get_actions();
            $actionmap = [];
            foreach ($actions as $a) {
                $actionmap[$a['id']] = $a['name'];
            }

            // Collect and normalize consumption data.
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
     * Defines the return structure.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Request status (success/error)'),
            'total' => new external_value(PARAM_INT, 'Total number of consumption records found'),
            'consumption' => new external_multiple_structure(
                new external_single_structure([
                    'id_consumption' => new external_value(PARAM_INT, 'Consumption record ID'),
                    'action' => new external_value(PARAM_TEXT, 'Performed action'),
                    'id_service' => new external_value(PARAM_TEXT, 'Used service'),
                    'userid' => new external_value(PARAM_INT, 'Moodle user ID', VALUE_OPTIONAL),
                    'cant_tokens' => new external_value(PARAM_INT, 'Number of tokens consumed'),
                    'balance' => new external_value(PARAM_INT, 'Remaining token balance'),
                    'date' => new external_value(PARAM_RAW, 'Consumption date (YYYY-MM-DD)'),
                    'created_at' => new external_value(PARAM_RAW, 'Record creation date in the API'),
                ])
            ),
        ]);
    }
}
