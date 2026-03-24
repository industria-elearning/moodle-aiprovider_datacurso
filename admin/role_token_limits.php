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
 * Admin page to manage per-role token limits for the Datacurso AI provider.
 *
 * @package    aiprovider_datacurso
 * @copyright  2026 Datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use aiprovider_datacurso\local\role_token_limit_manager;

$context = context_system::instance();
require_capability('aiprovider/datacurso:managetokenlimits', $context);

$search = optional_param('search', '', PARAM_RAW_TRIMMED);
$sort = optional_param('sort', 'roleshortname', PARAM_ALPHA);
$dir = optional_param('dir', 'ASC', PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 20, PARAM_INT);

$pageurl = new moodle_url('/ai/provider/datacurso/admin/role_token_limits.php', [
    'search' => $search,
    'sort' => $sort,
    'dir' => $dir,
    'page' => $page,
    'perpage' => $perpage,
]);

require_login();
$PAGE->set_context($context);
$PAGE->set_url($pageurl);
$PAGE->set_pagelayout('admin');

$heading = get_string('link_roletokenlimits', 'aiprovider_datacurso');
$PAGE->set_title($heading);
$PAGE->set_heading($SITE->fullname);

$allowedsorts = ['rolename', 'roleshortname', 'priority', 'tokenlimit', 'tokensused', 'tokensavailable'];
if (!in_array($sort, $allowedsorts, true)) {
    $sort = 'roleshortname';
}
$dir = (strtoupper($dir) === 'DESC') ? 'DESC' : 'ASC';

$total = role_token_limit_manager::count($search);
$offset = $page * $perpage;
$records = role_token_limit_manager::get_records($search, $sort, $dir, $offset, $perpage);

$addurl = new moodle_url('/ai/provider/datacurso/admin/role_token_limit_edit.php', [
    'returnurl' => $PAGE->url->out_as_local_url(false),
]);

$headers = [
    'rolename' => get_string('role'),
    'roleshortname' => get_string('shortname'),
    'priority' => get_string('roletokenlimit_priority', 'aiprovider_datacurso'),
    'tokenlimit' => get_string('usertokenlimit_limit', 'aiprovider_datacurso'),
    'tokensused' => get_string('usertokenlimit_used', 'aiprovider_datacurso'),
    'tokensavailable' => get_string('tokens_available', 'aiprovider_datacurso'),
    'actions' => get_string('actions', 'moodle'),
];

$columns = [];
foreach (['rolename', 'roleshortname', 'priority', 'tokenlimit', 'tokensused', 'tokensavailable', 'actions'] as $col) {
    $coldata = ['key' => $col, 'label' => $headers[$col]];
    $iscurrent = ($sort === $col);
    if ($iscurrent) {
        $coldata['current'] = true;
        $coldata['dirasc'] = ($dir === 'ASC');
        $coldata['dirdesc'] = ($dir === 'DESC');
    }
    if ($col !== 'actions') {
        $nextdir = ($sort === $col && $dir === 'ASC') ? 'DESC' : 'ASC';
        $coldata['sorturl'] = (new moodle_url($PAGE->url, [
            'sort' => $col,
            'dir' => $nextdir,
            'search' => $search,
            'page' => $page,
            'perpage' => $perpage,
        ]))->out(false);
    }
    $columns[] = $coldata;
}

$rows = [];
foreach ($records as $record) {
    $editurl = new moodle_url('/ai/provider/datacurso/admin/role_token_limit_edit.php', [
        'id' => $record->id,
        'returnurl' => $PAGE->url->out_as_local_url(false),
    ]);

    $prioritylevel = role_token_limit_manager::priority_to_level((string)$record->priority);
    $prioritylabel = get_string('roletokenlimit_priority_' . $prioritylevel, 'aiprovider_datacurso');

    $rows[] = [
        'id' => (int)$record->id,
        'rolename' => format_string((string)($record->rolename ?? '')),
        'roleshortname' => s((string)$record->roleshortname),
        'priority' => $prioritylabel,
        'tokenlimit' => (int)$record->tokenlimit,
        'tokensused' => (int)$record->tokensused,
        'tokensavailable' => max(0, (int)$record->tokenlimit - (int)$record->tokensused),
        'canreset' => $record->tokensused > 0,
        'editurl' => $editurl->out(false),
    ];
}

$base = new moodle_url('/ai/provider/datacurso/admin/role_token_limits.php', [
    'search' => $search,
    'sort' => $sort,
    'dir' => $dir,
    'perpage' => $perpage,
]);

$templatadata = [
    'addurl' => $addurl->out(false),
    'searchaction' => (new moodle_url('/ai/provider/datacurso/admin/role_token_limits.php'))->out(false),
    'searchvalue' => $search,
    'sort' => $sort,
    'dir' => $dir,
    'perpage' => $perpage,
    'columns' => $columns,
    'rows' => $rows,
    'empty' => empty($rows),
    'nothingtodisplay' => get_string('nothingtodisplay'),
    'pagingbar' => $OUTPUT->paging_bar($total, $page, $perpage, $base),
];

$PAGE->requires->js_call_amd('aiprovider_datacurso/role_token_limits', 'init');

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);
echo $OUTPUT->render_from_template('aiprovider_datacurso/role_token_limits', $templatadata);
echo $OUTPUT->footer();
