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

/**
 * Interface for adding per-service ratelimit admin settings.
 *
 * @package     aiprovider_datacurso
 * @category    admin
 * @copyright   2025 Wilber Narvaez <https://datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_datacurso\local\ratelimit;

/**
 * Contract to contribute ratelimit settings for a given service id.
 */
abstract class ratelimit_settings {
    /**
     * Add ratelimit admin settings to the provider settings page.
     *
     * @param \admin_settingpage $settings Settings page to add controls to.
     * @param string $component Frankenstyle service/component id (e.g. 'local_coursegen').
     * @return void
     */
    abstract public function add_settings(\admin_settingpage $settings, string $component): void;

    /**
     * Retrieve the list of selectable users for the autocomplete control.
     *
     * @return array<string,string>
     */
    protected static function get_user_choices(array $capabilities): array {
        global $DB;

        list($insql, $params) = $DB->get_in_or_equal($capabilities, SQL_PARAMS_NAMED);

        $params['deleted'] = 0;
        $params['suspended'] = 0;
        $params['permission'] = CAP_ALLOW;
        $params['capabilitiescount'] = count($capabilities);

        $records = $DB->get_records_sql(
            "SELECT u.id, u.firstname, u.lastname
            FROM
                {user} u
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {role_capabilities} rc ON rc.roleid = ra.roleid
            WHERE
                rc.permission = :permission
                AND u.deleted = :deleted
                AND u.suspended = :suspended
                AND rc.capability {$insql}
            GROUP BY
                u.id
            HAVING
                COUNT(DISTINCT rc.capability) = :capabilitiescount
            ORDER BY u.lastname, u.firstname, u.id",
            $params
        );

        $choices = [];
        foreach ($records as $user) {
            $choices[(string)$user->id] = fullname($user);
        }

        if (empty($choices)) {
            return ['' => get_string('noselection', 'form')];
        }

        return $choices;
    }
}
