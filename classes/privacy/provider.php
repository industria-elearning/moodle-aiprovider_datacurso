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

/**
 * Privacy Subsystem implementation for aiprovider_datacurso.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_datacurso\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;

/**
 * Privacy Subsystem for aiprovider_datacurso implementing metadata provider.
 *
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\core_userlist_provider {
    /**
     * Return the fields which contain personal data.
     *
     * @param collection $collection a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link(
            'datacurso_ai_services',
            [
                'userid' => 'privacy:metadata:datacurso_ai_services:userid',
                'request_data' => 'privacy:metadata:datacurso_ai_services:request_data',
                'response_data' => 'privacy:metadata:datacurso_ai_services:response_data',
                'tokens_consumed' => 'privacy:metadata:datacurso_ai_services:tokens_consumed',
                'timestamp' => 'privacy:metadata:datacurso_ai_services:timestamp',
            ],
            'privacy:metadata:datacurso_ai_services',
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid the userid.
     * @return contextlist the list of contexts containing user info for the user.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        // This plugin does not store user data locally in Moodle.
        // All data is sent to external Datacurso AI services.
        return new contextlist();
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist the userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        // This plugin does not store user data locally in Moodle.
        // All data is sent to external Datacurso AI services.
    }

    /**
     * Export personal data for the given approved_contextlist.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        // This plugin does not store user data locally in Moodle.
        // Data is processed by external Datacurso AI services and not stored locally.
        // Users should contact Datacurso directly for data export requests.
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context the context to delete in.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        // This plugin does not store user data locally in Moodle.
        // All data is processed by external Datacurso AI services.
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        // This plugin does not store user data locally in Moodle.
        // Users should contact Datacurso directly for data deletion requests.
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        // This plugin does not store user data locally in Moodle.
        // Users should contact Datacurso directly for data deletion requests.
    }
}
