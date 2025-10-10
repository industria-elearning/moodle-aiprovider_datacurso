<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace aiprovider_datacurso\external;

use external_multiple_structure;
use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use aiprovider_datacurso\webservice_config;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');


/**
 * AJAX external functions for configuring the Datacurso web service.
 *
 * @package    aiprovider_datacurso
 * @category   external
 * @copyright  2025
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class webservice_config_api extends external_api {
    /**
     * Defines the parameters for the setup function.
     *
     * @return external_function_parameters The parameters object.
     */
    public static function setup_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Performs the web service setup.
     *
     * @return array The setup result.
     */
    public static function setup(): array {
        self::validate_parameters(self::setup_parameters(), []);
        return webservice_config::setup();
    }

    /**
     * Defines the return structure for the setup function.
     *
     * @return external_single_structure The return structure object.
     */
    public static function setup_returns(): external_single_structure {
        return self::status_structure();
    }

    /**
     * Defines the parameters for the regenerate_token function.
     *
     * @return external_function_parameters The parameters object.
     */
    public static function regenerate_token_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Regenerates the web service token.
     *
     * @return array The regeneration result.
     */
    public static function regenerate_token(): array {
        self::validate_parameters(self::regenerate_token_parameters(), []);
        $result = webservice_config::regenerate_token();
        return $result;
    }

    /**
     * Defines the return structure for the regenerate_token function.
     *
     * @return external_single_structure The return structure object.
     */
    public static function regenerate_token_returns(): external_single_structure {
        return self::status_structure();
    }

    /**
     * Parameters for get_status.
     *
     * @return external_function_parameters
     */
    public static function get_status_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Return current configuration status.
     *
     * @return array
     */
    public static function get_status(): array {
        self::validate_parameters(self::get_status_parameters(), []);
        return webservice_config::get_status();
    }

    /**
     * Return structure for get_status.
     *
     * @return external_single_structure
     */
    public static function get_status_returns(): external_single_structure {
        return self::status_structure();
    }

    /**
     * Common return structure for status responses.
     *
     * @return external_single_structure
     */
    private static function status_structure(): external_single_structure {
        $fields = [
            'webservicesenabled' => new external_value(PARAM_BOOL, 'Web services enabled'),
            'restenabled' => new external_value(PARAM_BOOL, 'REST protocol enabled'),
            'userassigned' => new external_value(PARAM_BOOL, 'Role assigned to service user'),
            'tokenexists' => new external_value(PARAM_BOOL, 'Token exists'),
            // Token created is formatted string for UI consistency.
            'tokencreated' => new external_value(PARAM_TEXT, 'Token creation time (formatted)', VALUE_DEFAULT, ''),
            'isconfigured' => new external_value(PARAM_BOOL, 'Everything configured properly'),
            'needsrepair' => new external_value(PARAM_BOOL, 'Needs setup/repair'),
            'retryonly' => new external_value(
                PARAM_BOOL,
                'Show only Retry when registration was attempted but is not active',
                VALUE_DEFAULT,
                false
            ),
            'user' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'User ID', VALUE_DEFAULT, 0),
                'username' => new external_value(PARAM_TEXT, 'Username', VALUE_DEFAULT, ''),
                'email' => new external_value(PARAM_TEXT, 'Email', VALUE_DEFAULT, ''),
                'confirmed' => new external_value(PARAM_BOOL, 'Confirmed', VALUE_DEFAULT, false),
                'auth' => new external_value(PARAM_TEXT, 'Auth', VALUE_DEFAULT, ''),
            ], 'Service user', VALUE_DEFAULT, []),
            'role' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Role ID', VALUE_DEFAULT, 0),
                'name' => new external_value(PARAM_TEXT, 'Role name', VALUE_DEFAULT, ''),
                'shortname' => new external_value(PARAM_TEXT, 'Role shortname', VALUE_DEFAULT, ''),
            ], 'Service role', VALUE_DEFAULT, []),
            'service' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Service ID', VALUE_DEFAULT, 0),
                'name' => new external_value(PARAM_TEXT, 'Service name', VALUE_DEFAULT, ''),
                'enabled' => new external_value(PARAM_BOOL, 'Service enabled', VALUE_DEFAULT, false),
                'restrictedusers' => new external_value(PARAM_BOOL, 'Restricted users', VALUE_DEFAULT, false),
            ], 'External service', VALUE_DEFAULT, []),
            'registration' => new external_single_structure([
                'laststatus' => new external_value(PARAM_TEXT, 'Last registration status', VALUE_DEFAULT, ''),
                'lasterror' => new external_value(PARAM_TEXT, 'Last registration error', VALUE_DEFAULT, ''),
                'lastsent' => new external_value(PARAM_TEXT, 'Last registration timestamp', VALUE_DEFAULT, ''),
                'verified' => new external_value(PARAM_BOOL, 'Registration verified with external service', VALUE_DEFAULT, false),
            ], 'Registration status', VALUE_DEFAULT, []),
            'site' => new external_single_structure([
                'domain' => new external_value(PARAM_URL, 'Site domain'),
                'siteid' => new external_value(PARAM_ALPHANUMEXT, 'Site unique id'),
            ], 'Site info'),
            'messages' => new external_multiple_structure(
                new external_value(PARAM_TEXT, 'Message'),
                'Step messages', VALUE_DEFAULT, []
            ),
        ];

        return new external_single_structure($fields);
    }
}
