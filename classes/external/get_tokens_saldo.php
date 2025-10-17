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
use external_value;
use aiprovider_datacurso\httpclient\datacurso_api;

/**
 * Web service to get the token balance.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_tokens_saldo extends external_api {
    /**
     * Input parameters (none in this case).
     */
    public static function execute_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * WS logic: makes the call to the external API and returns the array.
     */
    public static function execute() {
        $client = new datacurso_api();

        $response = $client->get('/tokens/saldo');

        if (empty($response) || !isset($response['status'])) {
            return [
                'status' => 'error',
                'saldo_actual' => 0,
                'message' => 'Could not retrieve the token balance from the external API',
            ];
        }

        return [
            'status' => $response['status'] ?? 'error',
            'saldo_actual' => (int) ($response['saldo_actual'] ?? 0),
            'message' => $response['message'] ?? '',
        ];
    }

    /**
     * Output structure.
     */
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Request status (success/error)'),
            'saldo_actual' => new external_value(PARAM_INT, 'Current token balance'),
            'message' => new external_value(PARAM_RAW, 'Additional API message or error'),
        ]);
    }
}
