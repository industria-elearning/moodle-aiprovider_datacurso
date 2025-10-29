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
 * Admin report sections for Datacurso AI Provider plugin.
 *
 * This file manages the main administrative report sections of the
 * Datacurso AI Provider plugin. It renders tabs for:
 * - AI consumption history
 * - General usage report
 * - Installed plugins list
 *
 * @package    aiprovider_datacurso
 * @category   admin
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../../config.php');

// Ensure the user is logged in and has permission to access this page.
$context = context_system::instance();
require_login();
require_capability('aiprovider_datacurso/datacurso:viewreports', $context);

// Set up page context and layout.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/ai/provider/datacurso/admin/report_sections.php'));
$PAGE->set_pagelayout('report');

// Get the current tab parameter.
$tab = optional_param('tab', 'consumption', PARAM_ALPHAEXT);

// Define tabs for navigation.
$tabs = [];
$tabs[] = new tabobject(
    'consumption',
    new moodle_url('/ai/provider/datacurso/admin/report_sections.php', ['tab' => 'consumption']),
    get_string('link_consumptionhistory', 'aiprovider_datacurso')
);

$tabs[] = new tabobject(
    'generalreport',
    new moodle_url('/ai/provider/datacurso/admin/report_sections.php', ['tab' => 'generalreport']),
    get_string('link_generalreport', 'aiprovider_datacurso')
);

$tabs[] = new tabobject(
    'pluginslist',
    new moodle_url('/ai/provider/datacurso/admin/report_sections.php', ['tab' => 'pluginslist']),
    get_string('link_listplugings', 'aiprovider_datacurso')
);

// Render page header and navigation.
echo $OUTPUT->header();

// Render custom header logo output.
$headerlogo = new \aiprovider_datacurso\output\header_logo();
echo $OUTPUT->render($headerlogo);

// Render tab navigation.
echo $OUTPUT->tabtree($tabs, $tab);

// Load tab content.
switch ($tab) {
    case 'consumption':
        // Render AI consumption history page.
        $page = new \aiprovider_datacurso\output\consumption_page();
        echo $OUTPUT->render($page);
        $PAGE->requires->js_call_amd('aiprovider_datacurso/consumption', 'init');
        break;

    case 'generalreport':
        // Render general statistics and charts.
        $page = new \aiprovider_datacurso\output\report_page();
        echo $OUTPUT->render($page);
        $PAGE->requires->js_call_amd('aiprovider_datacurso/report_charts', 'init');
        break;

    case 'pluginslist':
        // List of compatible AI-related plugins.
        global $DB;

        $pluginslist = [
            [
                'name' => get_string('pluginname_coursegen', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_coursegen', 'aiprovider_datacurso'),
                'component' => 'local_coursegen',
                'url' => 'https://moodle.org/plugins/local_coursegen',
            ],
            [
                'name' => get_string('pluginname_forum_ai', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_forum_ai', 'aiprovider_datacurso'),
                'component' => 'local_forum_ai',
                'url' => 'https://moodle.org/plugins/local_forum_ai',
            ],
            [
                'name' => get_string('pluginname_datacurso_ratings', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_datacurso_ratings', 'aiprovider_datacurso'),
                'component' => 'local_datacurso_ratings',
                'url' => 'https://moodle.org/plugins/local_datacurso_ratings',
            ],
            [
                'name' => get_string('pluginname_assign_ai', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_assign_ai', 'aiprovider_datacurso'),
                'component' => 'local_assign_ai',
                'url' => 'https://moodle.org/plugins/local_assign_ai',
            ],
            [
                'name' => get_string('pluginname_dttutor', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_dttutor', 'aiprovider_datacurso'),
                'component' => 'local_dttutor',
                'url' => 'https://moodle.org/plugins/local_dttutor',
            ],
            [
                'name' => get_string('pluginname_socialcert', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_socialcert', 'aiprovider_datacurso'),
                'component' => 'local_socialcert',
                'url' => 'https://moodle.org/plugins/local_socialcert',
            ],
            [
                'name' => get_string('pluginname_lifestory', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_lifestory', 'aiprovider_datacurso'),
                'component' => 'report_lifestory',
                'url' => 'https://moodle.org/plugins/report_lifestory',
            ],
            [
                'name' => get_string('pluginname_smartrules', 'aiprovider_datacurso'),
                'description' => get_string('plugindesc_smartrules', 'aiprovider_datacurso'),
                'component' => 'local_smartrules',
                'url' => 'https://moodle.org/plugins/local_smartrules',
            ],
        ];

        // Check installed status of each plugin.
        foreach ($pluginslist as &$plugin) {
            $plugin['installed'] = $DB->record_exists('config_plugins', [
                'plugin' => $plugin['component'],
            ]);
            unset($plugin['component']);
        }
        unset($plugin);

        $page = new \aiprovider_datacurso\output\plugin_list_page($pluginslist);
        echo $OUTPUT->render($page);
        break;
}

// Render page footer.
echo $OUTPUT->footer();
