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
 * TODO describe file report_sections
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/ai/provider/datacurso/admin/report_sections.php'));
$PAGE->set_pagelayout('report');

$tab = optional_param('tab', 'consumption', PARAM_ALPHA);

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

$output = $PAGE->get_renderer('core');
echo $output->header();
$headerlogo = new \aiprovider_datacurso\output\header_logo();
echo $output->render($headerlogo);
echo $output->tabtree($tabs, $tab);

// Contenido según pestaña.
switch ($tab) {
    case 'consumption':
        $page = new \aiprovider_datacurso\output\consumption_page();
        echo $output->render($page);
        $PAGE->requires->js_call_amd('aiprovider_datacurso/consumption', 'init');
        break;

    case 'generalreport':
        $page = new \aiprovider_datacurso\output\report_page();
        echo $output->render($page);
        $PAGE->requires->js_call_amd('aiprovider_datacurso/report_charts', 'init');
        break;

    case 'pluginslist':
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


        foreach ($pluginslist as &$plugin) {
            $plugin['installed'] = $DB->record_exists('config_plugins', [
                'plugin' => $plugin['component'],
            ]);
            unset($plugin['component']);
        }
        unset($plugin);

        $page = new \aiprovider_datacurso\output\plugin_list_page($pluginslist);
        echo $output->render($page);
        break;
}

echo $output->footer();
