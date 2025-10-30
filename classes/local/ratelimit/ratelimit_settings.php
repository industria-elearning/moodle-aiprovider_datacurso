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

defined('MOODLE_INTERNAL') || die();

/**
 * Contract to contribute ratelimit settings for a given service id.
 */
interface ratelimit_settings {
    /**
     * Add ratelimit admin settings to the provider settings page.
     *
     * @param \admin_settingpage $settings Settings page to add controls to.
     * @param string $component Frankenstyle service/component id (e.g. 'local_coursegen').
     * @return void
     */
    public function add_settings(\admin_settingpage $settings, string $component): void;
}
