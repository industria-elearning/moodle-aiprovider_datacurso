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
 * Web service to get the credits balance.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_credits_balance extends external_api {
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
        $params = self::validate_parameters(self::execute_parameters(), []);
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('aiprovider_datacurso/datacurso:viewreports', $context);
        $client = new datacurso_api();

        $response = $client->get('/tokens/saldo');

        if (empty($response) || !isset($response['status'])) {
            return [
                'status' => 'error',
                'balance' => 0,
                'message' => get_string('errorgetbalancecredits', 'aiprovider_datacurso'),
            ];
        }

        return [
            'status' => $response['status'] ?? 'error',
            'balance' => (int) ($response['saldo_actual'] ?? 0),
            'message' => $response['message'] ?? '',
        ];
    }

    /**
     * Output structure.
     */
    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Request status (success/error)'),
            'balance' => new external_value(PARAM_INT, 'Current credits balance'),
            'message' => new external_value(PARAM_RAW, 'Additional API message or error'),
        ]);
    }
}
