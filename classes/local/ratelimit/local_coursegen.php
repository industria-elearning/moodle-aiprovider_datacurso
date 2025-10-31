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

namespace aiprovider_datacurso\local\ratelimit;

use admin_settingpage;
use core_admin\local\settings\autocomplete;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/user/lib.php');

/**
 * Class local_coursegen
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_coursegen implements ratelimit_settings {
    /** @var string Plugin component name. */
    private const PLUGIN = 'aiprovider_datacurso';

    /**
     * Add the rate limit settings related to course generation.
     *
     * @param admin_settingpage $settings Settings page to append controls to.
     * @param string $component Component name used to namespace config keys.
     */
    public function add_settings(admin_settingpage $settings, string $component): void {
        $configprefix = self::PLUGIN . "/ratelimit_{$component}";

        $coursecreators = $this->create_user_setting(
            "{$configprefix}_coursecreators",
            'ratelimit_local_coursegen_coursecreators',
            'ratelimit_local_coursegen_coursecreators_desc'
        );
        $settings->add($coursecreators);
        $settings->hide_if("{$configprefix}_coursecreators", "{$configprefix}_enable", 'eq', 0);

        $activitycreators = $this->create_user_setting(
            "{$configprefix}_activitycreators",
            'ratelimit_local_coursegen_activitycreators',
            'ratelimit_local_coursegen_activitycreators_desc'
        );
        $settings->add($activitycreators);
        $settings->hide_if("{$configprefix}_activitycreators", "{$configprefix}_enable", 'eq', 0);
    }

    /**
     * Build the autocomplete admin setting for a user selection.
     *
     * @param string $settingname Full admin setting name (including plugin prefix).
     * @param string $labelkey Language string key for the setting label.
     * @param string $desckey Language string key for the setting description.
     * @return autocomplete
     */
    private function create_user_setting(string $settingname, string $labelkey, string $desckey): autocomplete {
        $attributes = [
            'ajax' => 'core_user/form_user_selector',
            'multiple' => true,
            'showsuggestions' => true,
            'placeholder' => get_string('search'),
            'noselectionstring' => get_string('noselection', 'form'),
        ];

        $choices = self::get_all_user_choices();

        return new autocomplete(
            $settingname,
            new \lang_string($labelkey, self::PLUGIN),
            new \lang_string($desckey, self::PLUGIN),
            [],
            $choices,
            $attributes
        );
    }

    /**
     * Retrieve the list of selectable users for the autocomplete control.
     *
     * @return array<string,string>
     */
    private static function get_all_user_choices(): array {
        global $DB;

        [$sort, $sortparams] = users_order_by_sql('u');
        if (!empty($sortparams)) {
            throw new \coding_exception('Unexpected query parameters returned by users_order_by_sql().');
        }

        $fieldsapi = \core_user\fields::for_name();
        $fields = $fieldsapi->get_sql('u', false, '', '', false);

        $records = $DB->get_records_sql(
            "SELECT u.id, {$fields->selects}
               FROM {user} u
              WHERE u.deleted = 0
           ORDER BY {$sort}"
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
