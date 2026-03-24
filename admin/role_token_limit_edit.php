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
 * Edit page for role token limits.
 *
 * @package    aiprovider_datacurso
 * @copyright  2026 Datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use aiprovider_datacurso\form\role_token_limit_form;
use aiprovider_datacurso\local\role_token_limit_manager;

$context = context_system::instance();
require_capability('aiprovider/datacurso:managetokenlimits', $context);
require_login();

$id = optional_param('id', 0, PARAM_INT);
$returnurl = optional_param('returnurl', '/ai/provider/datacurso/admin/role_token_limits.php', PARAM_LOCALURL);
$returnurlobj = new moodle_url($returnurl);

$record = null;
$rolename = '';
if ($id > 0) {
    $record = role_token_limit_manager::get_by_id($id);
    if (!$record) {
        throw new moodle_exception('error_roletokenlimit_notfound', 'aiprovider_datacurso');
    }

    $role = $DB->get_record('role', ['id' => $record->roleid], '*', MUST_EXIST);
    $rolename = role_get_name($role, $context);
}

$pageurl = new moodle_url('/ai/provider/datacurso/admin/role_token_limit_edit.php', [
    'id' => $id,
    'returnurl' => $returnurlobj->out_as_local_url(false),
]);

$PAGE->set_context($context);
$PAGE->set_url($pageurl);
$PAGE->set_pagelayout('admin');
$title = $id > 0
    ? get_string('roletokenlimit_edit_title', 'aiprovider_datacurso', $rolename)
    : get_string('roletokenlimit_add_title', 'aiprovider_datacurso');
$PAGE->set_title($title);
$PAGE->set_heading($SITE->fullname);

$form = new role_token_limit_form($pageurl->out(false), [
    'record' => $record,
    'rolename' => $rolename,
    'returnurl' => $returnurlobj->out_as_local_url(false),
]);

if ($form->is_cancelled()) {
    redirect($returnurlobj);
}

if ($data = $form->get_data()) {
    role_token_limit_manager::save(
        (int)$data->roleid,
        (int)$data->tokenlimit,
        (string)$data->priority,
        (int)$data->id,
        (int)($data->recurringintervalenabled ?? 0),
        (string)($data->recurringintervalunit ?? 'day'),
        (int)($data->recurringintervalvalue ?? 0)
    );
    redirect($returnurlobj, get_string('roletokenlimit_saved', 'aiprovider_datacurso'));
}

if ($record) {
    $record->rolename = $rolename;
    $form->set_data($record);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);
$form->display();
echo $OUTPUT->footer();
