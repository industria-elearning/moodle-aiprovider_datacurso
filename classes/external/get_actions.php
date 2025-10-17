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
use external_multiple_structure;
use external_single_structure;
use external_value;

/**
 * External API class for retrieving AI provider actions.
 *
 * @package    aiprovider_datacurso
 * @category   external
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_actions extends \external_api {
    /**
     * Describes the parameters for the get_actions external function.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * Retrieves available AI provider actions.
     *
     * @return array containing actions with id and name
     */
    public static function execute() {
        $actions = \aiprovider_datacurso\provider::get_actions();
        return ['actions' => $actions];
    }

    /**
     * Describes the structure of the data returned by the external function.
     *
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure([
            'actions' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_TEXT, 'Action ID'),
                    'name' => new external_value(PARAM_TEXT, 'Action name'),
                ])
            ),
        ]);
    }
}
