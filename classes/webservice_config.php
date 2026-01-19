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

use aiprovider_datacurso\httpclient\ai_services_api;

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
 * @copyright  Datacurso 2025
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class webservice_config {
    /** Constants used for creating items. */
    /** @var string Username for the webservice user. */
    public const USERNAME = 'datacursows';
    /** @var string Email for the webservice user. */
    public const USEREMAIL = 'webservice@datacurso.com';
    /** @var string Name for the role. */
    public const ROLENAME = 'Datacurso web service';
    /** @var string Short name for the role. */
    public const ROLESHORTNAME = 'datacursows';
    /** @var string Name for the service. */
    public const SERVICENAME = 'Datacurso web service';
    /** @var string Short name for the service. */
    public const SERVICESHORTNAME = 'datacursows';

    /**
     * Returns a summary of the current configuration status.
     *
     * @return array
     */
    public static function get_status(): array {
        // Compose status from small, self-explanatory steps.
        $status = self::base_status();
        self::set_rest_status($status);
        self::set_user($status);
        self::set_role_and_assignment($status);
        self::set_service_and_token($status);
        self::compute_flags($status);
        $status = self::verify_remote_registration($status);

        return $status;
    }

    /**
     * Quickly tell if the Datacurso webservice has been fully configured.
     *
     * @return bool
     */
    public static function is_configured(): bool {
        $status = self::get_status();
        return !empty($status['isconfigured']);
    }

    /**
     * Returns the URL to the automatic webservice configuration page.
     *
     * @return \moodle_url
     */
    public static function get_url(): \moodle_url {
        global $CFG;
        return new \moodle_url('/ai/provider/datacurso/admin/webservice_config.php');
    }

    /**
     * Build the initial status scaffold with site and registration info.
     *
     * @return array
     */
    private static function base_status(): array {
        global $CFG;
        return [
            'webservicesenabled' => (bool)get_config('core', 'enablewebservices'),
            'restenabled' => false,
            'user' => [],
            'role' => [],
            'service' => [],
            'userassigned' => false,
            'tokenexists' => false,
            'tokencreated' => null,
            'registration' => [
                'verified' => get_config('aiprovider_datacurso', 'registration_verified') ?: false,
                'lastsent' => get_config('aiprovider_datacurso', 'registration_lastsent') ?: '',
                'laststatus' => get_config('aiprovider_datacurso', 'registration_laststatus') ?: '',
            ],
            'site' => [
                'domain' => $CFG->wwwroot,
                'siteid' => self::get_site_id(),
            ],
            'isconfigured' => false,
            'needsrepair' => true,
            'retryonly' => false,
        ];
    }

    /**
     * Populate REST protocol enabled status.
     *
     * @param array $status
     * @return void
     */
    private static function set_rest_status(array &$status): void {
        $plugininfo = core_plugin_manager::instance()->get_plugin_info('webservice_rest');
        $status['restenabled'] = $plugininfo && $plugininfo->is_enabled();
    }

    /**
     * Populate service user details if exists.
     *
     * @param array $status
     * @return void
     */
    private static function set_user(array &$status): void {
        global $DB;
        if ($user = $DB->get_record('user', ['username' => self::USERNAME, 'deleted' => 0])) {
            $status['user'] = [
                'id' => (int)$user->id,
                'username' => $user->username,
                'email' => $user->email,
                'confirmed' => (bool)$user->confirmed,
                'auth' => $user->auth,
            ];
        }
    }

    /**
     * Populate role info and whether it's assigned to the user at system context.
     *
     * @param array $status
     * @return void
     */
    private static function set_role_and_assignment(array &$status): void {
        global $DB;
        if ($roleid = $DB->get_field('role', 'id', ['shortname' => self::ROLESHORTNAME])) {
            $status['role'] = [
                'id' => (int)$roleid,
                'name' => self::ROLENAME,
                'shortname' => self::ROLESHORTNAME,
            ];
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
    }

    /**
     * Populate service info and token status for the user.
     *
     * @param array $status
     * @return void
     */
    private static function set_service_and_token(array &$status): void {
        global $DB;
        if ($service = $DB->get_record('external_services', ['shortname' => self::SERVICESHORTNAME])) {
            $status['service'] = [
                'id' => (int)$service->id,
                'name' => $service->name,
                'enabled' => (bool)$service->enabled,
                'restrictedusers' => (bool)$service->restrictedusers,
            ];
            if (!empty($status['user']['id'])) {
                $params = [
                    'userid' => $status['user']['id'],
                    'externalserviceid' => $service->id,
                ];
                $token = $DB->get_record(
                    'external_tokens',
                    $params,
                    '*',
                    IGNORE_MULTIPLE,
                );
                if ($token) {
                    $status['tokenexists'] = true;
                    $timecreated = !empty($token->timecreated) ? (int)$token->timecreated : 0;
                    $status['tokencreated'] = $timecreated
                        ? userdate($timecreated, get_string('strftimedatetime', 'langconfig'))
                        : null;
                }
            }
        }
    }

    /**
     * Compute high-level flags like isconfigured, needsrepair, retryonly.
     *
     * @param array $status
     * @return void
     */
    private static function compute_flags(array &$status): void {
        $status['isconfigured'] = (
            !empty($status['webservicesenabled']) &&
            !empty($status['restenabled']) &&
            !empty($status['user']['id'] ?? 0) &&
            !empty($status['role']['id'] ?? 0) &&
            !empty($status['service']['id'] ?? 0) &&
            !empty($status['userassigned']) &&
            !empty($status['tokenexists']) &&
            !empty($status['registration']['verified'])
        );
        $anypresent = (
            !empty($status['webservicesenabled']) ||
            !empty($status['restenabled']) ||
            !empty($status['user']['id'] ?? 0) ||
            !empty($status['role']['id'] ?? 0) ||
            !empty($status['service']['id'] ?? 0) ||
            !empty($status['userassigned']) ||
            !empty($status['tokenexists'])
        );
        $status['needsrepair'] = (!$status['isconfigured'] && $anypresent);
        $hadattempt = !empty($status['registration']['lastsent']);
        $status['retryonly'] = (!$status['isconfigured'] && $hadattempt && empty($status['registration']['verified']));
    }

    /**
     * Perform full setup. Returns stepwise messages and final status.
     *
     * @return array
     */
    public static function setup(): array {
        global $DB;

        require_capability('moodle/site:config', context_system::instance());

        $messages = [];

        try {
            // 1) Enable required plugins.
            $messages[] = get_string('ws_step_enableauth', 'aiprovider_datacurso');
            self::enable_webservices_and_rest();

            // 2) Ensure service user exists.
            $messages[] = get_string('ws_step_user_check', 'aiprovider_datacurso', self::USERNAME);
            $user = self::ensure_service_user();

            // 3) Ensure role and caps.
            [$roleid, $rolecreated] = self::ensure_role();
            $messages[] = $rolecreated
                ? get_string('ws_step_role_create', 'aiprovider_datacurso', self::ROLENAME)
                : get_string('ws_step_role_exists', 'aiprovider_datacurso', $roleid);
            $messages[] = get_string('ws_step_role_caps', 'aiprovider_datacurso');
            self::assign_role_capabilities($roleid);
            $messages[] = get_string('ws_step_role_assign', 'aiprovider_datacurso');
            self::assign_role_to_user($roleid, $user->id);

            // 4) Ensure external service and functions.
            $messages[] = get_string('ws_step_service_enable', 'aiprovider_datacurso');
            $service = self::ensure_external_service();
            $messages[] = get_string('ws_step_service_functions', 'aiprovider_datacurso');
            self::attach_default_functions($service->id);
            $messages[] = get_string('ws_step_service_user', 'aiprovider_datacurso');
            self::authorise_user_for_service($service->id, $user->id);

            // 5) Ensure token and send registration.
            $messages[] = get_string('ws_step_token_create', 'aiprovider_datacurso');
            $token = self::get_or_create_token($service, $user->id);
            if (!empty($token)) {
                $sendmessages = self::send_registration($token);
                if (is_array($sendmessages)) {
                    $messages = array_merge($messages, $sendmessages);
                } else if (!empty($sendmessages)) {
                    $messages[] = (string)$sendmessages;
                }
            }

            $status = self::get_status();
            if ($status['registration']['verified']) {
                $messages[] = get_string('ws_step_token_generated', 'aiprovider_datacurso');
            }
            $status['messages'] = $messages;
            return $status;
        } catch (\Exception $e) {
            $status = self::get_status();
            $messages[] = get_string('ws_error_setup', 'aiprovider_datacurso');
            $status['messages'] = $messages;
            return $status;
        }
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
            $status['messages'][] = get_string('ws_error_missing_setup', 'aiprovider_datacurso');
            return $status;
        }

        $messages = [];

        try {
            // Recreate token.
            $DB->delete_records('external_tokens', [
                'userid' => $status['user']['id'],
                'externalserviceid' => $status['service']['id'],
            ]);
            $service = $DB->get_record('external_services', ['id' => $status['service']['id']], '*', MUST_EXIST);
            $token = self::create_token($service, $status['user']['id']);

            if (!empty($token)) {
                $sendmessages = self::send_registration($token);
                $messages = array_merge($messages, $sendmessages);
            }

            $status = self::get_status();
            if ($status['registration']['verified']) {
                $messages[] = get_string('ws_step_token_regenerated', 'aiprovider_datacurso');
            }
            $status['messages'] = $messages;
            return $status;
        } catch (\Exception $e) {
            $status['messages'][] = get_string('ws_error_regenerate_token', 'aiprovider_datacurso');
            return $status;
        }
    }

    /**
     * Enable core web service auth and REST protocol.
     * Single-responsibility: only toggles plugin settings required for WS.
     *
     * @return void
     */
    private static function enable_webservices_and_rest(): void {
        // Enable web service authentication plugin.
        $authclass = core_plugin_manager::resolve_plugininfo_class('auth');
        $authclass::enable_plugin('webservice', true);
        // Enable global web services.
        set_config('enablewebservices', 1);
        // Enable REST protocol.
        $webserviceclass = core_plugin_manager::resolve_plugininfo_class('webservice');
        $webserviceclass::enable_plugin('rest', true);
    }

    /**
     * Ensure service user exists with correct auth and confirmation.
     *
     * @return object Moodle user record
     */
    private static function ensure_service_user(): object {
        global $DB, $CFG;
        $user = $DB->get_record('user', ['username' => self::USERNAME, 'deleted' => 0]);
        if ($user) {
            return $user;
        }
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
        return $user;
    }

    /**
     * Ensure role exists. Returns [roleid, created].
     *
     * @return array{0:int,1:bool}
     */
    private static function ensure_role(): array {
        global $DB;
        if ($DB->record_exists('role', ['shortname' => self::ROLESHORTNAME])) {
            $roleid = (int)$DB->get_field('role', 'id', ['shortname' => self::ROLESHORTNAME]);
            return [$roleid, false];
        }
        $roleid = create_role(self::ROLENAME, self::ROLESHORTNAME, 'Role for Datacurso web service');
        return [$roleid, true];
    }

    /**
     * Assign role capabilities for required contexts.
     *
     * @param int $roleid
     * @return void
     */
    private static function assign_role_capabilities(int $roleid): void {
        $context = context_system::instance();
        set_role_contextlevels($roleid, [CONTEXT_SYSTEM, CONTEXT_COURSE, CONTEXT_MODULE]);
        $capabilities = self::get_capabilities();
        foreach ($capabilities as $capability) {
            // Capability must exist.
            if (get_capability_info($capability)) {
                assign_capability($capability, CAP_ALLOW, $roleid, $context, true);
            }
        }
    }

    /**
     * Get a list of capabilities to assign to the role.
     *
     * @return array
     */
    private static function get_capabilities(): array {
        return [
            'webservice/rest:use',
            'moodle/category:viewhiddencategories',
            'moodle/course:enrolreview',
            'moodle/course:view',
            'moodle/course:viewhiddencourses',
            'moodle/course:viewhiddensections',
            'moodle/course:viewparticipants',
            'moodle/course:viewhiddenactivities',
            'moodle/course:view',
            'moodle/course:viewhiddencourses',
            'mod/forum:viewdiscussion',
            'mod/forum:viewqandawithoutposting',
            'mod/wiki:viewpage',
            'mod/glossary:view',
            'mod/book:read',
            'mod/lesson:view',
            'mod/choice:choose',
            'mod/choice:readresponses',
            'mod/feedback:view',
            'mod/feedback:viewanalysepage',
            'mod/scorm:viewreport',
            'mod/scorm:viewscores',
            'mod/h5pactivity:view',
            'mod/h5pactivity:reviewattempts',
            'mod/resource:view',
            'mod/page:view',
            'mod/assign:view',
            'mod/assign:viewgrades',
            'mod/data:viewentry',
            'mod/data:view',
            'mod/folder:view',
            'mod/label:view',
            'mod/url:view',
            'mod/workshop:view',
        ];
    }

    /**
     * Assign role to the target user in system context.
     *
     * @param int $roleid
     * @param int $userid
     * @return void
     */
    private static function assign_role_to_user(int $roleid, int $userid): void {
        $context = context_system::instance();
        role_assign($roleid, $userid, $context->id);
    }

    /**
     * Ensure external service exists and is enabled.
     *
     * @return object external service record (with id)
     */
    private static function ensure_external_service(): object {
        global $DB;
        $webservicemanager = new \webservice();
        if ($service = $DB->get_record('external_services', ['shortname' => self::SERVICESHORTNAME])) {
            $service->enabled = 1;
            $service->restrictedusers = 1;
            $webservicemanager->update_external_service($service);
            return $service;
        }
        $service = (object) [
            'name' => self::SERVICENAME,
            'shortname' => self::SERVICESHORTNAME,
            'enabled' => 1,
            'restrictedusers' => 1,
            'downloadfiles' => 1,
            'uploadfiles' => 0,
        ];
        $service->id = $webservicemanager->add_external_service($service);
        return $service;
    }

    /**
     * Attach a predefined set of core functions to the service if present.
     *
     * @param int $serviceid
     * @return void
     */
    private static function attach_default_functions(int $serviceid): void {
        global $DB;
        $webservicemanager = new \webservice();
        $wsfunctions = self::get_web_service_functions();
        foreach ($wsfunctions as $functionname) {
            $existfunction = $DB->record_exists('external_functions', ['name' => $functionname]);
            $isassigned = $DB->record_exists('external_services_functions', [
                'externalserviceid' => $serviceid,
                'functionname' => $functionname,
            ]);
            if ($existfunction && !$isassigned) {
                $webservicemanager->add_external_function_to_service($functionname, $serviceid);
            }
        }
    }

    /**
     * Get a list of web service functions to attach to the service.
     *
     * @return array
     */
    private static function get_web_service_functions(): array {
        return [
            'core_course_get_contents',
            'core_course_get_courses_by_field',
            'mod_assign_get_submissions',
            'mod_forum_get_forum_discussions',
            'mod_forum_get_discussion_posts',
            'mod_wiki_get_wikis_by_courses',
            'mod_wiki_get_subwikis',
            'mod_wiki_get_subwiki_pages',
            'mod_wiki_get_page_contents',
            'mod_wiki_get_subwiki_files',
            'mod_glossary_get_glossaries_by_courses',
            'mod_glossary_get_entries_by_search',
            'mod_glossary_get_entry_by_id',
            'mod_book_get_books_by_courses',
            'mod_lesson_get_lessons_by_courses',
            'mod_lesson_get_pages',
            'mod_lesson_get_page_data',
            'mod_choice_get_choices_by_courses',
            'mod_choice_get_choice_options',
            'mod_choice_get_choice_results',
            'mod_feedback_get_feedbacks_by_courses',
            'mod_feedback_get_items',
            'mod_feedback_get_finished_responses',
            'mod_feedback_get_analysis',
            'mod_scorm_get_scorms_by_courses',
            'mod_scorm_get_scorm_scoes',
            'mod_scorm_get_scorm_user_data',
            'mod_scorm_get_scorm_sco_tracks',
            'mod_h5pactivity_get_h5pactivities_by_courses',
            'mod_h5pactivity_get_attempts',
            'mod_h5pactivity_get_results',
            'mod_resource_get_resources_by_courses',
            'mod_resource_view_resource',
            'mod_page_get_pages_by_courses',
            'mod_assign_get_assignments',
            'mod_assign_view_assign',
            'mod_assign_get_submission_status',
            'mod_data_get_databases_by_courses',
            'mod_data_get_entries',
            'mod_data_get_fields',
            'mod_folder_get_folders_by_courses',
            'mod_folder_view_folder',
            'mod_label_get_labels_by_courses',
            'mod_url_get_urls_by_courses',
            'mod_url_view_url',
            'mod_workshop_get_workshops_by_courses',
            'mod_workshop_view_workshop',
            'mod_workshop_get_submissions',
        ];
    }

    /**
     * Ensure the user is authorized for the service.
     *
     * @param int $serviceid
     * @param int $userid
     * @return void
     */
    private static function authorise_user_for_service(int $serviceid, int $userid): void {
        global $DB;
        $webservicemanager = new \webservice();
        $authorised = $DB->record_exists('external_services_users', [
            'externalserviceid' => $serviceid,
            'userid' => $userid,
        ]);
        if (!$authorised) {
            $serviceuser = (object) [
                'externalserviceid' => $serviceid,
                'userid' => $userid,
            ];
            $webservicemanager->add_ws_authorised_user($serviceuser);
        }
    }

    /**
     * Upgrade-time synchronization: if the Datacurso service/role already exist,
     * attach any new WS functions to the service and assign any new capabilities to the role.
     * No external calls are performed.
     *
     * @return void
     */
    public static function upgrade_sync_ws_and_capabilities(): void {
        global $DB;

        // 1) Attach any missing functions to existing service.
        if ($service = $DB->get_record('external_services', ['shortname' => self::SERVICESHORTNAME])) {
            self::attach_default_functions((int)$service->id);
        }

        // 2) Assign any missing capabilities to existing role and ensure context levels.
        if ($roleid = (int)$DB->get_field('role', 'id', ['shortname' => self::ROLESHORTNAME])) {
            self::assign_role_capabilities($roleid);
        }
    }

    /**
     * Get or create a permanent token for the user/service pair.
     *
     * @param object $service
     * @param int $userid
     * @return string token
     */
    private static function get_or_create_token(object $service, int $userid): string {
        global $DB;
        $tokenrec = $DB->get_record('external_tokens', [
            'userid' => $userid,
            'externalserviceid' => $service->id,
        ], '*', IGNORE_MULTIPLE);
        if ($tokenrec && !empty($tokenrec->token)) {
            return $tokenrec->token;
        }
        return (string)self::create_token($service, $userid);
    }

    /**
     * Verify remote registration status and return updated status array.
     * Non-fatal: any exception results in verified=false.
     *
     * @param array $status
     * @return array
     */
    private static function verify_remote_registration(array $status): array {
        try {
            $client = new ai_services_api();
            $registration = $client->request('GET', '/registration-status', [
                'site_id' => self::get_site_id(),
            ]);
            if (!empty($registration['is_registered'])) {
                $status['registration']['verified'] = true;
            } else {
                $status['registration']['verified'] = false;
            }
        } catch (\Exception $e) {
            $status['registration']['verified'] = false;
        }
        return $status;
    }

    /**
     * Retrieve current token value (server-side only) and POST registration.
     * This does NOT expose the token to the UI.
     * @param string $token The webservice token to send.
     * @return array
     * @throws moodle_exception
     */
    private static function send_registration($token): array {
        global $CFG;

        $payload = [
            'site_id' => self::get_site_id(),
            'domain' => $CFG->wwwroot,
            'token' => $token,
        ];

        $client = new ai_services_api();

        try {
            $result = $client->request('POST', '/register-site', $payload);
            $verified = $result['verified'] ?? false;
            set_config('registration_verified', $verified, 'aiprovider_datacurso');
            // Persist last sent time and human status for UI clarity.
            $datestr = userdate(time(), get_string('strftimedatetime', 'langconfig'));
            set_config('registration_lastsent', $datestr, 'aiprovider_datacurso');
            set_config('registration_laststatus', $verified ? 'sent_verified' : 'sent_pending', 'aiprovider_datacurso');
            return [get_string('ws_step_registration_sent', 'aiprovider_datacurso')];
        } catch (\Exception $e) {
            set_config('registration_verified', false, 'aiprovider_datacurso');
            // Persist failure state as well, so UI can decide retry-only.
            $datestr = userdate(time(), get_string('strftimedatetime', 'langconfig'));
            set_config('registration_lastsent', $datestr, 'aiprovider_datacurso');
            set_config('registration_laststatus', 'error', 'aiprovider_datacurso');
            return [get_string('ws_error_registration', 'aiprovider_datacurso', $e->getMessage())];
        }
    }

    /**
     * Get unique site identifier string.
     *
     * @return string
     */
    private static function get_site_id(): string {
        global $CFG;
        return md5($CFG->wwwroot);
    }

    /**
     * Create a permanent token for the service user.
     *
     * @param object $service The external service record
     * @param int $userid The user ID
     * @return string The generated token
     */
    private static function create_token($service, $userid) {
        if (function_exists('moodle_major_version') && moodle_major_version() >= 4.5 && class_exists('core_external\\util')) {
            return \core_external\util::generate_token(
                EXTERNAL_TOKEN_PERMANENT,
                $service,
                $userid,
                context_system::instance(),
                0,
                '',
                'datacurso token'
            );
        }

        return external_generate_token(EXTERNAL_TOKEN_PERMANENT, $service, $userid, context_system::instance(), 0, '');
    }
}
