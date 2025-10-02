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

namespace aiprovider_datacurso;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/clilib.php');
require_once($CFG->libdir . '/datalib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/webservice/lib.php');

use context_system;
use core_plugin_manager;
use moodle_exception;

/**
 * Automates Moodle web service configuration for the Datacurso AI provider.
 *
 * This class mirrors the steps of the provided CLI reference, but exposes
 * them to the UI and AJAX so admins can configure everything with one click.
 *
 * Steps performed:
 * - Enable webservice auth and REST protocol
 * - Create or reuse the dedicated service user
 * - Create or reuse a role with required capabilities and assign to the user
 * - Create or reuse the external service and attach required functions
 * - Authorise the user for the external service
 * - Create or reuse a permanent token for the service user
 * - Optionally POST the token to the registration endpoint
 *
 * All methods return structured arrays (safe for AJAX) and never expose tokens.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class webservice_config {
    /** Constants used for creating items. */
    public const USERNAME = 'datacurso';
    public const USEREMAIL = 'datacurso@localhost';
    public const ROLENAME = 'Datacurso web service';
    public const ROLESHORTNAME = 'datacursows';
    public const SERVICENAME = 'datacurso';
    public const SERVICESHORTNAME = 'datacurso';

    /**
     * Returns a summary of the current configuration status.
     *
     * @return array
     */
    public static function get_status(): array {
        global $DB, $CFG;

        $status = [
            'webservicesenabled' => (bool)get_config('core', 'enablewebservices'),
            'restenabled' => false,
            'user' => null,
            'role' => null,
            'service' => null,
            'userassigned' => false,
            'tokenexists' => false,
            'tokencreated' => null,
            'registration' => [
                'laststatus' => get_config('aiprovider_datacurso', 'registration_last_status') ?: '',
                'lasterror' => get_config('aiprovider_datacurso', 'registration_last_error') ?: '',
                'lastsent' => get_config('aiprovider_datacurso', 'registration_last_sent') ?: '',
            ],
            'site' => [
                'domain' => $CFG->wwwroot,
                'siteid' => self::get_site_id(),
            ],
        ];

        // Check REST enabled.
        $plugininfo = core_plugin_manager::instance()->get_plugin_info('webservice_rest');
        $status['restenabled'] = $plugininfo && $plugininfo->is_enabled();

        // User.
        if ($user = $DB->get_record('user', ['username' => self::USERNAME, 'deleted' => 0])) {
            $status['user'] = [
                'id' => (int)$user->id,
                'username' => $user->username,
                'email' => $user->email,
                'confirmed' => (bool)$user->confirmed,
                'auth' => $user->auth,
            ];
        }

        // Role.
        if ($roleid = $DB->get_field('role', 'id', ['shortname' => self::ROLESHORTNAME])) {
            $status['role'] = [
                'id' => (int)$roleid,
                'name' => self::ROLENAME,
                'shortname' => self::ROLESHORTNAME,
            ];

            // Assigned?
            if (!empty($status['user']['id'])) {
                $context = context_system::instance();
                $assigned = $DB->record_exists('role_assignments', [
                    'roleid' => $roleid,
                    'userid' => $status['user']['id'],
                    'contextid' => $context->id,
                ]);
                $status['userassigned'] = (bool)$assigned;
            }
        }

        // Service.
        if ($service = $DB->get_record('external_services', ['shortname' => self::SERVICESHORTNAME])) {
            $status['service'] = [
                'id' => (int)$service->id,
                'name' => $service->name,
                'enabled' => (bool)$service->enabled,
                'restrictedusers' => (bool)$service->restrictedusers,
            ];

            // Token existence.
            if (!empty($status['user']['id'])) {
                if ($token = $DB->get_record('external_tokens', [
                    'userid' => $status['user']['id'],
                    'externalserviceid' => $service->id,
                ], '*', IGNORE_MULTIPLE)) {
                    $status['tokenexists'] = true;
                    $status['tokencreated'] = !empty($token->timecreated) ? (int)$token->timecreated : null;
                }
            }
        }

        return $status;
    }

    /**
     * Perform full setup. Returns stepwise messages and final status.
     *
     * @return array
     */
    public static function setup(): array {
        global $CFG, $DB;

        require_capability('moodle/site:config', context_system::instance());

        $messages = [];

        // Enable web services and REST protocol.
        $messages[] = get_string('ws_step_enableauth', 'aiprovider_datacurso');
        $authclass = core_plugin_manager::resolve_plugininfo_class('auth');
        $authclass::enable_plugin('webservice', true);

        $messages[] = get_string('ws_step_enablews', 'aiprovider_datacurso');
        set_config('enablewebservices', 1);

        $messages[] = get_string('ws_step_enablerest', 'aiprovider_datacurso');
        $webserviceclass = core_plugin_manager::resolve_plugininfo_class('webservice');
        $webserviceclass::enable_plugin('rest', true);

        // Ensure user exists.
        $messages[] = get_string('ws_step_user_check', 'aiprovider_datacurso', self::USERNAME);
        $user = $DB->get_record('user', ['username' => self::USERNAME, 'deleted' => 0]);
        if (!$user) {
            $messages[] = get_string('ws_step_user_create', 'aiprovider_datacurso', self::USERNAME);
            $user = (object) [
                'username' => self::USERNAME,
                'password' => AUTH_PASSWORD_NOT_CACHED,
                'firstname' => 'Datacurso',
                'lastname' => 'Service',
                'email' => self::USEREMAIL,
                'auth' => 'webservice',
                'confirmed' => 1,
                'maildisplay' => 0,
                'mnethostid' => $CFG->mnet_localhost_id,
            ];
            $user->id = user_create_user($user, false, false);
        }

        // Create role if needed.
        if ($DB->record_exists('role', ['shortname' => self::ROLESHORTNAME])) {
            $roleid = (int)$DB->get_field('role', 'id', ['shortname' => self::ROLESHORTNAME]);
            $messages[] = get_string('ws_step_role_exists', 'aiprovider_datacurso', $roleid);
        } else {
            $messages[] = get_string('ws_step_role_create', 'aiprovider_datacurso', self::ROLENAME);
            $roleid = create_role(self::ROLENAME, self::ROLESHORTNAME, 'Role for Datacurso web service');
        }

        // Assignable contexts and permissions.
        set_role_contextlevels($roleid, [CONTEXT_SYSTEM]);
        $messages[] = get_string('ws_step_role_caps', 'aiprovider_datacurso');
        $context = context_system::instance();
        assign_capability('webservice/rest:use', CAP_ALLOW, $roleid, $context, true);

        // Assign role to user.
        $messages[] = get_string('ws_step_role_assign', 'aiprovider_datacurso');
        role_assign($roleid, $user->id, $context->id);

        // Ensure external service exists and is enabled.
        $messages[] = get_string('ws_step_service_enable', 'aiprovider_datacurso');
        $webservicemanager = new \webservice();
        if ($service = $DB->get_record('external_services', ['shortname' => self::SERVICESHORTNAME])) {
            $service->enabled = 1;
            $service->restrictedusers = 1;
            $webservicemanager->update_external_service($service);
        } else {
            $service = (object) [
                'name' => self::SERVICENAME,
                'shortname' => self::SERVICESHORTNAME,
                'enabled' => 1,
                'restrictedusers' => 1,
                'downloadfiles' => 0,
                'uploadfiles' => 0,
                'timecreated' => time(),
                'component' => 'aiprovider_datacurso',
            ];
            $service->id = $webservicemanager->add_external_service($service);
        }

        // Attach some common core functions if they exist.
        $messages[] = get_string('ws_step_service_functions', 'aiprovider_datacurso');
        $corefunctions = [
            'core_course_get_courses',
            'core_course_get_courses_by_field',
            'core_enrol_get_enrolled_users',
            'core_user_get_users',
            'core_user_get_users_by_field',
        ];
        foreach ($corefunctions as $fname) {
            if ($function = $DB->get_record('external_functions', ['name' => $fname])) {
                $exists = $DB->record_exists('external_services_functions', [
                    'externalserviceid' => $service->id,
                    'functionname' => $fname,
                ]);
                if (!$exists) {
                    $webservicemanager->add_external_function_to_service($fname, $service->id);
                }
            }
        }

        // Add user to the external service if not already authorised.
        $messages[] = get_string('ws_step_service_user', 'aiprovider_datacurso');
        $authorised = $DB->record_exists('external_services_users', [
            'externalserviceid' => $service->id,
            'userid' => $user->id,
        ]);
        if (!$authorised) {
            $serviceuser = (object) [
                'externalserviceid' => $service->id,
                'userid' => $user->id,
                'timecreated' => time(),
            ];
            $webservicemanager->add_ws_authorised_user($serviceuser);
        }

        // Create or reuse a permanent token (not returned in response).
        $messages[] = get_string('ws_step_token_create', 'aiprovider_datacurso');
        $tokenrec = $DB->get_record('external_tokens', [
            'userid' => $user->id,
            'externalserviceid' => $service->id,
        ], '*', IGNORE_MULTIPLE);

        if (!$tokenrec) {
            if (function_exists('moodle_major_version') && moodle_major_version() >= 4.5 && class_exists('core_external\\util')) {
                $token = \core_external\util::generate_token(
                    EXTERNAL_TOKEN_PERMANENT,
                    $service,
                    $user->id,
                    context_system::instance(),
                    0,
                    '',
                    'datacurso token'
                );
            } else {
                $token = external_generate_token(
                    EXTERNAL_TOKEN_PERMANENT,
                    $service,
                    $user->id,
                    context_system::instance(),
                    0,
                    ''
                );
            }
            // Refetch for status.
            $tokenrec = $DB->get_record('external_tokens', [
                'userid' => $user->id,
                'externalserviceid' => $service->id,
            ], '*', IGNORE_MULTIPLE);
        }

        $status = self::get_status();
        $status['messages'] = $messages;
        return $status;
    }

    /**
     * Regenerate the token for the service user (revoke old if exists), and return status.
     * Note: Token value is never returned.
     *
     * @return array
     * @throws moodle_exception
     */
    public static function regenerate_token(): array {
        global $DB;
        require_capability('moodle/site:config', context_system::instance());

        $status = self::get_status();
        if (empty($status['service']['id']) || empty($status['user']['id'])) {
            throw new moodle_exception('ws_error_missing_setup', 'aiprovider_datacurso');
        }

        // Delete existing tokens for this user/service.
        $DB->delete_records('external_tokens', [
            'userid' => $status['user']['id'],
            'externalserviceid' => $status['service']['id'],
        ]);

        $messages = [get_string('ws_step_token_regenerating', 'aiprovider_datacurso')];

        // Create new permanent token.
        $service = $DB->get_record('external_services', ['id' => $status['service']['id']], '*', MUST_EXIST);
        $userid = $status['user']['id'];
        if (function_exists('moodle_major_version') && moodle_major_version() >= 4.5 && class_exists('core_external\\util')) {
            \core_external\util::generate_token(
                EXTERNAL_TOKEN_PERMANENT,
                $service,
                $userid,
                context_system::instance(),
                0,
                '',
                'datacurso token'
            );
        } else {
            external_generate_token(
                EXTERNAL_TOKEN_PERMANENT,
                $service,
                $userid,
                context_system::instance(),
                0,
                ''
            );
        }

        $newstatus = self::get_status();
        $newstatus['messages'] = array_merge($messages, [get_string('ws_step_token_regenerated', 'aiprovider_datacurso')]);
        return $newstatus;
    }

    /**
     * Retrieve current token value (server-side only) and POST registration.
     * This does NOT expose the token to the UI.
     *
     * @return array
     * @throws moodle_exception
     */
    public static function send_registration(): array {
        global $DB, $CFG;

        require_capability('moodle/site:config', context_system::instance());

        $status = self::get_status();
        if (empty($status['service']['id']) || empty($status['user']['id'])) {
            throw new moodle_exception('ws_error_missing_setup', 'aiprovider_datacurso');
        }

        // Fetch token for sending.
        $tokenrec = $DB->get_record('external_tokens', [
            'userid' => $status['user']['id'],
            'externalserviceid' => $status['service']['id'],
        ], '*', IGNORE_MULTIPLE);

        if (!$tokenrec || empty($tokenrec->token)) {
            throw new moodle_exception('ws_error_missing_token', 'aiprovider_datacurso');
        }

        $payload = [
            'site_id' => self::get_site_id(),
            'domain' => $CFG->wwwroot,
            'token' => $tokenrec->token,
        ];

        $client = new \aiprovider_datacurso\external_api_client();
        $result = $client->register_site($payload);

        // Persist last registration status.
        set_config('registration_last_status', $result['status'] ?? '', 'aiprovider_datacurso');
        set_config('registration_last_error', $result['error'] ?? '', 'aiprovider_datacurso');
        set_config('registration_last_sent', time(), 'aiprovider_datacurso');

        $status = self::get_status();
        $status['messages'] = [get_string('ws_step_registration_sent', 'aiprovider_datacurso')];
        return $status;
    }

    /**
     * Get unique site identifier string.
     *
     * @return string
     */
    private static function get_site_id(): string {
        global $CFG, $DB;
        // Prefer the core site identifier if present.
        $siteid = get_config('core', 'siteidentifier');
        if (!empty($siteid)) {
            return $siteid;
        }
        // Fallback: md5 of wwwroot.
        return md5($CFG->wwwroot);
    }
}

