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

use aiprovider_datacurso\local\service\credit_token_service;
use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * External function to delete a role token limit.
 *
 * @package    aiprovider_datacurso
 * @category   webservice
 * @copyright  2026 Datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_role_token_limit extends external_api {
    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Record ID to delete', VALUE_REQUIRED),
        ]);
    }

    /**
     * Delete a role token limit record.
     *
     * @param int $id
     * @return array
     */
    public static function execute(int $id): array {
        $params = self::validate_parameters(self::execute_parameters(), ['id' => $id]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('aiprovider/datacurso:managetokenlimits', $context);

        return credit_token_service::delete_role_token_limit((int)$params['id']);
    }

    /**
     * Returns description of method return value.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Operation success'),
            'message' => new external_value(PARAM_TEXT, 'Result message'),
        ]);
    }
}
