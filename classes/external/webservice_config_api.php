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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use aiprovider_datacurso\webservice_config;

/**
 * AJAX external functions for configuring the Datacurso web service.
 *
 * @package    aiprovider_datacurso
 * @category   external
 * @copyright  2025
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class webservice_config_api extends external_api {

    public static function get_status_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    public static function get_status(): array {
        self::validate_parameters(self::get_status_parameters(), []);
        return webservice_config::get_status();
    }

    public static function get_status_returns(): external_single_structure {
        return self::status_structure();
    }

    public static function setup_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    public static function setup(): array {
        self::validate_parameters(self::setup_parameters(), []);
        return webservice_config::setup();
    }

    public static function setup_returns(): external_single_structure {
        return self::status_structure(true);
    }

    public static function regenerate_token_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    public static function regenerate_token(): array {
        self::validate_parameters(self::regenerate_token_parameters(), []);
        return webservice_config::regenerate_token();
    }

    public static function regenerate_token_returns(): external_single_structure {
        return self::status_structure(true);
    }

    public static function send_registration_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    public static function send_registration(): array {
        self::validate_parameters(self::send_registration_parameters(), []);
        return webservice_config::send_registration();
    }

    public static function send_registration_returns(): external_single_structure {
        return self::status_structure(true);
    }

    /**
     * Common return structure for status responses.
     *
     * @param bool $withmessages
     * @return external_single_structure
     */
    private static function status_structure(bool $withmessages = false): external_single_structure {
        $fields = [
            'webservicesenabled' => new external_value(PARAM_BOOL, 'Web services enabled'),
            'restenabled' => new external_value(PARAM_BOOL, 'REST protocol enabled'),
            'userassigned' => new external_value(PARAM_BOOL, 'Role assigned to service user'),
            'tokenexists' => new external_value(PARAM_BOOL, 'Token exists'),
            'tokencreated' => new external_value(PARAM_INT, 'Token creation time', VALUE_DEFAULT, null),
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
            ], 'Registration status', VALUE_DEFAULT, []),
            'site' => new external_single_structure([
                'domain' => new external_value(PARAM_URL, 'Site domain'),
                'siteid' => new external_value(PARAM_ALPHANUMEXT, 'Site unique id'),
            ], 'Site info'),
        ];

        if ($withmessages) {
            $fields['messages'] = new \external_multiple_structure(
                new \external_value(PARAM_TEXT, 'Message'),
                'Step messages', VALUE_DEFAULT, []
            );
        }

        return new external_single_structure($fields);
    }
}
