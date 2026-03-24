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

namespace aiprovider_datacurso\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\metadata\provider as metadata_provider;
use core_privacy\local\request\core_userlist_provider;
use core_privacy\local\request\plugin\provider as plugin_provider;
use core_privacy\local\request\writer;
use stdClass;

/**
 * Privacy Subsystem for aiprovider_datacurso implementing metadata provider.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements core_userlist_provider, metadata_provider, plugin_provider {
    #[\Override]
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link('aiprovider_datacurso', [
            'prompt' => 'privacy:metadata:aiprovider_datacurso:prompt',
            'numberimages' => 'privacy:metadata:aiprovider_datacurso:numberimages',
            'userid' => 'privacy:metadata:aiprovider_datacurso:userid',
        ], 'privacy:metadata:aiprovider_datacurso:externalpurpose');

        $fields = [
            'userid', 'serviceid', 'windowstart', 'tokensused', 'lastsync', 'timecreated', 'timemodified',
        ];
        $fielddata = [];
        foreach ($fields as $field) {
            $fielddata[$field] = get_string('privacy:metadata:aiprovider_datacurso_rlimit:' . $field, 'aiprovider_datacurso');
        }
        $collection->add_database_table(
            'aiprovider_datacurso_rlimit',
            $fielddata,
            get_string('privacy:metadata:aiprovider_datacurso_rlimit', 'aiprovider_datacurso')
        );

        $userlimitfields = [
            'userid' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:userid', 'aiprovider_datacurso'),
            'tokenlimit' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:tokenlimit', 'aiprovider_datacurso'),
            'tokensused' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:tokensused', 'aiprovider_datacurso'),
            'countfrom' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:countfrom', 'aiprovider_datacurso'),
            'lastsync' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:lastsync', 'aiprovider_datacurso'),
            'recurringintervalenabled' => get_string(
                'privacy:metadata:aiprovider_datacurso_userlimit:recurringintervalenabled',
                'aiprovider_datacurso'
            ),
            'recurringintervalunit' => get_string(
                'privacy:metadata:aiprovider_datacurso_userlimit:recurringintervalunit',
                'aiprovider_datacurso'
            ),
            'recurringintervalvalue' => get_string(
                'privacy:metadata:aiprovider_datacurso_userlimit:recurringintervalvalue',
                'aiprovider_datacurso'
            ),
            'nextresetat' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:nextresetat', 'aiprovider_datacurso'),
            'timecreated' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:timecreated', 'aiprovider_datacurso'),
            'timemodified' => get_string('privacy:metadata:aiprovider_datacurso_userlimit:timemodified', 'aiprovider_datacurso'),
        ];
        $collection->add_database_table(
            'aiprovider_datacurso_userlimit',
            $userlimitfields,
            get_string('privacy:metadata:aiprovider_datacurso_userlimit', 'aiprovider_datacurso')
        );

        $rolelimitfields = [
            'roleid' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:roleid', 'aiprovider_datacurso'),
            'priority' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:priority', 'aiprovider_datacurso'),
            'tokenlimit' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:tokenlimit', 'aiprovider_datacurso'),
            'tokensused' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:tokensused', 'aiprovider_datacurso'),
            'countfrom' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:countfrom', 'aiprovider_datacurso'),
            'lastsync' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:lastsync', 'aiprovider_datacurso'),
            'recurringintervalenabled' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit:recurringintervalenabled',
                'aiprovider_datacurso'
            ),
            'recurringintervalunit' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit:recurringintervalunit',
                'aiprovider_datacurso'
            ),
            'recurringintervalvalue' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit:recurringintervalvalue',
                'aiprovider_datacurso'
            ),
            'nextresetat' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:nextresetat', 'aiprovider_datacurso'),
            'timecreated' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:timecreated', 'aiprovider_datacurso'),
            'timemodified' => get_string('privacy:metadata:aiprovider_datacurso_rolelimit:timemodified', 'aiprovider_datacurso'),
        ];
        $collection->add_database_table(
            'aiprovider_datacurso_rolelimit',
            $rolelimitfields,
            get_string('privacy:metadata:aiprovider_datacurso_rolelimit', 'aiprovider_datacurso')
        );

        $roleusagefields = [
            'rolelimitid' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit_userusage:rolelimitid',
                'aiprovider_datacurso'
            ),
            'userid' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit_userusage:userid',
                'aiprovider_datacurso'
            ),
            'tokensused' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit_userusage:tokensused',
                'aiprovider_datacurso'
            ),
            'lastsync' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit_userusage:lastsync',
                'aiprovider_datacurso'
            ),
            'timecreated' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit_userusage:timecreated',
                'aiprovider_datacurso'
            ),
            'timemodified' => get_string(
                'privacy:metadata:aiprovider_datacurso_rolelimit_userusage:timemodified',
                'aiprovider_datacurso'
            ),
        ];
        $collection->add_database_table(
            'aiprovider_datacurso_rolelimit_userusage',
            $roleusagefields,
            get_string('privacy:metadata:aiprovider_datacurso_rolelimit_userusage', 'aiprovider_datacurso')
        );
        return $collection;
    }

    #[\Override]
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        if (self::user_has_data($userid)) {
            $contextlist->add_user_context($userid);
        }
        return $contextlist;
    }

    #[\Override]
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        if (!$context instanceof \context_user) {
            return;
        }
        if (self::user_has_data($context->instanceid)) {
            $userlist->add_user($context->instanceid);
        }
    }

    #[\Override]
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        if (empty($contextlist->count())) {
            return;
        }
        $user = $contextlist->get_user();
        $context = \context_user::instance($user->id);
        $tables = static::get_table_user_map($user);

        foreach ($tables as $table => $filterparams) {
            $records = $DB->get_recordset($table, $filterparams);
            foreach ($records as $record) {
                writer::with_context($context)->export_data([
                    get_string('privacy:metadata:aiprovider_datacurso', 'aiprovider_datacurso'),
                    get_string('privacy:metadata:' . $table, 'aiprovider_datacurso'),
                ], $record);
            }
            $records->close();
        }
    }

    #[\Override]
    public static function delete_data_for_all_users_in_context(\context $context) {
        if ($context->contextlevel == CONTEXT_USER) {
            self::delete_user_data($context->instanceid);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        $context = $userlist->get_context();
        if ($context instanceof \context_user) {
            self::delete_user_data($context->instanceid);
        }
    }

    #[\Override]
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        if (empty($contextlist->count())) {
            return;
        }
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel == CONTEXT_USER) {
                self::delete_user_data($context->instanceid);
            }
        }
    }

    /**
     * Delete all user data from this plugin tables for a given user.
     *
     * @param int $userid The user ID
     * @return void
     */
    private static function delete_user_data(int $userid) {
        global $DB;

        $userdata = new stdClass();
        $userdata->id = $userid;

        $tables = self::get_table_user_map($userdata);
        foreach ($tables as $table => $filterparams) {
            $DB->delete_records($table, $filterparams);
        }
    }

    /**
     * Whether the user has data stored in this plugin tables.
     *
     * @param int $userid The user ID
     * @return bool
     */
    private static function user_has_data(int $userid): bool {
        global $DB;
        $userdata = new stdClass();
        $userdata->id = $userid;

        $tables = self::get_table_user_map($userdata);
        foreach ($tables as $table => $filterparams) {
            if ($DB->record_exists($table, $filterparams)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Map of tables containing user data and their filter params for a given user.
     *
     * @param stdClass $user The user
     * @return array<string,array<string,int>>
     */
    protected static function get_table_user_map(stdClass $user): array {
        return [
            'aiprovider_datacurso_rlimit' => ['userid' => $user->id],
            'aiprovider_datacurso_userlimit' => ['userid' => $user->id],
            'aiprovider_datacurso_rolelimit_userusage' => ['userid' => $user->id],
        ];
    }
}
