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

require(__DIR__ . '/../../../config.php');

require_login();
require_capability('moodle/site:config', context_system::instance());

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/ai/provider/datacurso/admin/webservice_config.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('webserviceconfig_title', 'aiprovider_datacurso'));
$PAGE->set_heading(get_string('webserviceconfig_title', 'aiprovider_datacurso'));

// Initial status for page render.
$status = \aiprovider_datacurso\webservice_config::get_status();

echo $OUTPUT->header();

$PAGE->requires->js_call_amd('aiprovider_datacurso/webservice_config', 'init');

echo $OUTPUT->render_from_template('aiprovider_datacurso/webservice_config', $status);

echo $OUTPUT->footer();

