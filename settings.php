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
 * Plugin administration pages are defined here.
 *
 * @package     aiprovider_datacurso
 * @category    admin
 * @copyright   Josue <josue@datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_ai\admin\admin_settingspage_provider;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingspage_provider(
        'aiprovider_datacurso',
        new lang_string('pluginname', 'aiprovider_datacurso'),
        'moodle/site:config',
        true
    );

    // Settings general.
    $settings->add(new admin_setting_heading(
        'aiprovider_datacurso/general',
        new lang_string('settings', 'core'),
        ''
    ));

    // License key.
    $settings->add(new admin_setting_configtext(
        'aiprovider_datacurso/licensekey',
        new lang_string('licensekey', 'aiprovider_datacurso'),
        new lang_string('licensekey_desc', 'aiprovider_datacurso'),
        '',
        PARAM_TEXT
    ));

    // Token threshold notification.
    $settings->add(new admin_setting_configtext(
        'aiprovider_datacurso/tokenthreshold',
        new lang_string('tokenthreshold', 'aiprovider_datacurso'),
        new lang_string('tokenthreshold_desc', 'aiprovider_datacurso'),
        50,
        PARAM_INT
    ));

    $ADMIN->add('reports', new admin_externalpage(
        'aiprovider_datacurso_reports', // Identificador único.
        get_string('link_generalreport_datacurso', 'aiprovider_datacurso'),
        new moodle_url('/ai/provider/datacurso/admin/report_sections.php'),
        'moodle/site:config'
    ));

    // Web service configuration page for automatic setup and token management.
    $ADMIN->add('server', new admin_externalpage(
        'aiprovider_datacurso_webservice',
        get_string('link_webservice_config', 'aiprovider_datacurso'),
        new moodle_url('/ai/provider/datacurso/admin/webservice_config.php'),
        'moodle/site:config'
    ));

}
